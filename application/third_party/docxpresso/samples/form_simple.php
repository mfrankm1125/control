<?php
/**
 * This sample script inserts a simple form
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .odt, other document formats do not support forms
//insert the form wrapper
$doc->form(array('name' => 'sampleForm', 'action' => 'http://www.Docxpresso/test_form.php', 'method' => 'get'));
//insert the form controls
//input text
$doc->paragraph()
        ->text(array('text' => 'Your name: '))
        ->inputField();
//select dropdown
$items = array('Male' => 'man', 'Female' => 'woman');
$doc->paragraph()
        ->text(array('text' => 'Gender: '))
        ->select(array('items' => $items, 'selected' => 'Female'));
//checkboxes
$checkboxStyle = 'margin-left: 20pt';
$doc->paragraph()
        ->text(array('text' => 'Programming languages: '))
        ->checkbox(array('name' => 'PHP', 'value' => '1', 'checked' => true))->style($checkboxStyle)
        ->text(array('text' => ' PHP '))
        ->checkbox(array('name' => 'Java', 'value' => '1'))->style($checkboxStyle)
        ->text(array('text' => ' Java '))
        ->checkbox(array('name' => 'NodeJS', 'value' => '1', 'checked' => true))->style($checkboxStyle)
        ->text(array('text' => ' NodeJS '));
//radio buttons
$radioButtonStyle = 'margin-left: 20pt';
$doc->paragraph()
        ->text(array('text' => 'Favourite OS: '))
        ->radioButton(array('name' => 'OS', 'value' => 'Linux', 'selected' => true))->style($radioButtonStyle)
        ->text(array('text' => ' Linux '))
        ->radioButton(array('name' => 'OS', 'value' => 'Windows'))->style($radioButtonStyle)
        ->text(array('text' => ' Windows '))
        ->radioButton(array('name' => 'OS', 'value' => 'Mac'))->style($radioButtonStyle)
        ->text(array('text' => ' Mac '));
//textarea
$doc->paragraph()
        ->text(array('text' => 'Message: '))
        ->textarea();
//submit
$doc->paragraph()
        ->button(array('value' => 'Send'));

//include in the render method the path where you want your document to be saved
$doc->render('simple_form' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_form' . $format . '">Download document</a>';