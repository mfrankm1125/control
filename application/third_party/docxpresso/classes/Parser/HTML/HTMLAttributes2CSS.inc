<?php

/**
 * HTML attributes to CSS
 *
 * @copyright  Copyright (c) 2017 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    3.5
 * @since      1.0
 */

namespace Docxpresso\Parser\HTML;

/**
 * This class stores certain resources needed for the parsing HTML attributes
 *
 * @package    Docxpresso
 * @subpackage Parser
 */

class HTMLAttributes2CSS
{
    /**
     * This array holds all attributes associated to a given HTML node as a key-
     * value pair
     * 
     * @var array
     * @access public
     */
    public $nodeName;
 
    /**
     *
     * @access public
     * @static
     * @var array
     * 
     * In HTML5 is deprecated the use of attributes for styling purposes but 
     * for a few particular cases
     */
    public static $attributes = array(
        'basefont' => array(
            'color' => false,
            'face'  => false,
            'size'  => false,
            ),
        'dir' => array(
            'compact' => false,
            ),
        'div' => array(
            'align' => false,
            ),
        'dl' => array(
            'compact' => false,
            ),
        'font' => array(
            'color' => false,
            'face'  => false,
            'size'  => false,
            ),
        'h1' => array(
            'align' => false,
            ),
        'h2' => array(
            'align' => false,
            ),
        'h3' => array(
            'align' => false,
            ),
        'h4' => array(
            'align' => false,
            ),
        'h5' => array(
            'align' => false,
            ),
        'h6' => array(
            'align' => false,
            ),
        'hr' => array(
            'align'   => false,
            'noshade' => false,
            'size'    => false,
            'width'   => false,
            ),
        'html' => array(
            'dir' => false,
            ),
        'img' => array(
            'align'  => false,
            'border' => false,
            'height' => true,
            'hspace' => false,
            'vspace' => false,
            'width'  => true,
            ),
        'li' => array(
            'type'    => false,
            'value'   => false,
            ),
        'menu' => array(
            'compact' => false,
            ),
        'ol' => array(
            'compact' => false,
            'start'   => false,
            'type'    => true,
            ),
        'p' => array(
            'align'  => false,
            ),
        'pre' => array(
            'width' => false,
            ),
        'table' => array(
            'align'       => false,
            'bgcolor'     => false,
            'border'      => true,
            'cellpadding' => false,
            'cellspacing' => false,
            'frame'       => false,
            'rules'       => false,
            'width'       => false,
            ),
        'td' => array(
            'align'   => false,
            'bgcolor' => false,
            'colspan' => true,
            'height'  => false,
            'nowrap'  => false,
            'rowspan' => true,
            'valign'  => false,
            'width'   => true,
            ),
        'th' => array(
            'align'   => false,
            'bgcolor' => false,
            'colspan' => true,
            'height'  => false,
            'nowrap'  => false,
            'rowspan' => true,
            'valign'  => false,
            'width'   => true,
            ),
        'tr' => array(
            'align'  => false,
            'bgcolor' => false,
            'valign' => false,
            ),
        'ul' => array(
            'compact' => false,
            'type'    => true,
            )
    );
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct()
    {        
        
    }
    
    /**
     * Parses the HTML attributes of a given node
     *
     * @param string $nodeName
     * @param NodeMap $attributes
     * @access public
     */
    public function parseAttributes($nodeName, $attributes)
    {        
        $style = array();
        $this->nodeName = $nodeName;
        
        if ($this->nodeName == 'table') {
            $style = $this->_parseTableAttributes($attributes);
        } else if ($this->nodeName == 'td' || $this->nodeName == 'th') {
            $style = $this->_parseTableCellAttributes($attributes);
        } else if ($this->nodeName == 'img') {
            $style = $this->_parseImageAttributes($attributes);
        } else if ($this->nodeName == 'font') {
            $style = $this->_parseFontAttributes($attributes);
        } else if ($this->nodeName == 'ul' || $this->nodeName == 'ol') {
            $style = $this->_parseListAttributes($attributes);
        }else {
            $style = $this->_parseGeneralAttributes($attributes);
        }
        
        return $style;
    }
    
    /**
     * Parses the HTML attributes of a font node
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseFontAttributes($attributes)
    {        
        $style = array();
        foreach($attributes as $index => $attr){
            if ($attr->name == 'color'){
                $style['color'] = $attr->value;
            } else if ($attr->name == 'face'){
                $style['font-family'] = $attr->value;
            } else if ($attr->name == 'size'){
                //the size attribute is not correctly parsed so
                //we comment it out
                /*$fontSizes = array('0.6em', // xx-small
                                   '0.75em', //x-small
                                   '0.9em', //small
                                   '1em', //medium
                                   '1.2em', //large
                                   '1.5em', //x-large
                                   '2.0em' //xx-large
                                  );
                if (isset($fontSizes[$attr->value - 1])) {
                    $style['font-size'] = $fontSizes[$attr->value - 1];
                }*/
            }
        }
        return $style;
    }
    
    /**
     * Parses the HTML attributes of a img node
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseImageAttributes($attributes)
    {        
        $style = array();
        foreach($attributes as $index => $attr){
            if ($attr->name == 'width'){
                $style['width'][0] = ($attr->value * 0.75);
                $style['width'][1] = 'pt';
            } else if ($attr->name == 'height'){
                $style['height'][0] = ($attr->value * 0.75);
                $style['height'][1] = 'pt';
            } 
        }
        return $style;
    }
    
    /**
     * Parses the HTML attributes of a table node
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseTableAttributes($attributes)
    {        
        $style = array();
    
        foreach($attributes as $index => $attr){;
            if ($attr->name == 'border'){
                if (\is_numeric($attr->value) && $attr->value > 0 ){
                    $style['border-width'] = array(($attr->value * 0.75), 'pt');
                    //we also include a "fake style" that allows us to manage the
                    //inheritance of the border attribute in tables
                    $style['table-border'] = ($attr->value * 0.75) . 'pt';
                }
            } else if ($attr->name == 'dir'){
                if ($attr->value == 'rtl'){
                    $dir = 'rl-tb';
                } else if ($attr->value == 'ltr'){
                    $dir = 'lr-tb';
                } else {
                    $dir = '';
                }
                if (!empty($dir)) {
                    $style['writing-mode'] = $dir;
                }
            }  
        }
        return $style;
    }
    
    /**
     * Parses the HTML attributes of a table cell node (td or th)
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseTableCellAttributes($attributes)
    {        
        $style = array();
        foreach($attributes as $index => $attr){
            //although col and row span are structural and not style attributes
            //we use the CSS styles array to hold their values
            if ($attr->name == 'colspan'){
                $style['colspan'] = $attr->value;
            } else if ($attr->name == 'rowspan'){
                $style['rowspan'] = $attr->value;
            } else if ($attr->name == 'width'){
                $style['width'][0] = ($attr->value * 0.75);
                $style['width'][1] = 'pt';
            } else if ($attr->name == 'dir'){
                if ($attr->value == 'rtl'){
                    $dir = 'rl-tb';
                } else if ($attr->value == 'ltr'){
                    $dir = 'lr-tb';
                } else {
                    $dir = '';
                }
                if (!empty($dir)) {
                    $style['writing-mode'] = $dir;
                }
            }
        }
        return $style;
    }
    
    /**
     * Parses general HTML attributes
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseGeneralAttributes($attributes)
    {        
        $style = array();
        foreach($attributes as $index => $attr){
            if ($attr->name == 'dir'){
                if ($attr->value == 'rtl'){
                    $dir = 'rl-tb';
                } else if ($attr->value == 'ltr'){
                    $dir = 'lr-tb';
                } else {
                    $dir = '';
                }
                if (!empty($dir)) {
                    $style['writing-mode'] = $dir;
                }
            }
        }
        return $style;
    }
    
    /**
     * Parses attributes for ordered and unordered lists
     *
     * @param NodeMap $attributes
     * @access private
     */
    private function _parseListAttributes($attributes)
    {        
        $style = array();
        foreach($attributes as $index => $attr){
            if ($attr->name == 'type'){
                $style['list-style-type'] = $attr->value;
            }
        }
        return $style;
    }

}