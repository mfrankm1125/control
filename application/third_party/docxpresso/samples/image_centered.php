<?php
/**
* This sample script inserts a centered image
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A centered image:'));
//insert an image
$doc->paragraph()->style('text-align: center;')
		->image(array('src' => 'img/openoffice.jpg'));
//insert some text
$doc->paragraph(array('text' => 'A final sentence.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_centered_image' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_centered_image' . $format . '">Download document</a>'; 