<?php
/**
 * This sample script inserts a radar chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a radar chart
$data = array(
                'series' => array('First', 'Second', 'Third'),
                'Cat 1' => array(20,40, 60),
                'Cat 2' => array(30,10, 10),
                'Cat 3' => array(12.5, 54),
                'Cat 4' => array(50, 20, 60),
                'Cat 5' => array(90, 54, 12),
              );
$doc->paragraph()->style('text-align: center')
		->chart('radar')->style('width: 12cm; height: 9cm;')
			->chartData($data)
			->chartLegend()
			->chartGrid('y', 'minor');
//include in the render method the path where you want your document to be saved
$doc->render('radar_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'radar_chart' . $format . '">Download document</a>';