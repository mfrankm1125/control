<?php
/**
 * This sample script clones some bookmarked content
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_clone_bookmark.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//clone bookmarked text
$doc->cloneContent(array('needle' => 'html52pdf', 'element' => 'bookmark'));	
//include in the render method the path where you want your document to be saved
$doc->render('clone_bookmarked' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'clone_bookmarked' . $format . '">Download document</a>'; 