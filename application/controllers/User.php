<?php // User: Frank Date: 08/11/2016* Time: 02:12 PM
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CMS_Controller
{
    private $_list_perm;
    public function __construct()
    {
        parent::__construct();

       /* if (!$this->acceso()) {
            redirect('errors/error/1');
        } */
        $this->_list_perm = $this->lista_permisos();

    }

    public function index(){
        // databale js
        $this->template->add_js('url_theme','plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme','plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme','plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        //multi js
        $this->template->add_js('url_theme','plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');

        //$this->template->add_js('url_theme','js/demo/tables-datatables');

        $this->template->add_js('url_theme','plugins/bootstrap-select/bootstrap-select.min');
        $this->template->add_css('url_theme','plugins/bootstrap-select/bootstrap-select.min');
        //
        $this->template->add_js('url_theme','plugins/bootstrap-wizard/jquery.bootstrap.wizard.min');

        $this->template->add_js('base','funciones');
        $this->template->add_js('base','underscore');
        $this->template->add_js('view','index');

        $out_rol=$this->db->get_where('roles',['estado'=>1])->result_array();
        $data=array('titulo'=>'Usuarios',
                    'o_rol'=>$out_rol);
        $this->template->set_data('data',$data);
        $this->template->renderizar('users/index');
    }


    public function out_data_user(){
        $this->db->select('users.*,roles.id as id_role, roles.role');
        $this->db->from('users');
        $this->db->join('roles', 'users.role = roles.id');
        $this->db->where(['users.status'=>1 ,'users.active'=>1,'roles.estado'=>1 ]);
        $this->db->order_by("users.created_at", "desc");
        $data = $this->db->get()->result_array();
        $data= json_encode($data);
        $result='{"data":'.$data.'}';
        echo $result;
    }
    public function getDataSelDependecias(){
        $this->db->select('users.*,roles.id as id_role, roles.role');
        $this->db->from('users');
        $this->db->join('roles', 'users.role = roles.id');
        $this->db->where(['users.status'=>1 ,'users.active'=>1,'roles.estado'=>1,'roles.id'=>18 ]);
        $this->db->order_by("users.nombrearearesponsable", "asc");
        $data = $this->db->get()->result_array();
        $data= json_encode($data);
        echo $data;
    }

    public function i_ins_user(){
        $post=$this->input->post();
        $is_edita=(int)$this->input->post('is_edita',true);
        $table_id=(int)$this->input->post('table_id',true);
        //valores
        $name=trim("");
        $lastname=trim("");
        $nombrearearesp=trim($this->input->post("nombrearearesp",true));

        $telefono1=trim($this->input->post('telefono',true));
        $direccion=trim("");
        $dni=trim("");
        $ciudad=trim("");
        $email=trim($this->input->post('email',true));
        $user=trim($this->input->post('usuario',true));
        $password=trim($this->input->post('pass',true));
        $iddependecia=trim($this->input->post('selDependecia',true));
        //$password=$this->encrypt_decrypt('encrypt',$password);

        $role=(int)$this->input->post('sel_r',true);
        //
        $data=array('name'=>$nombrearearesp ,
                    'lastnames' => $lastname,
                    'telefono1'=>$telefono1 ,
                    'telefono2'=>$telefono1 ,
                    'direccion'=>$direccion ,
                    'dni'=>$dni ,
                    'ciudad'=>$ciudad ,
                    'email'=> $email,
                    'user'=>$user ,
                    'password'=>$password ,
                    'role'=> $role,
                    'status'=> 1,
                    'nombrearearesponsable'=> $nombrearearesp,
                    'active'=> 1,
                    'created'=>$this->session->userdata('user_id'),
                    'created_at'=>$this->getfecha_actual(),
                    'foto'=> '',
                    'iddependeciauser'=>$iddependecia

            );

        $data_update=array('name'=>$nombrearearesp ,
            'lastnames' => $lastname,
            'telefono1'=>$telefono1 ,
            'telefono2'=>$telefono1 ,
            'direccion'=>$direccion ,
            'dni'=>$dni ,
            'ciudad'=>$ciudad ,
            'email'=> $email,
            'user'=>$user ,
            'password'=>$password ,
            'role'=> $role,
            'nombrearearesponsable'=> $nombrearearesp,
            'status'=> 1,
            'active'=> 1,
            'modified'=>$this->session->userdata('user_id'),
            'modified_at'=>$this->getfecha_actual(),
            'foto'=> '',
            'iddependeciauser'=>$iddependecia
        );

        if($is_edita == 0 && $table_id == 0 ){
            $this->db->insert('users',$data);

        }
        if($is_edita == 1  &&  $table_id >0 ){

            $this->db->where(['id'=>$table_id])->update('users',$data_update);
        }


        echo json_encode($data["status"]='ok');

    }

    public function exist_user(){
        $post=$this->input->post(null,true);
        $r=array();
        if(sizeof($post)>0){
            $user=trim($post['user']);
            $isEdit=(int)$post['isEdit'];

                $r=$this->db->get_where('users',['user'=>$user])->num_rows();

           
           
        }else{

        }


        echo json_encode($r);
    }

    public function o_data_edit(){
        $id=(int)$this->input->post('id',true);
        $r=$this->db->get_where('users',['id'=>$id])->result_array();
        echo json_encode($r);

    }

    public function o_data_edit_pass(){
        $pass=$this->input->post('passw',true);
        $pass=$this->encrypt_decrypt('decrypt',$pass);
        echo  json_encode($pass) ;
    }

    public function eliminar(){
        $id=(int)$this->input->post('id',true);
        $this->db->where(['id'=>$id])->update('users',['status'=>0,'active'=>0]);
        echo 'ok';
    }

    public function ins_perm_user(){
        $id_u=$this->input->post('id_u',true);
        $id_perm=$this->input->post('id_perm',true);
        $id_perm=explode(',',$id_perm);
        $condicion=array('user'=>$id_u);
        $this->db->where($condicion)->update('user_permission', ['value' => 0]);

        $col_perm=$this->db->get_where('user_permission',$condicion)->result_array();
        $col_perm2=array_column($col_perm, 'permission');

        foreach($col_perm as $col_p){
            if(in_array($col_p['permission'],$id_perm)){
                $this->db->where(['permission'=>$col_p['permission']])->update('user_permission', ['value' => 1]);
            }
        }

        foreach($id_perm as $id_m_p){
            if(in_array($id_m_p,$col_perm2)){
            }else{
                $this->db->insert('user_permission',['user'=>$id_u ,'permission'=>$id_m_p , 'value'=>1]);
            }

        }

        echo 'ok';

    }
    
    // extraas
    
    public function ver_perm_u(){
        $id_u=(int)$this->input->post('id',true);
        $id_r=(int)$this->input->post('id_r',true);
        $r=$this->c_sel_perm_role1($id_u,$id_r);
        $r2=$this->c_sel_perm_role2($id_u,$id_r);

        $d=$this->c_sel_perm_user1($id_u);
        $d2=$this->c_sel_perm_user2($id_u);

        $data=array('data1'=>$r,
                    'data2'=>$r2,
                    'datau1'=>$d,
                    'datau2'=>$d2
                    );
        echo json_encode($data);
    }

    private function c_sel_perm_role1($id_u,$id_r){
        // permisos por rol agrupado
        $sql="SELECT   \n".
            "modulos.nombre as modulo,\n".
            "modulos.url as url, \n".
            "modulos.id_modulo \n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN users ON users.role = roles.id\n".
            "where acciones.estado=1  and permissions.estado=1 and roles.estado=1\n".
            "and role_permission.`value`=1 and modulos.estado=1 \n".
            "and users.id=".$id_u." and roles.id=".$id_r." \n".
            "GROUP BY  modulos.nombre  ,\n".
            " modulos.id_modulo  ,\n".
            "modulos.url  ";
        $r=$this->db->query($sql)->result_array();

        return $r;

    }

    private function c_sel_perm_role2($id_u,$id_r){
        // permisos por rol sin agrupar
        $sql="SELECT \n".
            "modulos.id_modulo, \n".
            "modulos.nombre as modulo,\n".
            "modulos.url,\n".
            "acciones.nombre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN users ON users.role = roles.id\n".
            "where acciones.estado=1  and permissions.estado=1 and roles.estado=1\n".
            "and role_permission.`value`=1 and modulos.estado=1 \n".
            "and users.id=".$id_u."  and roles.id=".$id_r;

        $r=$this->db->query($sql)->result_array();

        return $r;


    }

    private function c_sel_perm_user1($id){
        // muestra usuarios por permisos agrupado por modulos
        $sql="SELECT \n".
            " \n".
            "modulos.id_modulo,\n".
            "modulos.nombre as modulo,\n".
            "modulos.url\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.`status`=1 and users.active=1 and user_permission.`value`=1\n".
            "and permissions.estado=1 and modulos.estado=1 and acciones.estado=1\n".
            "and users.id=".$id." \n".
            "GROUP BY  modulos.id_modulo,\n".
            "modulos.nombre ,\n".
            "modulos.url";
        $r=$this->db->query($sql)->result_array();

        return $r;
    }

    private function c_sel_perm_user2($id){
        // myestra acciones por modulo user
        $sql="SELECT\n".
            "modulos.id_modulo,\n".
            "modulos.nombre as modulo,\n".
            "modulos.url,\n".
            "permissions.id as id_perm,\n".
            "acciones.nombre\n".

            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.`status`=1 and users.active=1 and user_permission.`value`=1\n".
            "and permissions.estado=1 and modulos.estado=1 and acciones.estado=1\n".
            "and users.id=".$id." \n";
        $r=$this->db->query($sql)->result_array();
        return $r;
    }

    public function sel_perm_u_d(){
        $id=(int)$this->input->post('id',true);
        $id_r=(int)$this->input->post('id_r',true);
        $r=$this->c_select_perm_u_d($id,$id_r);// datos del combo 1 solo permisos disponibles sin repetir
        $r2=$this->c_sel_perm_role2($id,$id_r);//tabla de permisos heredados del rol
        $r3=$this->c_sel_perm_user2($id);//combo2 permisos extras del usuario asigandos;
        $data=array('data1'=>$r,'data2'=>$r2,'data3'=>$r3);
        echo json_encode($data);
    }

    private function c_select_perm_u_d($id,$id_r){

        $sql="SELECT\n".
            " \n".
            "modulos.nombre as modulo,\n".
            "modulos.url,\n".
            "acciones.nombre,\n".
            "permissions.id_modulo,\n".
            "permissions.id_accion,\n".
            "permissions.id\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where modulos.estado=1 and acciones.estado=1 and   concat(modulos.nombre,acciones.nombre) not in(\n".
            "SELECT\n".
            "concat(modulos.nombre,acciones.nombre) as ma \n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.`status`=1 and users.active=1 and user_permission.`value`=1\n".
            "and permissions.estado=1 and modulos.estado=1 and acciones.estado=1\n".
            "and users.id=".$id." \n".
            " \n".
            ") and  concat(modulos.nombre,acciones.nombre) not in (\n".
            "SELECT concat(modulos.nombre, acciones.nombre) as ma          \n".
            "            FROM\n".
            "            role_permission\n".
            "            INNER JOIN roles ON role_permission.role = roles.id\n".
            "            INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "            INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "            INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "            INNER JOIN users ON users.role = roles.id\n".
            "            where acciones.estado=1  and permissions.estado=1 and roles.estado=1\n".
            "            and role_permission.`value`=1 and modulos.estado=1 \n".
            "            and users.id=".$id."  and roles.id=".$id_r."\n".
            ")";

        $r=$this->db->query($sql)->result_array();


        return $r;
    }

    public function getDataUserPerfil(){
       $idUser=$this->session->userdata('user_id');
        $query="SELECT\n".
            "roles.role as perfil ,\n".
            "users.nombrearearesponsable,\n".
            "users.`user`,\n".
            "users.`status`,\n".
            "users.email,\n".
            "users.role,\n".
            "users.`name`,\n".
            "users.lastnames,\n".
            "users.id,\n".
            "users.telefono1,\n".
            "users.telefono2,\n".
            "users.dni,\n".
            "users.direccion,\n".
            "users.ciudad,\n".
            "users.active\n".
            "FROM\n".
            "users\n".
            "INNER JOIN roles ON users.role = roles.id  where users.id=".$idUser;
        $res=$this->db->query($query)->result_array();
        echo json_encode($res);
    }

    public function editUserperfil(){
        $post =$this->input->post(null,true);
        $data=array();
        if(sizeof($post) > 0){
            $iduser=$this->getUserId();
            $pass1=trim($post["passuser"]);
            $pass2=trim($post["passuser2"]);
            $email=$post["emailuser"];
            $tel=$post["telefonouser"];
            if($pass1 == $pass2){
                    $dataUpdate=array(
                         'email'=>$email,'telefono1'=>$tel
                    );
                if($pass1 != ""){
                    $dataUpdate["password"]=$pass1;
                }
                $this->db->where(['id'=>$iduser])
                    ->update('users',$dataUpdate);
                $data["status"]="ok";
            }else{
                $data["status"]="Error pass";
            }
        }else{
            $data["status"]="Error post";
        }

        echo json_encode($data);
    }

}