<?php
/**
 * This sample script inserts a few tabbed paragraphs
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$doc->paragraph(array('text' => 'First a few left tabbed paragraphs:'));
//insert a few runs of (left) tabbed text
for($j=0; $j < 6; $j++){
    $doc->paragraph(array('text' => 'Line : ' . $j))
            ->tab(array('position' => 200))
            ->text(array('text' => 'Data:' . $j));
}
$doc->paragraph(array('text' => 'Now some right tabbed paragraphs with a dotted leader:'));
//insert a few runs of (right) tabbed text with a dot as leader char
for($j=0; $j < 6; $j++){
    $doc->paragraph(array('text' => 'Line : ' . $j))
            ->tab(array('position' => 200, 'type' => 'right', 'leader' => 'dotted'))
            ->text(array('text' => 'Data:' . $j));
}
$doc->paragraph(array('text' => 'Now some tabbed paragraphs aligned with respect the decimal point:'));
//insert a few runs of (char: decimal point) tabbed text
for($j=0; $j < 6; $j++){
    $doc->paragraph(array('text' => 'Price : ' . $j))
            ->tab(array('position' => 200, 'type' => 'char', 'character' => '.'))
            ->text(array('text' => rand(1, 20) . '.'  . rand(0,9) . rand(0,9) . ' USD'));
}
//include in the render method the path where you want your document to be saved
$doc->render('tabs' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'tabs' . $format . '">Download document</a>';