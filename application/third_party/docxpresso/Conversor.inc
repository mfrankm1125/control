<?php

/**
 * Conversor API
 *
 * @copyright  Copyright(c)2017 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    3.5
 * @since      3.5
 */

namespace Docxpresso;

use Docxpresso\Commons as commons;
use Docxpresso\Core as core;

require_once 'CreateDocument.inc';

/**
 * Allows for the remote creation of documents
 *
 * @package    Docxpresso
 * @subpackage Conversor
 */

class Conversor
{
    /**
     * this array holds the data parsed from the config.ini file
     * 
     * @var array
     * @access public
     * @static
     */
    public static $config;
    
    /**
     * HTTP response status codes 
     * 
     * @var array
     * @access private
     */
    private $_codes = array(
        '400' => 'Bad Request',
        '403' => 'Forbidden',
        '503' => 'Service Unavailable'
    );
    
    /**
     * Content types 
     * 
     * @var array
     * @access private
     */
    private $_contentTypes = array(
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'rtf' => 'application/rtf',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'csv' => 'text/csv',
        'txt' => 'text/plain'
    );
    
    /**
     * available conversions 
     * 
     * @var array
     * @access private
     */
    private $_conversions = array(
        'txt' => array('pdf', 'docx', 'doc', 'odt', 'rtf'),
        'doc' => array('pdf', 'docx', 'txt', 'odt', 'rtf'),
        'docx' => array('pdf', 'doc', 'txt', 'odt', 'rtf'),
        'odt' => array('pdf', 'docx', 'doc', 'txt', 'rtf'),
        'rtf' => array('pdf', 'docx', 'doc', 'odt', 'txt'),
        'ods' => array('pdf', 'xlsx', 'xls', 'csv'),
        'xls' => array('pdf', 'xlsx', 'ods', 'csv'),
        'xlsx' => array('pdf', 'xls', 'ods', 'csv'),
        'csv' => array('pdf', 'xlsx', 'xls', 'ods'),
        'odp' => array('pdf', 'pptx', 'ppt'),
        'ppt' => array('pdf', 'pptx', 'odp'),
        'pptx' => array('pdf', 'ppt', 'odp'),
    );
    
    
    /**
     * Construct
     * @param string $src the path to the document to be converted
	 * @param string $target the path for the converted document
     * @param array $options rendering options with the following keys
     * and values
     * The avalaible keys and values are:
     *  'soffice' => (string) path to soffice.exe . If not explicitely given
     *   the path for soffice stored in config.ini is used.
     *  'legacy' => (bool) if true converts the ouput to Word 97 compatible
     *   .doc file. Only applies to .doc format.
     *  'PageRange' => (string) if empty (default) includes all pages. If a 
     *   single number it renders only the corresponding page and if a range,
     *   i.e. 1-3 renders from the page 1 to three both included.
     *  'UseLosslessCompression' => (bool) if true images are not compressed. 
     *   The default is false.
     *  'Quality' => (int) the quality of the compressed images (JPG). 
     *   Deafault value is 90.
     *  'ReduceImageResolution' => (bool) if true the image resolution is 
     *   changed. Default is false.
     *  'MaxImageResolution' => (int) maximum image resolution. 
     *   Default value is 300.
     *  'SelectPdfVersion => (int) PDF version:
     *      0: PDF 1.4 (default).
     *      1: PDF/A-1 (ISO 19005-1:2005).
     *  'UseTaggedPDF => (bool) if true the generated PDF will be tagged for 
     *   accesibility. Default value is false.
     *  'ExportFormFields => (bool) exports form fields. Default value is true.
     *  'FormsType' => (int) form protocol:
     *      0: FDF.
     *      1: PDF.
     *      2: HTML(default value).
     *      3: XML.
     *  'ExportBookmarks' => (bool) exports bookmarks. Default value is true.
     *  'EmbedStandardFonts' => (bool) embeds the standard PDF fonts. Default 
     *   value is false.
     *  'Watermark' => (string) watermark text. Default is an empty string.
     *  'InitialView' => (int) initial view:
     *      0: (default) default view mode, neither outlines or thumbnails.
     *      1: outline pane opened.
     *      2: thumbnail pane opened.
     *  'Magnification' => (int) PDF magnification on openning:
     *      0: (default) default magnification.
     *      1: fit entire page within the viewer window.
     *      2: fit entire page width within the viewer window.
     *      3: fit entire page width (cutting margins) within the viewer window.
     *      4: opens with the zoom specified in the Zoom option.
     *  'Zoom' => (int) zoom level on openning. Default is 100 (it only 
     *   applies if Maginification equals 4). The available range is 50-1600.
     *  'PageLayout' => (int) PDF layout on openning:
     *      0: (default) default viewer configuration.
     *      1: one page at a time.
     *      2: display pages in one column.
     *      3: display pages in two columns.
     *  'FirstPageOnLeft' => (bool) if true the first page should be on the 
     *   left. Default is false (only used if PageLayout equals 3).
     *  'CenterWindow' => (bool) if true the PDF window is centered in the 
     *   screen. Default is false.
     *  'OpenInFullScreenMode' => (bool) if true the PDF opens in full screen 
     *   mode. Default is false.
     *  'DisplayPDFDocumentTitle' => (bool) if true the PDF window shows the 
     *   document title. Default is true.
     *  'HideViewerMenubar' => (bool) if true hides the viewer menu. Default 
     *   is false.
     *  'HideViewerToolbar' => (bool) if true hides the viewer toolbar. Default 
     *   is false.
     *  'HideViewerWindowControls' => (bool) if true hides the viewer window 
     *   controls. Default is false.
     *  'EncryptFile' => (bool) if true encrypts the file. Default is false.
     *  'DocumentOpenPassword' => (string) password to open file if the option 
     *   EncryptFile is set to true. Default is an empty string.
     *  'RestrictPermissions' => (bool) if true restricts you may restrict 
     *   printing, copying and modifying. Default is false.
     *  'PermissionPassword' => (string) password to to access the restricted 
     *   functionalities if the option DocumentOpenPassword is set to true. 
     *   Default is an empty string.
     *  'Printing' => (int) printing configuration:
     *      0: the document can not be printed.
     *      1: the document can only be printed in low resolution.
     *      2: (default) the document can be printed with no restrictions.
     *  'Changes' => (int) specify which changes are allowed:
     *      0: protected against any change.
     *      1: pages can be added, deleted or rotated.
     *      2: filling forms.
     *      3: filling forms and adding comments.
     *      4: (default) all changes are allowed but page extraction.
     *  'EnableCopyingOfContent' => (bool) if true the content can be copied 
     *   and pasted elsewhere. Default is true.
     * 
     * @access public
     */
    public function __construct($source, $target, $options = array())
    {
        if ($source == $target) {
            $message = 'The target file path can not equal the source path';
            commons\DOCXPRESSOLogger::logger($message, 'critical');   
            exit('The target file path can not equal the source path.');
        }
        //get source extension
        $srcTemp = explode('.', $source);
        $srcExt = array_pop($srcTemp);
        //get target extension
        $targetTemp = explode('.', $target);
        $targetExt = array_pop($targetTemp);
        if (!isset($this->_conversions[$srcExt])
            || !in_array($targetExt, $this->_conversions[$srcExt])) {
            $message = 'The required document formats are not available';
            commons\DOCXPRESSOLogger::logger($message, 'critical');   
            exit('The required document formats are not available');
        }
        //we have to write in the CSV folder
        $baseURI = \getcwd();
            
        //fill the lacking $options
        $options['outputFormat'] = \strtoupper($targetExt);
        
        if (!isset($options['legacy']) && $options['outputFormat'] == 'DOC') {
            $options['legacy'] = true;
        }
		if (!isset($options['legacy']) && $options['outputFormat'] == 'ODT') {
            $options['legacy'] = true;
        }
        if ($this->_isAbsolutePath($source)) {
            $options['source'] = $source;
        } else {
            $options['source'] = $baseURI . '/' . $source;
        }   

        if ($this->_isAbsolutePath($target)) {
            $options['target'] = $target;
        } else {
            $options['target'] = $baseURI . '/' . $target;
        } 

        //create an instance of the Render class
        $render = new core\Render();
        $opt = $render->options($options);
        //we have to remove the options that have no default value
        $opt = \str_replace('_nil_', '', $opt);
        $tmpName = '/csv/' . microtime(true) . '-' . mt_rand(9, 99999999);
        $tmpFile = fopen( __DIR__ . $tmpName . '.tmp' , 'w');
        \fwrite($tmpFile, $opt);
        \fclose($tmpFile);
        \rename(__DIR__ . $tmpName . '.tmp', __DIR__ . $tmpName . '.csv');
		
    }
    
	
    /**
     * detects if a path is absolute or relative
     * 
     * @param string $path
     * @return boolean
     * @access private
     */
    private function _isAbsolutePath($path) 
    {
        $initial = \substr($path, 0, 1);
        if ($initial == '/' | $initial == '\\') {
            return true;
        }
        $regex = '/^[a-zA-Z0-9]?:/';
        $volume = \preg_match($regex, $path);
        if ($volume == 1) {
            return true;
        }
        return false;
    }
    
    
}