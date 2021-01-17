<?php
/**
 * This sample script inserts a series of headings
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a level 1 heading and some text
$title_1 = 'This is the first title';
$text_1 = 'Some plain text to fill out the space.';
$doc->heading(array('level' => 1, 'text' => $title_1));
$doc->paragraph(array('text' => $text_1));
//insert a level 2 heading and some text
$title_2 = 'Subtitle of level 2';
$text_2 = 'Some more text with no special meaning at all.';
$doc->heading(array('level' => 2, 'text' => $title_2));
$doc->paragraph(array('text' => $text_2));
//insert a new level 1 heading but in a new page
$title_3 = 'This is the second title';
$text_3 = 'This is the last paragraph.';
$doc->heading(array('level' => 1, 'text' => $title_3))->style('page-break-before: always;');
$doc->paragraph(array('text' => $text_3));
//include in the render method the path where you want your document to be saved
$doc->render('headings' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'headings' . $format . '">Download document</a>';