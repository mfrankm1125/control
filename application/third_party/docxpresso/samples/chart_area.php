<?php
/**
 * This sample script inserts an area chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert an area chart
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$dataStyles = array(
                    array(
                          'opacity' => '65%',
                          ),
                    array(
                          'opacity' => '65%',
                          ),
                    );
$doc->paragraph()->style('text-align: center')
		->chart('area')->style('width: 12cm; height: 9cm;')
			->chartData($data, $dataStyles)
			->chartLegend();
//include in the render method the path where you want your document to be saved
$doc->render('area_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'area_chart' . $format . '">Download document</a>';