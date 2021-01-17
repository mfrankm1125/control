<?php
/**
 * This sample script inserts a paragraph with a line break
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a line break
$doc->paragraph()
        ->text(array('text' => 'this is the first line'))
        ->lineBreak()
        ->text(array('text' => 'and this is the second one.'));
//include in the render method the path where you want your document to be saved
$doc->render('line_break' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'line_break' . $format . '">Download document</a>'; 