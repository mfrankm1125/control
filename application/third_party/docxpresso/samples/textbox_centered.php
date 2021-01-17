<?php
/**
* This sample script inserts a centered textbox
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A centered textbox:'));
//insert a textbox
$doc->paragraph()->style('text-align: center;')
        ->textbox()->style('width: 6cm')
           ->paragraph(array('text' => 'First paragraph within the textbox.'))
           ->unorderedList(array('List item 1', 'List item 2'))
           ->paragraph(array('text' => 'Last paragraph within the textbox.'));
//insert some text
$doc->paragraph(array('text' => 'A final sentence.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_centered_textbox' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_centered_textbox' . $format . '">Download document</a>';