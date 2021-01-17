<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CMS_Controller
{
    private $_list_perm;

    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->load->model("ga_model");
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index()
    {

        //$this->template->add_css('url', 'assets/scripts/gantt/codebase/dhtmlxgantt.css');
        // $this->template->add_js('base', 'gantt/codebase/dhtmlxgantt');


        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_js('base', 'papaparse/papaparse');

        // $this->template->add_css('url_theme', 'plugins/magic-check/css/magic-check.min');
        //$this->template->add_js('base', 'gantt/codebase/locale/locale_es');
        //$this->template->add_js('base', 'highcharts/highcharts');
        /*$data=$this->previewTest();
        echo $data;exit();*/
        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('configuracion/index');
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
                            //$ht.="actOp:".utf8_encode($actOpCsv)." unidad:".$umCsv." mes1:".$mes1Csv." mes2:".$mes2Csv."<br>";
                            $umCsv=explode(":", $datos[4]);
                            if(!isset($umCsv[1]) ){
                                $umText= trim($datos[4]);

                            }else{
                                $umText=trim($umCsv[1]);

                            }
                            $idaccestra="idaccestra";
                            $idactpre="idactpre";
                            $idarea="idarea";
                            $um=$umText;
                            $codCsv=$datos[0];
                            $actOpCsv=utf8_encode($datos[1]);
                            $ubigeoCsv=$datos[2];
                            $metaSolesCsv=$datos[3];
                            $mes1=str_replace(',','',$datos[5]);
                            $mes2=str_replace(',','',$datos[6]);
                            $mes3=str_replace(',','',$datos[7]);
                            $mes4=str_replace(',','',$datos[8]);
                            $mes5=str_replace(',','',$datos[9]);
                            $mes6=str_replace(',','',$datos[10]);
                            $mes7=str_replace(',','',$datos[11]);
                            $mes8=str_replace(',','',$datos[12]);
                            $mes9=str_replace(',','',$datos[13]);
                            $mes10=str_replace(',','',$datos[14]);
                            $mes11=str_replace(',','',$datos[15]);
                            $mes12=str_replace(',','',$datos[16]);
                            $idactop=$actOpCsv;
                            $metaFisica=$mes1+$mes2+$mes3+$mes4+$mes5+$mes6+$mes7+$mes8+$mes9+$mes10+$mes11+$mes12;
                            $ht.="<tr>";
                            $ht.="<td>".$c."</td>";
                            $ht.="<td>".$actOpCsv."</td>";
                            $ht.="<td style='text-align: center;' >".$um."</td>";
                            $ht.="<td style='text-align: right;'><b>".$metaFisica."</b></td>";
                            $ht.="<td>".$mes1."</td>";
                            $ht.="<td>".$mes2."</td>";
                            $ht.="<td style='background-color: #f7dfd6;'>".$mes3."</td>";
                            $ht.="<td>".$mes4."</td>";
                            $ht.="<td>".$mes5."</td>";
                            $ht.="<td style='background-color: #f7dfd6;'>".$mes6."</td>";
                            $ht.="<td>".$mes7."</td>";
                            $ht.="<td>".$mes8."</td>";
                            $ht.="<td style='background-color: #f7dfd6;'>".$mes9."</td>";
                            $ht.="<td>".$mes10."</td>";
                            $ht.="<td>".$mes11."</td>";
                            $ht.="<td style='background-color: #f7dfd6;'>".$mes12."</td>";
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

    public function theadTablePreview(){

    }

    public function testCSV(){
        $ht="";
        $data=null;
        $path =FCPATH."assets/uploads/csvtemp/ejemplo2.csv";
        //echo print_r($path);exit();
        $delitador=$this->detectDelimiter($path);
        $fila = 1;
        if (file_exists($path)) {
            if (($gestor = fopen($path, "r")) !== FALSE) {

                while (($datos = fgetcsv($gestor, 1000,$delitador)) !== FALSE) {// delimitador es ; o ,
                    $numero = count($datos);
                    //$ht.="<p> $numero de campos en la línea $fila: <br /></p>\n";
                    /*for ($c=0; $c < $numero; $c++) {
                        $ht.=$datos[$c] . "<br />\n";
                    }*/

                    if($fila != 1){
                        //$ht.="actOp:".utf8_encode($actOpCsv)." unidad:".$umCsv." mes1:".$mes1Csv." mes2:".$mes2Csv."<br>";
                        $umCsv=explode(":", $datos[4]);
                        if(!isset($umCsv[1]) ){
                            $umText= trim($datos[4]);
                            $idUm=$this->issetUnidadMedida($umText);
                        }else{
                            $umText=trim($umCsv[1]);
                            $idUm=$this->issetUnidadMedida($umText);
                        }
                        $idaccestra="idaccestra";
                        $idactpre="idactpre";
                        $idarea="idarea";
                        $um=$idUm;
                        $codCsv=$datos[0];
                        $actOpCsv=utf8_encode($datos[1]);
                        $ubigeoCsv=$datos[2];
                        $metaSolesCsv=$datos[3];
                        $mes1=$datos[5];
                        $mes2=$datos[6];
                        $mes3=$datos[7];
                        $mes4=$datos[8];
                        $mes5=$datos[9];
                        $mes6=$datos[10];
                        $mes7=$datos[11];
                        $mes8=$datos[12];
                        $mes9=$datos[13];
                        $mes10=$datos[14];
                        $mes11=$datos[15];
                        $mes12=$datos[16];
                        $idactop=$actOpCsv;
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 1, "meta" => $mes1);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 2, "meta" => $mes2);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 3, "meta" => $mes3);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 4, "meta" => $mes4);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 5, "meta" => $mes5);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 6, "meta" => $mes6);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 7, "meta" => $mes7);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 8, "meta" => $mes8);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 9, "meta" => $mes9);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 10, "meta" => $mes10);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 11, "meta" => $mes11);
                        $data[]=array("idactividad" =>$idaccestra,"id_actpresupuestariareal"=>$idactpre,"id_actvpresupuestaria"=>$idactop ,"id_user" => $idarea, "id_unidadmedida" => $um, "id_tiempo" => 12, "meta" => $mes12);

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

        return $data;
    }

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