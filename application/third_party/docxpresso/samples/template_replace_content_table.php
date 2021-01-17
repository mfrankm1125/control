<?php
/**
 * This sample script replaces some content in a complex table
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/table_template_replace.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//populate the table
$vars =array('name' => array('value' => array('Smart phone', 'MP3 player <i style="color: red">(out of stock)</i>', 'Camera')),
			 'reference' => array('value' => array('345-H26-CC', '115-H27-CC', '225-J76-CD')),
			 'currency' => array('value' => array('$', '€', '$')),
             'price' => array('value' => array('430.00', '49.99', '198.49')),
);
$doc->replace($vars, array('element' => 'table'));	
//include in the render method the path where you want your document to be saved
$doc->render('replace_table' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'replace_table' . $format . '">Download document</a>'; 