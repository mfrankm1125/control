<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'third_party/PhpWord/Autoloader.php';



use PhpOffice\PhpWord\Autoloader as Autoloader;
use PhpOffice\PhpWord\Settings as Settings;
Autoloader::register();
Settings::loadConfig(APPPATH."third_party/PhpWord/phpword.ini.dist");




class Word extends Autoloader {

}

