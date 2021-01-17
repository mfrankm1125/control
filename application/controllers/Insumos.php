<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insumos extends CMS_Controller {
    private $_list_perm;
     
    private $className="Insumo";
    private $table='insumos';
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

        $this->template->set_data('dataProv',$this->getProvedor());
        $this->template->set_data('dataAlma',$this->getAlamacen());
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');


        $this->template->add_js('url_theme','plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');


        $this->template->add_js('base', 'underscore');
        $this->template->add_js('view','index');
        $this->template->set_data('tipoinsumo',$this->getTipoInsumo2());
        $this->template->renderizar('insumos/index');
    }

    
    public function getDataInsumoTable(){
        $result=json_encode($this->_getDataI(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getDataInsumo(){
        if(sizeof($this->input->post())>0){
            $id=(int)$this->input->post('id',true);
            $result=json_encode($this->_getDataI($id));
            echo $result;
        }
    }

    private function _getDataI($id){
        $idd=(int)$id;
        if($id == 0){
            $condicion=array(
                'ins.estado'=>1,
                'tpi.estado'=>1,
                'ins.usercrea'=> $this->getUserId());

        }else{
            $condicion=array(
                'ins.id_insumo'=>$idd,
                'tpi.estado'=>1,
                'ins.estado'=>1,
                'ins.usercrea'=> $this->getUserId());
        }
        
        /*$this->db->select('in.* , tpin.nombre as ntipoinsumo ,tpin.abreviatura');
        $this->db->from('insumos as in');
        $this->db->join('tipoinsumo as tpin', 'in.id_tipoinsumo = tpin.id_tipoinsumo');*/


        $this->db->select('sto.*,ins.*,alm.id_almacen,alm.nombre as nalmacen,unid.nombre as nunidad ,unid.abreviatura,tpi.nombre as ntinsumo,tpi.abreviatura as tpiabreviatura');
        $this->db->from('stock as sto ');
        $this->db->join('insumos as ins','sto.id_insumo=ins.id_insumo' ,'inner' );
        $this->db->join('almacenes as alm','sto.id_almacen=alm.id_almacen','inner');
        $this->db->join('unidadmedida as unid','ins.id_unidadmedida=unid.id_unidadmedida','inner');
        $this->db->join('tipoinsumo as tpi','ins.id_tipoinsumo=tpi.id_tipoinsumo','inner');
        $this->db->where($condicion);
        $this->db->order_by('ins.fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getTipoInsumo(){
        $condicion=array(
            'estado'=>1,
            'usercrea'=> 1
        );
        $this->db->select('id_tipoinsumo,nombre,abreviatura');
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get_where('tipoinsumo',$condicion)->result_array();
        $html='<option value="0">Seleccione</option>';
        foreach ($result as $res){
        $html =$html."<option value='".$res["id_tipoinsumo"]."'  data-id='".$res["abreviatura"]."' >".$res["nombre"]."</option>";
        }

       echo $html;
    }

    
    public function getTipoInsumo2(){
        $condicion=array(
            'estado'=>1,
            'usercrea'=> $this->getUserId()
        );
        $this->db->select('id_tipoinsumo,nombre,abreviatura');
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get_where('tipoinsumo',$condicion)->result_array();
        return $result;
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

    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);
            $idtpinsumo=(int)$this->input->post('idtpinsumo',true);
            $tagtpinsumo=(string)$this->input->post('tagtpinsumo',true);

            $idtipoagroquimico=(int)$this->input->post('idtpagroquimico',true);
            $idunidadmedida=(int)$this->input->post('idumedida',true);
            $idcultivo=(int)$this->input->post('idcultivo',true);
            $funcion=$this->input->post('funcion',true);
            $composicion=$this->input->post('composicion',true);
            $ninsumo=$this->input->post('ninsumo',true);
            $podergerminico=$this->input->post('pgermi',true);
            $variedad=$this->input->post('variedad',true);
            $desc=$this->input->post('desc',true);
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $idalmacen=$this->input->post('almacen',true);
            $idproveedor=$this->input->post('proveedor',true);
            $fechacompra=$this->input->post('fecha',true);
            $cantidad=$this->input->post('cant',true);
            $preciounidad=$this->input->post('precio',true);

            //fert
            $data=array(
                'id_tipoinsumo'=>$idtpinsumo,
                'nombre' =>$ninsumo ,
                'id_unidadmedida'=>$idunidadmedida,
                'funcion'=>$funcion,
                'composicion'=>$composicion,
                'descripcion'=>$desc                 
            );

            if($tagtpinsumo == "aq"){
              $data['id_tipoagroquimico']=$idtipoagroquimico ;

            }
            if($tagtpinsumo == "se"){
                $data['id_cultivo']=$idcultivo ;
                $data['variedadsemilla']=$variedad ;
                $data['podergerminico']=$podergerminico ;
            }

            if( $isEdit == 0 ){
                $data['usercrea']=$userId ;
                $data['fechacrea']=$fechaActual ;
                $data['estado']=1 ;
                //print_r($data);exit();
                $this->db->trans_start();
                $this->db->insert($this->table, $data);
                $idInsumo = $this->db->insert_id();
                $this->db->trans_complete();

                $dataDetStock=array(
                    'id_insumo'=>$idInsumo,
                    'id_almacen'=>$idalmacen,
                    'cantidad'=>$cantidad,
                    'precio'=>$preciounidad,
                    'estado'=>1,
                    'usercrea'=>$this->getUserId(),
                    'fechacrea'=>$fechaActual
                );

                $this->db->insert("stock", $dataDetStock);

                $dataCompra=array(
                    'id_proveedor' =>$idproveedor ,
                    'id_almacen'=>$idalmacen,
                    'nombre'=>"compra".$fechacompra,
                    'descripcion'=>"descCompra",
                    'numfact'=>"0000",
                    'totalref'=>0,
                    "fechacompra"=>$fechacompra,
                    'usercrea'=>$userId,
                    'fechacrea'=>$fechaActual,
                    'estado'=>1
                );

                $this->db->trans_start();
                $this->db->insert("compra", $dataCompra);
                $idCompra = $this->db->insert_id();
                $this->db->trans_complete();

                $dataDetCompra=array(
                    'id_compra'=>$idCompra,
                    'id_insumo'=>$idInsumo,
                    'cantidad' =>$cantidad,
                    'precio'=>$preciounidad,
                    'estado'=>1,
                    'usercrea'=>$userId,
                    'fechacrea'=>$fechaActual,
                );


                $this->db->insert("detcompra", $dataDetCompra);


                echo 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId ;
                $data['fechaupdate']=$fechaActual ;

                $condicion=array('id_insumo'=>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $data);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }

    public function setStock(){
        if(sizeof($this->input->post()) > 0){
            $idInsumo=$this->input->post('idStock',true);
            $idalmacen=$this->input->post('almacen',true);
            $idproveedor=$this->input->post('proveedor',true);
            $fechacompra=$this->input->post('fecha',true);
            $cantidad=$this->input->post('cant',true);
            $preciounidad=$this->input->post('precio',true);
            $userId=$this->getUserId();
            $condicion=array(
                'id_insumo'=>$idInsumo,
                'id_almacen'=>$idalmacen,
                'usercrea'=>$userId,
            );
            $this->db->set('cantidad', 'cantidad+'.$cantidad, FALSE);
            $this->db->set('precio', $preciounidad);
            $this->db->where($condicion)->update("stock");

            print_r($this->input->post());
        }else{
            echo 'fail';
        }

    }

    public function deleteInsumo(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array('id_insumo'=>$id,'usercrea'=>$this->getUserId());
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


    public function getTipoAgroquimico(){
        if(sizeof($this->input->post())>0){
            $condicion=array(
                'estado'=>1,
                'usercrea'=> 1,
            );
            $this->db->select('id_tipoagroquimico,nombre');
            $this->db->order_by('nombre', 'DESC');
            $result = $this->db->get_where('tipoagroquimico',$condicion)->result_array();
            $html='<option value="0" >Seleccione...</option>';
            foreach ($result as $res){
                $html.="<option value='".$res["id_tipoagroquimico"]."'>".$res["nombre"]."</option>";
            }
            // retorna la opcion del select
            echo $html;

        }else{

        }

    }

    public function getUnidadMedida(){
        if(sizeof($this->input->post())>0){
            $condicion=array(
                'estado'=>1,
                'usercrea'=> 1,
            );
            $this->db->select('id_unidadmedida,nombre,abreviatura');
            $this->db->order_by('nombre', 'DESC');
            $result = $this->db->get_where('unidadmedida',$condicion)->result_array();
            $html='<option value="0" >Seleccione...</option>';
            foreach ($result as $res){
                $html.="<option value='".$res["id_unidadmedida"]."'>".$res["nombre"]."(".$res["abreviatura"].")</option>";
            }
            // retorna la opcion del select
            echo $html;
        }else{

        }

    }

    public function getCultivo(){
        if(sizeof($this->input->post())>0){
            $condicion=array(
                'estado'=>1,
                'usercrea'=> 1,
            );
            $this->db->select('id_cultivo,nombre');
            $this->db->order_by('nombre', 'DESC');
            $result = $this->db->get_where('cultivos',$condicion)->result_array();
            $html='<option value="0" >Seleccione...</option>';
            foreach ($result as $res){
                $html.="<option value='".$res["id_cultivo"]."'>".$res["nombre"]."</option>";
            }
            // retorna la opcion del select
            echo $html;
        }else{

        }

    }

    function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

}
?>