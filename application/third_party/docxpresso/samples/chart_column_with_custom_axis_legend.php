<?php
/**
 * This sample script inserts a column chart with a custom legend and axis
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a column bar chart with a custom legend and axis
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$doc->paragraph()->style('text-align: center')
        ->chart('column')->style('width: 12cm; height: 10cm; padding: 0.2cm')
            ->chartData($data)
            ->chartLegend(array('legend-position' => 'top', 'stroke' => 'solid', 'stroke-color' => '#cccccc'))
            ->chartAxis('y', array('maximum' => 120, 'minimum' => 5, 'stroke-color' => '#b70000', 'stroke-width' => '2pt'))
            ->chartAxis('x', array('stroke-color' => '#b70000', 'stroke-width' => '2pt'));
//include in the render method the path where you want your document to be saved
$doc->render('axis_legend_column_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'axis_legend_column_chart' . $format . '">Download document</a>';