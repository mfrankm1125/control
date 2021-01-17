<?php
/**
 * This sample script inserts some (extended) HTML5 code into the document
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//html code
$html = '
<html>
    <head>
        <style>
        body {font-family: Calibri; font-size: 11pt}
        .niceTable {border-collapse: collapse}
        .niceTable td {border: 1px solid #657899; padding: 2px 5px; width: 5cm; margin: 0}
        .niceTable th {vertical-align: bottom; border-bottom: 1px solid #657899 !important; padding: 2px 5px; width: 5cm; font-weight: bold; margin: 0}
        .niceTable th.firstCol {font-style: italic; border: none; text-align: right; background-color: white}
        .niceTable td.firstCol {font-style: italic; border: none; border-bottom: 1px solid #ffffff !important; text-align: right; background-color: white}
        .odd {background-color: #d5e0ff}
        </style>
    </head>
    <body>
        <p>Just a nicely formatted table:</p>
        <table class="niceTable">
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
$doc->render('nice_table_html' . $format);   
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'nice_table_html' . $format . '">Download document</a>';