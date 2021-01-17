<?php
/**
 * This sample script inserts a paragraph with an endnote
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph with an endnote
$noteStyle = 'margin-left: 0.5cm; text-indent: -0.5cm; font-style: italic; font-size: 11pt; margin-bottom: 0cm';
$doc->paragraph()
        ->text(array('text' => 'This endnote'))
        ->endnote(array('label' => '[1]'))
            ->paragraph()->style($noteStyle)
                ->text(array('text' => 'This endnote enjoys some additional format and '))
                ->text(array('text' => 'some text in bold.'))->style('font-weight: bold')
            ->paragraph()->style($noteStyle)->style('margin-top: 0cm')
                ->text(array('text' => 'This is a second endnote paragraph with a '))
                ->link(array('url' => 'http://www.google.com', 'text' => 'link to Google'))->end('link')
                ->text(array('text' => '.'))
            ->end('endnote')
        ->text(array('text' => ' is pretty complex.'));
//include in the render method the path where you want your document to be saved
$doc->render('endnote' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'endnote' . $format . '">Download document</a>';