<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notificacion extends CMS_Controller
{
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table = "notificaciones";
    private $pk_idtable = "id_notificacion";

    public function __construct()
    {
        parent::__construct();
       /* if (!$this->acceso()) {
            redirect('errors/error/1');
        } */
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index()
    {

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme', 'plugins/chosen/chosen.min');


        //$this->template->add_css('url_theme', 'plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        //$this->template->add_js('view', 'index');
        $this->template->set_data("factual",date("Y-m-d"));
        $this->template->renderizar('notificacion/index');
    }


    public function showAllNotixArea(){
        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('notificacion/showAllNotixArea');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getDataTableBoletines(){
        $result=json_encode($this->_getDataBoletin(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getDataTableShowAllNotyArea($idarea=null){
        if($idarea != null && $idarea!=0){
            $result=json_encode($this->queryDataNotifyxArea($idarea,0));
            $result='{"data":'.$result.'}';
            //print_r($result);
            echo $result;
        }

    }
    
    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getData($id));
            echo $result;
        }

    }

    public function getDataBoletin(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getDataBoletin($id));
            echo $result;
        }

    }
    private function _getDatax($id){
        $campoEditaID=$this->pk_idtable;
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

    public function _getDataBoletin($id){
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1
        );
        if($id > 0) $condicion["id_boletin"]=$idd;
        $this->db->select('*');
        $this->db->from("boletin");
        $this->db->where($condicion);
        $this->db->order_by('id_boletin', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function _getData($id){
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1
        );
        $campoEditaID="id_notificacion";
        if($id > 0) $condicion[$campoEditaID]=$idd;
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        //print_r($result);exit();
        $data=array();
        foreach ($result as $res){
            $detUsers=$this->_getNumDetNoti((int)$res['id_notificacion']);
            $numDet=(int)sizeof($detUsers);
            $dt=array(
                 'id_notificacion'=>$res["id_notificacion"],
                  'id_user'=>$res["id_user"],
                 'id_role'=>$res["id_role"],
                  'nombre'=>$res["nombre"],
                  'asunto'=>$res["asunto"],
                  'comentario'=>$res["comentario"],
                  'descripcion'=>$res["descripcion"],
                  'url'=>$res["url"],
                  'estado'=>$res["estado"] ,
                  'status'=>$res["status"],
                  'usercrea'=>$res["usercrea"],
                  'fechacrea'=>$res["fechacrea"],
                  'countElementDet'=>$numDet,
                  'usersNotify'=>$detUsers
            );
            array_push($data,$dt);
        }

    return $data;
    }

    private function _getNumDetNoti($idnoti){
        $condicion=array(
            'estado'=>1,
            'id_notificacion'=>$idnoti
        );
        $this->db->select('notixuser.*,us.nombrearearesponsable');
        $this->db->from('notixuser');
        $this->db->join('users as us','notixuser.id_user=us.id' ,'inner' );
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function queryDataNotify($idArea,$idnoty){
        $where="";
        if(isset($idnoty) && (int)$idnoty>0){
            $where=" and notificaciones.id_notificacion=".$idnoty;
        }
        $areResp=(int)$idArea;
        $query="SELECT\n".
            "notificaciones.asunto,\n".
            "notificaciones.nombre,\n".
            "notificaciones.descripcion,\n".
            "notificaciones.comentario,\n".
            "notificaciones.url,\n".
            "notificaciones.fechacrea,\n".
            "notificaciones.id_notificacion,\n".
            "notixuser.status \n".
            "FROM\n".
            "notixuser\n".
            "INNER JOIN users ON users.id = notixuser.id_user\n".
            "INNER JOIN notificaciones ON notificaciones.id_notificacion = notixuser.id_notificacion\n".
            "where notificaciones.estado=1 and notixuser.estado=1 and notixuser.id_user=".$areResp."  ".$where." \n".
            "GROUP BY notificaciones.asunto,\n".
            "notificaciones.nombre,\n".
            "notificaciones.descripcion,\n".
            "notificaciones.comentario,\n".
            "notificaciones.url,\n".
            "notixuser.status, \n".
            "notificaciones.fechacrea,\n".
            "notificaciones.id_notificacion  order by notificaciones.fechacrea Desc";
        $result=$this->db->query($query)->result_array();
        return $result;
    }
    private function queryDataNotifyxArea($idArea,$idNoty=null){

        $areResp=(int)$idArea;
        $query="SELECT\n".
            "notificaciones.asunto,\n".
            "notificaciones.nombre,\n".
            "notificaciones.descripcion,\n".
            "notificaciones.comentario,\n".
            "notificaciones.url,\n".
            "notificaciones.fechacrea,\n".
            "notificaciones.id_notificacion,\n".
            "notixuser.status \n".
            "FROM\n".
            "notixuser\n".
            "INNER JOIN users ON users.id = notixuser.id_user\n".
            "INNER JOIN notificaciones ON notificaciones.id_notificacion = notixuser.id_notificacion\n".
            "where notificaciones.estado=1 and notixuser.estado=1 and notixuser.id_user=".$areResp." \n".
            "order by notificaciones.fechacrea Desc";
        $result=$this->db->query($query)->result_array();
        return $result;
    }

    public function setForm(){
        $estado=array();
        $post=$this->input->post(null,true);
        if(sizeof($this->input->post()) > 0){
            //echo "<pre>";print_r($post);exit();
            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $asunto=$this->input->post('asunto',true);
            $areasDestino=$this->input->post('areasDestino',true);
            $desc=$this->input->post('desc',true);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'asunto' =>$asunto ,
                'descripcion'=>$desc
            );


            if( $isEdit == 0 ){
                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;
                //print_r($areasDestino);exit();
                $this->db->trans_start();
                $this->db->insert('notificaciones', $data);
                $idMaxNoti = $this->db->insert_id();
                $this->db->trans_complete();

                $dataInsDet=array();
                for ($i=0;$i<count($areasDestino);$i++){
                    $dataInsDet[]=array(
                        'id_user'=>$areasDestino[$i],
                        'id_notificacion'=>$idMaxNoti,
                        'status'=>1,
                        'estado'=>1,
                        'usercrea'=>$userId,
                        'fechacrea'=>$fechaActual
                    );
                }

                $this->db->insert_batch("notixuser", $dataInsDet);
                $estado["status"]='ok';
            }
            if( $isEdit == 1 ){
                $data=array(
                    'asunto' =>$asunto ,
                    'descripcion'=>$desc
                );
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;
                $condicion=array("id_notificacion" =>$idEdit);
                $this->db->where($condicion)->update("notificaciones", $data);
                //actualizamos el detalle
                $condiciondet=array("id_notificacion" =>$idEdit);
                $this->db->where($condiciondet)->update("notixuser",["estado"=>0]);
                $dataInsDet=array();
                for ($i=0;$i<count($areasDestino);$i++){
                    $dataInsDet[]=array(
                        'id_user'=>$areasDestino[$i],
                        'id_notificacion'=>$idEdit,
                        'status'=>1,
                        'estado'=>1,
                        'usercrea'=>$userId,
                        'fechacrea'=>$fechaActual
                    );
                }

                $this->db->insert_batch("notixuser", $dataInsDet);
                $estado["status"]='ok';
            }

        }else{
            $estado["status"]='Error culo';
        }

        echo json_encode($estado);

    }

    public function setFormBoletin(){
        $estado=array();
        $post=$this->input->post(null,true);
        if(sizeof($this->input->post()) > 0){
            //echo "<pre>";print_r($post);exit();
            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $asunto=$this->input->post('asunto',true);
            $fini=$this->input->post('fini',true);
            $fend=$this->input->post('fend',true);
            $desc=$this->input->post('desc',true);
            //--
            $data=array(
                'titulo' =>$asunto ,
                'descripcion'=>$desc,
                'fechaini'=>$fini,
                'fechafin'=>$fend
            );
            if( $isEdit == 0 ){
                $data['estado']=1;
                $data['status']=1;
                $this->db->insert('boletin', $data);
                $estado["status"]='ok';
             }
            if( $isEdit == 1 ){
                $condicion=array("id_boletin" =>$idEdit);
                $this->db->where($condicion)->update("boletin", $data);
                $estado["status"]='ok';
            }

        }else{
            $estado["status"]='Error culo';
        }

        echo json_encode($estado);

    }


    public function deleteData(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array($this->pk_idtable =>$id);
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
    public function deleteDataBoletin(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array("id_boletin" =>$id);
            $dataD=array(
                'estado'=>0,
                'status'=>0
            );
            $this->db->where($condicion)->update("boletin", $dataD);
            echo json_encode('ok');

        }else{

        }
    }

    public function getNotybyArea(){
        $post=$this->input->post(null,true);
        $data=array();
        if(sizeof($post)>0){
            $id=(int)$post["id"];
            $idnoty=(int)$post["idNoty"];
            $isNotiOpen=(int)$post["isNotiOpen"];
            if($isNotiOpen > 0){
                $this->db->where(['id_user'=>$id,'id_notificacion'=>$idnoty])->update('notixuser', ['status'=>2]);
            }
                $data=$this->queryDataNotify($id,$idnoty);


        }else{
            $data["status"]="error";
        }
        echo json_encode($data);
    }
//// CRUD NOTIFY
}