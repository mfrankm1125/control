<?php
/**
 * This sample script inserts math equations in StarMath 5.0 format
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph:
$doc->paragraph(array('text' => 'This is a sample math equation:'));
//insert a paragraph with a math formula
$eq='A = left [ matrix { a # b ## c # d } right ]';
$doc->paragraph(array('style' => 'text-align: center; vertical-align: middle'))
		->math($eq, array('math-settings' => array('base-font-size' => 16), 'type' => 'StarMath'));
//and some additional inline math
$eq2 = '2 %pi r';
$doc->paragraph()
                ->text(array('text' => 'The circumference length is given by '))
		->math($eq2, array('type' => 'StarMath'))
                ->text(array('text' => ' .'));
//include in the render method the path where you want your document to be saved
$doc->render('starmath' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'starmath' . $format . '">Download document</a>';