<?php
/**
 * This sample script inserts a simple centered table with header and banded rows
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//inserts a table
//styles for the first row
$firstRowCell='background-color: #b70000; padding: 2px 9px;';
$firstRowText='font-weight: bold; color: white; font-size: 13px; font-family: Verdana; margin: 3px;';
//banded row style
$bandedRow = array('', 'background-color: #f0f0ff');
$standardCell='padding: 2px 9px';
$leftText='font-size: 12px; font-family: Arial; font-weight: bold; color: #444444; margin: 3px';
$rightText='font-size: 12px; font-family: Arial; text-align: right; margin: 3px';
//insert the table
$table = $doc->table(array('grid' => array('6cm', '4cm')))->style('margin: auto')
            ->row(array('header' => true))
                ->cell()->style($firstRowCell)
                    ->paragraph(array('text' => 'Item Name'))->style($firstRowText)
                ->cell()->style($firstRowCell)
                    ->paragraph(array('text' => 'Price($)'))->style($firstRowText)->style('text-align: right');

for ($j = 0; $j < 100; $j++){
	$table->row(array('style' => 'page-break-inside: avoid'))->style($bandedRow[$j%2])
			->cell()->style($standardCell)
				->paragraph(array('text' => 'Item:' . $j))->style($leftText)
			->cell()->style($standardCell)
				->paragraph(array('text' => rand(0, 99) . '.' . rand(10,99)))->style($rightText);
}
//include in the render method the path where you want your document to be saved
$doc->render('simple_table_header_banded' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_table_header_banded' . $format . '">Download document</a>';