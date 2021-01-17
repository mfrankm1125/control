<?php
/**
 * This sample script inserts a more complex paragraph
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a complex paragraph
$doc->paragraph()->style('text-indent: 1cm; font-family: Arial; font-size: 11pt;')
        ->text(array('text' => 'Let us build a paragraph with a few different elements. '))
        ->image(array('src' => 'img/Docxpresso.png'))->style('float: right; margin: 0 0 10pt 10pt')
        ->text(array('text' => 'We include an image drawn by our website illustrator '))
        ->text(array('text' => 'Pablo Matera '))->style('font-weight: bold; color: #b70000')
        ->text(array('text' => 'as well as a '))
        ->link(array('text' => 'clickable link', 'url' => 'http://Docxpresso.com'))->end('link')
        ->text(array('text' => ' to our website.'));
$doc->render('paragraph' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'paragraph' . $format . '">Download document</a>'; 