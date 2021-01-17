<?php
/**
 * This sample script inserts a basic column chart
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();

$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a basic column bar chart
$html = '
<html>
    <head>
        <style>
         
        </style>
    </head>
    <body>
         
        <table border="1" style="width:600px;" >
            <tr>
                <th class="firstCol">Table title</th>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
            <tr class="odd">
                <td class="firstCol">Row 1</td>
                <td class="odd">Cell_1_1</td>
                <td class="odd">Cell_1_2</td>
            </tr>
            <tr>
                <td class="firstCol">Row 2</td>
                <td>Cell_2_1</td>
                <td>Cell_2_2</td>
            </tr>
        </table>
    </body>
</html>
';
$doc->html(array('html' => $html));
$data = array(
                'series' => array('F'),
                'Category 1' => array(20),
                'Category 2' => array(30),
                'Category 3' => array(12.5),
                'Category 4' => array(12.5),
                'Category 5' => array(12.5),
                'Category 6' => array(12.5),
              );
$chartProperties = array('data-label-number' => 'percentage', 'label-position' => 'center');
$doc->paragraph()->style('text-align: center')
        ->chart('column', array('data' => $data,'chart-properties' => $chartProperties))->style('border: 1pt solid #777; width: 10cm; height: 6cm; padding: 0.2cm')	->chartLegend();
//include in the render method the path where you want your document to be saved
$doc->render('basic_column_chart' . $format); 
//echo a link to the generated document
 echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'basic_column_chart' . $format . '">Download document</a>';