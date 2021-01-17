<?php
/**
 * This sample script inserts some whitespace within a paragraph
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert 5 whitespaces
$doc->paragraph()
        ->text(array('text' => 'Some text before 5 whitespaces'))
        ->whitespace(5)
        ->text(array('text' => 'some more text.'));
//include in the render method the path where you want your document to be saved
$doc->render('whitespace' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'whitespace' . $format . '">Download document</a>';  