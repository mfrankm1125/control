<?php
/**
 * This sample script replaces the data from a chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_replace_chartdata.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//replace chart data
$data = array( 
    'series' => array('Series 1', 'Series 2', 'Series 3'),
    'Category 1' => array(10,20, 10), 
    'Category 2' => array(30,10, 30), 
    'Category 3' => array(12.5, 50, 5),
    'Category 4' => array(40, 10, 10),
);
$doc->replaceChartData('', array('data' => $data));
//include in the render method the path where you want your document to be saved
$doc->render('replaced_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'replaced_chart' . $format . '">Download document</a>';