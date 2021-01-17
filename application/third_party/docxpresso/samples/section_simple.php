<?php
/**
 * This sample script inserts a new section in landscape mode
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text in the first section
$doc->paragraph()
    ->text(array('text' => 'This document has two sections. '))
    ->text(array('text' => 'This is the first. '));
//insert the new section in landscape mode
$doc->section(array('orientation' => 'landscape'));
//insert some text in the second section
$doc->paragraph()
    ->text(array('text' => 'This text belongs to the second section in landscape mode.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_section' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_section' . $format . '">Download document</a>';