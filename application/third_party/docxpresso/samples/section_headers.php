<?php
/**
 * This sample script inserts a new section with a different header
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a very simple header in the first section
$doc->header()->paragraph(array('text' => 'SECTION 1 HEADER', 'style' => 'color:red'));
//insert some text in the first section
$doc->paragraph()
    ->text(array('text' => 'This document has two sections. '))
    ->text(array('text' => 'This is the first. '));
//insert the new section
$doc->section();
//insert a very simple header in the second section
$doc->header()->paragraph(array('text' => 'SECTION 2 HEADER', 'style' => 'color:green'));
//insert some text in the second section
$doc->paragraph()
    ->text(array('text' => 'This text belongs to the second section with a different header.'));
//include in the render method the path where you want your document to be saved
$doc->render('section_header' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'section_header' . $format . '">Download document</a>';