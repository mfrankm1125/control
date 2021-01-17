<?php
/**
 * This sample script inserts an absolutely positioned textbox
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A paragraph with an absolutely positioned textbox:'));
//insert the containing paragraph
$p = $doc->paragraph()->style('text-align: justify;');
//insert floating image in the middle of the paragraph
for ($j = 0; $j < 30; $j++) {
    $p->text(array('text' => 'This text content should be wrapping the textbox. We repeat it quite a few times so you can see the effect. '));
}
//insert a textbox
$doc->textbox()->style('position: absolute; top: 2cm; left: 4cm; width: 7cm; height: 4cm; border: 2px solid blue; padding: 0.5cm; margin: 0 0.5cm;')
        ->paragraph(array('text' => 'First paragraph within the textbox.'))
        ->unorderedList(array('items' => array('List item 1', 'List item 2')))->end('list')
        ->paragraph(array('text' => 'Last paragraph within the textbox.'));

$doc->paragraph(array('text' => 'And an extra paragraph that should also be wrapping the textbox in order to illustrated how the flaoting of textboxes do work. '));

//include in the render method the path where you want your document to be saved
$doc->render('absolutely_positioned_textbox' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'absolutely_positioned_textbox' . $format . '">Download document</a>';