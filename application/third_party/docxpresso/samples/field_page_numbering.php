<?php
/**
 * This sample script inserts page numberings and page counts
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with page number and total page count
$doc->paragraph(array('text' => 'This is page  '))
        ->field('page-number')
        ->text(array('text' => ' of a total of '))
        ->field('page-count')
        ->text(array('text' => ' pages.'));
//insert a paragraph with page number and total page count in a new page
$doc->paragraph(array('text' => 'This is page  '))->style('page-break-before: always')
        ->field('page-number')
        ->text(array('text' => ' of a total of '))
        ->field('page-count')
        ->text(array('text' => ' pages.'));
//include in the render method the path where you want your document to be saved
$doc->render('page_numbering' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'page_numbering' . $format . '">Download document</a>'; 