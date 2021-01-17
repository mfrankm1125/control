<?php
/**
 * This sample script inserts a simple footer with page numbering
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf

//insert the footer 
$pStyle = array('style' => 'text-align: center; font-family: Arial; font-size: 12pt;');
$doc->footer()
        ->paragraph($pStyle)
            ->text(array('text' => 'page '))
            ->field('page-number')
            ->text(array('text' => ' of '))
            ->field('page-count');
$doc->paragraph()
    ->text(array('text' => 'This document has a footer with the page number. '));
//include in the render method the path where you want your document to be saved
$doc->render('footer' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'footer' . $format . '">Download document</a>';