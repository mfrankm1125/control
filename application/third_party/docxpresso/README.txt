DOCXPRESSO v3.5 Release Notes
============================
This minor version includes:

    * A new format Conversor subpackage
    * Compatibility with latest PHP versions (up to PHP 7.1.3)
    * The possibility of carrying out simple variable replacement 
      in spreadsheets (.ods)
    * Several improvements in HTML to ODF conversion
    * Better rendering of SVG in ODF2HTML5
    * Minor bug fixes
	
Conversor subpackage
--------------------
This subpackage opens the internal Docxpresso document format conversion engine 
to arbitrary format conversions among standard document formats.

It is enough to give the path to the document to be converted and the path to
the final converted document (there are also a bunch of additional options for 
PDF conversión, like zoom, password protection, etcetera).

WARNING: this functionality has required to update the Docxpresso extension 
for Libre Office and Open Office so if you are updating a current installation
you should first remove the old extensions.


DOCXPRESSO v3.1 Release Notes
============================
This minor version includes:

    * Several improvements in HTML to ODF conversion
    * Better rendering of SVG in ODF2HTML5
    * Improvements in the repairVariables method
    * Minor performance improvements
    * "check.php" a command line utility to check the installation of Docxpresso.
    * Embedded installation instructions
    * A yet undocumented JSON REST interface that enables interact remotely with 
      the Docxpresso Core API
    * Minor bug fixes


DOCXPRESSO v3.0 Release Notes
============================
Besides a bunch of minor improvements and bug fixes in the core functionality of 
the library, v3.0 incorporates new and useful methods and a full new subpackage 
ODF2HTML5.

Templates
---------

When introducing placeholder variables in a template using MS Word it may happen 
that they are internally torn apart by the MS Word editor, thus breaking the proper 
functioning of the replace method.

Although this problem can be circumvented by carefully rewriting the variable, we 
have introduced a new repairVariables that eliminates the need to do so.

By running the repairVariables method on any Docxpresso template you will make sure 
that the integrity of the included variables is always preserved.

With version 3.0 we have also introduced partial support for .odp (the Open 
Document Format version of .pptx and .ppt files) so you can now replace 
placeholder variables, clone and remove chinks of contents in your 
"presentation" files.

ODF2HTML5
---------

This new subpackage allows for the conversion of documents into HTML5 + CSS with 
native support for:

    *Nicely formatted text
    *Headings (numbered)
    *Tables
    *Links and bookmarks
    *Nested lists with sophisticated numberings
    *Images
    *Textboxes
    *Footnotes and endnotes
    *Headers and Footers
    *Tables of contents (TOCs)
    *Charts
    *Comments
    *Math equations
    *Drop caps
    *Office SmartArt and forms (partial)
    *Support for Right-To-Left languages
    *And much more...

Samples
-------

We also have included in the downloadable packege a samples folder with close to
100 scripts that illustrate the functionality of the library.

These samples are the perfect starting point to get you acquainted with Docxpresso
functionality and they also can serve you as a basis to start building your own
scripts.


DOCXPRESSO v2.0 Release Notes
============================
DOCXPRESSO v2.0 comes bundle with a great deal of new features that will help you
to generate beautiful business reports and general purpose document even easier
than before.

Besides a bunch of minor improvements and bug fixes in the core functionality of
the library, v2.0 incorporates full support for templates and themes.

Templates
---------

In DOCXPRESSO v2.0 templates are much more than passive receptacles for 
additional custom content, thanks to the new Templates subpackage you may:

    * Clone arbitrary sections of the template: paragraphs, tables, sections, 
      chapters, images, charts and bookmarked text and insert it anywhere else 
      within the template.
    * Replace placeholder variables (or plain text) by plain text, HTML content 
      and/or document fragments.
    * Remove arbitrary template contents so there is no need to use different 
      templates for slightly different final documents.
    * Modify chart data without altering its format and display features.
    * Locate the cursor anywhere within the template so one may insert content 
      at ease wherever required.

Thanks to this new extended API you may generate with a few lines of code, and 
the help of a template, a truly sophisticated data driven document streamlining 
all the required design process.

Themes
------

The main goal of the Themes subpackage is to simplify the creation of richly 
formatted content with the core API of DOCXPRESSO.

You may now use theme stylesheets that allows for the straightforward generation 
of paragraphs, titles and tables that automatically incorporate all their 
predefined formatting.

In particular DOCXPRESSO v2.0 comes already bundled with a "word-blue" theme that
mimics the styling of the latest versions of the Microsoft Office suite. You 
may, of course, build your own themes just by modifying this one or creating 
them directly from scratch.


DOCXPRESSO v1.0 Release Notes
============================

DOCXPRESSO is a highly sophisticated library designed to facilitate the 
generation and deployment of documentation in a web server.

DOCXPRESSO allows for the dynamical generation of PDF, Word, ODF and/or RTF files 
from virtually any data source,in any web platform infrastructure in a simple 
and cost-efficient way.

One may generate all kind of documentation from academic papers to business 
reports with a single tool in almost any conceivable format just by transforming 
HTML5 code or directly using its powerful public API.

The generated documents may contain:

    * Standard document components like, paragraphs, images, tables and list,
    * headers and footers.
    * footnotes and endnotes,
    * tables of contents,
    * 2D and 3D charts,
    * cross-references,
    * interactive forms (only PDF and ODF output formats),
    * etcetera.
    * DOCXPRESSO may be used under different licenses that only differ in its 
      scope of use but not functionality:
        ** FREE, at no cost, for personal use,testing and development and 
           charitable organizations (the last may apply for a free PRO license). 
           The documentation generated with this type of license include a very 
           discreet DOCXPRESSO branding at the end of the document.
        ** PRO, conceived for professional and commercial use it allows 
           the generation of unlimited documents by unlimited editors in a 
           single (web) server.
        ** SaaS, for the integration within a web app with SaaS or 
           PaaS architecture.
        ** OEM, for the redistributionwithin third party software or bulk 
           licensing.