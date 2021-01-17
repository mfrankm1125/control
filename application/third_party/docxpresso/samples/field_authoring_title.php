<?php
/**
 * This sample script inserts author and title fields
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//first insert some metadata
$metadata = array('author' => 'William Shakespeare', 'title' => 'Hamlet');
$doc->metadata($metadata);
//Insert now a paragraph including those two fields
$doc->paragraph(array('text' => 'This is the original manuscript of '))->style('font-style: italic')
        ->field('title')->style('color: #b70000')
        ->text(array('text' => ' written by '))
        ->field('author-name')->style('color: #b70000; font-weight: bold')
        ->text(array('text' => ' the Bard of Avon.'));
//include in the render method the path where you want your document to be saved
$doc->render('authoring' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'authoring' . $format . '">Download document</a>';