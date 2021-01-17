<?php
/**
 * This sample script inserts a page break between two paragraphs
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//inserts a page break between two paragraphs
$doc->paragraph(array('text' => 'First paragraph.'));
$doc->pageBreak();
$doc->paragraph(array('text' => 'Second paragraph.'));
//include in the render method the path where you want your document to be saved
$doc->render('page_break' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'page_break' . $format . '">Download document</a>';