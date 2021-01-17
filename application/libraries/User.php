<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__) . '/Acl.php';
class User {

    private $CI;
    private $table = 'users';
    private $lang;
    private $acl;
    private $errors = [];
    private $user_id;
    private $user_user;
    private $user_name;
    private $user_email;
    private $user_status;
    private $user_active;
    private $patron = "/^([-a-z0-9_-])+s/i";

    public function __construct($options = array()) {
        $this->CI=& get_instance();
        $this->_set_lenguage(isset($options['lang'])? $options['lang'] :null );
        $row=null;
        if(isset($options['id']) && (int)$options['id'] > 0 ){
            $row =$this->_row(['id'=> (int)$options['id'] ]);//cargar usuarios desde la base ded atos

            if(sizeof($row) == 0){
                show_error($this->CI->lang->line('user_error_validate_user'));
            }
        }elseif((int) $this->CI->session->userdata('user_id') > 0){
            $row =$this->_row(['id'=> $this->CI->session->userdata('user_id')  ]);//cargar usuarios desde la base ded atos

            if(is_array($row)){
                if(  $row->active != 1 || $row->status != 1 ){

                    $this->CI->session->sess_destroy();
                    $this->_load(null);
                    return;
                }
            }


        }

        $this->_load($row);

    }

    public function __get($name){
        $property = 'user_'.$name;
        if(isset($this->$property)){
            return $this->$property;
        }
    }

    public function errors(){
        return $this->errors;
    }
    public function permissions(){
        return $this->acl->permissions;
    }

    public function site_permissions(){
        return $this->acl->site_permissions;
    }

    public function has_permission($name){
        return $this->acl->has_permission($name);
    }

    public function is_logged_in() {//avisa si esta logeado

        if ($this->user_id > 0) {

            return $this->user_id == (int) $this->CI->session->userdata('user_id');
        }
        return FALSE;
    }

    public function login($user,$password,$hash='sha256'){

        if (empty($user)) { //  || preg_match($this->patron, $user) preg_match  regula si el usuario ingresado coninsida con el patron que se ingresa
            $this->errors[] = $this->CI->lang->line('user_error_username');
        }
        if (empty($password)) {
            $this->errors[] = $this->CI->lang->line('user_error_empty_password');
        }
        if (count($this->errors)) {

            return FALSE;
        }

        //$this->CI->load->library('encrypt');
        $row = $this->_row(['user' => $user, 'password' =>  $password ]);//devuelve un registo de usuario lo que yo ida

        if(is_object ($row)){

            if ( $row->active != 1 || $row->status != 1) {
                $this->errors[] = $this->CI->lang->line('user_error_wrong_credentials');
                redirect("users/login");
                return FALSE;

            }

        }


        $this->_load($row);
        return TRUE;
    }


    private function _load($row = null){
        if( !is_object($row)  ){

            $this->user_id = 0;
            $this->user_user = $this->CI->lang->line('cms_general_label_site_visitor_user');
            $this->user_name = $this->CI->lang->line('cms_general_label_site_visitor_name');
            $this->user_email = '';
            $this->user_active = 0;
            $this->user_status = 0;
            $this->acl = new Acl(['lang' => $this->lang]);
            return;

        }


        $this->user_id = $row->id;
        $this->user_user = $row->user;
        $this->user_name = $row->name;
        // echo ( $this->user_id );exit();
        $this->CI->session->set_userdata('usuario',$this->user_name);
        $this->CI->session->set_userdata('id_role',$row->role);
        $this->CI->session->set_userdata('foto',$row->foto);
        $this->CI->session->set_userdata('autenticado','1');
        $this->CI->session->set_userdata('namearearesp',$row->nombrearearesponsable);
        //echo "<script>alert('".$row->role."');</script>";
        $this->user_email = $row->email;
        $this->user_active = $row->active;
        $this->user_status = $row->status;
        $this->acl = new Acl(['id' => $row->id, 'lang' => $this->lang]);
    }

    private function _row($where = null , $select = null){
        if(is_array($where)){
            $this->CI->db->where($where);
        }
        if(is_array($select)){
            $this->CI->db->select($select);
        }
        return $this->CI->db->get($this->table)->row();// retorna fila de usuario

    }

    private function  _set_lenguage($lang = null){
        // solo para esta libreria
        $languages = ['english','spanish'];
        if(! $lang){
            if(in_array($this->CI->config->item('language'),$languages)){

                $lang=$this->CI->config->item('language');
            }else{
                $lang =$languages[0];
            }
        }else{
            if(! in_array($lang,$languages)){
                $lang =$languages[0];

            }

        }
        $this->lang=$lang;
        $this->CI->load->language('user',$lang);
    }


}