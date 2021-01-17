<?php
/**
 * This sample script sets the cursor at different points
 * of the document and inserts some simple content
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_cursor_sample.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//include a simple paragraph at the end of the document
$doc->paragraph()
    ->text(array('text' => 'A paragraph at the very end of the document.'));
//with all default options the cursor is located before the first paragraph
$doc->cursor();
$doc->paragraph()
    ->text(array('text' => 'The first text included before the first paragraph (we use all default options).'));
//locate the cursor after the paragraph containing the word 'Second'
$doc->cursor(array ('needle' => 'Second', 'position' => 'after'));
$doc->paragraph()
    ->text(array('text' => 'Now we include this text just after the paragraph containing the word "Second".'));
//locate the cursor before the first table
$doc->cursor(array ('element' => 'table'));
$doc->paragraph()
    ->text(array('text' => 'Now some text before the first table.'));
//locate the cursor within a table cell
$doc->cursor(array ('needle' => 'Cell 1 2'));
$doc->paragraph()
    ->text(array('text' => 'This goes at the beginning of the first row second col cell.'));
//and back to the end of the document
$doc->cursor(array ('position' => 'end'));
$doc->paragraph()
    ->text(array('text' => 'The end.'));
//include in the render method the path where you want your document to be saved
$doc->render('cursor_sample' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'cursor_sample' . $format . '">Download document</a>';