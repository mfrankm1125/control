<?php
/**
 * This sample script inserts a simple unordered nested list
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a simple nested list
$items = array('First list item.',
               'Second entry.',
               array('First sublist element',
                     'Second sublist entry.',
                     ),
               'Last item.',
               );
$doc->unorderedList(array('items' => $items));
//include in the render method the path where you want your document to be saved
$doc->render('simple_list' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_list' . $format . '">Download document</a>';