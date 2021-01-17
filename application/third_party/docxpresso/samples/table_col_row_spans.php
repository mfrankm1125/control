<?php
/**
 * This sample script inserts a simple centered table with col and row spans
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph
$doc->paragraph(array('text' => 'A table with col and row spans: '));
//inserts a table
//styles for the different cells
$rightCell = 'background-color: #f0f0f0; padding: 4px 9px 0 9px; border: 1px solid #999999;';
$leftCell = 'background-color: #fffff0;padding: 4px 9px 0 9px; border: 1px solid #999999;';
//insert the table
$doc->table(array('grid' => array('4cm', '6cm')))->style('margin: auto')
        ->row()
            ->cell(array('rowspan' => 2))->style($leftCell)
                ->paragraph(array('text' => 'A row span of 2'))
            ->cell()->style($rightCell)
                ->paragraph(array('text' => 'Some text'))
        ->row()
            ->cell()->style($rightCell)->style('background-color: #f0f0ff')
                ->paragraph(array('text' => 'Some other random text'))
        ->row()
            ->cell(array('colspan' => 2))->style($rightCell)->style('background-color: #fff0f0')
                ->paragraph(array('text' => 'A cell spanning two columns'))
        ->row()
            ->cell(array('rowspan' => 2))->style($leftCell)
                ->paragraph(array('text' => 'Another row span of 2'))
            ->cell()->style($rightCell)
                ->paragraph(array('text' => 'A sample test'))
        ->row()
            ->cell()->style($rightCell)->style('background-color: #f0f0ff')
                ->paragraph(array('text' => 'Some other sample text'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_table_col_row_span' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_table_col_row_span' . $format . '">Download document</a>';