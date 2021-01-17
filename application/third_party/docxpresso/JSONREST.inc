<?php

/**
 * JSON REST API
 *
 * @copyright  Copyright(c)2017 No-nonsense Labs (http://www.nononsenselabs.com)
 * @license    http://www.docxpresso.com/licenses
 * @link       http://www.docxpresso.com
 * @version    3.5
 * @since      3.1
 */

namespace Docxpresso;

use Docxpresso\Commons as commons;

require_once 'CreateDocument.inc';

/**
 * Allows for the remote creation of documents
 *
 * @package    Docxpresso
 * @subpackage REST
 */

class JSONREST
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
        'doc(legacy)' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'rtf' => 'application/rtf',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
    );
    
    /**
     * This object is generated from the input JSON
     * 
     * @var Object
     * @access private
     */
    private $_jsonObj;
    
    /**
     * This is an instance of the Docxpresso CreateDocument class 
     * 
     * @var CreateDocument
     * @access private
     */
    private $_doc;
    
    /**
     * Path to the generated doc
     * 
     * @var string
     * @access private
     */
    private $_docPath;
    
    /**
     * Error message 
     * 
     * @var mixed
     * @access private
     */
    private $_errorMessage;
    
    /**
     * HTTP response status code 
     * 
     * @var integer
     * @access private
     */
    private $_statusCode;
    
    
    /**
     * Construct
     * @param string $json
     * @access public
     */
    public function __construct($json)
    {
        $this->_errorMessage = false;
        self::$config = \parse_ini_file( 'config.ini', true);
        $this->_jsonObj = json_decode($json, true);
        if ($this->_jsonObj === NULL) {
            $this->_statusCode = 400;
            $this->_throwRESTError('incorrect JSON format');
            return;
        }
        //start to extract all required info
        //security
        $access = $this->_grantAccess();
        if ($access !== true) {
            $this->_statusCode = 403;
            $this->_throwRESTError($access);
        }
        //create an instance of Docxpresso
        if (isset($this->_jsonObj['template'])) {
            if (\file_exists($this->_jsonObj['template'])) {
                $data = array('template' => $this->_jsonObj['template']);
                $this->_doc = new CreateDocument($data); 
                if (!isset($this->_jsonObj['output']['repairVariables'])
                    || $this->_jsonObj['output']['repairVariables'] == 'true') {
                    $this->_doc->repairVariables();
                }
            } else {
                $tError = "There were problems opening the requested template";
                $this->_statusCode = 400;
                $this->_throwRESTError($tError);
                return;
            }
        } else {
            $this->_doc = new CreateDocument();
        }
        if ($this->_errorMessage === false) {
            //Data sources
            $this->_parseDataSources();
        }
        if ($this->_errorMessage === false) {
            //direct replacements
            $this->_parseRemovers();
        }
        if ($this->_errorMessage === false) {
            //direct replacements
            $this->_parseCloners();
        }
        if ($this->_errorMessage === false) {
            //direct replacements
            $this->_parseReplacements();
        }
        if ($this->_errorMessage === false) {
            //HTML insertions
            $this->_parseHTML();
        }
        if ($this->_errorMessage === false) {
            //Charts
            $this->_parseCharts();
        }

        if ($this->_errorMessage === false) {
            $format = \strtolower($this->_jsonObj['output']['format']);
            $name = $this->_jsonObj['output']['name'];
            $time = \time();
            $base = self::$config['RESTAPI']['basePath'];
            $this->_docPath = $base . '/' . $name . '_' . $time . '.' . $format;
            $this->_doc->render($this->_docPath);
        }
    }
    
    /**
     * API response 
     * 
     * $param array $response
     * @return array
     * @access public
     */
    public function response() 
    {
        
        //get some formatting info
        if (!isset($this->_jsonObj['output'])){
            $ext = 'odt';
            $doc = false;
            $name = 'sample';
        } else {
            if (!isset($this->_jsonObj['output']['format'])) {
                $ext = 'odt';
            } else {
                $ext = $this->_jsonObj['output']['format'];
                if (!isset($this->_contentTypes[$ext])) {
                    $this->_statusCode = 400;
                    $this->_errorMessage = 'Unavailable format: ' . $ext;
                    $this->_errorResponse();
                    return;
                }
            }
            if (!isset($this->_jsonObj['output']['name'])) {
                $name = 'sample';
            } else {
                $name = $this->_jsonObj['output']['name'];
            }
            if (!isset($this->_jsonObj['output']['response'])) {
                $doc = false;
            } else {
                $doc = $this->_jsonObj['output']['response'];
            }
        }

        if ($this->_errorMessage !== false) {
            $sapi_type = php_sapi_name();
            if (substr($sapi_type, 0, 3) == 'cgi'){
                header('Status: ' . $this->_statusCode . ' ' . 
                        $this->_codes[$this->_statusCode]);
            } else {
                header('HTTP/1.1 ' . $this->_statusCode . ' ' . 
                        $this->_codes[$this->_statusCode]);
            }
            echo $this->_errorMessage;
            $this->_errorResponse();
            return;
        } else {
            if ($doc !== 'doc') {
                header('Content-Type:text/plain'); 
                echo $this->_docPath;
            } else {
                header('Content-Type: ' . $this->_contentTypes[$ext]);
                header('Content-Disposition: attachment; filename="' . 
                        $name . '.' . $ext . '"');
                header('Content-Transfer-Encoding: binary');
                $counter = 0;
                $max = ini_get('max_execution_time') - 2; 
                while( !file_exists($this->_docPath) && $counter < $max){
                    sleep(1);
                    $counter++;
                }
                if (file_exists($this->_docPath)) {
                    header('Content-Length: ' . filesize($this->_docPath));
                    readfile($this->_docPath);
                } else {
                    $this->_statusCode = 503;
                    $this->_errorMessage = 'There was a problem ';
                    $this->_errorMessage = 'generating the requested file.';
                    $this->_errorResponse();
                    return;
                }
            }
        }
    }
    
    /**
     * Send an error response 
     * 
     * @return void
     * @access private
     */
    private function _errorResponse() 
    {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi'){
            header('Status: ' . $this->_statusCode . ' ' . 
                    $this->_codes[$this->_statusCode]);
        } else {
            header('HTTP/1.1 ' . $this->_statusCode . ' ' . 
                    $this->_codes[$this->_statusCode]);
        }
        echo $this->_errorMessage;
    }
    
    /**
     * Run individual queries 
     * 
     * $param array $query
     * $param PDO $DBHandle
     * @return void
     * @access private
     */
    private function _execQuery($query, $DBHandle) 
    {
        $q = $DBHandle->prepare($query['query']);
        $q->setFetchMode(\PDO::FETCH_OBJ);
        if (isset($query['parameters'])) {
            $q->execute($query['parameters']);
        } else {
            $q->execute();
        }
        //let us prepare the array to handle the required data values
        $dataBindings = $query['dataBindings'];
        $varValues = array();
        foreach ($dataBindings as $key => $val) {
            $varValues[$val] = array('value' => array());
        }
        while($row = $q->fetch()) {
            foreach($row as $key => $val){
                if (isset($dataBindings[$key])){
                    $varValues[$dataBindings[$key]]['value'][] = $val;
                }
            }
        }
        //check if values should be grouped by values
        //In that case we erase all values but first appearance. In order to get
        //reliable results the query resulting rows should have been previously
        //grouped in the SQL query
        if (isset($query['groupBy'])) {
            foreach ($query['groupBy'] as $group) {
                $varValues = $this->_groupBy($varValues, $group);
            }
        }
        //prepare the Docxpresso command
        $this->_doc->replace($varValues, $query);
    }
    
    /**
     * Checks REST API key to grant access
     * 
     * @return mixed a boolean or string value
     * @access private
     */
    private function _grantAccess() 
    {
        //extract security level
        $level = self::$config['RESTAPI']['securityLevel'];
        if ($level == 0) {
            return true;
        } 
        //extract security data
        $APIKEY = $this->_jsonObj['security']['APIKEY'];
        if (empty($APIKEY)) {
            return 'APIKEY not given';
        }
        $timestamp = $this->_jsonObj['security']['timestamp'];
        if (empty($timestamp)) {
            return 'timestamp not given';
        }
        $publicKey = $this->_jsonObj['security']['publicKey'];
        $pKey = self::$config['RESTAPI']['publicKey'];
        if (empty($publicKey)) {
            return 'publicKey not given';
        } else if ($publicKey != $pKey) {
            return 'Incorrect public key';
        }
        $privateKey = self::$config['RESTAPI']['privateKey'];

        $expires = self::$config['RESTAPI']['expires'];
        if ($level = 2){
            //get current timestamp
            $current = time();
            $delay = abs($current - $timestamp);
            if ($delay > $expires) {
                return 'Invalid or expired timestamp';
            }    
        }
        $control = sha1($publicKey . $privateKey . $timestamp);
        $check = commons\Utilities::apikey_control($APIKEY, $control, $privateKey);
        if ($check) {
            return true;
        } else {
            return 'Invalid APIKEY';
        } 
    }
    
    /**
     * Prepares the data rows to be "grouped" by a certain value.
     * We do so by emptying the repeated var values so they are not "printed"
     * more than once
     * 
     * @param array $vars
     * @param string $group
     * @return array
     * @access private
     */
    private function _groupBy($vars, $group) 
    {
        $ref = '';
        if (isset($vars[$group])) {
            $len = \count($vars[$group]['value']);
            for($j = 0; $j < $len; $j++) {
                if ($vars[$group]['value'][$j] == $ref) {
                    $vars[$group]['value'][$j] = '';
                } else {
                    $ref = $vars[$group]['value'][$j];
                }
            }
        }
        return $vars;
    }
    
    /**
     * Parses data sources 
     * 
     * @return void
     * @access private
     */
    private function _parseDataSources() 
    {
        if (!empty($this->_jsonObj['dataSources'])){
            $dataSources = $this->_jsonObj['dataSources'];
            if (!empty($dataSources)){
                foreach ($dataSources as $dataSource) {
                    $conPath = $dataSource['connector'];
                    $conJSON = \file_get_contents($conPath);
                    $con = json_decode($conJSON);
                    $strCon = $con->engine .':';
                    $strCon .= 'host=' . $con->host . ';';
                    $strCon .= 'dbname=' . $con->dbname;
                    if ($con->engine == 'mysql') {
                        //only work for PHP > 5.3.6
                        //otherwise use the option 
                        //array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8")
                        //in the PDO constructor
                        $strCon .= ';charset=utf8';
                    }
                    try {
                        $DBHandle = new \PDO($strCon, 
                                             $con->user, 
                                             $con->password);
                        $DBHandle->setAttribute( \PDO::ATTR_ERRMODE, 
                                                 \PDO::ERRMODE_EXCEPTION );
                    }
                    catch(PDOException $e) {
                        $this->_statusCode = 400;
                        $this->_throwRESTError($e->getMessage());
                    }
                    $queries = $dataSource['queries'];
                    foreach ($queries as $query) {
                        $this->_execQuery($query, $DBHandle);
                    }
                }
            }
        }
    }
    
    /**
     * Parses charts data
     * 
     * @return void
     * @access private
     */
    private function _parseCharts() 
    {
        if (!empty($this->_jsonObj['charts'])) {
            $charts = $this->_jsonObj['charts'];
            foreach ($charts as $chart) {
                $this->_doc->replaceChartData($chart['var'], $chart);
                //var_dump($chart['var']);
            }
        }
    }
    
    /**
     * Parses HTML insertions
     * 
     * @return void
     * @access private
     */
    private function _parseHTML() 
    {
        if (!empty($this->_jsonObj['HTML'])) {
            $HTMLs = $this->_jsonObj['HTML'];
            foreach ($HTMLs as $HTML) {
                $HTML['html'] = \rawurldecode($HTML['html']);
                $this->_doc->html($HTML);
                //var_dump($HTML);
            }
        }
    }
    
    /**
     * Parses the cloning of document elements
     * 
     * @return void
     * @access private
     */
    private function _parseCloners() 
    {
        $options = array();
        if (!empty($this->_jsonObj['clone'])) {
            $cloners = $this->_jsonObj['clone'];
            foreach ($cloners as $cloner) {
                foreach ($cloner['options'] as $index => $value) {
                    $options[$index] = $value;
                }
                $this->_doc->cloneContent($options);
            } 
        }
    }
    
    /**
     * Parses the removal of document elements
     * 
     * @return void
     * @access private
     */
    private function _parseRemovers() 
    {
        $options = array();
        if (!empty($this->_jsonObj['remove'])) {
            $removers = $this->_jsonObj['remove'];
            foreach ($removers as $remover) {
                foreach ($remover['options'] as $index => $value) {
                    $options[$index] = $value;
                }
                $this->_doc->removeContent($options);
            }  
        }
    }
    
    /**
     * Parses direct replacements
     * 
     * @return void
     * @access private
     */
    private function _parseReplacements() 
    {
        if (!empty($this->_jsonObj['replace'])) {
            $replaces = $this->_jsonObj['replace'];
            foreach ($replaces as $replace) {
                $vars = array();
                foreach ($replace['vars'] as $index => $var) {
                    $vars[$var['var']] = $var;
                }
                $options = array();
                if (!empty($replace['options'])){
                    $options = $replace['options'];
                }
                $this->_doc->replace($vars, $options);
            }   
        }
    }
       
    /**
     * Throws an error message
     * 
     * @param string $message error message
     * @return string
     * @access private
     */
    private function _throwRESTError($message='') 
    {
        $this->_errorMessage = $message;
        if (empty($this->_statusCode)){
            $this->_statusCode = 403;
        }
        return;
    }
    
    
}