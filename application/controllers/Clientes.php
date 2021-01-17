<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="clientes";
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


        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        //$this->template->add_js('base', 'underscore');
        $this->template->add_js('view','index');
        $this->template->renderizar('clientes/index');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getData($id));
            echo $result;
        }

    }

    private function _getData($id){
        $campoEditaID="id_cliente";
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1

        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $nombre=$this->input->post('name',true);
            $apellidos=$this->input->post('lastname',true);
            $direccion=$this->input->post('address',true);
            $telefono=$this->input->post('phone',true);
            $doc=$this->input->post('doc',true);
            //$desc=$this->input->post('desc',true);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre ,
                'apellidos'=>$apellidos ,
                'direccion' =>$direccion ,
                'telefono'=> $telefono,
                'documento'=>$doc,
                'descripcion'=>null,
                'ruc'=>null,
                'razonsocial'=>null
            );





            if( $isEdit == 0 ){
                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;

                $this->db->insert($this->table, $data);
                echo 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;

                $condicion=array('id_cliente'=>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $data);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }

    public function deleteData(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array('id_cliente'=>$id,'usercrea'=>$this->getUserId());
            $dataD=array(
                'userdelete'=>$this->getUserId(),
                'fechadelete' =>$this->getfecha_actual(),
                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            echo json_encode('ok');

        }else{

        }
    }


}
?>