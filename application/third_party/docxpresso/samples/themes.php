<?php
/**
 * This sample script creates a document with the help of a Docxpresso theme
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//we load the desired theme
$doc->loadTheme('../themes/word-blue.css');
$doc->heading(array('text' => 'A sample document with a theme', 'level' => 1));
$doc->paragraph()
    ->text(array('text' => 'We are going to generate a series of tables with the different styles defined in the ' ))
    ->text(array('text' => 'word-blue' ))->style('font-weight: bold')
    ->text(array('text' => ' theme.'));
//list of table styles
$styles = array('Grid', 'Grid1', 'Grid2', 'Grid3', 'Grid4', 'List', 'List1', 'List2', 'List3', 'List4');
//loop over them
foreach ($styles as $style){
    $doc->heading(array('text' => 'Table style: ' . $style, 'level' =>2))->style('margin-top: 20px');
    $doc->table(array('grid' => array('4cm', '5cm', '5cm'), 'style' => $style, /*'mask' => array('bandedCol' => true)*/))
        ->row()
            ->cell()->paragraph(array('text' => 'Product'))
            ->cell()->text(array('text' => 'Reference'))
            ->cell()->text(array('text' => 'Availability'))
        ->row()
            ->cell()->paragraph(array('text' => 'Camera'))
            ->cell()->text(array('text' => '33-HJK-98'))
            ->cell()->text(array('text' => 'In stock'))
        ->row()
            ->cell()->paragraph(array('text' => 'MP3 Player'))
            ->cell()->text(array('text' => '34-KJU-02'))
            ->cell()->text(array('text' => '2 weeks'));
}
$doc->paragraph(array('text' => 'We finish this example by using a custom paragraph style.', 'style' => 'quote'));
//include in the render method the path where you want your document to be saved
$doc->render('theme' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'theme' . $format . '">Download document</a>';  