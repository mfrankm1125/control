<?php
/**
 * This sample script inserts a very simple header in the document
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert the header with some basic formatting
$style = 'min-height: 2cm;';
$style .= 'border: 1px solid red;';
$style .= 'margin-bottom: 1cm;';
$style .= 'padding: 0.5cm;';
$style .= 'background-color: #ffff99;';
$doc->header()
        ->paragraph(array('style' => $style))
            ->text(array('text' => 'This is a header.'));
$doc->paragraph()
        ->text(array('text' => 'This document has a very simple header. '))
        ->text(array('text' => 'Don\'t you agree? '));
//include in the render method the path where you want your document to be saved
$doc->render('simple_header_word' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_header_word' . $format . '">Download document</a>';