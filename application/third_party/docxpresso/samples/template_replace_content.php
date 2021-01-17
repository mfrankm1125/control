<?php
/**
 * This sample script replaces some content in a template
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/template_replace_general.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//replace single variable
$doc->replace(array('variable' => array('value' => 'replaced text')));
//replace natural text
$doc->replace(array('replace me, please' => array('value' => 'another text')), array('format' => array('','')));
//populate the list
$doc->replace(array('item' => array('value' => array('first', 'second', 'third'))), array('element' => 'list'));
//populate the table
$vars =array('product' => array('value' => array('Smart phone', 'MP3 player', 'Camera')),
             'price' => array('value' => array('430.00', '49.99', '198,49')),
);
$doc->replace($vars, array('element' => 'table'));	
//replace single variable by different values
$doc->replace(array('test' => array('value' => array('one', 'two', 'three'))));
//and now a variable in the header
$doc->replace(array('example_header' => array('value' => 'header text')), array('target' => 'header'));
//include in the render method the path where you want your document to be saved
$doc->render('replaced_content' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'replaced_content' . $format . '">Download document</a>';