<?php
/**
 * This sample script inserts math equations
 */
require_once '../CreateDocument.inc';
$doc = new Docxpresso\createDocument();
$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a paragraph:
$doc->paragraph(array('text' => 'This is a sample math equation:'));
//insert a math equation in MathML 1.0
$eq='<math xmlns="http://www.w3.org/1998/Math/MathML">
        <mrow>
            <mrow>
                <mstyle mathvariant="bold">
                    <mrow>
                        <mi>A</mi>
                    </mrow>
                </mstyle>
                <mo stretchy="false">=</mo>
                <mfenced open="[" close="]">
                    <mrow>
                        <mtable>
                            <mtr>
                                <mtd>
                                    <mrow>
                                        <mi>a</mi>
                                    </mrow>
                                </mtd>
                                <mtd>
                                    <mrow>
                                        <mi>b</mi>
                                    </mrow>
                                </mtd>
                            </mtr>
                            <mtr>
                                <mtd>
                                    <mrow>
                                        <mi>c</mi>
                                    </mrow>
                                </mtd>
                                <mtd>
                                    <mrow>
                                        <mi>d</mi>
                                </mrow>
                                </mtd>
                            </mtr>
                        </mtable>
                    </mrow>
                </mfenced>
            </mrow>
        </mrow>
    </math>';

$doc->paragraph(array('style' => 'text-align: center;'))
		->math($eq, array('math-settings' => array('base-font-size' => 16)));
//and some additional inline math
$eq2 = '<math xmlns="http://www.w3.org/1998/Math/MathML">
            <mrow>
                <mn>2</mn><mi>π</mi><mi>r</mi>
            </mrow>
        </math>';
$doc->paragraph()
                ->text(array('text' => 'The circumference length is given by '))
		->math($eq2)
                ->text(array('text' => ' .'));
//include in the render method the path where you want your document to be saved
$doc->render('simple_math' . $format); 
//echo a link to the generated document
echo 'You may download the generated document from the link below:<br/>';
echo '<a href="' . 'simple_math' . $format . '">Download document</a>';