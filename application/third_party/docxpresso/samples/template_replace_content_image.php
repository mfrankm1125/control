<?php
/**
 * This sample script replaces some images within the document
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/image_template_replace.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//replace images
$doc->replace(array('myImage' => array('value' => 'img/new.jpg', 'image' => true)));
//in the second one we will change the original image dimensions
$doc->replace(array('myImage_2' => array('value' => 'img/new_2.jpg', 'image' => true, 'width' => '400px', 'height' => '200px')));
//include in the render method the path where you want your document to be saved
$doc->render('replace_image' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'replace_image' . $format . '">Download document</a>';