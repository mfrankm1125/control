<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClaseAnimal extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="ClaseAnimal";
    private $pk_idtable="idclaseanimal";
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
      
        $this->template->renderizar('claseanimal/index');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=$post['id'];
            $result=json_encode($this->_getData($id));

        }
        echo $result;

    }

    public function getDataX(){
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getData($id));
            echo $result;
        }

    }

    private function _getData($id){
        $campoEditaID='idtipoanimal';
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1            
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('*');
        $this->db->from('tipoanimales');
        $this->db->where($condicion);
        $this->db->order_by('idtipoanimal', 'DESC');
        $result = $this->db->get()->result_array();
        $dt=array();

        foreach ($result as $res) {
           $datasubtipo=$this->getSubTipoequipo($res["idtipoanimal"]);
          $d=array(
                "idtipoanimal"=>$res["idtipoanimal"],
                 "nombre"=>$res["nombre"],
                 'data'=>$datasubtipo
          );  

          array_push($dt, $d);
        }
        return $dt;
    }

    public function getSubTipoequipo($idtipo=null){
        if($idtipo != null){
        $condicion["idtipoanimal"]=$idtipo;
        $condicion["estado"]=1;
        $this->db->select('*');
        $this->db->from("claseanimal");
        $this->db->where($condicion);   
        $result = $this->db->get()->result_array();  

        }else{
        $result=null;
        }
        return $result;

    }

    public function setForm(){
         $datar=array();  
         $post=$this->input->post(null ,true);
        if(sizeof($post) > 0){
            //echo "<pre>";print_r($post);exit();
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];        

            $nombre=$post['name'];
            $sexo=$post['selSexo'];
            $orden=$post['orden'];

            $data=array(
                'nombre' =>$nombre                
            );
                

            if( $isEdit == 0 ){      
                $this->db->trans_start();
                $data['estado']=1;
                $this->db->insert('tipoanimales',$data);
                $idmax = $this->db->insert_id();                   
                $this->db->trans_complete();
                $arr=$post["subtipoequipo"];
                foreach ($arr as $key => $value) {
                    if($value != null && $value != ""){
                          $dataSub=array(
                           "idtipoanimal"=> $idmax,
                           "nombre"=>$value,
                           "idsexo"=>$sexo[$key],
                           "orden"=>$orden[$key],
                           "estado"=>1   
                        );
                        $this->db->insert("claseanimal", $dataSub);
                    }
                   
                }

               
                $datar["status"]="ok";
                 
            }
            if( $isEdit == 1 ){     
                $condicion=array('idtipoanimal' =>$idEdit);
                $this->db->where($condicion)->update('tipoanimales', $data);
             


                $arrEditVal=$post["subtipoequipoedit"];
                $idArrEditVal=$post["idsubtipoequipo"];
                if(sizeof($arrEditVal)>0 ){
                   for($i=0;$i<count($arrEditVal) ;$i++){
                         $this->db->where(["idclaseanimal"=>$idArrEditVal[$i]])->update("claseanimal",["nombre"=>$arrEditVal[$i],"idsexo"=>$sexo[$i],"orden"=>$orden[$i]]);
                   } 
                       if (isset($post["subtipoequipo"])> 0 ) {
                        $arrNew=$post["subtipoequipo"];
                        if(sizeof($arrNew) > 0){
                            for($j=0;$j<count($arrNew) ;$j++){
                             $dataSub=array(
                               "idtipoanimal"=> $idEdit,
                               "nombre"=>$arrNew[$j],
                                 "idsexo"=>$sexo[$j],
                                 "orden"=>$orden[$j],
                               "estado"=>1   
                            );
                            $this->db->insert("claseanimal", $dataSub);
                           } 
                        }
                    } 

                }else if (isset($post["subtipoequipo"])> 0 ) {
                    $arrNew=$post["subtipoequipo"];
                    if(sizeof($arrNew) > 0){
                        for($j=0;$j<count($arrNew) ;$j++){
                         $dataSub=array(
                           "idtipoanimal"=> $idEdit,
                           "nombre"=>$arrNew[$j],
                             "idsexo"=>$sexo[$j],
                             "orden"=>$orden[$j],
                           "estado"=>1   
                        );
                        $this->db->insert("claseanimal", $dataSub);
                       } 
                    }
                }


               
                $datar["status"]="ok";
            }

        }else{
           $datar["status"]="Error";
        }
        echo json_encode($datar);

    }

    public function deleteData(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array('idtipoanimal'=>$id);
            $dataD=array(             
                'estado'=>0            );

            $this->db->where($condicion)->update('tipoanimales', $dataD);
            echo json_encode('ok');

        }else{

        }
    }


     public function deleteDataSubtipo(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array("idclaseanimal" =>$id);
            $dataD=array(             
                'estado'=>0            );

            $this->db->where($condicion)->update("claseanimal", $dataD);
            echo json_encode('ok');

        }else{

        }
    }


}
?>