<?php
/**
 * This sample script inserts a bubble chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a bubble chart
//each data point array is given by (y, x, size) in pdf, odt and ref
//or (x, y, size) in doc format
$data = array(
               array(40, 10, 4),
               array(50, 20, 3),
               array(12, 30, 9),
               array(30, 40, 2),
               array(22, 50, 7),
              );
$doc->paragraph()->style('text-align: center')
		->chart('bubble')->style('width: 12cm; height: 9cm;')
			->chartData($data)
			->chartGrid('y', 'minor');
//include in the render method the path where you want your document to be saved
$doc->render('bubble_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'bubble_chart' . $format . '">Download document</a>';