<?php
/**
 * This sample script inserts a 3D line chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a 3D line chart
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$dataStyles = array(
                    array(
                          'opacity' => '85%',
                          ),
                    array(
                          'opacity' => '85%',
                          ),
                    );
$doc->paragraph()->style('text-align: center')
		->chart('3Dline')->style('width: 12cm; height: 9cm;')
			->chartData($data, $dataStyles)
			->chartLegend();
//include in the render method the path where you want your document to be saved
$doc->render('line_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'line_chart' . $format . '">Download document</a>';