<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poiinia extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="tipoanimales";
    private $pk_idtable="idtipoanimal";
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
        $this->template->renderizar('poiinia/index');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=$post['id'];
            $result=json_encode($this->_getData($id));
        }else{
            $result="sss";
        }
        echo $result;

    }

    private function _getData($id=0){
        $campoEditaID=$this->pk_idtable;
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1
        );
        if((int)$id > 0) $condicion["anio"]=$idd;

        if((int)$id == 0){$this->db->select('anio'); }
        else{$this->db->select('*');}
        $this->db->from("poi");
        $this->db->where($condicion);
       // $this->db->order_by('fechacrea', 'DESC');
        if((int)$id == 0){  $this->db->group_by('anio');  }
        $this->db->order_by('anio', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }



    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombre=$post['name'];
            $nombre=str_replace( "\r\n", ' ', $nombre);
            //--

            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre

            );

            if( $isEdit == 0 ){

                $data['estado']=1;
                $this->db->insert($this->table, $data);
                $return['status']='ok';
            }
            if( $isEdit == 1 ){
                //$data['userupdate']=$userId;
                //$data['fechaupdate']=$fechaActual;


                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function deleteData(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array("anio" =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("poi", $dataD);
            $return['status']='ok';
        }else{
           $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    public function deleteDetailPOI(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array('idpoi' =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("poi", $dataD);
            $return['status']='ok';
        }else{
            $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    ///-----------------------------------------------------------------------------------
    public function setDataDetail(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
          //echo "<pre>";print_r($post);exit();
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            $actop= $post["actop"];
            $arearesp=$post["arearesp"];
            $um= $post["um"];
            $meta= $post["meta"];
            $anio= $post["anio"];
            $dataIns=array(
                "anio"=>$anio,
                "actop"=>$actop,
                "arearesponsable"=>$arearesp,
                "um"=>$um,
                "metaanual"=>$meta,
                "estado"=>1,
                "usercrea"=>$userId,
                "fechacrea"=>$fechaActual
            );
            $this->db->insert("poi", $dataIns);
            $return['status']='ok';

            $return["data"]=$this->_getData($anio);
        }
        echo json_encode($return);
    }
    public function setFormP(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $aniopoi=trim($post['aniopoi']);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            if( $isEdit == 0 ){
                $actop=$post["actop"];
                $arearesp=$post["arearesp"];
                $um=$post["um"];
                $meta=$post["meta"];
                $dtIns=null;
                for($i=0;$i <count($actop) ;$i++){
                    $dtIns[]=array(
                        "anio"=>$aniopoi,
                        "actop"=>$actop[$i],
                        "arearesponsable"=>$arearesp[$i],
                        "um"=>$um[$i],
                        "metaanual"=>$meta[$i],
                        "estado"=>1,
                        "usercrea"=>$userId,
                        "fechacrea"=>$fechaActual

                    );
                }
               // echo "<pre>";print_r($dtIns);exit();
                if(sizeof($dtIns) > 0){
                    $this->db->insert_batch("poi", $dtIns);
                    $return['status']='ok';
                }else{
                    $return['status']='fail';
                }


            }
            if( $isEdit == 1 ){
                //$data['userupdate']=$userId;
                //$data['fechaupdate']=$fechaActual;
                $idpoi=$post["idpoi"];
                $metaalcanzada=$post["metaalcanzada"];
                for($i=0;$i <count($idpoi) ;$i++){
                    $dtUp=array(
                        "metaalcanzada"=>$metaalcanzada[$i],
                        "anio"=>$aniopoi,
                    );
                    $this->db->where(["idpoi"=>$idpoi[$i]])->update("poi", $dtUp);
                }
                 // $condicion=array($this->pk_idtable =>$idEdit);
                //$this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

}
?>