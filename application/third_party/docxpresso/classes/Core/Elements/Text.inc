<?php

/**
 * Text
 *
 * @copyright  Copyright(c)2017 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    3.5
 * @since      1.0
 */

namespace Docxpresso\Core\Elements;

use Docxpresso\Commons as commons;

/**
 * Creates a span element
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class Text extends ODFElement
{
    /**
     * allowed parent nodes
     * 
     * @var array
     * @access public
     * @static
     */
    public static $parentNodes = array(
        'text:a' => true, 
        'text:h' => true, 
        'text:meta' => true, 
        'text:meta-field' => true, 
        'text:p' => true, 
        'text:ruby-base' => true,
        'text:span' => true,
        //also include the custom office:fragment tag
        'office:fragment' => true,
    );
    /**
     * reference to the created node
     * 
     * @var DOMNode
     * @access private
     */
    private $_newNode;
       
    /**
     * Construct
     *
     * @param ODFElement $odfNode
     * @param string $text
     * @access public
     */
    public function __construct($odfNode, $text = '')
    {
        $node = $odfNode->getNode();
        $styleId = $odfNode->getStyleId();
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $textNode = $node->ownerDocument->createElement('text:span');
            //the following is just to protect ampersands and non usual
            //html entities like &frac12;
            $parsedText = \htmlspecialchars(\html_entity_decode($text, ENT_QUOTES|ENT_HTML5|ENT_HTML401, 'UTF-8'), ENT_NOQUOTES);
            $textNode->nodeValue = $parsedText;
            if (!empty($styleId)){
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $textNode->setAttributeNS($ns, 'text:style-name', $styleId);
            }
            $this->_newNode = $node->appendChild($textNode);
            $this->setNode($this->_newNode);
            $this->setNodeType($this->_newNode->nodeName);
        } else {
            $message = 'You can not add text to the parent element.';
            commons\DOCXPRESSOLogger::logger($message, 'critical');
        }
    }
    
    /**
     * Returns the default styles
     *
     * @param int $data
     * @return string
     * @access public
     */
    public static function getDefaultStyles($data)
    {
        $defaultStyles = self::setDefaultStyles($data);
        return $defaultStyles;
    }
    
    /**
     * Sets the default styles
     *
     * @param int $data
     * @return string
     * @access public
     */
    public static function setDefaultStyles($data)
    {
        //set the default styles
        $defaultStyles = '';
        return $defaultStyles;
    }
   
}