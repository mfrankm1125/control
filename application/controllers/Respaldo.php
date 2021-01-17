<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Respaldo extends CMS_Controller{
    private $_list_perm;

    public function __construct(){
        parent::__construct();
        /*if (!$this->acceso()) {
            redirect('errors/error/1');
        }*/
        $this->load->model('global_model');
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){
        echo "hola";
    }

    public function Novale(){
       $db_host="localhost";
       $db_name="pyinia";
       $db_user="root";
       $db_pass="";
       $fechaFile=time();
       $salida_sql=$db_name.'_'.$fechaFile.'.sql';

       $PathMyslDump='D:\xampp\mysql\bin\mysqldump';
       $dump=" --host=".$db_host." --user=".$db_user." --password=".$db_pass." --opt $db_name > $salida_sql";
       $dump2=" --host=".$db_host." --useru=".$db_user." --password=".$db_pass." --opt ".$db_name." > ".$salida_sql;
       $finalPath=$PathMyslDump.$dump2;

        //echo $finalPath;
        //Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
        //$ultima_linea=system($PathMyslDump, $sal);
      // echo $ultima_linea;
        //print_r($sal);
            $ultima_linea = system('cd D:/xampp', $retval);

    // Imprimir informacion adicional
            echo '
    </pre>
    <hr />Ultima linea de la salida: ' . $ultima_linea . '
    <hr />Valor de retorno: ' . $retval;
        $o=shell_exec($dump2);
        print_r($o);
    }


    public function respaldo(){//funcionando
        $this->load->dbutil();
        $prefs = array(
            //'tables'        => array('table1', 'table2'),   // Array of tables to backup.
            //'ignore'        => array(),                     // List of tables to omit from the backup
            'format'        => 'zip',                       // gzip, zip, txt
            'filename'      => 'mybackup.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            //'newline'       => "\n"                         // Newline character used in backup file
        );
        $backup =& $this->dbutil->backup($prefs);
        $db_name = 'bk-'. date("Y-m-d-H-i-s") .'.zip';
        $save = 'pathtobkfolder/'.$db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->load->helper('download');
        force_download($db_name, $backup);
    }
}