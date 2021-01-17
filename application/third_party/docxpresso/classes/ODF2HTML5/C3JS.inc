<?php

/**
 * ChartParser
 *
 * @copyright  Copyright(c)2017 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    3.5
 * @since      3.0
 */

namespace Docxpresso\ODF2HTML5;


use Docxpresso;
use Docxpresso\Commons as commons;
use Docxpresso\Parser as parser;
use Docxpresso\Core\Charts as charts;

/**
 * This class parses chart data and transforms it into c3 scripting
 *
 * @package    Docxpresso
 * @subpackage ODF2HTML5
 */

class C3JS
{
    /**
     * Chart axis format
     * 
     * @var array
     * @access private
     */
    private $_axis;
    /**
     * Chart categories
     * 
     * @var array
     * @access private
     */
    private $_categories;
    /**
     * Chart DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_chart;
    /**
     * Chart data
     * 
     * @var array
     * @access private
     */
    private $_data;
    /**
     * Chart global type info
     * 
     * @var array
     * @access private
     */
    private $_globalType;
    /**
     * Chart div id
     * 
     * @var string
     * @access private
     */
    private $_id;
    /**
     * Chart legend format
     * 
     * @var array
     * @access private
     */
    private $_legend;
    /**
     * Chart series format
     * 
     * @var array
     * @access private
     */
    private $_series;
    /**
     * Chart title data
     * 
     * @var array
     * @access private
     */
    private $_title;
    
    /**
     * Construct
     *
     * @param array $options
     * @access public
     */
    public function __construct($options)
    {          
        $this->_id = $options['name'];
    }
    
    /**
     * Sets the chart axis format
     *
     * @param array $axis
     * @return void
     * @access public
     */
    public function setAxis($axis) 
    {
        $this->_axis = $axis;  
    }
      
    /**
     * Sets the chart global type
     *
     * @param array $type
     * @return void
     * @access public
     */
    public function setGlobalType($type) 
    {
        $this->_globalType = $type;
    }
    
    /**
     * Sets the chart legend format
     *
     * @param array $legend
     * @return void
     * @access public
     */
    public function setLegend($legend) 
    {
        $this->_legend = $legend;
    }
    
    /**
     * sets the chart raw data
     *
     * @param array $data
     * @return void
     * @access public
     */
    public function setData($data) 
    {
        $this->_data = $data;
    }
    
    /**
     * Sets the chart series format
     *
     * @param array $series
     * @return void
     * @access public
     */
    public function setSeries($series) 
    {
        $this->_series = $series;      
    }
    
    /**
     * Sets the chart title data
     *
     * @array $title
     * @return void
     * @access public
     */
    public function setTitle($title) 
    {
        $this->_title = $title;      
    }
    
    /**
     * Renders the chart data in c3.js compatible format
     *
     * @param DOMDocument $chart
     * @return string
     * @access public
     */
    public function renderScript($chart) 
    {
        
        $this->_chart = $chart;
        $unid = 'chart_' . \uniqid();
        $script = '<script>';
        $script .= 'var ' . $unid . ' = c3.generate({';
        $script .= 'bindto: \'#' . $this->_id .  '\',';
        switch ($this->_globalType['type']) {
            case 'chart:circle':
                $code = $this->_pieChart('pie');
                $script .= $code;
                break;
            case 'chart:ring':
                $code = $this->_pieChart('donut');
                $script .= $code;
                break;
            case 'chart:bar':
                $code = $this->_genericChart('bar');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:area':
                $code = $this->_genericChart('area');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:line':
                $code = $this->_genericChart('line');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:radar':
                //there is currently no specific support for radar charts
                $code = $this->_genericChart('line');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:filled-radar':
                //there is currently no specific support for filled radar charts
                $code = $this->_genericChart('area');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            default:
                $code = $this->_genericChart();
                $script .= $code;
                break;            
        }
        if (!empty($this->_legend['pos'])) {
            $script .= 'legend: {position: \'' . $this->_legend['pos'] . '\'},';
        } else {
            $script .= 'legend: {hide: true},';
        }
        $script .= 'color: {pattern: [' . $this->_getColorPattern() . ']},';
        $script .= '});';
        $script .= '</script>';
        return $script;
    }
    
    /**
     * Generates the generic chart JSON code
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _genericChart($subtype = 'bar') 
    {      
        $data = 'data: {rows: [';
        //check the categories
        foreach ($this->_axis as $axis){
           if (isset($axis['x']['range'])) {
               $catRange = $axis['x']['range'];
               $catLetterArray = \explode('$', $catRange);
               if (count($catLetterArray) > 1) {
                $catLetter = $catLetterArray[1];
               }
           } 
        }
        //the real data    
        foreach ($this->_series as $key => $series) {
            $labels = array();
            $categories = array();
            $groups = array();
            foreach ($series as $num => $value){
                $label= $series[$num]['label'];
                $letterArray = \explode('$', $label);
                $serLetter = $letterArray[1];
                $labels[] = $serLetter;
            }
            if (isset($this->_globalType['percentage'])
            && $this->_globalType['percentage']) {
                //we need to manipulate the data because c3.js does not
                //support natively percentage (stacked) 
                $length = \count($this->_data[$key]);
                for ($j = 2; $j <= $length; $j++) {
                    $subdata = $this->_data[$key][$j];
                    \array_shift($subdata);
                    $subtotal = \array_sum($subdata);
                    if ($subtotal != 0) {
                        foreach ($subdata as $ord => $num) {
                            $base = $subdata[$ord]/$subtotal;
                            $percent = \round(10000 * $base)/100;
                            $this->_data[$key][$j][$ord] = $percent;
                        }    
                    }
                }
            }
                
            foreach ($this->_data[$key] as $row => $cells){
                $data .= '[';
                if ($row > 1 && isset($catLetter) && isset($cells[$catLetter])) {
                    $categories[] =  $cells[$catLetter];
                }
                foreach($labels as $letter){
                    if (\is_numeric($cells[$letter])) {
                        $data .= $cells[$letter] . ',';
                        if ($cells[$letter] < 0) {
                            $this->_globalType['negativeValues'] = true;
                        }
                    } else {
                        $groups[] = '\'' . $cells[$letter] . '\'';
                        $data .= '\'' . $cells[$letter] . '\',';
                    }
                }
                $data .= '],';
            }                
        }    
        $data .= '], ';
        $data .= ' type : \'' . $subtype . '\',';
        $data .= 'order: null,';

        if ((isset($this->_globalType['stacked']) 
            && $this->_globalType['stacked'] === true)
            || (isset($this->_globalType['percentage']) 
            && $this->_globalType['percentage'] === true)) {
            $data .= ' groups: [[';
            $data .= \implode(',', $groups);
            $data .= ']],';
        }
        $data.= '},';
        $this->_categories = $categories;
        return $data;
    }
    
    /**
     * Generates the color pattern for a bar chart
     *
     * @return string
     * @access private
     */
    private function _genericColorPattern() 
    {
        $pattern = array();
        $colors = array();
        $data = \array_keys($this->_data);
        $tableName = $data[0];
        $xpath = new \DOMXPath($this->_chart);
        foreach($this->_series[$tableName] as $ser){
            $style = $ser['style'];
            $query = '//style:style[@style:name="' . $style . '"]';
            $query .= '/style:graphic-properties';
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                $pointColor = $nodes->item(0)->getAttribute('draw:fill-color');
                $fill = $nodes->item(0)->getAttribute('draw:fill-color');
                if ($fill == 'none') {
                    $colors[] = '#ffffff';
                } else if (!empty($pointColor)) {
                    $colors[] = $pointColor;
                } else {
                    $colors[] = '';   
                }
            } else {
                $colors[] = '';
            }
        }
        $numColors = \count($colors);
        $numDefault = \count(ChartParser::$defaultColorPattern);
        $counter = max(array($numColors, $numDefault));
        for ($j = 0; $j < $counter; $j++) {
            if (!empty($colors[$j])){
                $pattern[$j] = '\'' . $colors[$j] . '\'';
            } else if (!empty(ChartParser::$defaultColorPattern)) {
                $pattern[$j] = '\'' . ChartParser::$defaultColorPattern[$j];
                $pattern[$j] .= '\'';
            } else {
                $pattern[$j] = '\'#ffffff\'';
            }
        }
        $colorPattern = \implode(',', $pattern);
        
        return $colorPattern;        
    }
    
    /**
     * Generates the axis JSON code
     *
     * @return string
     * @access private
     */
    private function _chartAxis() 
    {
        $axis = 'axis: {';
        if (isset($this->_globalType['rotated'])
            && $this->_globalType['rotated'] === true) {
            $axis .= 'rotated: true,';
        } 
        //x-axis
        $axis .= 'x: {';
        if (isset($this->_categories)) {
            $axis .= 'type: \'category\',';
            $axis .= 'categories:[';
            foreach($this->_categories as $cat){
                $axis .= '\'' . $cat . '\',';
            }
            $axis .= '],';
        }
        $axis .= '},';//close x-axis
        //y-axis
        $axis .= 'y: {';
        if (isset($this->_globalType['percentage'])
            && $this->_globalType['percentage']) {
            $axis .= 'max: 95,'; 
            $axis .= 'min: 9,'; 
            $axis .= 'tick: {format: function (d) { return d + "%"; }}';
        }
        $axis .= '},';//close y-axis
        $axis .= '},';//close axis
        $axis .= 'grid: {';
        foreach ($this->_axis as $grid) {
            if(isset($grid['x']['grid']['class'])
               && ($grid['x']['grid']['class'] == 'major'
                   || $grid['x']['grid']['class'] == 'minor')){
                $axis .= 'x: {show: true,},';
            }
            if(isset($grid['y']['grid']['class'])
               && ($grid['y']['grid']['class'] == 'major'
                   || $grid['y']['grid']['class'] == 'minor')){
                $axis .= 'y: {show: true,},';
            } else if (isset($this->_globalType['negativeValues']) 
                       && $this->_globalType['negativeValues'] === true) {
                $axis .= 'y: {lines: [{value:0}],},';
            }
        }
        $axis .= '},';//close grid
        return $axis;
    }
    
    /**
     * Generates the color pattern for a generic type chart
     *
     * @return string
     * @access private
     */
    private function _getColorPattern() 
    {
        switch ($this->_globalType['type']) {
            case 'chart:circle':
                $pattern = $this->_pieColorPattern();
                break;
            default:
                $pattern = $this->_genericColorPattern();
                break;            
        }
        return $pattern;
    }
    
    /**
     * Generates the specific pie chart JSON code
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _pieChart($subtype = 'pie') 
    {
        $data = 'data: {columns: [';
        //check the categories
        foreach ($this->_axis as $axis){
           if (isset($axis['x']['range'])) {
               $catRange = $axis['x']['range'];
               $catLetterArray = \explode('$', $catRange);
               $catLetter = $catLetterArray[1];
           } 
        }
        //the real data    
        foreach ($this->_series as $key => $series) {
            //for pie chart we should only take into account the first series
            foreach ($series as $num => $value){
                if ($num == 0) {
                    $labels= $series[0]['label'];
                    $letterArray = \explode('$', $labels);
                    $serLetter = $letterArray[1];
                    foreach ($this->_data[$key] as $row => $cells){
                        if ($row >1) {
                            $data .= '[';
                            $data .= '"' . $cells[$catLetter] . '",';
                            $data .= $cells[$serLetter] . ',';
                            $data .= '],';
                        }
                    }
                }
            }
        }     
        $data .= '], ';
        $data .= ' type : \'' . $subtype . '\',';
        $data .= 'order: null,},';
        return $data;
    }
    
    /**
     * Generates the color pattern for a pie chart
     *
     * @return string
     * @access private
     */
    private function _pieColorPattern() 
    {
        $pattern = array();
        $colors = array();
        $data = \array_keys($this->_data);
        $tableName = $data[0];
        $dataPoints = $this->_series[$tableName][0]['data-points'];
        $xpath = new \DOMXPath($this->_chart);
        foreach ($dataPoints as $data) {
            $query = '//style:style[@style:name="' . $data . '"]';
            $query .= '/style:graphic-properties';
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                $pointColor = $nodes->item(0)->getAttribute('draw:fill-color');
                $fill = $nodes->item(0)->getAttribute('draw:fill-color');
                if ($fill == 'none') {
                    $colors[] = '#ffffff';
                } else if (!empty($pointColor)) {
                    $colors[] = $pointColor;
                } else {
                    $colors[] = '';   
                }
            } else {
                $colors[] = '';
            }
        }
        $numColors = \count($colors);
        $numDefault = \count(ChartParser::$defaultColorPattern);
        $counter = max(array($numColors, $numDefault));
        for ($j = 0; $j < $counter; $j++) {
            if (!empty($colors[$j])){
                $pattern[$j] = '\'' . $colors[$j] . '\'';
            } else if (!empty(ChartParser::$defaultColorPattern)) {
                $pattern[$j] = '\'' . ChartParser::$defaultColorPattern[$j];
                $pattern[$j] .= '\'';
            } else {
                $pattern[$j] = '\'#ffffff\'';
            }
        }
        $colorPattern = \implode(',', $pattern);
        
        return $colorPattern;        
    }

}