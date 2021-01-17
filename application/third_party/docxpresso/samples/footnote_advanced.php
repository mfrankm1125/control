<?php
/**
 * This sample script inserts a paragraph with a footnote
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with a footnote
$noteStyle = 'margin-left: 0.5cm; text-indent: -0.5cm; font-style: italic; font-size: 11pt; margin-bottom: 0cm';
$doc->paragraph()
        ->text(array('text' => 'This footnote'))
        ->footnote()
            ->paragraph()->style($noteStyle)
                ->text(array('text' => 'This footnote enjoys some additional format and '))
                ->text(array('text' => 'some text in bold.'))->style('font-weight: bold')
            ->paragraph()->style($noteStyle)->style('margin-top: 0cm')
                ->text(array('text' => 'This is a second footnote paragraph with a '))
                ->link(array('url' => 'http://www.google.com', 'text' => 'link to Google'))->end('link')
                ->text(array('text' => '.'))
            ->end('footnote')
        ->text(array('text' => ' is slightly more complex.'));
//include in the render method the path where you want your document to be saved
$doc->render('footnote' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'footnote' . $format . '">Download document</a>';