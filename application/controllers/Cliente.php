<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="cliente";
    private $pk_idtable="idcliente";
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
        $this->template->renderizar('cliente/index');
    }


    public function getData(){

        $this->load->library('Datatables');
        $this->datatables->select('
             idcliente,
             wallet,
             nombre,
             apellidos,
             documento,            
             fechareg
            
                 ')->from('(
                SELECT
            cliente.idcliente,
            cliente.wallet,
            cliente.nombre,
            cliente.apellidos,
            cliente.documento,
            cliente.estado,
            cliente.fechareg,
            cliente.idinstitucion,
            cliente.idusuario
            FROM
            cliente
            where cliente.estado>0
             
            )temp  ')->order_by(" temp.idcliente desc  ");

        echo $this->datatables->generate();
    }


    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            //echo $this->showErrors($post);

            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombres=$post["nombres"];
            $apellidos=$post["apellidos"];
            $wallet=$post["wallet"];
            $documento=$post["documento"];
            $fregistro=$post["fregistro"];

            $nombres=str_replace( "\r\n", ' ', $nombres);
            $apellidos=str_replace( "\r\n", ' ', $apellidos);

            //--

            $data=array(
                "nombre"=>$nombres,
                "apellidos"=>$apellidos,
                "wallet"=>$wallet,
                "documento"=>$documento,
                "fechareg"=>$fregistro
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