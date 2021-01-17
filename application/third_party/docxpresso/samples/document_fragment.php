<?php
/**
 * This sample script inserts a simple document fragment within a table cell
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//create a document fragment
$fragment = $doc->documentFragment();
$fragment->paragraph()
            ->text(array('text' => 'A simple chunk of text with a '))
            ->link(array('url' => 'http://google.com', 'text' => 'link to Google'))
            ->text(array('text' => '. '));
//create a simple table and insert the fragment within
$cellStyle = array('style' => 'border: 1px solid red; padding: 10pt');
$doc->table(array('grid' => array('30%', '70%'), 'style' => 'width: 15cm')) 
        ->row()
            ->cell($cellStyle)->paragraph(array('text' => 'First cell'))
            ->cell($cellStyle)->insertDocumentFragment($fragment);
//include in the render method the path where you want your document to be saved
$doc->render('document_fragment' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'document_fragment' . $format . '">Download document</a>';  