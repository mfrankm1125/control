<?php
/**
 * This sample script inserts some (extended) HTML5 code into the document
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\CreateDocument();
$format = '.odt';//.pdf, .odt, other doc formats do not support forms
//html code
$html = '
<html>
    <head>
        <style>
        body {font-family: Arial; font-size: 11pt}
        input, select {margin-left: 10px}
        .Docxpressoorm {border: 1px solid #333; padding: 15px 15px 0 15px; background-color: #f6f6ff; margin: 15px}
        .Docxpressoorm p {margin-bottom: 10px;}
        </style>
    </head>
    <body>
        <form class="Docxpressoorm">
            <p><label>Your name:</label> <input type="text" name="yourName" value="" /></p>
            <p>
                <label>Gender:</label> 
                <select name="gender">
                    <option value="male">Male</option>
                    <option value="female" selected>Female</option>
                    <option value="other">Other</option>
                </select>
            </p>
            <p><label>I like Docxpresso:</label> <input type="checkbox" name="like" value="0" checked /></p>
        </form>
    </body>
</html>
';
$doc->html(array('html' => $html));
$doc->render('form_html' . $format);   
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'form_html' . $format . '">Download document</a>';