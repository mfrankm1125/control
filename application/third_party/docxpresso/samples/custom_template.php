<?php
/**
 * This sample script generates a sample
 * document out of HTML code with a custom
 * template
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument(array('template' => 'templates/customdoc.odt'));
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
$html='
<html>
    <head>
        <style>
         * {font-family: Arial; font-size: 10pt}
         h1 {margin-top: 25pt}
         h2 {margin: 25pt 0 5pt 0}
         p {margin-bottom: 10pt}
         th {background-color: #5B9BD5; padding: 3px 7px}
         th p {color: #f6f6f6; font-weight: bold; font-size: 10pt; margin: 0}
         td {padding: 3px 7px; border: 0.5pt solid #ffffff}
         td p {color: #333; font-size: 10pt; margin: 0}
         li {margin-bottom: 5pt}
         li li {font-style: italic; color: #555;}
         header {height: 1.5cm}
         .headerTable {width: 15cm}
         .headerTable td {border: none; border-bottom: 0.5pt solid #333}
         .headerTable td.left {text-align: right; width: 5cm; background-color: #b74400}
         .headerTable td.left p {color: #ffffff}
         .headerTable td.middle {color: #333; width: 8cm}
         .headerTable td.right {text-align: right; color: #333; width: 2cm}
         .summary {margin: 10pt 40pt; text-align: justify}
         .footnote {font-size: 9pt}
         .image {text-align: center}
         .nice {width: 12cm; margin: auto}
         .firstCol {background-color: #5B9BD5;}
         .firstCol p {color: #f6f6f6; font-weight: bold; font-size: 10pt; margin: 0}
         .even {background-color: #BDD6EE;}
         .odd {background-color: #DEEAF6}
        </style>
    </head>
    <body>
        <h1>Sample document</h1>
        <p class="summary"><strong>Summary. </strong> This is a document created out from HTML5 and CSS code. 
                The document includes a few standard document elements to better illustrate the package functionality.</p>
        <h2>Some random content</h2>
        <p>This is a paragraph where we are going to include a couple of elements. Here we include a 
        footnote<footnote><span class="footnote">To include a footnote is as simple as this.</span></footnote> and also a link to
        <a href="http://www.docxpresso.com">DOCXPRESSO</a> website.</p>
        <p class="image"><img src="img/scene.jpg"/></p>
        <h2>A nice table</h2>
        <p>Let us include a nicely formatted table:</p>
        <table class="nice">
            <tr>
                <th><p>First Column</p></th>
                <th><p>Second Column</p></th>
                <th><p>Third Column</p></th>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 1</p></td>
                <td class="even"><p>C_1_1</p></td>
                <td class="even"><p>C_1_2</p></td>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 2</p></td>
                <td class="odd"><p>C_2_1</p></td>
                <td class="odd"><p>C_2_2</p></td>
            </tr>
            <tr>
                <td class="firstCol"><p>Row 3</p></td>
                <td class="even"><p>C_3_1</p></td>
                <td class="even"><p>C_3_2</p></td>
            </tr>	
        </table>
        <h2>A nested list</h2>
        <p>In order to finish the example a nested list: </p>
        <ul>
            <li>This item has a <strong>sublist</strong>:
                <ul>
                    <li>Subitem 1.</li>
                    <li>Subitem 2.</li>
                </ul>
            </li>
            <li>The final item.</li>
        </ul>
    </body>
</html>
';
//insert the HTML
$doc->html(array('html' => $html));
//include in the render method the path where you want your document to be saved
$doc->render('template_HTML' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'template_HTML' . $format . '">Download document</a>'; 