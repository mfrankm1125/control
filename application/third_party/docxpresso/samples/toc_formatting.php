<?php
/**
 * This sample script inserts a default TOC
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$doc->paragraph(array('text' => 'Let us add a TOC with some formatting options:'));
//insert the TOC
$TOCStyle = array();
$TOCStyle[0] = 'font-family: Cambria; color: #b70000; font-size: 16pt';
$TOCStyle[1] = 'font-family: Arial; color: #333333; font-size: 12pt; margin-top: 3pt';
$TOCStyle[2] = 'font-family: Arial; color: #555555; font-size: 11pt; font-style: italic';
$doc->toc(array('title' => 'Table of Contents', 'style' => $TOCStyle));
//insert some headings and content so we can populate the TOC
for ($j=1; $j<5; $j++) {
    $doc->heading(array('level' => 1, 'text' => 'The Title number:' . $j, 'style' => 'page-break-before: always'));
    $doc->heading(array('level' => 2, 'text' => 'A subtitle of the title number: ' . $j));
    $doc->paragraph(array('text' => 'Some random text.'));
}
//include in the render method the path where you want your document to be saved
$doc->render('toc' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'toc' . $format . '">Download document</a>';