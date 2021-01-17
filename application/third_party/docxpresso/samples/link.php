<?php
/**
 * This sample script inserts a simple paragraph with a link
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a link
$linkData = array(
    'url' => 'http://www.google.com', 
    'text' => 'Google', 
    'title' => 'Google search Engine',
);
$doc->paragraph(array('text' => 'This is a very simple paragraph with a link to '))
        ->link($linkData)->end('link')
        ->text(array('text' => '.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_link' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_link' . $format . '">Download document</a>';