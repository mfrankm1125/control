<?php
global $system,$console,$br,$cont_phases,$exists_all_class_are_installed,$array_ini;

$system = 'LINUX';
$console = 'FILE';
$br = '<BR>';
$cont_phases = 1;
$exists_all_class_are_installed = 1;
$array_ini = null;



function initVars()
{
    global $system,$console,$br;

    if (php_sapi_name() != 'cli'){
        $console = 'WEB';
    }

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $system = 'WINDOWS';
    }

    $br = ($console=='WEB'?'<br>':"\r\n");
}

function is_installed($class,$optional=false)
{
    global $exists_all_class_are_installed;
    $ret_ = false;
    $ret_ = (class_exists($class)?true:false);
    if(!$ret_ && !$optional) $exists_all_class_are_installed = 0;
    return $ret_;
}

function is_installed_java()
{
    global $system,$br;

    $command = "echo ".($system=='LINUX'?'$PATH':'%PATH%');
    $result = exec($command);
    $arr_ = explode(($system=='LINUX'?':':';'),$result);
    $file_java_found = false;
    foreach($arr_ as $value){
            if(file_exists($value.($system=='LINUX'?'/':'\\').'java'.($system=='LINUX'?'':'.exe'))) $file_java_found = true;
    }
    return $file_java_found;
}

function is_registered_docxpresso_extension()
{
    global $exists_all_class_are_installed,$system,$console,$br,$array_ini;
    $ret_ = 0;
    $output = "";
    if($array_ini==null) $array_ini = parse_ini_file("config.ini");
    exec(($system=='LINUX'?'':''.($system=="WINDOWS"?"\"":"").substr($array_ini['path'],0,strrpos($array_ini['path'],($system=="WINDOWS"?"\\":"/"))+1)).'unopkg'.($system=="WINDOWS"?".exe\"":"").' list Docxpresso.oxt'.($system=="LINUX"?' 2>&1':''), $output);
    foreach($output as $value)
    {
            $value = preg_replace('/[[:^print:]]/', '', $value);
            if(strpos($value,"is registered: yes")>0) $ret_ = 2;
    }
    if($ret_==0) {
        for($k=0;$k<2;$k++){
            if($ret_==0 || (($system=='LINUX' && $k>=0) || ($system!='LINUX' && $k<=0))){
            exec(($system=='LINUX'?($k>=1?'sudo ':''):''.($system=="WINDOWS"?"\"":"").substr($array_ini['path'],0,strrpos($array_ini['path'],($system=="WINDOWS"?"\\":"/"))+1)).'unopkg'.($system=="WINDOWS"?".exe\"":"").' list --shared Docxpresso.oxt'.($system=="LINUX"?' 2>&1':''),$output);
            foreach($output as $value){
                $value = preg_replace('/[[:^print:]]/', '', $value);
                if(strpos($value,"is registered: yes")>0) $ret_ = 1;
            }
        }
    }
}
    //if($ret_!=0 && $console=='FILE') $exists_all_class_are_installed=0;
    return $ret_;
}

function is_running_extension()
{
    global $system;
    if($system=='LINUX'){
        $command = "ps auxwwwww | grep docxpresso.php | grep -v grep";
        $result = exec($command,$output);
        if(sizeof($output)>=1) return true;
        else return false;
    } else if($system=='WINDOWS'){
        exec("tasklist 2>NUL", $task_list);
        $ret_ = false;
        foreach($task_list as $value){
                if(strpos("a".$value,"soffice")>0) $ret_ = true;
        }
        return $ret_;
    }
    return false;
}

function exists_libreoffice($get_)
{
    global $array_ini,$exists_all_class_are_installed;

    if($array_ini == null){
        $array_ini = parse_ini_file("config.ini");
    }
    if($get_=='PATH') {
        return $array_ini['path'];
    } else if($get_=='FILE') { 
        if(!file_exists($array_ini['path']) && !file_exists($array_ini['path'].".exe") && !file_exists($array_ini['path'].".bin")) { 
            $exists_all_class_are_installed=0; 
        } 
        return (file_exists($array_ini['path']) || file_exists($array_ini['path'].".exe") || file_exists($array_ini['path'].".bin")); 
    } else if($get_=='EXTENSION') { 
        return is_registered_docxpresso_extension();
    } else if($get_=='DEAMON') {
        return is_running_extension();
    }
    return null;
}

function subTextPermissions($tmp_perm)
{
	global $br;
	$texto_tmp = ($tmp_perm=="777"?"This directory is public and writable":"".
			($tmp_perm=="775"?"This directory is only writable by users belonging to the folder owner group":"".
			"It's probable that the web user can't write in the directory unless it is the owner.")).$br;
        if ($tmp_perm=="777"){
            $texto_temp = ' --This directory is public and writable';
        } else if ($tmp_perm=="775") {
            $texto_temp = ' --This directory is only writable by users belonging to the folder owner group.';
        } else {
            $texto_temp = ' --The web server and the docxpresso daemon may have problems trying to write in this folder.';
        }
        $texto_tmp .= $br;
	return $texto_tmp;
}
$is_directory_permissions_775 = true;
function get_directory_permissions($ruta){
     global $is_directory_permissions_775,$br,$exists_all_class_are_installed,$console;
	$csv_perm = substr(sprintf('%o', fileperms($ruta."/csv")), -3);
	$log_perm = substr(sprintf('%o', fileperms($ruta."/log")), -3);
	$tmp_perm = substr(sprintf('%o', fileperms($ruta."/tmp")), -3);
	$texto_tmp = $ruta."/csv have permissions ".$csv_perm.$br.subTextPermissions($csv_perm);
    $texto_tmp .= $ruta."/log have permissions ".$log_perm.$br.subTextPermissions($log_perm);
    $texto_tmp .= $ruta."/tmp have permissions ".$tmp_perm.$br.subTextPermissions($tmp_perm);
	
	if($csv_perm!="777" && $csv_perm!="775") {
		$is_directory_permissions_775=false;
	}
	if($log_perm!="777" && $log_perm!="775") {
		$is_directory_permissions_775=false;
	}
	if($tmp_perm!="777" && $tmp_perm!="775") {
		$is_directory_permissions_775=false;
	}
	
	echo boxAlert($texto_tmp,"ORANGE").($console=='FILE'?$br:'');
} 

function boxAlert($text,$color)
{
	global $console;
	if($console=='WEB') {
        	$ret_ = '<div class="alert alert-success fade in" style="background-color:#'.($color=='RED'?'8d0f00':($color=='ORANGE'?'FF8C00':'738d00')).';">';
        	$ret_ .= '        <span class="white"><h5 style="margin-bottom: 0">'.$text.'</h5></span>';
		$ret_ .= '</div>';
	} else $ret_ = $text;
	return $ret_;
}

function createHelloFile($format)
{
    global $br;
    require_once './CreateDocument.inc';
    $doc = new Docxpresso\createDocument();
    //$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
    //insert a paragraph with a link to the bookmarked paragraph
    $doc->paragraph(array('text' => 'Interactive Online Documents'));
    $doc->paragraph(array('text' => 'Convert your Office documents into interactive web pages.'));
    $doc->paragraph()
    ->text(array('text' => 'Generate dynamical documents with:'))
    ->lineBreak()
    ->text(array('text' => 'Direct user input.'))
    ->lineBreak()
    ->text(array('text' => 'Data from your databases.'));
    $doc->paragraph(array('text' => 'Gather user data without expensive IT developments.'));
    $doc->paragraph(array('text' => 'Turn your stale document repository into a live digital asset accessible from anywhere and in any device.'));
    $doc->paragraph(array('text' => 'Share knowledge with your colleagues and clients.'));
    $doc->paragraph()
    ->text(array('text' => 'Create beautiful documents and reports:'))
    ->lineBreak()
    ->text(array('text' => 'In any format PDF, Word, (ODF &amp; RTF).'))
    ->lineBreak()
    ->text(array('text' => 'From any data source.'))
    ->lineBreak()
    ->text(array('text' => 'With interactive charts.'))
    ->lineBreak()
    ->text(array('text' => 'Editable online.'))
    ->lineBreak()
    ->text(array('text' => 'Based on Open Standards.'))
    ->lineBreak()
    ->text(array('text' => 'And much more...'));

	$texto_tmp = "The file is not created, it's posible that the directory does not have the needed writing permissions.";
    if($format=='.pdf')
    {
        $options = array();
        $options['EncryptFile'] = false;
        //$options['DocumentOpenPassword'] = '1234';
        $options['Magnification'] = 4;
        $options['Zoom'] = 60;
        $options['InitialView'] = 2;
        //include in the render method the path where you want your document to be saved
        $doc->render('tmp/hello' . $format, $options);
		sleep(1);
		if(!file_exists('./tmp/hello' . $format)) {
			return boxAlert('tmp/hello' . $format.$br.$texto_tmp,"RED");
		}

    }
    else
    {
        //include in the render method the path where you want your document to be saved
        $doc->render('tmp/hello' . $format);
		sleep(1);
		if(!file_exists('./tmp/hello' . $format)) {
			return boxAlert('tmp/hello' . $format.$br.$texto_tmp,"RED");
		}
    }
	return "";
}

function getHeadHTML()
{
ob_start(); 

?><!DOCTYPE html>
<html lang="en" dir="ltr" prefix="content: http://purl.org/rss/1.0/modules/content/  dc: http://purl.org/dc/terms/  foaf: http://xmlns.com/foaf/0.1/  og: http://ogp.me/ns#  rdfs: http://www.w3.org/2000/01/rdf-schema#  schema: http://schema.org/  sioc: http://rdfs.org/sioc/ns#  sioct: http://rdfs.org/sioc/types#  skos: http://www.w3.org/2004/02/skos/core#  xsd: http://www.w3.org/2001/XMLSchema# ">
<head>
  <meta charset="utf-8" />
<meta name="title" content="Docxpresso" />
<meta name="description" content="There is no doubt that, even if we have tried to be as comprehensive as possible, there may be many unanswered questions or other issues regarding Docxpresso that may not have been properly covered elsewhere in this website." />
<meta name="MobileOptimized" content="width" />
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="http://www.docxpresso.com/themes/docxpresso/favicon.ico" type="image/vnd.microsoft.icon" />

  <title>Docxpresso: check installation</title>
  <link rel="stylesheet" href="http://www.docxpresso.com/sites/default/files/css/css_SAGtjnI0bn8PmeYKo4Zu7UhD8b9qafHUX_aCAOrqWKs.css?oi47sf" media="all" />
<link rel="stylesheet" href="http://www.docxpresso.com/sites/default/files/css/css_eWmbbi3frMJPauCYHygIVEjcDmNqivacE1SJjDW017s.css?oi47sf" media="all" />
<link rel="stylesheet" href="http://www.docxpresso.com/http://fonts.googleapis.com/css?family=Arimo:400,700,400italic,700italic" media="all" />
<link rel="stylesheet" href="http://www.docxpresso.com/sites/default/files/css/css_dmpeD7aMc1c1Bh6aReWegpBxApo854dEsfeOOdpj-mk.css?oi47sf" media="all" />

<!--[if lte IE 9]>
<link rel="stylesheet" href="http://www.docxpresso.com/sites/default/files/css/css_Vbh9K5xeQlGh_eskY8KFkEYDwOL6CVD98hqq7MwDjlE.css?oi47sf" media="all" />
<![endif]-->

  
<!--[if lte IE 8]>
<script src="http://www.docxpresso.com/sites/default/files/js/js_VtafjXmRvoUgAzqzYTA3Wrjkx9wcWhjP0G4ZnnqRamA.js"></script>
<![endif]-->

  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>">
  <![endif]-->
</head>
<body id="boxed-bg" class="boxed fixed-top">
    
  <div class="page-box">
  <div class="page-box-content">
    <div id="top-box">
  <div class="top-box-wrapper">
    <div class="container">
	<nav role="navigation" aria-labelledby="block-shortcuts-menu" id="block-shortcuts">
 </nav>

      </div>
    </div>
  </div>
</div>

    <header class="header">
  <div class="header-wrapper">
    <div class="container">
      <div class="row">
        <div class="logo-box">
      <div class="logo">
      <a href="/" title="Home">
        <img alt="" class="logo-img" src="http://www.docxpresso.com/themes/docxpresso/images/logo_docxpresso.png">
      </a>
    </div>
      </div>           
<nav role="navigation" aria-labelledby="block-docxpressomainnavegation-menu" id="block-docxpressomainnavegation">
<?php
$head_ = ob_get_contents();
ob_end_clean();
return $head_;
}

initVars();

$cont_phases = 1;

if($console=='WEB') echo getHeadHTML();

if($console=='WEB') echo '<h2 class="visually-hidden" id="block-docxpressomainnavegation-menu">';

echo "Docxpresso...".$br;

echo "".$br;

if($console=='WEB') echo '</h2>';

if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases.".-Modules PHP:".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

echo boxAlert("ZipArchive... ".(is_installed('ZipArchive')?"installed":"NOT INSTALLED"),(is_installed('ZipArchive')?"GREEN":"RED")).($console=='FILE'?$br:'');
echo boxAlert("DOMDocument... ".(is_installed('DOMDocument')?"installed":"NOT INSTALLED"),(is_installed('DOMDocument')?"GREEN":"RED")).($console=='FILE'?$br:'');
echo boxAlert("Tidy... ".(is_installed('Tidy',true)?"installed":"NOT INSTALLED")." (optional but highly recommended)",(is_installed('Tidy',true)?"GREEN":"ORANGE")).($console=='FILE'?$br:'');

echo $br;

if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases.".-JDK (optional):".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

echo boxAlert("...".(is_installed_java()?"installed":"NOT INSTALLED"),(is_installed_java()?"GREEN":"ORANGE")).($console=='FILE'?$br:'');
echo $br;

if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases."-Path LibreOffice/Openoffice".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

echo boxAlert("...".exists_libreoffice('PATH')." ".(exists_libreoffice('FILE')?"installed":"NOT INSTALLED"),(exists_libreoffice('FILE')?"GREEN":"RED")).($console=='FILE'?$br:'');
echo $br;

if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases."-It's running docxpresso deamon?".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

        $exists_extenison_ = exists_libreoffice('DEAMON');
        if(!$exists_extenison_) {
        	if($system=='WINDOWS')
			echo boxAlert("Rename the docxpresso._vbs_, docxpresso._bat_ and soffice._vbs_ to docxpresso.vbs, docxpresso.bat and soffice.vbs respectively (we have renamed these files to avoid virus warnings while downloading the package)".$br."Navigate to the package root directory and run docxpresso.vbs \"C:\Program Files\LibreOffice 4\program\soffice\"",($exists_extenison_?'GREEN':'ORANGE')).($console=='FILE'?$br:'');
		else
			/*
		The daemon for PDF conversion is not running. If you want to generate PDFs, DOCXs or RTFs please run the following command:
			*/
			echo boxAlert("".($exists_extenison_?"running":"The daemon for PDF conversion is not running. If you want to generate PDFs, DOCXs or RTFs please run the following command:".$br."nohup php [path/Docxpresso package]/docxpresso.php > /dev/null &"),($exists_extenison_?'GREEN':'ORANGE')).($console=='FILE'?$br:'');
	}
	else
		echo boxAlert("...running",'GREEN').($console=='FILE'?$br:'');

echo $br;



if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases."-Extension LibreOffice".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

if($console=='WEB' && $system=="LINUX")
{
	echo boxAlert("To confirm that the extension is running, you need to run in the command line as superuser:".$br.($system=='LINUX'?'sudo ':'')."unopkg list --shared Docxpresso.oxt",'ORANGE');
}
else
{
	$add_text = "";
	$exists_extenison_ = exists_libreoffice('EXTENSION');
	if($exists_extenison_==0 && $console=='FILE') {
		echo "Run this command:".$br;
		//echo "nohup php [path/Docxpresso package]/docxpresso.php > /dev/null &".$br;
		echo ($system=='LINUX'?'sudo ':'')."unopkg add --shared [path/Docxpresso package]/extensions/[LibreOffice or OpenOffice]/Docxpresso.oxt".$br;
	}
	else if($exists_extenison_==2)
	{
		$add_text .= "The extension is only installed for the current user, not for all the users.".$br;
		$add_text .= "It's necesary that the docxpresso".($system=="LINUX"?".php":".vbs")." script is run by the same user.".$br;
		//2. La extensión está sólo instalada para el usuario: avisar de que el usuario que corra el conversor tiene que ser el mismo.
	}
	echo boxAlert($add_text."...".($exists_extenison_!=0?"installed":"NOT INSTALLED"),($exists_extenison_!=0?($exists_extenison_==2?"ORANGE":"GREEN"):"RED")).($console=='FILE'?$br:'');
}
echo $br;

if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

echo $cont_phases."-Access permissions".($system=='LINUX'?" (775 or 777)":"").":".($console=='WEB'?'</a></h5>':$br);
$cont_phases++;

get_directory_permissions(getcwd());
if($is_directory_permissions_775)
	echo boxAlert("All ok",'GREEN');

echo $br;
if($console=='FILE') echo $br;

if($exists_all_class_are_installed==1)
{

	if($console=='WEB') echo '<h5><a href="" onClick="return false;" class="text-decoration:none;">';

	echo $cont_phases."-Example \"Hello World\" Script".($console=='WEB'?'</a></h5>':$br);
	$cont_phases++;
	$text_odt = createHelloFile('.odt');
	$text_pdf = createHelloFile('.pdf');
	echo $text_odt;
	if($text_odt=="") {
		echo boxAlert("File odt: ".($console=='WEB'?'<a href="tmp/hello.odt">':"")."tmp/hello.odt".($console=='WEB'?'</a>':''),'GREEN');
	}
	if($console=='FILE') echo $br;
	echo $text_pdf;
	if($text_pdf=="") {
		echo boxAlert("File pdf: ".($console=='WEB'?'<a href="tmp/hello.pdf">':"")."tmp/hello.pdf".($console=='WEB'?'</a>':''),'GREEN');
	}
	if($console=='FILE') echo $br;
}
else
{
	echo "Doc: ".($console=='WEB'?'<a href="http://www.docxpresso.com/documentation-api/quick-user-guide">':"")."http://www.docxpresso.com/documentation-api/quick-user-guide".($console=='WEB'?'</a>':'').$br;
}


if($console=='WEB')
{
echo $br.$br;
echo '</body>';
echo '</html>';
}
