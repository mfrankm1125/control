<?php
/**
 * This sample script removes all the content associated with a heading
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_remove_heading.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//remove heading
$doc->removeContent(array('needle' => 'Remove', 'element' => 'heading'));
//include in the render method the path where you want your document to be saved
$doc->render('remove_heading' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'remove_heading' . $format . '">Download document</a>';