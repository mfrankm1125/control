<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportarData extends CMS_Controller
{
    private $_list_perm;

    public function __construct()
    {
        parent::__construct();

        $this->load->model("global_model");
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){

        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_js('base', 'papaparse/papaparse');

        $this->template->add_js('base', 'underscore');
        $this->template->set_data("dt",$this->getRangFechaProdLeche());
        $this->template->renderizar('importarData/form');


    }

    public function exportData(){
        $viewHtml=$this->load->view('importarData/tmpReportProdLecheCodFecha',["dt"=>$this->getRangFechaProdLeche()], TRUE);
         $this->load->view('importarData/tmpExcel',["data"=>$viewHtml,"file_name"=>"prodleche"]);
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

    public  function uploadFileCSVDiaro(){
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

    public function previewTestDiario(){
        $post=$this->input->post(null,true);
        $data=null;
        if(sizeof($post) > 0){
            $nameFile=trim($post["nameFile"]);
            $ht="";
            $path =FCPATH."assets/uploads/csvtemp/".$nameFile;
            //echo print_r($path);exit();
            $delitador=$this->detectDelimiter($path);
            $fila = 1;
            $c=0;
            if (file_exists($path)) {
                if (($gestor = fopen($path, "r")) !== FALSE) {
                    while (($datos = fgetcsv($gestor, 1000,$delitador)) !== FALSE) {// delimitador es ; o ,
                        $numero = count($datos);
                        if($fila == 2){
                            $ht.="Fecha Registro de Día:<input type='text' id='fechaProdDiario' name='fechaProdDiario' value='".$datos[4]."'>";
                        }
                        if($fila != 1){
                            $c++;


                            $d=explode("/",trim($datos[1]));
                            $fd="";
                            $ht.="<tr>";
                            $ht.="<td>".$c."</td>";
                            $ht.="<td><input type='hidden' name='codanimal[]' value='".trim($datos[0])."'>".$datos[0]."</td>";
                            $ht.="<td><input type='hidden' name='prodmaniana[]' value='".$datos[1]."' >".$datos[1]."</td>";
                            $ht.="<td><input type='hidden' name='prodtarde[]' value='".trim($datos[2])."'>".$datos[2]."</td>";
                            $ht.="<td><input type='hidden' name='isrecria[]' value='".trim($datos[3])."'>".$datos[3]."</td>";
                            $ht.="<td><input type='hidden' name='fecha[]' value='".trim($datos[4])."'>".$datos[4]."</td>";
                            $ht.="</tr>";
                            $data=$ht;
                        }


                        $fila++;

                    }

                    $status["status"]="OK";
                    fclose($gestor);
                }else{
                    $status["status"]="fail";
                }
            }else{
                $status["status"]="File Not found";
            }
        }
        echo $data;
    }



    public function previewTest(){
        $post=$this->input->post(null,true);
        $data=null;
        if(sizeof($post) > 0){
            $nameFile=trim($post["nameFile"]);
            $ht="";
            $path =FCPATH."assets/uploads/csvtemp/".$nameFile;
            //echo print_r($path);exit();
            $delitador=$this->detectDelimiter($path);
            $fila = 1;
            $c=0;
            if (file_exists($path)) {
                if (($gestor = fopen($path, "r")) !== FALSE) {
                    while (($datos = fgetcsv($gestor, 1000,$delitador)) !== FALSE) {// delimitador es ; o ,
                        $numero = count($datos);
                        if($fila != 1){
                            $c++;


                            $d=explode("/",trim($datos[1]));
                            $fd=$d[2]."-".$d[1]."-".$d[0];
                            $ht.="<tr>";
                            $ht.="<td>".$c."</td>";
                            $ht.="<td><input type='hidden' name='codanimal[]' value='".trim($datos[0])."'>".$datos[0]."</td>";
                            $ht.="<td><input type='hidden' name='fechaanimal[]' value='".$fd."' >".$datos[1]."</td>";
                            $ht.="<td><input type='hidden' name='raza[]' value='".trim($datos[2])."'>".$datos[2]."</td>";
                            $ht.="</tr>";
                            $data=$ht;
                        }


                        $fila++;

                    }

                    $status["status"]="OK";
                    fclose($gestor);
                }else{
                    $status["status"]="fail";
                }
            }else{
                $status["status"]="File Not found";
            }
        }
        echo $data;
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


    public function saveImportDiario(){
        $post=$this->input->post(null,true);
        $status=null;
        $dataIns=null;
        $dataVacaNoExiste=array();
        $cuenta=0;
        $cuentaIns=0;
        $dd=null;
        if(sizeof($post)>0){

            ///---------
            $var = $post["fechaProdDiario"];
            $date = str_replace('/', '-', $var);
            $fechaRegDiario= date('Y-m-d', strtotime($date));

            $codanimal=$post["codanimal"];
            $prodlechemaniana=$post["prodmaniana"];
            $prodlechetarde=$post["prodtarde"];
            $isRecria=$post["isrecria"];
            $totalStockIns=0;
            for($i=0;$i<sizeof($codanimal);$i++){
                $cuenta++;
                $idanimalx=trim($codanimal[$i]);
                $ifExistAnimal=$this->existCodAnimal($idanimalx);

                $codaniamlbd="";

                $ifExisteAnimalEnBd=0;
                if($ifExistAnimal["idanimal"] == "0"){
                    //$dd[]=[$idanimalx;
                    $dataVacaNoExiste[]=array(
                        "idanimal"=>$idanimalx,
                        "Mensaje"=>"No Existe la vaca Revise el documento...."
                    );

                    $ifExisteAnimalEnBd=0;
                }else{
                    $codaniamlbd=$ifExistAnimal["idanimal"];
                    $ifExisteAnimalEnBd=1;
                    $cuentaIns++;
                }
                $valRecria=0;
                if($isRecria[$i]  == 1){
                    $valRecria=4;
                }

                if($ifExisteAnimalEnBd ==1){
                    $dataIns[]=array(
                        'idanimal'=>$codaniamlbd ,
                        'idpersonal'=>0 ,
                        'idturno'=>0,
                        'cantidad'=>0,
                        'cantmaniana'=>$prodlechemaniana[$i] ,
                        'canttarde'=>$prodlechetarde[$i],
                        'cantrecria'=>$valRecria,
                        'fechaprodleche'=>$fechaRegDiario,
                        'estado'=>1,
                    );
                    $totalStockIns=$totalStockIns+$prodlechemaniana[$i]+$prodlechetarde[$i];
                }
            }
            if(sizeof($dataIns)>0){
                $this->db->insert_batch("prodleche", $dataIns);
                $this->setStockProdLeche($fechaRegDiario,$totalStockIns);
                $status["status"]="ok";
            }
            $status["fail"]=$dataVacaNoExiste;
            $status["cuenta"]=$cuenta;
            $status["cuentaIns"]=$cuentaIns;
            $status["dd"]=$dd;
        }else{
            $status["status"]="Fail post";

        }
        echo json_encode($status);
    }


    public function setStockProdLeche($fecha,$montoTotalReg){
        $issetDataStockTotal=$this->global_model->ifExistDataStockProdLeche();
        $dataProdLeche=$this->global_model->getDataProdLeche();
        if(sizeof($issetDataStockTotal)>0){
            $totalmaniana=floatval($dataProdLeche[0]["totalmaniana"]);
            $totaltarde=floatval($dataProdLeche[0]["totaltarde"]);
            $total=floatval($dataProdLeche[0]["total"]);

            $this->db->set('totalmaniana',$totalmaniana, FALSE);
            $this->db->set('totaltarde',$totaltarde, FALSE);
            $this->db->set('total',$total, FALSE);
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

    }

    private function existCodAnimal($codAnimal){
        $this->db->select('idanimal,codanimal');
        $this->db->from("animales");
        $this->db->where(" codanimal  like '".trim($codAnimal)."' ");
        $return = $this->db->get()->result_array();
        $count=sizeof($return);
        $data=["idanimal"=>"0"];
        if($count > 0){
            $data=["idanimal"=>$return[0]["idanimal"]];
        }
        return $data;

    }

    public function saveImport(){
        $post=$this->input->post(null,true);
        $status=null;
        if(sizeof($post)>0){


            $claseanimal=2;

            $tipoanimal=4;
            $sexo=1;
            $claseactivo=27;


            ///---------
            $codanimal=$post["codanimal"];
            //print_r($codanimal);exit();
            $fechaanimal=$post["fechaanimal"];
            $raza=$post["raza"];
            $dataIns=null;
            for($i=0;$i<sizeof($codanimal);$i++){
                $dataIns=array(
                            "idtipoanimal"=>$tipoanimal,
                            "codanimal"=>$codanimal[$i],
                            "codraza"=> $raza[$i],
                            "fechanacimiento"=>$fechaanimal[$i],
                            "idtiposalidaanimal"=>0,
                            "sexo"=>$sexo,
                            "estado"=>1
                     );

                $this->db->trans_start();
                $this->db->insert("animales", $dataIns);
                $idMaxAnimal = $this->db->insert_id();
                $this->db->trans_complete();

                $dClase=$this->global_model->getDataClaseAnimalByTipoAnimalSexo($tipoanimal,$sexo);

                $dataDet=[];

                for($j=0;$j<sizeof($dClase);$j++){
                    $dataDet[]=array(
                        "idanimal"=>$idMaxAnimal,
                        "idclaseanimal"=>$dClase[$j]["idclaseanimal"],
                        "estado"=>1
                    );

                }
                if(sizeof($dataDet)>0){

                    $this->db->insert_batch("detanimalxclaseanimal", $dataDet);
                    $this->db->where(["idanimal"=>$idMaxAnimal,"idclaseanimal"=>$claseactivo])
                        ->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                    $return["status"]="ok";
                }

            }

            print_r($dataIns);exit();


        }else{
            $status["status"]="Fail post";
        }
        echo json_encode($status);
    }




    public function getRangFechaProdLeche(){
        $query="SELECT
        prodleche.fechaprodleche
        FROM
        prodleche
        where prodleche.estado=1 and prodleche.fechaprodleche BETWEEN '2018-01-01' and '2018-01-07'
        GROUP BY prodleche.fechaprodleche asc";
        $dtfechas= $this->db->query($query)->result_array();

        $query2="SELECT
        animales.codanimal
        FROM
        prodleche
        INNER JOIN animales ON animales.idanimal = prodleche.idanimal
        where  animales.estado>0 and prodleche.fechaprodleche in (
        SELECT
        prodleche.fechaprodleche
        FROM
        prodleche
        where   prodleche.estado=1 and prodleche.fechaprodleche BETWEEN '2018-01-01' and '2018-01-07'
        GROUP BY prodleche.fechaprodleche asc)
        GROUP BY animales.codanimal
        order by  animales.codanimal asc";
        $dtCodAnimal= $this->db->query($query2)->result_array();


        $dtxy=null;
        foreach($dtCodAnimal as $valAnimal){

             $dtFtmp=null;
             foreach ($dtfechas as $valfechas){
                   $dataTmpProd=$this->getProdAnimalByCodAnimalFecha($valAnimal["codanimal"],$valfechas["fechaprodleche"]);
                 $dtFtmp[]=array(
                     "fechaprod"=>$valfechas["fechaprodleche"],
                     "dtProdCodAnimalFecha"=>$dataTmpProd
                 );
             }

            $dtxy[]=array(
                "codanimal"=>$valAnimal["codanimal"],
                "prodFechas"=>$dtFtmp
            );
        }

        $data=array("fechas"=>$dtfechas,"cod"=>$dtCodAnimal,"dataProd"=>$dtxy);

        return $data;

    }

    private function getProdAnimalByCodAnimalFecha($codAnimal,$fecha){
        $query="SELECT
         animales.codanimal,
        prodleche.fechaprodleche,
        prodleche.cantmaniana,
        prodleche.canttarde,
        prodleche.cantrecria
        FROM
        prodleche
        INNER JOIN animales ON animales.idanimal = prodleche.idanimal
        where prodleche.fechaprodleche = '".$fecha."' and animales.codanimal ='".$codAnimal."'
				and prodleche.estado=1 and animales.estado > 0";
        $r= $this->db->query($query)->result_array();
        return $r;
    }


}