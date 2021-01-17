<?php

/**
 * HTMLTagger
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

/**
 * This class parses HTML to search for template-like variables
 *
 * @package    Docxpresso
 * @subpackage ODF2HTML5
 */

class HTMLTagger
{
    /**
     * Stores variable groups info
     * 
     * @var array
     * @access public
     * @static
     */
    public static $groups = array();
    /**
     * Stores special variable info
     * 
     * @var array
     * @access public
     * @static
     */
    public static $specialVars = array();
    /**
     * Stores template structural info
     * 
     * @var array
     * @access public
     * @static
     */
    public static $structure;
    /**
     * Stores the groups for each variable
     * 
     * @var array
     * @access public
     * @static
     */
    public static $varGroups = array();
    /**
     * HTML DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    /**
     * ending variable delimiter
     * 
     * @var string
     * @access private
     */
    private $_end;
    /**
     * max depth for tagged embedded tables
     * 
     * @var int
     * @access private
     */
    private $_maxTableDepth;
    /**
     * starting varaible delimiter
     * 
     * @var string
     * @access private
     */
    private $_start;
    /**
     * The DOMXPath object obtained from $this->_dom
     * 
     * @var DOMXPath
     * @access private
     */
    private $_xpath;
    
    /**
     * Construct
     *
     * @param DOMDocument $html
     * @param string $path
     * @param array $options
     * @access public
     */
    public function __construct($html, $options = array())
    {        
        $this->_dom = $html;
        $this->_xpath = new \DOMXPath($this->_dom);
        if (isset($options['format']) && count($options['format']) == 2) {
            $this->_start = $options['format'][0];
            $this->_end = $options['format'][1];
            if ($this->_start == $this->_end) {
                $message = 'You can not set the initial variable delimiter';
                $message .= ' equal to the ending variable delimiter.';
                commons\DOCXPRESSOLogger::logger($message, 'critical');
                return;
            }
        } else {
            $this->_start = '{{';
            $this->_end = '}}';
        }
        //we need to search for variables that may potentially belong to a 
        //group like: table, list or bookmarks
        $vars = Docxpresso\CreateDocument::$placeholderVariables;
        //bookmarks
        $ancestors = array('p',
                           'h1',
                           'h2',
                           'h3',
                           'h4',
                           'h5',
                           'h6',
                           'table',
                           'ul',
                           'ol',
            );
        //because Word does not admit bookmark name that include non standard
        //chars we need to do some extra work and detect bookmarks that contain
        //variables that may be cloned
        $bmQuery = '//*[@data-bookmarkstart !="" ';
        //this condition is to avoid automatic bookmarks
        $bmQuery .= 'and not(starts-with(@data-bookmarkstart, "_"))';
        $bmQuery .= ']';
        $bmNodes = $this->_xpath->query($bmQuery);
        $bms = array();
        if ($bmNodes->length) {
            foreach ($bmNodes as $bmNode) {
                $bmName = $bmNode->getAttribute('data-bookmarkstart');
                if (!in_array($bmName, $bms)){
                    $bms[] = $bmName;
                }
            } 
        }
        foreach ($bms as $bm) {
            $query_1 = '//*[@data-bookmarkstart="' . $bm . '"]';
            $query_1 .= '/ancestor-or-self::*';
            $query_1 .= '[(';
            foreach ($ancestors as $val) {
                $query_1 .= 'self::' . $val . ' or ';
            }
            $query_1 .= '1=2) and not(../self::li)][1]';
            //the xpath condition to avoid siblings coming after the 
            //end of the bookmark
            $condition = '0 = count(preceding-sibling::*';
            $condition .= '[.//*[@data-bookmarkend="' . $bm . '"]])';
            $query_2 = '/following-sibling::*[' . $condition . ']';
            $query = $query_1 . ' | ' . $query_1 . $query_2;
            $nodes = $this->_xpath->query($query);
            if ($nodes->length) {
                //check if there are variable included
                $str = '';
                foreach ($nodes as $n) {
                    $str .= $n->nodeValue;
                }
                $stVar = strpos($str, $this->_start);
                $enVar = strpos($str, $this->_end);
                if ($stVar !== false && $enVar !== false) {
                    $this->_wrapNodes($nodes, $bm); 
                }
            }
        }
        
        //TABLES
        //WARNING: all clonable rows should be consecutive. If that is not the 
        //case include a dummy variable that later may be removed.
        $this->_maxTableDepth = 1;
        //$query = '//table[descendant::text()';
        //$query .= '[contains(., "' . $this->_start . '") '; 
        //$query .= 'and contains(., "' . $this->_end . '")]]';
        $query = '//table[tr[td[';
        $query .= 'contains(., "' . $this->_start . '") and ';
        $query .= 'contains(., "' . $this->_end . '")';
        $query .= 'and not(descendant::table)]]]';
        $tables = $this->_xpath->query($query); 
        //assign a depth to each selected table
        foreach ($tables as $table) {
            $path = $table->getNodePath();
            $depth = substr_count($path, '/table');
            $table->setAttribute('data-tableDepth', $depth);
            if ($depth > $this->_maxTableDepth){
                $this->_maxTableDepth = $depth;
            }
        }
        //let us now work recursively from higher depth to lower depths
        for ($k = $this->_maxTableDepth; $k > 0; $k--){
            $query = '//table[@data-tableDepth="' . $k . '"]';
            $tables = $this->_xpath->query($query); 
            foreach ($tables as $table) {
                $groupId = \uniqid('', true);
                $groupId = \str_replace('.', '', $groupId);
                $table->setAttribute('data-type', 'table');
                $table->setAttribute('data-id', $groupId);
                $tableTag = $table->getAttribute('data-h5p');
                //get the variable names
                //we are assuming that there are no tbody tags and the like
                //$subquery = './tr[not(descendant::table) and';
                //eliminating the descendant clause we do not allow cloning
                //rows inside clonable rows
                //TODO: decide better option!!
                $subquery = './tr[td[';
                $subquery .= 'contains(., "' . $this->_start . '") and ';
                $subquery .= 'contains(., "' . $this->_end . '")';
                //$subquery .= ']]';
                $subquery .= 'and not(descendant::table)]]';
                $nodes = $this->_xpath->query($subquery, $table);
                //we need to wrap the found tr nodes within a tbody tag that will
                //later allow us to clone and sort all the required table row groups
                $numrows = $nodes->length;
                if ($numrows > 0) {
                    //let us find the relative tr positions
                    $relpos = array();
                    foreach ($nodes as $node) {
                        $count = $this->_xpath->evaluate("count(. | preceding-sibling::tr)", $node);
                        $relpos[] = $count;
                    }
                    $max = max($relpos);
                    $min = min($relpos);
                    $tbody = $nodes->item(0)->ownerDocument->createElement('tbody');
                    $tbody->setAttribute('data-h5p', 'tb_' . $tableTag);
                    $tbody->setAttribute('data-type', 'tbody');
                    $tbody->setAttribute('class', 'h5p_clone_row_group');
                    $nodes->item(0)->parentNode
                                   ->insertBefore($tbody, $nodes->item(0));
                    //Let us now run over all tr in positions between $min and
                    //$max
                    $query = './tr[';
                    $query .= 'position() >=' . $min;
                    $query .= 'and position() <=' . $max;
                    $query .= ']';
                    $trs = $this->_xpath->query($query, $table);
                    foreach ($trs as $tr) {
                        $this->_rowStyle($tr);
                        $text = $tr->nodeValue;
                        //extract the variable names
                        $base = \explode($this->_start, $text);
                        if ($this->_start != $this->_end) {
                            for ($j = 0; $j < count($base); $j++) {
                                $data = \explode($this->_end, $base[$j]);
                                if (!empty($data[0]) && count($data) > 1){
                                    self::$groups['table'][$groupId]['variables'][] = $data[0];
                                    self::$groups['table'][$groupId]['bookmark'] = '';
                                    if (isset(self::$varGroups[$data[0]])) {
                                        self::$varGroups[$data[0]][] = $tableTag;
                                    } else {
                                        self::$varGroups[$data[0]] = array();
                                        self::$varGroups[$data[0]][] = $tableTag;
                                    }
                                }
                            }
                        }
                        $new = $tr->cloneNode(true);
                        $tbody->appendChild($new);
                        $tr->setAttribute('removeTR', '1');
                    }
                    //remove the original nodes
                    $query = './tr[@removeTR]';
                    $remove = $this->_xpath->query($query, $table);
                    $length = $remove->length;
                    for ($j = $length -1; $j > -1; $j--) {
                        $remove->item($j)->parentNode->removeChild($remove->item($j));
                    }
                    $this->_addTableTools($table);
                }
            }
        }
        
        //LISTS
        $query = '//li[contains(., "' . $this->_start . '") '; 
        $query .= 'and contains(., "' . $this->_end . '")]';
        $items = $this->_xpath->query($query); 
        foreach ($items as $item) {
            $groupId = \uniqid('', true);
            $groupId = \str_replace('.', '', $groupId);
            $item->setAttribute('data-type', 'list');
            $listTag = $item->getAttribute('data-h5p');
            $item->setAttribute('data-id', $groupId);    
            
            $text = $item->nodeValue;
            //extract the variable names
            $base = \explode($this->_start, $text);
            if ($this->_start != $this->_end) {
                for ($j = 0; $j < count($base); $j++) {
                    $data = \explode($this->_end, $base[$j]);
                    if (!empty($data[0]) && count($data) > 1){
                        self::$groups['list'][$groupId]['variables'][] = $data[0];
                        self::$groups['list'][$groupId]['bookmark'] = '';
                    }
                    if (isset(self::$varGroups[$data[0]])) {
                        self::$varGroups[$data[0]][] = $listTag;
                    } else {
                        self::$varGroups[$data[0]] = array();
                        self::$varGroups[$data[0]][] = $listTag;
                    }
                }
            }
        $this->_addListTools($item);
        
        }
        //IMAGES
        $query = '//img[contains(@title, "' . $this->_start . '") '; 
        $query .= 'and contains(@title, "' . $this->_end . '")]';
        $images = $this->_xpath->query($query); 

        foreach ($images as $image) {
            $pretit = $image->getAttribute('title');
            $tit = $this->_extractVarName($pretit);
            if ($tit !== NULL) {
                $image->setAttribute('data-image', $tit);
            }
        }
        
        //iDEAS Signatures
        //this parse for placeholders for iDEAS compatible signatures
        //One needs to include in the picture title the legend
        //##signature## or ##signature_*## where * stands for a natural number
        $query = '//img[contains(@title, "##signature")]'; 
        $signatures = $this->_xpath->query($query); 

        foreach ($signatures as $signature) {
            $ref = $signature->getAttribute('title');
            $tit = $this->_extractSigData($ref);
            if ($tit !== NULL) {
                $signature->setAttribute('data-signature', trim($tit));
            }
        }
        
        //we should parse now the structural info required to build a form
        //associated with this template
        self::$structure = array();
        $this->_getStructuralInfo($this->_dom->documentElement, 
                                       self::$structure);
    }
    
    /**
     * Inserts an add list item button at the end of the table
     *
     * @param DOMNode $item
     * @return string
     * @access private
     */
    private function _addListTools($item)
    {
        $className = 'h5p_listitem';
        $class = $item->getAttribute('class');
        if (\strpos($class, $className) === false) {
            $class .= ' ' . $className;
            $item->setAttribute('class', $class);
        }
    }
    
    /**
     * Inserts an add row bytton at the end of the table
     *
     * @param DOMNode $table
     * @return string
     * @access private
     */
    private function _addTableTools($table)
    {
        //add an additional class to the table for further styling
        $className = 'h5p_table';
        $class = $table->getAttribute('class');
        if (\strpos($class, $className) === false) {
            $class .= ' ' . $className;
            $table->setAttribute('class', $class);
        }
        //tag the table td's so we can later insert the add button with JS
        $query = './tbody/tr';
        $rows = $this->_xpath->query($query, $table);
        $numRows = $rows->length;
        $j = 0;
        //search SE td node
        while ($j < $numRows) {
            $td = $rows->item($j)->firstChild;
            $rowspan = $td->getAttribute('rowspan');
            if (!empty($rowspan)){
               $rowspan = 0;
            }
            $j = $j + 1 + $rowspan;
        }
        if (!empty($td)) {
            $td->setAttribute('data-tableloc', 'SE');
        }
        $td = NULL;
        $j = 0;
        //search SW td node
        while ($j < $numRows) {
            $td = $rows->item($j)->lastChild;
            $rowspan = $td->getAttribute('rowspan');
            if (!empty($rowspan)){
               $rowspan = 0;
            }
            $j = $j + 1 + $rowspan;
        }
        if (!empty($td)) {
            $td->setAttribute('data-tableloc', 'SW');
        }
    }
    
    /**
     * Extracts the name of the image variable
     *
     * @param string $str
     * @return string
     * @access private
     */
    private function _extractVarName($str)
    {
        $regex ='/' . $this->_start . '(.*)' . $this->_end . '/';
        \preg_match($regex, $str, $match);
        if (isset($match[1])) {
            return $match[1];
        } else {
            return NULL;
        }
    }
    
    /**
     * Extracts the info of the signature placeholder
     *
     * @param string $str
     * @return string
     * @access private
     */
    private function _extractSigData($str)
    {
        $regex ='/##signature' . '(.*)' . '##/';
        \preg_match($regex, $str, $match);
        if (isset($match[1])) {
            if (!empty($match[1])) {
                return \substr($match[1], 1);
            } else {
                return "1";
            }
        } else {
            return NULL;
        }
    }
    
     /**
     * Extracts the name of the image variable
     *
     * @param DOMNode $node
     * @param array $strut 
     * @return void
     * @access private
     */
    private function _getStructuralInfo($node, &$strut)
    {
        $childs = $node->childNodes;
        foreach ($childs as $child){
            //we should distinguish between plain variable nodes,
            //table nodes, list nodes, bookmark nodes and images
            if ($child->nodeType != 3 && isset($child->tagName)) {
                $name = $child->tagName;
                switch ($name) {
                    case 'section':
                        $id = $child->getAttribute('id');
                        $obj = new \stdClass();
                        $obj->element = 'section';
                        $obj->name = $id;
                        $obj->label = $id;
                        $obj->description = '';
                        $obj->active = true;
                        if ($child->hasChildNodes()) {
                            $obj->childs = array();
                            $this->_getStructuralInfo($child, $obj->childs);
                        }
                        $strut[] = $obj;
                        break;
                    case 'tbody':
                    case 'div':
                    case 'li':
                        $type = $child->getAttribute('data-type');
                        if (!empty($type)) {
                            if ($name == 'tbody') {
                                $dataId = $child->parentNode
                                                ->getAttribute('data-id');
                            } else {
                                $dataId = $child->getAttribute('data-id');
                            }
                            $dataH5p = $child->getAttribute('data-h5p');
                            $obj = new \stdClass();
                            $obj->id = $dataId;
                            $obj->element = $type;
                            $obj->name = $dataH5p;
                            $obj->active = true;
                            $obj->childs = new \stdClass();
                            if ($child->hasChildNodes()) {
                                $obj->childs = array();
                                $this->_getStructuralInfo($child, $obj->childs);
                            }
                            $strut[] = $obj;
                        } else {
                            $this->_getStructuralInfo($child, $strut);
                        }
                        break;
                    case 'img':
                        $type = $child->getAttribute('data-image');
                        if (!empty($type)) {
                            $obj = new \stdClass();
                            $obj->element = 'image';
                            $obj->name = $type;  
                            $obj->active = true;
                            $strut[] = $obj;
                        }
                        //check if it is a signature placeholder
                        $typeDoc = $child->getAttribute('data-signature');
                        if (!empty($typeDoc)) {
                            $obj = new \stdClass();
                            $obj->element = 'signature';
                            $obj->name = $typeDoc;  
                            $obj->active = true;
                            $strut[] = $obj;
                        }
                        break;
                    default:
                        $this->_getStructuralInfo($child, $strut);
                }
            } else if ($child->nodeType == 3) {
                //this is already a text node and we should extract the variable
                //names
                $regex ='/' . $this->_start;
                $regex .= '([^' . $this->_end . ']*)';
                $regex .= $this->_end . '/';
                \preg_match_all($regex, $child->nodeValue, $match);
                if(count($match[1]) > 0){
                    foreach($match[1] as $value) {
                        $obj = new \stdClass();
                        $obj->element = 'var';
                        $obj->name = $value;
                        $obj->active = true;
                        $strut[] = $obj;
                    }
                }
            }
        }
    }
    
    /**
     * Includes an additional class in the table row
     *
     * @param DOMNode $row
     * @return void
     * @access private
     */
    private function _rowStyle($row)
    {   
        $className = 'h5p_rowgroup';
        $class = $row->getAttribute('class');
        if (\strpos($class, $className) === false) {
            $class .= ' ' . $className;
            $row->setAttribute('class', $class);
        }
    }
    
    /**
     * Wraps the given nodes in a div
     *
     * @param DOMNodeList $nodes
     * @param string $bm bookmark name
     * @return void
     * @access private
     */
    private function _wrapNodes($nodes, $bm)
    {   
        $groupId = \uniqid('', true);
        $groupId = \str_replace('.', '', $groupId);
        $cont = $nodes->item(0)->ownerDocument->createElement('div');
        $cont->setAttribute('class', 'h5p_container_div');
        $div = $nodes->item(0)->ownerDocument->createElement('div');
        $div->setAttribute('class', 'h5p_wrappingdiv');
        $div->setAttribute('data-type', 'block');
        $div->setAttribute('data-id', $groupId);
        $div->setAttribute('data-h5p', $bm);
        
        //start the insertion of the selected nodes
        foreach ($nodes as $node) {
            $text = $node->nodeValue;
            //extract the variable names
            $base = \explode($this->_start, $text);
            if ($this->_start != $this->_end) {
                for ($j = 0; $j < count($base); $j++) {
                    $data = \explode($this->_end, $base[$j]);
                    if (!empty($data[0]) && count($data) > 1){
                        self::$groups['block'][$groupId]['variables'][] = $data[0];
                        self::$groups['block'][$groupId]['bookmark'] = $bm;
                    }
                    if (isset(self::$varGroups[$data[0]])) {
                        self::$varGroups[$data[0]][] = $bm;
                    } else {
                        self::$varGroups[$data[0]] = array();
                        self::$varGroups[$data[0]][] = $bm;
                    }
                }
            }
            $new = $node->cloneNode(true);
            $div->appendChild($new);
        }
        $cont->appendChild($div);
        $nodes->item(0)->parentNode->insertBefore($cont, $nodes->item(0));
        //remove the copied nodes
        $length = $nodes->length;
        for ($j = $length -1; $j > -1; $j--) {
            $nodes->item($j)->parentNode->removeChild($nodes->item($j));
        }
    }

}