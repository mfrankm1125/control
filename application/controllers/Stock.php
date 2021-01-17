<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="stock";
    private $pk_idtable="id_stock";
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


        $this->template->set_data('dataProveedor',$this->getProvedor());
        $this->template->set_data('dataAlmacen',$this->getAlamacen());
        $this->template->set_data('dataInsumo',$this->getInsumos());
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');

        $this->template->add_js('url_theme','plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');

       // $this->template->add_js('url_theme','plugins/select2/select2.min');
        //$this->template->add_css('url_theme','plugins/select2/select2.min');
        //$this->template->add_js('url_theme','plugins/bootstrap-select/bootstrap-select.min');
        //$this->template->add_css('url_theme','plugins/bootstrap-select/bootstrap-select.min');

        $this->template->add_js('base', 'underscore');
        $this->template->add_js('view','index');
       //FUnciona echo $this->template->getAssetsJS() ;exit();
        $this->template->renderizar('stock/index');
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

    public function getProvedor(){
        $condicion=array(
            'estado'=>1,
            'usercrea'=> $this->getUserId()
        );

        $this->db->select('*');
        $this->db->from("proveedores");
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getAlamacen(){
        $condicion=array(
            'estado'=>1,
            'usercrea'=> $this->getUserId()
        );

        $this->db->select('*');
        $this->db->from("almacenes");
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getInsumos(){
        $condicion=array(
            'estado'=>1,
            'usercrea'=> $this->getUserId()
        );

        $this->db->select('*');
        $this->db->from("insumos");
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    private function _getData($id){
        $campoEditaID=$this->pk_idtable;
        $idd=(int)$id;
        $condicion=array(
            'sto.estado'=>1,
            'sto.usercrea'=> $this->getUserId(),
            'ins.estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('sto.*,ins.nombre as ninsumo,alm.nombre as nalmacen,unid.nombre as nunidad ,unid.abreviatura,tpi.nombre as ntinsumo');
        $this->db->from('stock as sto ');
        $this->db->join('insumos as ins','sto.id_insumo=ins.id_insumo' ,'inner' );
        $this->db->join('almacenes as alm','sto.id_almacen=alm.id_almacen','inner');
        $this->db->join('unidadmedida as unid','ins.id_unidadmedida=unid.id_unidadmedida','inner');
        $this->db->join('tipoinsumo as tpi','ins.id_tipoinsumo=tpi.id_tipoinsumo','inner');
        $this->db->where($condicion);
        $this->db->order_by('sto.fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function _query(){

    }
    public function setForm(){

        if(sizeof($this->input->post()) > 0){

           // print_r($this->input->post());exit();
            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $idProv=(int)$this->input->post('proveedor',true);
            $date=(int)$this->input->post('date',true);
            $numfact=$this->input->post('numfact',true);
            $idalmacen=$this->input->post('almacen',true);


            $idinsumox=$this->input->post('insumox',true);
            $cantidadx=$this->input->post('cantidadx',true);
            $preciox=$this->input->post('preciox',true);


            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'id_proveedor' =>$idProv ,
                'id_almacen'=>$idalmacen,
                'nombre'=>"compra",
                'descripcion'=>"descCompra",
                'numfact'=>$numfact,
                'totalref'=>0,
                "fechacompra"=>$date
            );


            if( $isEdit == 0 ){

                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;
                $this->db->trans_start();
                $this->db->insert('compra', $data);
                $idCompra = $this->db->insert_id();
                $this->db->trans_complete();

                $dataDet=array();
                $dataStock=array();
                for ($i=0 ;$i<count($idinsumox);$i++  ){
                    $data=array(
                        'id_compra'=>$idCompra,
                        'id_insumo'=>$idinsumox[$i],
                        'cantidad' =>$cantidadx[$i] ,
                        'precio'=>$preciox[$i],
                        'estado'=>1,
                        'usercrea'=>$userId,
                        'fechacrea'=>$fechaActual,
                    );

                    $dataS=array(
                        'id_insumo'=>$idinsumox[$i],
                        'cantidad' =>$cantidadx[$i] ,
                        'precio'=>$preciox[$i],
                        'id_almacen'=>$idalmacen
                    );

                    array_push($dataDet,$data);
                    array_push($dataStock,$dataS);
                }

                
                $this->db->insert_batch("detcompra", $dataDet);






                echo 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit,'usercrea'=>$this->getUserId());
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
            $condicion=array($this->pk_idtable =>$id,'usercrea'=>$this->getUserId());
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