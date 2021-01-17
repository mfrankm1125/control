<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="proveedor";
    private $pk_idtable="idproveedor";
    public function __construct()
    {
        parent::__construct();
        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');


        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        //$this->template->add_js('view','index');
        $this->template->renderizar('proveedores/index');
    }


    public function getData(){

        $this->load->library('Datatables');
        $this->datatables->select('
             idproveedor,             
             nombre,
             apellidos,       
              documento,            
             razonsocial,      
               ruc,      
               pais,      
             fechareg
            
                 ')->from('(
                SELECT
                proveedor.idproveedor,
                proveedor.nombre,
                proveedor.apellidos,
                proveedor.razonsocial,
                proveedor.ruc,
                proveedor.documento,
                proveedor.pais,
                proveedor.fechareg,
                proveedor.estado,
                proveedor.idinstitucion,
                proveedor.idusuario
                FROM
                proveedor
                where proveedor.estado>0 
             
            )temp  ')->order_by(" temp.idproveedor desc  ");

        echo $this->datatables->generate();
    }



    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            //echo $this->showErrors($post);
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];

            $pais=$post['pais'];
            $nombres=$post['nombres'];
            $apellidos=$post['apellidos'];
            $documento=$post['documento'];
            $rsocial=$post['rsocial'];
            $fregistro=$post['fregistro'];
            $ruc=$post['ruc'];
            $nombres=str_replace( "\r\n", ' ', $nombres);
            $apellidos=str_replace( "\r\n", ' ', $apellidos);

            $data=array(
                'nombre' => $nombres,
                'apellidos' => $apellidos,
                'razonsocial' => $rsocial,
                'ruc' => $ruc,
                'documento' => $documento,
                'pais' => $pais,
                'fechareg' => $fregistro
            );

            if( $isEdit == 0 ){

                $data['estado']=1;

                $this->db->insert($this->table, $data);
                $return['status']='ok';
            }
            if( $isEdit == 1 ){
                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function delete(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array($this->pk_idtable =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update($this->table, $dataD);
            $return['status']='ok';
        }else{
           $return['satatus']='Fallo ';
        }
        echo json_encode($return);
    }


}
?>