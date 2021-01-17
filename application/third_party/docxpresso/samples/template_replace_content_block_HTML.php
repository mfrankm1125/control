<?php
/**
 * This sample script replaces "block-type" content
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_block_replace.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//replace single variable
$html ='
<html>
    <head>
    <style>
    * {font-family: Arial; font-size: 10pt}
     p {margin-bottom: 10pt}
     th {background-color: #5B9BD5; padding: 3px 7px}
     th p {color: #f6f6f6; font-weight: bold; font-size: 10pt; margin: 0}
     td {padding: 3px 7px; border: 0.5pt solid #ffffff}
     td p {color: #333; font-size: 10pt; margin: 0}
     .nice {width: 12cm; margin: auto}
     .firstCol {background-color: #5B9BD5;}
     .firstCol p {color: #f6f6f6; font-weight: bold; font-size: 10pt; margin: 0}
     .even {background-color: #BDD6EE;}
     .odd {background-color: #DEEAF6}
    </style>
    </head>
    <body>
        <table class="nice">
            <tr>
                <th><p>First Column</p></th>
                <th><p>Second Column</p></th>
                <th><p>Third Column</p></th>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 1</p></td>
                <td class="even"><p>C_1_1</p></td>
                <td class="even"><p>C_1_2</p></td>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 2</p></td>
                <td class="odd"><p>C_2_1</p></td>
                <td class="odd"><p>C_2_2</p></td>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 3</p></td>
                <td class="even"><p>C_3_1</p></td>
                <td class="even"><p>C_3_2</p></td>
            </tr>	
        </table>
    </body>
</html>';
$doc->replace(array('block_variable' => array('value' => $html)), array('block-type' => true));
//include in the render method the path where you want your document to be saved
$doc->render('replaced_block_HTML' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'replaced_block_HTML' . $format . '">Download document</a>';