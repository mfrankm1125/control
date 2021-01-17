<?php
/**
 * This sample script inserts a default TOC
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$doc->paragraph(array('text' => 'Let us add a TOC to the document with just the default options:'));
//insert the TOC
$doc->toc();
//insert some headings and content so we can populate the TOC
for ($j=1; $j<5; $j++) {
    $doc->heading(array('level' => 1, 'text' => 'The Title number:' . $j, 'style' => 'page-break-before: always'));
    $doc->heading(array('level' => 2, 'text' => 'A subtitle of the title number: ' . $j));
    $doc->paragraph(array('text' => 'Some random text.'));
}
//include in the render method the path where you want your document to be saved
$doc->render('simple_toc' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_toc' . $format . '">Download document</a>';