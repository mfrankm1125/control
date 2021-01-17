<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalController extends CMS_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("ga_model");

    }

    public function uploadFile()
    {
        if (!empty($_FILES)) {
            $option=(int)$this->input->post('op', true);
            $urlDir="";
            if($option == 2){
                $urlDir='/justify';
                $motivo = $this->input->post('motivo', true);
            }

            $iddetact = $this->input->post('iddetact', true);

            $new_name = time() . $_FILES["file"]['name'];
            $config['upload_path'] = './assets/uploads/archivos'.$urlDir;
            $config['allowed_types'] = '*';
            $config['file_name'] = htmlentities($new_name);
            $config['max_size'] = 5000 * 1024;

            $this->load->library('upload', $config);
            $table="";
            if (!$this->upload->do_upload('file')) {
                $data = array('error' => $this->upload->display_errors());
            } else {
                $data = $this->upload->data();
                if($option == 1){
                    $table="files";
                    $dataIns= array('id_detrespxactvpre' => $iddetact,
                        'file_name' => htmlentities($data['file_name']),
                        'file_ext' => $data['file_ext'],
                        'usercrea' => $this->getUserId(),
                        'fechacrea' => $this->getfecha_actual()
                    );
                }
                if($option == 2){
                    $table='justificaciones';
                    $motivo = $this->input->post('motivo', true);
                    $dataIns= array('id_detrespxactpre' => $iddetact,
                        'file_name' => htmlentities($data['file_name']),
                        'file_ext' => $data['file_ext'],
                        'estado' => 1,
                        'status' => 1,
                        'motivo' => $motivo,
                        'fechacrea' => $this->getfecha_actual(),
                        'usercrea' => $this->getUserId()
                    );
                    $dataUpdate=array('isJusty'=>1);
                    $condicion = array("id_detrespxactpre" => $iddetact);
                    $this->db->where($condicion)->update("detrespxactpre", $dataUpdate);
                }

                $this->db->insert($table, $dataIns);


                $data['status'] = "ok";
                /*
                $dataIns = array('id_detrespxactvpre' => $iddetact,
                    'file_name' => htmlentities($data['file_name']),
                    'file_ext' => $data['file_ext'],
                    'fechacrea' => $this->getfecha_actual()
                );
                $this->db->where(["id_detrespxactvpre" => $iddetact])->update("files", ["estado" => 0]);//de baja a todos los archivos que ya existen;
                //insertamos el nuevo
                $this->db->insert('files', $dataIns);
                //cambiamos el estado de la ctividad a enviado
                $condicion = array("id_detrespxactpre" => $iddetact);
                $data = array("status" => 1, "ejecutadosupuesto" => $ejecutado);
                $this->db->where($condicion)->update("detrespxactpre", $data);
                $data['status'] = "ok";
                */
            }
        } else {
            $data['status'] = "No cargo archivos";
        }

        echo json_encode($data);

    }


    public function getDataMediosVer(){
        $post=$this->input->post(null,true);
        $data=array();
        if(sizeof($post)>0){
            $iddet=(int)$post["iddet"];
            $this->db->select('*');
            $this->db->from("files");
            $this->db->where(['estado !='=>0,'files.id_detrespxactvpre'=>$iddet]);
            $this->db->order_by('files.fechacrea', 'DESC');
            $data = $this->db->get()->result_array();
        }else{
            $data['status']='error';
        }
        echo json_encode($data);
    }
    
    public function getDataJusty(){
        $post=$this->input->post(null,true);
        $data=array();
        if(sizeof($post)>0){
            $iddet=(int)$post["iddet"];
            $this->db->select('*');
            $this->db->from("justificaciones");
            $this->db->where(['estado !='=>0,'justificaciones.id_detrespxactpre'=>$iddet]);

            $this->db->order_by('justificaciones.fechacrea', 'DESC');
            $data = $this->db->get()->result_array();
        }else{
            $data['status']='error';
        }
        echo json_encode($data);
    }
 

    public function tachaDeleteFilesMedJusty(){
        $post=$this->input->post(null,true);
        $datax=array();
        if(sizeof($post)>0){
            $dataIns=array();
            $op=(int)$post["op"];
            $iddet=(int)$post["iddet"];
            $idfileJusty=(int)$post["idfileJusty"];
            $opTachaOrDel=$post["optTachaOrDelete"];

            $table="";
            $data="";
            $condicion="";
            $comentario="";
            if(isset($post["comentarioJustify"])){
                $comentario=$this->quitaSaltoLinea($post["comentarioJustify"]);
            };

            if($op == 1){//medios de verificacion
                $table="files";
                $condicion=array('id_files'=>$idfileJusty);
            }
            if($op == 2){//justificacion
                $table="justificaciones";
                $condicion=array('id_justy'=>$idfileJusty);
            }

            if($opTachaOrDel =='acepta'){
                $data['estado']=2;
                $data['descripcion']=$comentario;
            }
            if($opTachaOrDel =='rechaza'){
                $data['estado']=3;
                $data['descripcion']=$comentario;
            }
            if($opTachaOrDel == 'delete'){
                $data['estado']=0;
            }

            if($table != ""){
                $this->db->where($condicion)->update($table, $data);
                $datax['status'] = "ok";
            }



        }else{
            $datax['status'] = "Error wey";
        }
        echo json_encode($datax);

    }

    public function getAreasResponsables(){
        $this->db->select('*');
        $this->db->from("users");
        $this->db->where(["status"=>1,"role"=>18]);
        $this->db->order_by('users.nombrearearesponsable', 'asc');
        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }

}