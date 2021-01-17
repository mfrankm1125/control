<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl {

    private $CI;
    private $tables=[
        'users'=>'users',
        'roles'=>'roles',
        'perms'=>'permissions',
        'user_perms'=>'user_permission',
        'role_perms'=>'role_permission'
    ];
    private $user_id;//guarda id usuario
    private $user_role_id;// guarda ide role
    private $user_permissions;// permisos
    private $user_site_permissions;// los permisios para el citio

    public function __construct($options=array()){
        $this->CI = & get_instance();
        $this->CI->load->config('acl');
        $this->user_id=isset($options['id']) ? (int)$options['id'] : 0;
        if($this->user_id>0){
            // carga role
            $user_role =$this->CI->db->select('role')->get_where($this->tables['users'],['id'=>$this->user_id])->row();
            //setear id role

            $this->user_role_id = isset($user_role->role)? (int) $user_role->role : 0  ;
        }
        //setear lenguaje
        $this->_set_lenguage(isset($options['lang']) ? $options['lang']  :null );
        // setear permisos de usuario

       // $this->user_permissions=array_merge($this->role_permissions(),$this->user_permissions());

        // setear permisos del sitio
        //
        $this->user_site_permissions=$this->permissions('acl_site_perm','public');
    }

    public function __get($name){
        $property= 'user_'.$name;
        if(isset($this->$property)){
            return $this->$property;
        }
    }

    public function role_permissions_ids(){
      // devulve los id role
        $ids=[];
        if($this->user_role_id > 0){
                $perms=$this->CI->db->select('permission')->get_where($this->tables['role_perms'],['role'=>$this->user_role_id])->result_array();

                // devulve un array solo del item permission
                $ids=array_map(function($item){
                    return $item['permission'];
                },$perms);

            array_filter($perms);
        }

        return $ids;
    }
    public function role_permissions(){
        if($this->user_role_id > 0){
            $permissions = $this->CI->db
                          ->from($this->tables['role_perms'].' r')
                          ->select(['r.permission','r.value','p.name','p.titulo'])
                          ->join($this->tables['perms']. ' p','r.permission = p.id')
                           ->where(['r.role'=> $this->user_role_id])
                            ->get()->result();
            if(sizeof($permissions)>0){
                $data=[];
                foreach ($permissions as $permx){
                    if(trim($permx->name) == '') continue;//si es vacio continua sin colocarlo

                    $data[$permx->name]=[
                        'permission'=>$permx->name ,
                        'title'=>$permx->titulo ,
                        'value'=>$permx->value ==1 ? TRUE:FALSE ,
                        'heredado'=>TRUE ,
                        'id'=>$permx->permission ,
                    ];
                }

               if(sizeof($data)){
                   return $data;
               }

            }
        }
        return $this->permission('public');

    }

    public function user_permissions(){
        $data=[];
        if($this->user_id>0 && $this->user_role_id > 0){

            $ids=$this->role_permissions_ids();
            if(sizeof($ids) >0 ){
                $permissions = $this->CI->db
                    ->from($this->tables['user_perms'].' u')
                    ->select(['u.permission','u.value','p.name','p.titulo'])
                    ->join($this->tables['perms']. ' p','u.permission = p.id')
                    ->where(['u.user'=> $this->user_id])
                    ->where_in('u.permission',$ids)
                    ->get()->result();

                if(sizeof($permissions)>0){

                    foreach ($permissions as $permx){
                        if(trim($permx->name) == '') continue;//si es vacio continua sin colocarlo

                        $data[$permx->name]=[
                            'permission'=>$permx->name ,
                            'title'=>$permx->titulo ,
                            'value'=>$permx->value == 1 ? TRUE : FALSE ,
                            'heredado'=>FALSE ,
                            'id'=>$permx->permission ,
                        ];
                    }

                }
            }

        }
        return $data;


    }
    public function has_permission($perm_name){
        if(array_key_exists($perm_name,$this->user_permissions)){
            if($this->user_permissions[$perm_name]['value'] == TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }

    private function permissions($line ,$default){
        $permissions = $this->CI->config->item($line);
        $result=[];
        if(is_array($permissions)  && sizeof($permissions) > 0){
            foreach($permissions as $permxx){

                if($this->has_permission($permxx) === TRUE){

                    $result[]=$permxx;
                }
            }
        }
        if(sizeof($result) == 0){
            $result[]=$default;
        }
        return $result;

    }

    private function permission($name){
    $name=trim($name);
        if(! empty($name)){
            $permission=$this->CI->db->get_where($this->tables['perms'],['name'=>$name])->row();
            //print_r($permission);exit();
            if(sizeof($permission)>0){

                return [$permission->name= [
                    'permission'=>$permission->name ,
                    'title'=>$permission->titulo ,
                    //'value'=>$permission->value == 1 ? TRUE : FALSE ,
                    'value' =>TRUE,
                    'heredado'=>TRUE ,
                    'id'=>$permission->id ,
                ]];

            }
        }
        //show_error($this->CI->lang->line('acl_error_permission_not_found'));

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
        $this->CI->load->language('acl',$lang);
    }



}