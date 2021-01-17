<?php
/**
 * This sample script inserts a 3D pie chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a 3D pie chart
$data = array(
              'category_1' => 3,
              'category_2' => 5,
              'category_3' => 4.3,
             );
$chartProperties = array('data-label-number' => 'percentage', 'label-position' => 'center');
$doc->paragraph()->style('text-align: center')
        ->chart('3DPie', array('data' => $data, 'chart-properties' => $chartProperties))
			->style('width: 14cm; height: 12cm')
			->chartLegend();
//include in the render method the path where you want your document to be saved
$doc->render('pie_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'pie_chart' . $format . '">Download document</a>';