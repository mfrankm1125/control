<?php
/**
 * This sample script removes some bookmarked content
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_remove_bookmark.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//remove bookmarked text
$doc->removeContent(array('needle' => 'html52pdf', 'element' => 'bookmark'));
//include in the render method the path where you want your document to be saved
$doc->render('remove_bookmarked' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'remove_bookmarked' . $format . '">Download document</a>';