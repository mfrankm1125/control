<?php
/**
 * This sample script clones some general content from the template
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_clone_general.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//clone a paragraph by content
$doc->cloneContent(array('needle' => 'cloned', 'element' => 'paragraph'));
//clone a paragraph by position, beware that a previous paragraph has already be cloned
//so the position has increased by one
$doc->cloneContent(array('needle' => '', 'element' => 'paragraph', 'match' => 5));
//clone a table row
$doc->cloneContent(array('needle' => 'one', 'element' => 'table-row'));
//include in the render method the path where you want your document to be saved
$doc->render('clone_general_content' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'clone_general_content' . $format . '">Download document</a>';  