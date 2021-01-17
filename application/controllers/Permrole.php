<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Permrole extends CMS_Controller{

    public function __construct()
    {
        parent::__construct();
        if(!$this->acceso()){
            redirect('errors/error/1');
        }

    }

    public function index(){

        $this->template->add_js('url_theme','plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme','plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme','plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        //multi js
        $this->template->add_js('url_theme','plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        //$this->template->add_js('url_theme','js/demo/tables-datatables');
        $this->template->add_js('url_theme','plugins/bootstrap-select/bootstrap-select.min');
        $this->template->add_css('url_theme','plugins/bootstrap-select/bootstrap-select.min');


        $this->template->add_js('base','funciones');
        $this->template->add_js('base','underscore');
        $this->template->add_js('view','index');

        $roles_data=$this->db->get_where('roles',['estado'=>1])->result_array();


        $html=$this->load->view('permrole/table','',true);
        $data=array('titulo'=>'Permisos por Rol',
                    'rol_data'=>$this->_c_sel_rol(),
                    'd_role'=>$this->_c_data_role(),
                    'pag2'=>$html
                     );
        $this->template->set_data('data',$data);
        $this->template->renderizar('permrole/index');
    }

    public function sel_role_json(){
        $query="SELECT\n".
            "roles.role as perfil,\n".
            "modulos.nombre as modulo,\n".
            "modulos.url,\n".
            "role_permission.id as id_r_p,\n".
            "acciones.nombre as accion\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion";
        //--------------------------------------
        $query2="SELECT\n".
            "modulos.id_modulo,\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "roles.role,\n".
            "roles.id \n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where modulos.estado = 1 and permissions.estado = 1 and roles.estado = 1\n".
            "GROUP BY modulos.id_modulo,\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "roles.role,\n".
            "roles.id ";
        $data=json_encode($this->db->query($query2)->result_array());
        $data='{"data":'.$data.'}';
        echo $data;
    }

    public  function ver_perms(){
        $id_r=$this->input->post('id_r', true);
        $id_m=$this->input->post('id_m', true);
        $data=$this->c_ver_perms($id_r,$id_m);
        echo $data;
    }

    private function c_ver_perms($id_r){
        $query="SELECT\n".
            "modulos.id_modulo,\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "roles.role,\n".
            "roles.id ,\n".
            "permissions.id as id_perm,\n".
            " \n".
            "acciones.nombre as accion \n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where modulos.estado = 1 and role_permission.`value`=1 and permissions.estado = 1 and roles.estado = 1 and \n".
            "roles.id =".$id_r ."\n".
            "ORDER BY modulos.nombre";
         $d=$this->db->query($query)->result_array();

        return json_encode($d);

    }

    public function sel_accion_m(){
        $query="SELECT\n".
            "modulos.nombre as modulo,\n".
            "modulos.url,\n".
            "acciones.nombre AS accion,\n".
            "permissions.id\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where modulos.estado= 1  and acciones.estado=1  and permissions.estado= 1\n".
            "ORDER BY modulos.nombre";
        $data=json_encode($this->db->query($query)->result_array());
        echo $data;
    }

    public function ins_permrole(){
        $id_r=(int)$this->input->post('id_r', true);
        $id_m=explode(',',$this->input->post('id_m', true));//permss
        $is_edita=(int)$this->input->post('is_edita', true);


        if($is_edita == 0){

            foreach($id_m as $id_m_a){
                $this->db->insert('role_permission',['role'=>$id_r,'permission'=>$id_m_a,'value'=>1]);
            }

            echo "ok insert";
        }
        if($is_edita == 1){
            $condicion=array('role_permission.role'=>$id_r);
            $this->db->where($condicion)->update('role_permission', ['value' => 0]);
            
            $col_perm=$this->db->get_where('role_permission',$condicion)->result_array();

            $col_perm2=array_column($col_perm, 'permission');


            foreach($col_perm as $col_p){
                    if(in_array($col_p['permission'],$id_m)){
                        $this->db->where(['permission'=>$col_p['permission'],'role'=>$id_r])->update('role_permission', ['value' => 1]);
                    }
            }

            foreach($id_m as $id_m_p){
               if(in_array($id_m_p,$col_perm2)){
               }else{
                   $this->db->insert('role_permission',['role'=>$id_r ,'permission'=>$id_m_p , 'value'=>1]);
                }

            }

            echo "ok edit";
        }
    }

    public function edit_sel_perm(){
        $id_r=(int)$this->input->post('id_r', true);
        $id_m=$this->input->post('id_m', true);
        $r=json_decode($this->c_ver_perms($id_r));
        $rr=$this->c_edit_selperm_sin($id_r);
        $data=array('data1'=>$r,'data2'=>$rr);

        echo json_encode($data);
    }

    public function edit_selperm_sin(){
        $id_r=(int)$this->input->post('id_r', true);
        $id_m=$this->input->post('id_m', true);
        $r=$this->c_ver_perms($id_r,$id_m);
        $prueba=array(
            'data1'=>$this->c_edit_selperm_sin($id_m,$id_r),
            'data2'=>$this->c_edit_selperm_sin($id_m,$id_r)
        );

        echo json_encode($prueba);

    }

    public function ver_permisos(){
        $id=(int)$this->input->post('id_r',true);
        $d=$this->_c_data_role_mod($id);
        $d2=$this->_c_data_role_accion($id);

        $data= array('data1'=>$d,
                    'data2'=>$d2);

        echo json_encode($data);
    }

    private function _c_sel_rol(){
        $sql="SELECT\n".
            "roles.id,\n".
            "roles.role\n".
            "FROM\n".
            "roles\n".
            "where estado=1 and roles.id not in(SELECT\n".
            "role_permission.role\n".
            "FROM\n".
            "role_permission\n".
            "where role_permission.`value`=1\n".
            "GROUP BY  role_permission.role)";
        $r=$this->db->query($sql)->result_array();
        return $r;
    }

    private function _c_data_role(){
        $sql="SELECT roles.role,roles.id\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "where roles.estado=1 and permissions.estado=1   \n".
            "GROUP BY roles.role,\n".
            "roles.id\n".
            "ORDER BY  roles.role";

        $r=$this->db->query($sql)->result_array();
        return $r;
    }

    private function _c_data_role_mod($id){
        $sql="SELECT\n".
            "modulos.nombre,modulos.id_modulo,modulos.url\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where roles.estado=1 and permissions.estado=1 and modulos.estado=1\n".
            "and acciones.estado=1 and role_permission.`value`=1  and roles.id=".$id."   \n".
            "GROUP BY modulos.nombre,modulos.url,modulos.id_modulo\n".
            "ORDER BY  modulos.nombre";
        $r=$this->db->query($sql)->result_array();
        return $r;
    }

    private function _c_data_role_accion($id){
        $sql="SELECT\n".
            "modulos.id_modulo,\n".
            "acciones.nombre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where roles.estado=1 and permissions.estado=1 and modulos.estado=1\n".
            "and acciones.estado=1 and roles.id=".$id."\n".
            "GROUP BY modulos.id_modulo,\n".
            "acciones.nombre\n".
            "order  by modulos.id_modulo ";
        $r=$this->db->query($sql)->result_array();
        return $r;
    }

    private function c_edit_selperm_sin($id_r){
        $sql="SELECT\n".
            "acciones.nombre as accion ,\n".
            "permissions.id as id_perm,\n".
            "modulos.nombre as modulo,\n".
            "modulos.url\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where modulos.estado = 1 and permissions.estado = 1 and acciones.estado = 1 \n".
            "and CONCAT(acciones.nombre,modulos.nombre)not in(\n".
            "						SELECT \n".
            "						CONCAT(acciones.nombre,modulos.nombre) as modperm\n".
            "            FROM\n".
            "            role_permission\n".
            "            INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "            INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "            INNER JOIN roles ON role_permission.role = roles.id\n".
            "            INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "            where modulos.estado = 1 and role_permission.`value`=1 and  permissions.estado = 1 and roles.estado = 1 \n".
            "						and roles.id=".$id_r."   \n".
            "						ORDER BY modulos.nombre\n".
            ")\n".
            "order by modulos.nombre";

         $r=$this->db->query($sql)->result_array();
        return $r;
    }

    /*
    private function _c_update_permrole_update_0($id_r,$id_m){
        // consulta para actualizar el detalle de rol todos a 0
        $sql="UPDATE role_permission AS rp\n".
            "INNER JOIN permissions AS p ON rp.permission = p.id\n".
            "SET rp.`value`=0 \n".
            "WHERE p.id_modulo =".$id_m." and rp.role=".$id_r;
        $r=$this->db->query($sql);
    }

    private function _c_update_permrole_update_1($id_r,$id_m){
        $sql="UPDATE role_permission AS rp\n".
            "INNER JOIN permissions AS p ON rp.permission = p.id\n".
            "SET rp.`value`=1 \n".
            "WHERE p.id_modulo =".$id_m." and rp.role=".$id_r;
        $r=$this->db->query($sql);
    }

    */

}