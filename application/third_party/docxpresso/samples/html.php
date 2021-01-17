<?php
/**
 * This sample script inserts some (extended) HTML5 code into the document
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//html code
$html = '
<html>
    <head>
        <style>
        p {font-family: Verdana; font-size: 10pt}
        h1 {color: #b70000; margin-bottom: 12pt; font-family: "Century Gothic"; page-break-before: always}
        footnote {font-family: Verdana; font-size: 8pt}
        .headerTable {width: 15cm; border: none}
        .headerImage {width: 5cm}
        .headerTitle {width: 10cm; vertical-align: middle;}
        .headerTitle p {font-size: 12pt; font-weight: bold; color: #567896; font-family: "Century Gothic"}
        .docFooter {border-top: 1px solid #777; color: #555; text-align: right; font-family: Verdana; font-size: 10pt}
        .red {color: #b70000; font-size: 8pt; font-weight: bold}
        </style>
    </head>
    <body>
        <header>
            <table class="headerTable">
                <tr>
                    <td class="headerImage"><p><img src="Docxpresso.png"/></p></td>
                    <td class="headerTitle"><p>Docxpresso Sample Document</p></td>
                </tr>
            </table>
        </header>
        <h1>Sample document generated with HTML5</h1>
        <p>This example only aims to illustrate how <strong>HTML5PDF</strong> renders a sample
        web page in different document formats.</p>
        <p>We include a footnote<footnote>Just some random text with a 
        <span class="red"> little formatting</span>.</footnote>
        and a simple pie chart so we get a little more sophisticated example:</p>
        <chart type="pie" style="width: 15cm">
            <legend />
            <category name="First" value="30" />
            <category name="Second" value="20" />
            <category name="Third" value="25" />
            <category name="Fourth" value="10" />
        </chart>
        <h1>Another page</h1>
        <p>This is just to check how the header and footer are included in every 
        single page with the correct page numbering.</p>
        <footer>
            <p class="docFooter">Page <page /></p>
        </footer>
    </body>
</html>
';
$doc->html(array('html' => $html));
$doc->render('sample_html' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'sample_html' . $format . '">Download document</a>';