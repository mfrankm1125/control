<?php
/**
* This sample script inserts a floating textbox
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A paragraph with a floating textbox:'));
//insert the containing paragraph
$p = $doc->paragraph()->style('text-align: justify;');
//insert floating image in the middle of the paragraph
for ($j = 0; $j < 3; $j++) {
    $p->text(array('text' => 'This text content should be wrapping the textbox. We repeat it quite a few times so you can see the effect. '));
}
//insert a textbox
$p->textbox()->style('float: right; width: 7cm; height: 4cm; border: 2px solid blue; padding: 0.5cm; margin: 0.3cm; margin-right: 0')
        ->paragraph(array('text' => 'First paragraph within the textbox.'))
        ->unorderedList(array('items' => array('List item 1', 'List item 2')))->end('list')
        ->paragraph(array('text' => 'Last paragraph within the textbox.'));
$doc->paragraph(array('text' => 'And an extra paragraph that should also be wrapping the textbox in order to illustated how the flaoting of textboxes do work. '));
//include in the render method the path where you want your document to be saved
$doc->render('floating_textbox' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'floating_textbox' . $format . '">Download document</a>';