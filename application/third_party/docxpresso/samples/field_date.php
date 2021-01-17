<?php
/**
 * This sample script inserts a simple paragraph with a a date field
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a date field
$options = array('date-format' => array('month-short', '-' , 'day-short', '-', 'year'));
$doc->paragraph(array('text' => 'This document was generated the '))
        ->field('date', $options)
        ->text(array('text' => '.'));
//include in the render method the path where you want your document to be saved
$doc->render('date' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'date' . $format . '">Download document</a>'; 