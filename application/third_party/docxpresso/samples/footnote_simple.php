<?php
/**
 * This sample script inserts a paragraph with a simple footnote
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a simple footnote
$doc->paragraph()
        ->text(array('text' => 'Just include a footnote'))
        ->footnote(array('note' => 'This is the footnote text.'))
            ->end('footnote')
        ->text(array('text' => ' and finish the paragraph.'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_footnote' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_footnote' . $format . '">Download document</a>'; 