<?php
/**
 * This sample script sets the document size to a squared non-standard format
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set the required options
$options = array(
    'style' => 'width: 20cm; height: 20cm',
    );
$doc->pageLayout($options);
$doc->paragraph()
    ->text(array('text' => 'A simple "squared" document of 20x20 cm.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_squared' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_squared' . $format . '">Download document</a>'; 