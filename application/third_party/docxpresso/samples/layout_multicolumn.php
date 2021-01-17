<?php
/**
 * This sample script sets the document size to A4 with two columns
 * separated by a solid black line
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//set the required options
$options = array(
    'style' => 'column-count: 2; column-rule: 1pt solid black',
    );
$doc->pageLayout($options);
//include the following paragraph 40 times
for ($j = 0; $j < 40; $j++) {
$doc->paragraph()
    ->text(array('text' => 'This text should show up in a two column layout.'));
}
//include in the render method the path where you want your document to be saved
$doc->render('two_columns' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'two_columns' . $format . '">Download document</a>'; 