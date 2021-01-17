<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguridad extends CMS_Controller
{
    public function index()
    {
        $cmd = shell_exec('GETMAC /fo table /nh');
        $cmd2 = shell_exec('GETMAC /fo table /nh /v');

        $mac=explode("   ",$cmd);
        $dispo=explode("   ",$cmd2);

//echo"<pre>$xx</pre>";
        echo "<pre>";print_r(trim($mac[0]));
        echo "<pre>";print_r(trim($dispo[0]));

    }

}