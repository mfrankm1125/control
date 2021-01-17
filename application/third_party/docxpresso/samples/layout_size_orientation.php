<?php
/**
 * This sample script sets the document layout to be A3 landscape
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set the required options
$options = array(
    'orientation' => 'landscape',
    'paperSize'   => 'A3',
    );
$doc->pageLayout($options);
$doc->paragraph()
    ->text(array('text' => 'A simple A3-landscape document.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_A3_landscape' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_A3_landscape' . $format . '">Download document</a>';