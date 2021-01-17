<?php
/**
 * This sample script inserts a simple table
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$doc->paragraph(array('text' => 'First a few left tabbed paragraphs:'));
//inserts a table
//styles for the first row
$firstRowCell='border: 2px solid red; background-color: #b70000; padding: 1px 9px;';
$firstRowText='font-weight: bold; color: white; font-size: 13px; font-family: Verdana; margin: 3px;';
//normal row style
$standardCell='border: 1px solid #555555; padding: 1px 9px';
$leftText='font-size: 12px; font-family: Arial; font-weight: bold; color: #444444; margin: 3px';
$rightText='font-size: 12px; font-family: Arial; text-align: right; margin: 3px';
//insert the table
$doc->table(array('grid' => 2))
        ->row()
            ->cell()->style($firstRowCell)
                ->paragraph(array('text' => 'Item Name'))->style($firstRowText)
            ->cell()->style($firstRowCell)
                ->paragraph(array('text' => 'Price($)'))->style($firstRowText)->style('text-align: right')
        ->row()
            ->cell()->style($standardCell)->style('background-color: #f0f0f0')
                ->paragraph(array('text' => 'First item'))->style($leftText)
            ->cell()->style($standardCell)
                ->paragraph(array('text' => '356.45'))->style($rightText)
        ->row()
            ->cell()->style($standardCell)->style('background-color: #f0f0f0')
                ->paragraph(array('text' => 'Second item'))->style($leftText)
            ->cell()->style($standardCell)
                ->paragraph(array('text' => '145.33'))->style($rightText);
//include in the render method the path where you want your document to be saved
$doc->render('simple_table' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_table' . $format . '">Download document</a>';