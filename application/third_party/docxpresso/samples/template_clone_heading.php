<?php
/**
 * This sample script clones all the content associated with a heading
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_clone_heading.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//clone heading
$doc->cloneContent(array('needle' => 'Clone', 'element' => 'heading', 'heading-level' => 1));
//include in the render method the path where you want your document to be saved
$doc->render('clone_heading' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'clone_heading' . $format . '">Download document</a>'; 