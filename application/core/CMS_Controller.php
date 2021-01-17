<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->config('cms');//carga la configuracion de archivo config/cms.php
        $item = $this->config->item('cms_admin_panel_uri');
        if (!$item) {
            show_error('Configuration error');
        }
        $this->_set_lenguage();
        $this->lang->load('cms_general');
        $this->load->library(['template', 'user']);
    }


    public function admin_panel()
    {
        //print($this->uri->segment(1));exit();

        return strtolower($this->uri->segment(1)) == $this->config->item('cms_admin_panel_uri');
        //permite saber si se esta accediendo por url al admin
        //caso contrario botara al default=theme
    }

    private function _set_lenguage()
    {

        $lang = $this->session->userdata('global_lang');

        if ($lang && in_array($lang, $this->config->item('cms_lenguages'))) {

            $this->config->set_item('language', $lang);
        }
    }


    /*public function admin_panel_uri(){

        return $this->config->item('cms_admin_panel_uri').'/';
    }*/

    public function acceso(){
        
        $id_role=(int)$this->session->userdata('id_role');
        $id_user=(int)$this->session->userdata('user_id');

        $aut=(int)$this->session->userdata('autenticado');
        $url_m=trim($this->uri->segment(1));

        if($aut== 0){
            redirect();
        }
        $acceso=$this->_c_acceso($id_user,$id_role,$url_m);
        $r=FALSE;
        if($acceso >= 1){
            $r=TRUE;
        }
       // verifico datos ::$cadena=$id_role."x".$id_user."s".$aut;
        return $r;
    }
    
    public function lista_permisos(){

        $id_role=(int)$this->session->userdata('id_role');
        $id_user=(int)$this->session->userdata('user_id');
        $aut=(int)$this->session->userdata('autenticado');
        $url_m=(string)trim($this->uri->segment(1));

        if($aut == 0){
            redirect();
        }

        $sql="SELECT\n".
            "permissions.`name`,permissions.titulo\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN role_permission ON role_permission.permission = permissions.id\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "where modulos.estado= 1 and permissions.estado=1\n".
            "and role_permission.`value`=1 and roles.estado=1\n".
            "and roles.id=".$id_role."\n".
            "and modulos.url='$url_m'\n".
            "UNION\n".
            "SELECT\n".
            "permissions.`name`,permissions.titulo\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN user_permission ON user_permission.permission = permissions.id\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "where modulos.estado = 1 and permissions.estado=1 \n".
            "and user_permission.`value`=1 and users.active =1\n".
            "and users.id=".$id_user." \n".
            "and modulos.url='".$url_m."'";


        $permisos_lista=$this->_c_lista_permisos($id_user,$id_role,$url_m);
        $permisos_lista=array_column($permisos_lista,'nombre');


        return $permisos_lista;
    }

    public function getfecha_actual(){
        return date('Y-m-d H:i:s');
    }

    public function getUserId(){
        return (int)$this->session->userdata('user_id');
    }
    
    public function getNameAreaResponsable(){
        return $this->session->userdata('namearearesp');
    }
    public function getIdRoleUser(){
        return $this->session->userdata('id_role');
    }
    public function getDatosCaja(){
        $sql="SELECT\n".
            "cajas.saldo_cierre,\n".
            "cajas.id_caja\n".
            "FROM cajas\n".
            "where ISNULL(fecha_cierre) and estado_caja=1\n".
            "ORDER BY id_caja desc";
        $r=$this->db->query($sql)->result_array();
         
        return $r;
    }

    public function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function password($data , $algo = "sha256"){
        if(! $this->config->item("encryption_key")){
            show_error('Encryption Key not found');
        }
        $hash = hash_init($algo, HASH_HMAC ,$this->config->item("encryption_key"));
        hash_update($hash, $data);
        return hash_final($hash);
    }

    public  function limpiaEspacios($cadena){
	$cadena = str_replace(' ', '', $cadena);
	return $cadena;
    }

    public function quitaSaltoLinea($string){
        $string2=str_replace(array("'",'"'), " ",$string);
        $sustituye = array("(\r\n)", "(\n\r)", "(\n)", "(\r)");
        $texto = preg_replace($sustituye, " ",$string2);
        return  $texto;
    }

    private function _c_acceso($id_u,$id_r,$url){

        $sql=" Select * from (SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.id=".$id_u." and users.`status`=1 and permissions.estado=1 and user_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.icono,\n".
            "users.`name`,\n".
            "modulos.id_modulo \n".
            "union ALL\n".
            "SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "where roles.id=".$id_r." and roles.estado=1 and permissions.estado=1 and role_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre) as modulos_d  \n".
            "where modulos_d.url='".$url."' \n".
            "GROUP BY  modulos_d.nombre,\n".
            " modulos_d.url,\n".
            "modulos_d.orden,\n".
            "modulos_d.icono,\n".
            "modulos_d.id_modulo,\n".
            "modulos_d.id_modulo_padre \n".
            "ORDER BY modulos_d.nombre  ";
        $r=$this->db->query($sql)->result_array();
        $r=sizeof($r);

        return $r;

    }

    private function _c_lista_permisos($id_user,$id_role,$url_m){

        $sql="Select * from (\n".
            "SELECT\n".
            "acciones.nombre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where permissions.estado=1 and roles.estado=1 and modulos.estado=1 and acciones.estado=1\n".
            "and role_permission.`value`=1 and modulos.url='".$url_m."' and role_permission.role=".$id_role." \n".
            "UNION ALL\n".
            "SELECT\n".
            "acciones.nombre\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN users ON user_permission.`user` = users.id \n".
            "where permissions.estado=1 and modulos.estado=1 and acciones.estado=1 and user_permission.`value`=1\n".
            "and users.`status`=1 and users.active=1 and modulos.url='".$url_m."' and user_permission.`user`=".$id_user." \n".
            ") as listAcciones GROUP BY listAcciones.nombre ";

        $r=$this->db->query($sql)->result_array();
        return $r;


    }

    public function showErrors($array=null){
            echo "<pre>";print_r($array);exit();
    }
}
