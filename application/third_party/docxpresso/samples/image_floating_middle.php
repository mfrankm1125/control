<?php
/**
 * This sample script inserts a floating image in the middle of a paragraph
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A paragraph with a floating image in the middle:'));
//insert the containing paragraph
$p = $doc->paragraph()->style('text-align: justify;');
//insert floating image in the middle of the paragraph
for ($j = 0; $j < 10; $j++){
	$p->text(array('text' => 'This text content should be wrapping the image. we repeat it quite a few times so you can see the effect. '));
}
$p->image(array('src' => 'img/openoffice.jpg', 'style' => 'float: right; margin-left: 0.4cm'));
for ($j = 0; $j < 10; $j++){
	$p->text(array('text' => 'This text content should be wrapping the image. we repeat it quite a few times so you can see the effect. '));
}
//include in the render method the path where you want your document to be saved
$doc->render('floating_image_middle_paragraph' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'floating_image_middle_paragraph' . $format . '">Download document</a>';