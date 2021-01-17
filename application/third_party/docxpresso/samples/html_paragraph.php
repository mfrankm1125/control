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
		body {font-family: Georgia; font-size: 11pt}
		.Docxpresso {text-indent: 1cm;}
		.Docxpresso img {float: right;}
		</style>
	</head>
	<body>
		<p class="Docxpresso"><img src="img/Docxpresso.png" alt="A nice picture of the team"/>
		This paragraph nicely wraps around a floating image. It is also very simple
		to include all other kind of inline embedded elements like <strong>Bolded text</strong>, <i>italics</i>
		or <span class="coloured">coloured text</span>. You may also include <a href="http://www.Docxpresso.com">links</a>
		and any other inline element.</p>

	</body>
</html>
';
$doc->html(array('html' => $html));
$doc->render('paragraph_html' . $format);   
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'paragraph_html' . $format . '">Download document</a>';