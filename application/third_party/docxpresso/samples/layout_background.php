<?php
/**
 * This sample script generates a document with a centered background image
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set the required options
$options = array(
    'style' => 'background-image: url(img/PDF_logo.jpg); background-position: center center; background-repeat: no-repeat',
    );
$doc->pageLayout($options);
$doc->paragraph()
    ->text(array('text' => 'A document with a centered background image.'));
//include in the render method the path where you want your document to be saved
$doc->render('background_image' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'background_image' . $format . '">Download document</a>';  