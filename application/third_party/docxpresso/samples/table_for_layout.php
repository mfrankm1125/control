<?php
/**
 * This sample script inserts a table for layout purposes
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert the contents
$doc->paragraph(array('text' => 'A table used for layout: '));
$doc->table(array('grid' => array('6cm', '9cm')))
        ->row()->style('min-height: 3cm')
            ->cell()
                ->image(array('src' => 'img/Docxpresso.png'))
            ->cell()
                ->paragraph()
                    ->text(array('text' => 'Some text to the '))
                    ->text(array('text' => 'right '))->style('font-weight: bold')
                    ->text(array('text' => 'of this image.'));
$doc->paragraph(array('text' => 'A final paragraph.'));
//include in the render method the path where you want your document to be saved
$doc->render('tables_for_layout' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'tables_for_layout' . $format . '">Download document</a>';