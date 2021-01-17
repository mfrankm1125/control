<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modulos extends CMS_Controller{
    private $_list_perm;
    public function __construct(){
        parent::__construct();
        if(!$this->acceso()){
            redirect('errors/error/1');
        }
        $this->_list_perm=$this->lista_permisos();
        $this->load->model("modulos_model");

    }

    public function index(){
       // print_r($this->_list_perm);
       $modulData=$this->modulos_model->getModulosPadreHijos();
       $data=array('titulo'=>'Modulos',
                    'contenido'=>['hola'],
                     'modulos'=>$modulData["modulos"],
                    'perm'=>$this->_list_perm,
                    'sel_modp'=>$modulData["sel_mod_p"],
                    'sel_acciones'=>$this->sel_acciones());

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
       $this->template->add_js('base','funciones');
       $this->template->add_js('base','underscore');
       $this->template->add_js('view','index');
       $this->template->set_data('data',$data);
       $this->template->renderizar('modulos/index');
   }

    public function getDataTable(){
        $modulData=$this->modulos_model->getModulosPadreHijos();
        $modulos= json_encode($modulData["modulos"]);
        $result='{"data":'.$modulos.'}';
        echo $result;
    }

    public function perm_modul(){
        $return["status"]="Fail";
        $post=$this->input->post(null, true);
        if(sizeof($post)>0){
            $id=$post["id"];
            $return["status"]="ok";
            $return["data"]=$this->modulos_model->permModulosByIdModulo($id);
        }
       // $condicion=array('id_modulo'=>$id,'estado' => 1);

        //$result=json_encode($this->db->get_where('permissions',$condicion)->result_array());
        //$result=$this->ver_acciones($id);

        echo json_encode($return);
    }

    private function ver_acciones($idx){
        $consulta="SELECT\n".
            "acciones.nombre,\n".
            "permissions.id_accion,\n".
            "modulos.url,\n".
            "permissions.estado,\n".
            "permissions.id_modulo,\n".
            "permissions.id\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where acciones.estado= 1 and permissions.estado=1 and modulos.estado=1 and modulos.is_active=1 \n".
            "and permissions.id_modulo= $idx " ;
        $r=$this->db->query($consulta)->result_array();
        return $r;
    }


    public function sel_perm(){
        $return["status"]="Fail";
        $post=$this->input->post(null, true);
        if(sizeof($post)>0){
            $id=$post["id"];
            $return["data"]=$this->modulos_model->selPermisosByIdModulo($id);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function add_perm_modul(){
        $id=$this->input->post('id', true);
        $sel_perm=explode(',',$this->input->post('perm', true));
        foreach($sel_perm as $val){
            $data = array('name' => $val,
                            'id_modulo'=>$id);
            $this->db->insert('permissions',$data);
            
        }
        echo 'ok';
    }

    public function del_perm(){
        $id=$this->input->post('id', true);
        $condicion=array('id'=>$id);
        $data=array('estado'=>0);
        $this->db->where($condicion)->update('permissions',$data);
        echo 'ok';
    }

    public function ins_mod(){
        $id_edit_m=$this->input->post('id_edit_m',true);
        $id_mod_p = $this->input->post('id_m', true);
        $nombre = $this->input->post('nombre', true);
        $url = $this->input->post('url', true);
        $orden = $this->input->post('orden', true);
        $icono = $this->input->post('icono', true);

        $datam = array('nombre' => $nombre,
            'descripcion' => $nombre,
            'url' => $url,
            'icono' => $icono,
            'orden' => $orden,
            'id_modulo_padre' => $id_mod_p,
            'created' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $datam_u= array('nombre' => $nombre,
            'descripcion' => $nombre,
            'url' => $url,
            'icono' => $icono,
            'orden' => $orden,
            'id_modulo_padre' => $id_mod_p,
            'modified' => $this->session->userdata('user_id'),
            'modified_at' => date('Y-m-d H:i:s')
        );

        if($id_edit_m == 'null'){
            $this->db->trans_start();
            $this->db->insert('modulos', $datam);
            $idm = $this->db->insert_id();
            $this->db->trans_complete();
            $accion = explode(',', $this->input->post('accion', true));
            foreach ($accion as $acc) {
                $this->db->insert('permissions', ['id_accion' => $acc, 'id_modulo' => $idm]);
            }
        }
        if(is_numeric($id_edit_m)) {
            $condicion = array('id_modulo' => $id_edit_m);
            $this->db->where($condicion)->update('modulos', $datam_u);
            $this->db->where($condicion)->update('permissions', ['estado' => 0]);
            $datasel = $this->db->get_where('permissions', $condicion)->result_array();

            $accion = explode(',', $this->input->post('accion', true));
            
            foreach ($datasel as $dbaccion) {
                if (in_array($dbaccion['id_accion'], $accion)) {
                    $this->db->where(['id' => $dbaccion['id']])->update('permissions', ['estado' => 1]);
                }
            }
            
             foreach($accion as $acc){
                $acc_db=array_column($datasel, 'id_accion');
                if(in_array($acc,$acc_db) ){
                }else{
                    $this->db->insert('permissions',['id_accion'=>$acc,'id_modulo'=>$id_edit_m]);
                }

            }
            //$ss = array_column($datasel, 'id_accion');

        }
        //print_r($accion);
        echo "ok";
    }

    public function datos_edit(){
        $id=$this->input->post('id',true);
        $r=$this->db->get_where('modulos',['id_modulo'=> $id,'estado'=>1])->result_array();
        echo json_encode($r);
    }

    public function datos_edit_acc(){
        $id=$this->input->post('id',true);
        $r=$this->db->get_where('permissions',['id_modulo'=> $id,'estado'=>1])->result_array();
        echo json_encode($r);
    }

    public function elimina(){
        $id=$this->input->post('id', true);
        $condicion=array('id_modulo'=>$id);
        $data=array('estado'=>0,'is_active'=>0);
        $this->db->where($condicion)->update('modulos',$data);
        echo 'ok';

    }

    private function sel_acciones(){
        $r=$this->db->get_where('acciones',['estado'=> 1])->result_array();
        return $r;
    }





}