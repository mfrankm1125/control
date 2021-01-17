<?php
/**
 * This sample script inserts a simple paragraph
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph
$style = 'text-indent: 1cm; font-family: Arial; color: #444444';
$text = 'This is a ver simple paragraph in Arial font and color #444444 with text indent of 1cm. ';
$text .= 'If we build the paragraph in this way all the text has to share the same styling properties.';
$doc->paragraph(array('text' => $text, 'style' => $style));
$doc->render('simple_paragraph' . $format);  
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_paragraph' . $format . '">Download document</a>'; 