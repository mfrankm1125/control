 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ganado extends CMS_Controller
{
    private $_list_perm;

    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->load->model("global_model");
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){
        //$this->template->add_css('url', 'assets/scripts/gantt/codebase/dhtmlxgantt.css');
        // $this->template->add_js('base', 'gantt/codebase/dhtmlxgantt');

        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_js('base', 'papaparse/papaparse');
        $this->template->set_data("tiposalida",$this->global_model->getDataTipoSalida());
        $this->template->set_data("precioleche",$this->global_model->getPrecioLecheRef());
        // $this->template->add_css('url_theme', 'plugins/magic-check/css/magic-check.min');
        //$this->template->add_js('base', 'gantt/codebase/locale/locale_es');
        //$this->template->add_js('base', 'highcharts/highcharts');
        /*$data=$this->previewTest();
        echo $data;exit();*/
        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('ganado/index');
    }

    public function saveProdLeche(){
    	$post=$this->input->post(null,true);
    	$status=null;
    	if(sizeof($post)>0){
    		$codanimal=$post["codanimal"];
    		$fechareg=$post["fechareg"];
    		$cantmaniana=floatval($post["cantmaniana"]);
    		$canttarde=floatval($post["canttarde"]);

    		$dataInsert=array(
    			"idanimal"=>$codanimal,
  				"idpersonal"=>"",  				
 				"cantmaniana" =>$cantmaniana,
  				"canttarde"=>$canttarde,
  				"fechaprodleche"=>$fechareg,
  				"estado"=>1
    		);

    		$this->db->insert("prodleche",$dataInsert);    		
            $montoTotalReg=$cantmaniana+$canttarde;
            $this->setStockProdLeche($fechareg,$montoTotalReg);
            $status["status"]="ok";
    	}else{
			$status["status"]="Failpost";
    	}
    	echo json_encode($status);
    }

    public function setStockProdLeche($fecha,$montoTotalReg){
        $issetDataStockTotal=$this->global_model->ifExistDataStockProdLeche();
        $dataProdLeche=$this->global_model->getDataProdLeche();
        if(sizeof($issetDataStockTotal)>0){

                $this->db->set('totalmaniana',$dataProdLeche[0]["totalmaniana"], FALSE);
                $this->db->set('totaltarde',$dataProdLeche[0]["totaltarde"], FALSE);
                $this->db->set('total', $dataProdLeche[0]["total"], FALSE);
                $this->db->set('totalstock', '(totalstock + '.$montoTotalReg.' )' , FALSE);           
                $this->db->where('idcod',"stock");
                $this->db->update('prodlechestock'); 

        }else{
           $dataUp=array(
              "totalmaniana"=>$dataProdLeche[0]["totalmaniana"] ,
              "totaltarde"=>$dataProdLeche[0]["totaltarde"] ,
              "total"=>$dataProdLeche[0]["total"] ,
              "totalstock"=>$dataProdLeche[0]["total"],
              "montosalida"=>0,
              "estado"=> 1 ,
              "idcod"=>"stock",
              "fechaupate"=>$this->getfecha_actual()
           );     

            $this->db->insert("prodlechestock",$dataUp);
        }

        /*
        $ifexist=$this->global_model->ifExistDataStockProdLecheByFechaDia($fecha);
        $ifexist=sizeof($ifexist);      
        if($ifexist > 0 ){
                $dataR=$this->global_model->getDataStockProdLechebyFechaDia($fecha);

                $this->db->set('montotarde',$dataR[0]["totaltarde"], FALSE);
                $this->db->set('montomaniana',$dataR[0]["totalmaniana"], FALSE);
                $this->db->set('montototal', $dataR[0]["totaldia"], FALSE);
                $this->db->set('montostockactual', '(montostockactual + '.$montoTotalReg.' )' , FALSE);
                $this->db->where('fecha', $fecha);
                $this->db->update('stockprodleche'); 

                $this->db->trans_start();
                $this->db->set('totaltotalstock', '(totaltotalstock + '.$montoTotalReg.' )' , FALSE);         
                $this->db->update('stockprodleche'); 
                $this->db->trans_complete();
                $status["status"]="ok";  
        }else{
            $dataR=$this->global_model->getDataStockProdLechebyFechaDia($fecha);
          // print_r($dataR);exit();
            $data=array(
                "fecha"=>$fecha,
                "montotarde" =>$dataR[0]["totaltarde"],
                "montomaniana"=>$dataR[0]["totalmaniana"],
                "montototal"=>$dataR[0]["totaldia"],
                "montosalida" =>0,
                "montostockactual"=>$dataR[0]["totaldia"] ,
                "estado"=>1
            );
            $this->db->trans_start();
            $this->db->insert("stockprodleche",$data);
            $this->db->trans_complete();

             $this->db->set('totaltotalstock', '(totaltotalstock + '.$montoTotalReg.' )' , FALSE);         
             $this->db->update('stockprodleche');  
             $status["status"]="ok";
        }   */
    }

    public function updateDataProdLeche_(){
    	$post=$this->input->post(null,true);
    	$status["status"]=null;
    	if(sizeof($post)>0){
    		//print_r($post);exit();
    		$op=$post["op"];
    		$isGood=1;
    		$condicion=["idprodleche"=>$post["idprodleche"],"estado"=>1];
    		if($op =="fecha"){
    			$dataUpdate["fechaprodleche"]=$post["valor"];
    		}elseif ($op =="idanimal") {
    			$dataUpdate["idanimal"]=$post["valor"];
    		}elseif ($op =="cantmaniana") {
    			 $dataUpdate["cantmaniana"]=$post["valor"];
    		}elseif ($op =="canttarde") {
    			 $dataUpdate["canttarde"]=$post["valor"];
    		}else{
    			$isGood=0;
    		}

    		if($isGood == 1){
 				$this->db->update('prodleche',$dataUpdate,$condicion);
 				$status["status"]="ok";
    		}else{
    			$status["status"]="Faillooo";
    		}    	 
    		
    	}else{
			$status["status"]="Failpost";
    	}
    	echo json_encode($status);
    }

     public function updateDataProdLeche(){
    	$post=$this->input->post(null,true);
    	$status["status"]=null;
    	if(sizeof($post)>0){
    		//print_r($post);exit();    		 
    		$condicion=["idprodleche"=>$post["idprodleche"],"estado"=>1];    		
			$dataUpdate["fechaprodleche"]=$post["fechareg"];    		
			$dataUpdate["idanimal"]=$post["codanimal"];    		
			$dataUpdate["cantmaniana"]=floatval($post["cantmaniana"]);    		
		 	$dataUpdate["canttarde"]=floatval($post["canttarde"]); 

            $totalini=floatval($post["totalini"]); 
            $total=	floatval($post["cantmaniana"])+floatval($post["canttarde"]);
            $difTotales=$total-$totalini;             

			$this->db->update('prodleche',$dataUpdate,$condicion);
			$status["status"]="ok";	

            $this->setStockProdLeche($post["fechareg"],$difTotales);
    		
    	 }else{
			$status["status"]="Failpost";
    	}
    	echo json_encode($status);
    }
    

   public function getDataTable(){
        $result=json_encode($this->getDataProdLecheDiaria());
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }
    
    public function getDataProdLecheDiaria(){
        $this->db->select('*');
        $this->db->from('prodleche');
        $this->db->where('estado', "1");
        $this->db->order_by("idprodleche","desc");
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getYearsProdLeche(){
    	$data=$this->global_model->getYearsbyProdLeche();
    	echo json_encode($data);
    }


    public function delete(){
        $return["status"]="X";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $id=(int)$post["id"];
            $res=$this->global_model->getDataProdLecheByIdProdLeche($id);
            $totalQuita=-($res[0]["cantmaniana"]+$res[0]["canttarde"]);

            $this->db->update('prodleche',["estado"=>0],["estado"=>1,"idprodleche"=>$id]);
            $this->setStockProdLeche("",$totalQuita);
            $return["status"]="ok";    
           // print_r($res);exit();
        }

        echo json_encode($return);
    }


    public function getStockActualProdLeche(){
        $data=$this->global_model->getDataStockActualProleche();
        $r["stockactual"]="";
        if(sizeof($data)>0){
         $r["stockactual"]=$data[0]["totalstock"];
        }else{
         $r["stockactual"]=0;
        }
        echo json_encode($r);
    }
//--------------------------------------------------------------------------------------
    public function issetUnidadMedida($umText){
        $query='SELECT unidadmedida.id_unidadmedida FROM unidadmedida where 
                              unidadmedida.nombre like CONCAT("%","'.$umText.'","%") and unidadmedida.estado=1';

        $result=$this->db->query($query)->result_array();
        if(sizeof($result)>0){
            $idum=$result[0]["id_unidadmedida"];
        }else{
            $this->db->trans_start();
            $dataInsUm=array("nombre"=>$umText,
                "usercrea"=>$this->getUserId(),
                "fechacrea"=>$this->getfecha_actual(),
                "estado"=>1);
            $this->db->insert('unidadmedida',$dataInsUm);
            $idNewUm = $this->db->insert_id();
            $this->db->trans_complete();
            $idum=$idNewUm;
        }
        return $idum;
    }


    public function detectDelimiter($csvFile){
        $delimiters = array(';' => 0,',' => 0,"\t" => 0,"|" => 0);
        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }
        return array_search(max($delimiters), $delimiters);
    }


    public  function uploadFileCSV(){
     if (!empty($_FILES)) {
        $urlDir="";
        $new_name = time() . $_FILES["file"]['name'];
        $config['upload_path'] = './assets/uploads/csvtemp';
        $config['allowed_types'] = '*';
        $config['file_name'] = htmlentities($new_name);
        $config['max_size'] = 5000 * 1024;

        $this->load->library('upload', $config);
        $table="";
            if (!$this->upload->do_upload('file')) {
                $data = array('error' => $this->upload->display_errors());
            } else {
                $data =$this->upload->data();
                $data['status'] = "ok";

            }
       } else {
        $data['status'] = "No cargo archivos";
      }
    echo json_encode($data);
   }
    public function getDataPlazos(){
        $this->db->select('*');
        $this->db->from('configuracion');
        $this->db->where('tipo', "plazo");
        $query = $this->db->get()->result_array();
        echo json_encode($query);
    }

 	

    public function setValuePlazos(){
        $post=$this->input->post(null,true);
        $status=null;
        if(sizeof($post) >0 ){
            $op=$post["op"];
            $value=$post["value"];
            if($op == "user"){
                $this->db->update('configuracion',["valormuestra"=>$value], array('tipo' => "plazo","idconfig"=>1));
                $status["status"]="ok";
            }elseif ($op == "sis"){
                $this->db->update('configuracion',["valor"=>$value], array('tipo' => "plazo","idconfig"=>1));
                $status["status"]="ok";
            }else{
                $status["status"]="no option";
            }
        }else{
            $status["status"]="failpost";
        }
        echo json_encode($status);
    }

    public function getDataConfiguracion(){
        $this->db->select('*');
        $this->db->from('configuracion');
        $this->db->where('tipo', "formatoinforme");
        $this->db->or_where('tipo', "manualusuario");
        $this->db->or_where('tipo', "formatoinformejustify");
        $query = $this->db->get()->result_array();
        echo json_encode($query);
    }

    public function uploadFile(){
        $rtn["status"]="neutro";
        $post=$this->input->post(null,true);
        if(!empty($_FILES)){
            $files=$_FILES;
            $urlDir="";
            $res=$this->_uploadFile($files,$urlDir); //formato ruri "/......"   sin barra al final
            //print_r($res);exit();
            if($res["status"]=="ok"){
                if($post["op"] == "formatoinforme"){
                    $condicion = array("tipo" => "formatoinforme");
                    $this->db->where($condicion)->update("configuracion",["url"=>$res["file_name"]]);
                    $rtn["status"]="ok";
                }elseif ($post["op"] == "manualusuario") {
                    $condicion = array("tipo" => "manualusuario");
                    $this->db->where($condicion)->update("configuracion", ["url" => $res["file_name"]]);
                    $rtn["status"] = "ok";
                }elseif($post["op"] == "formatoinformejustify"){
                    $condicion = array("tipo" => "formatoinformejustify");
                    $this->db->where($condicion)->update("configuracion", ["url" => $res["file_name"]]);
                    $rtn["status"] = "ok";
                }else{
                    $rtn["status"]="Fail OP";
                }

            }else{
                $rtn["status"]="Fail Files1 ";
            }
           // print_r($res);exit();
        }else{
            $rtn["status"]="Fail Files Post";
        }
        echo json_encode($rtn);
    }

    private function _uploadFile($FILES,$urlDIR=""){
        $files=$FILES;
        //print_r($files);exit();
        if (!empty($files)) {
            $urlDir=$urlDIR; /// formato "/...... "  sin barra al final
            $new_name = time() ."_".$_FILES["file"]['name'];
            $config['upload_path'] = './assets/uploads'.$urlDir;
            $config['allowed_types'] = '*';
            $config['file_name'] = htmlentities($new_name);
            $config['max_size'] = 5000 * 1024;

            $this->load->library('upload', $config);
            $table="";
            if (!$this->upload->do_upload('file')) {
                $data["status"]="error";
                $data["errorDetail"] =$this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                /*$dataUpdate=array('isJusty'=>1);
                $condicion = array("id_detrespxactpre" => $iddetact);
                $this->db->where($condicion)->update("detrespxactpre", $dataUpdate);*/
                $data['status'] = "ok";
            }
        } else {
            $data['status'] = "No cargo archivos";
        }
        return $data;

    }

}