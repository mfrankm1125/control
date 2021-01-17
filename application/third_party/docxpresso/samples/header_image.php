<?php
/**
 * This sample script inserts a header in the document with an image and page numbering
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$doc->pageLayout(array('style' => 'margin-top: 0.5cm'));
//insert the header with images and page numbering
$headerStyle = array('style' => 'min-height: 3cm');
$rowStyle = array('style' => 'min-height: 2.5cm');//only required for nice .docx conversion
$cellStyle = array('style' => 'margin: 0; padding: 0; vertical-align: middle; border-bottom: 1px solid #444444');
$pStyle = array('style' => 'text-align: right; font-family: Arial; font-size: 12pt; margin-right: 10pt;');
$textStyle = 'font-family: Cambria; font-size: 18pt; color: #b70000';
$doc->header($headerStyle)
    ->table(array('grid' => array('5cm', '5cm', '7cm'), 'style' => 'margin: 0; padding: 0;'))
        ->row($rowStyle)
            ->cell($cellStyle)->paragraph()->image(array('src' => 'img/Docxpresso.png', 'style' => 'margin: 0; padding:0'))
            ->cell($cellStyle)->paragraph()->text(array('text' => 'Docxpresso', 'style' => $textStyle))
            ->cell($cellStyle)->paragraph($pStyle)->text(array('text' => 'page '))->field('page-number');
$doc->paragraph()
    ->text(array('text' => 'This document has a nice header with an image and page number. '));
//include in the render method the path where you want your document to be saved
$doc->render('header' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'header' . $format . '">Download document</a>';  