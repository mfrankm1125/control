<?php
/**
 * This sample script inserts an unordered nested list
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//create an empty list and populate it with the listItem method
$doc->unorderedList()
        ->listItem()
            ->text(array('text' => 'First item '))
            ->text(array('text' => 'with some bold text'))->style('font-weight: bold')
            ->text(array('text' => '.'))
        ->listItem()
            ->text(array('text' => 'Second item.'))
        ->unorderedList()
            ->listItem()
                ->text(array('text' => 'First subitem '))
                ->text(array('text' => 'with some red text'))->style('color: #b70000')
                ->text(array('text' => '.'))
            ->listItem()
                ->text(array('text' => 'Second subitem.'))
            ->end('list')
        ->listItem()
            ->text(array('text' => 'Last item.'));
//include in the render method the path where you want your document to be saved
$doc->render('unordered_list' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'unordered_list' . $format . '">Download document</a>';