<?php
/**
* This sample script inserts a floating image with a black border
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert some text
$doc->paragraph(array('text' => 'A paragraph with a floating image:'));
//insert a floating image
$doc->image(array('src' => 'img/openoffice.jpg', 'style' => 'float: left; margin-right: 0.5cm; border: 1pt solid black'));
//insert wrapping text
for ($j = 0; $j < 10; $j++){
	$doc->paragraph(array('text' => 'This text content should be wrapping the image. we repeat it quite a few times so you can see the effect.'));
}
//include in the render method the path where you want your document to be saved
$doc->render('floating_image' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'floating_image' . $format . '">Download document</a>'; 