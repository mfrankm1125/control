<?php
/**
 * This sample script inserts a custom column chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a custom column bar chart
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$dataStyles = array(
                    array(
                          'fill-color' => '#b70000',
                          'stroke' => 'solid',
                          'stroke-color' => '#660000',
                          'stroke-width' => '2pt',
                          ),
                    array(
                          'fill-color' => '#0000b7',
                          'stroke' => 'solid',
                          'stroke-color' => '#000066',
                          'stroke-width' => '2pt',
                          ),
                    );
$doc->paragraph()->style('text-align: center')
        ->chart('column')->style('width: 12cm; height: 9cm;')
            ->chartData($data, $dataStyles);
//include in the render method the path where you want your document to be saved
$doc->render('custom_column_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'custom_column_chart' . $format . '">Download document</a>';