<?php
/**
 * This sample script inserts a few runs of text with different formats
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a few runs of text
$doc->paragraph()
        ->text(array('text' => 'A paragraph with some '))
        ->text(array('text' => 'bold text ', 'style' => 'font-weight: bold'))
        ->text(array('text' => 'and some italics.', 'style' => 'font-style: italic'))
        ->text(array('text' => ' We finish with a text in red.', 'style' => 'color: red'));
//include in the render method the path where you want your document to be saved
$doc->render('text' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'text' . $format . '">Download document</a>'; 