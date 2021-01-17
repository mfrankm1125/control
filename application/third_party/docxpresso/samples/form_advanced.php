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
$inputStyles = 'border: 1pt solid #444; width: 5cm; font-family: Arial; margin-left: 5pt';
$textStyles = 'font-family: Arial; font-size: 10pt';
$doc->paragraph()->style($textStyles)
        ->text(array('text' => 'Your name: '))
        ->inputField()->style($inputStyles);
//select dropdown
$selectStyles = 'border: 1pt solid #444; width: 3cm; font-family: Arial; margin-left: 5pt';
$items = array('Male' => 'man', 'Female' => 'woman');
$doc->paragraph()->style($textStyles)
        ->text(array('text' => 'Gender: '))
        ->select(array('items' => $items, 'selected' => 'Female'))->style($selectStyles);
//checkboxes
$checkboxStyle = 'margin-left: 15pt';
$doc->paragraph()->style($textStyles)
        ->text(array('text' => 'Programming languages: '))
        ->checkbox(array('name' => 'PHP', 'value' => '1', 'checked' => true))->style($checkboxStyle)->style('margin-left: 5px')
        ->text(array('text' => ' PHP '))
        ->checkbox(array('name' => 'Java', 'value' => '1'))->style($checkboxStyle)
        ->text(array('text' => ' Java '))
        ->checkbox(array('name' => 'NodeJS', 'value' => '1', 'checked' => true))->style($checkboxStyle)
        ->text(array('text' => ' NodeJS '));
//radio buttons
$radioButtonStyle = 'margin-left: 15pt';
$doc->paragraph()->style($textStyles)
        ->text(array('text' => 'Favourite OS: '))
        ->radioButton(array('name' => 'OS', 'value' => 'Linux', 'selected' => true))->style($radioButtonStyle)->style('margin-left: 5px')
        ->text(array('text' => ' Linux '))
        ->radioButton(array('name' => 'OS', 'value' => 'Windows'))->style($radioButtonStyle)
        ->text(array('text' => ' Windows '))
        ->radioButton(array('name' => 'OS', 'value' => 'Mac'))->style($radioButtonStyle)
        ->text(array('text' => ' Mac '));
//textarea
$textareaStyles = 'border: 1pt solid #444; width: 9cm; height: 5cm; font-family: Arial; margin-left: 5pt';
$doc->paragraph()->style($textStyles)
        ->text(array('text' => 'Message: '))
        ->textarea(array('scroll' => false))->style($textareaStyles);
//submit
$buttonStyle = 'margin-left: 4.5cm; background-color: #ff6600; font-weight: bold; color: white; height: 1cm; width: 3cm';
$doc->paragraph()
        ->button(array('value' => 'Send Data'))->style($buttonStyle)->style($textStyles);

//include in the render method the path where you want your document to be saved
$doc->render('form' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'form' . $format . '">Download document</a>';