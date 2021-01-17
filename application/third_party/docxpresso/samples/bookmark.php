<?php
/**
 * This sample script inserts a link in the first page that points to a 
 * bookmark in the second page
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a link to the bookmarked paragraph
$linkData = array(
    'url' => '#myBookmark', 
    'text' => 'the bookmarked paragraph in the second page', 
    'title' => 'An internal cross-reference',
);
$doc->paragraph(array('text' => 'This is a very simple paragraph with a link to '))
        ->link($linkData)->end('link')
        ->text(array('text' => '.'));
//insert a paragraph with a bookmark in a new page
$doc->paragraph(array('style' => 'page-break-before: always'))
        ->bookmark(array('name' => 'myBookmark'))
        ->text(array('text' => 'This is the bookmarked paragraph.'));
//include in the render method the path where you want your document to be saved
$doc->render('bookmark' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'bookmark' . $format . '">Download document</a>';