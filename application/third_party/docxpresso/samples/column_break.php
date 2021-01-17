<?php
/**
 * This sample script inserts a column break between two paragraphs
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set first a two column layout
$doc->pageLayout(array('style' => 'column-count: 2; column-gap: 1cm;'));
//inserts a column break betwen two paragraphs
$doc->paragraph(array('text' => 'First paragraph.'));
$doc->columnBreak();
$doc->paragraph(array('text' => 'Second paragraph.'));
//include in the render method the path where you want your document to be saved
$doc->render('column_break' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'column_break' . $format . '">Download document</a>';