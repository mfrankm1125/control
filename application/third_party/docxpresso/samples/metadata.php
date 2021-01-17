<?php
/**
 * This sample script customizes the document metadata
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set the required options
$options = array('author' => 'Billy Paul',
                 'date' => '1972-06-03T19:12:43Z',   
                 'keywords' => 'Billy Paul, Amy Winehouse, record', 
                 'subject' => 'Song Lyrics.',
                 'title' => 'Me and Mr.Jones',                 
                );
$doc->metadata($options);
$doc->paragraph()
    ->text(array('text' => 'This document includes some custom metadata:'));
$doc->unorderedList()->listItem(array('text' => 'author'))
                     ->listItem(array('text' => 'date'))
                     ->listItem(array('text' => 'keywords'))
                     ->listItem(array('text' => 'subject'))
                     ->listItem(array('text' => 'title'));
$doc->render('metadata' . $format);   
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'metadata' . $format . '">Download document</a>';