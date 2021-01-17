<?php

/**
 * Plain text
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
 * Inserts plain text into a pargraph or span
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class PlainText extends ODFElement
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
        'text:p' => true, 
        'text:span' => true,
    );
       
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
        $target = $odfNode->getTarget();
        parent::__construct($node, $odfNode, $target);
        if (isset(self::$parentNodes[$node->nodeName])) {
            $parsedText = \htmlspecialchars(\html_entity_decode($text, ENT_QUOTES|ENT_HTML5|ENT_HTML401, 'UTF-8'), ENT_NOQUOTES);
            $textNode = $node->ownerDocument->createTextNode($parsedText);
            $node->appendChild($textNode);
        } else {
            $message = 'You can not add plain text to the parent element.';
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