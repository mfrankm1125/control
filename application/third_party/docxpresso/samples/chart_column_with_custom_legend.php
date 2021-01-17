<?php
/**
 * This sample script inserts a column chart with a custom legend
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a column bar chart with a custom legend
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$doc->paragraph()->style('text-align: center')
        ->chart('column', array('data' => $data))->style('width: 12cm; height: 10cm; padding: 0.2cm')
            ->chartLegend(array('legend-position' => 'top', 'stroke' => 'solid', 'stroke-color' => '#cccccc'));
//include in the render method the path where you want your document to be saved
$doc->render('custom_legend_column_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'custom_legend_column_chart' . $format . '">Download document</a>';