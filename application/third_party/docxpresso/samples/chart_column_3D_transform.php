<?php
/**
 * This sample script modifies the default 3d view of a chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a 3D column bar chart and change its angle and perspective
$data = array(
                'series' => array('First series', 'Second series'),
                'Category 1' => array(20,40),
                'Category 2' => array(30,10),
                'Category 3' => array(12.5, 54),
              );
$chartProperties = array('data-label-number' => 'percentage', 'label-position' => 'center');

$doc->paragraph()->style('text-align: center')
        ->chart('3Dcolumn',array('chart-properties' => $chartProperties))->style('width: 12cm; height: 11cm; padding: 0.2cm')
            ->chartData($data)
            ->chartLegend(array('legend-position' => 'bottom'))
            ->chart3dTransform(array('right-angled-axes' => false, 'perspective' => 15, 'rotate-y' => 30));
//include in the render method the path where you want your document to be saved
$doc->render('transform_3D_column_chart' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'transform_3D_column_chart' . $format . '">Download document</a>';