<?php
/**
 * This sample script generates a PDF with some custom options and password protected
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.pdf';
$doc->paragraph(array('text' => 'Let us add a simple TOC to the document with just the default options:'));
//insert the TOC
$doc->toc();
//insert some headings and content so we can populate the TOC
for ($j=1; $j < 5; $j++){
    $doc->heading(array('level' => 1, 'text' => 'The Title number:' . $j, 'style' => 'page-break-before: always'));
    $doc->heading(array('level' => 2, 'text' => 'A subtitle of the title number: ' . $j));
    $doc->paragraph(array('text' => 'Some random text.'));
}
//Let us include some advanced options
$options = array();
$options['EncryptFile'] = true;
$options['DocumentOpenPassword'] = 'html52pdf';
$options['Magnification'] = 4;
$options['Zoom'] = 60;
$options['InitialView'] = 2;
//include in the render method the path where you want your document to be saved
$doc->render('pdf_rendering_options' . $format, $options);  