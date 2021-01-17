<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insviviconsolidado extends CMS_Controller
{
    private $_list_perm;


    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);
        $this->load->model('inspecciones_model');
        $this->load->model('ubigeo_model');
    }

    public function index(){

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->add_js('base', 'highcharts/highcharts');

        $this->template->set_data('aniosregsvigilancia',$this->_aniosRegsInVigilancia());
        $this->template->set_data('provinciasregvigilancia',$this->_provinciaRegsInVigilancia());
        /*$this->template->set_data("viviendas",$this->getDataListViviendas());
        $this->template->set_data("jefebrigada",$this->inspecciones_model->getUsersByRole("Jefe Brigada"));
        $this->template->set_data("inspector",$this->inspecciones_model->getUsersByRole("Inspector"));
        $this->template->set_data("estadovivienda",$this->inspecciones_model->getEstadoViviendaEnInspeccion());*/



        $this->template->renderizar('inspecciones/viviendaconsolidado/indexvigivivienda');
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


    public function preViewFileCSV(){
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
                    while (($datos = fgetcsv($gestor, 2000,$delitador)) !== FALSE) {// delimitador es ; o ,
                        $numero = count($datos);
                        if($fila == 2){
                            $ht.="";
                        }
                        if($fila != 1){

                            $dt5= isset($datos[5]) ? trim($datos[5]) : "-";//codovi
                            if(strlen($dt5) > 0){
                                $c++;
                                $dt0= isset($datos[0]) ? trim($datos[0]) : "-";
                                $dt1= isset($datos[1]) ? trim($datos[1]) : "-";
                                $dt2= isset($datos[2]) ? trim($datos[2]) : "-";
                                $dt3= isset($datos[3]) ? trim($datos[3]) : "-";
                                $dt4= isset($datos[4]) ? trim($datos[4]) : "-";

                                $dt6= isset($datos[6]) ? trim($datos[6]) : "-";
                                $dt7= isset($datos[7]) ? trim($datos[7]) : "-";
                                $dt8= isset($datos[8]) ? trim($datos[8]) : "-";
                                $dt9= isset($datos[9]) ? trim($datos[9]) : "-";
                                $dt10= isset($datos[10]) ? trim($datos[10]) : "-";
                                $dt11= isset($datos[11]) ? trim($datos[11]) : "-";
                                $dt12= isset($datos[12]) ? trim($datos[12]) : "-";
                                $dt13= isset($datos[13]) ? trim($datos[13]) : "-";
                                $dt14= isset($datos[14]) ? trim($datos[14]) : "-";
                                $dt15= isset($datos[15]) ? trim($datos[15]) : "-";
                                $dt16= isset($datos[16]) ? trim($datos[16]) : "-";
                                $dt17= isset($datos[17]) ? trim($datos[17]) : "-";
                                $dt18= isset($datos[18]) ? trim($datos[18]) : "-";
                                $dt19= isset($datos[19]) ? trim($datos[19]) : "-";
                                $dt20= isset($datos[20]) ? trim($datos[20]) : "-";
                                $dt21= isset($datos[21]) ? trim($datos[21]) : "-";
                                $dt22= isset($datos[22]) ? trim($datos[22]) : "-";
                                $dt23= isset($datos[23]) ? trim($datos[23]) : "-";
                                $dt24= isset($datos[24]) ? trim($datos[24]) : "-";
                                $dt25= isset($datos[25]) ? trim($datos[25]) : "-";
                                $dt26= isset($datos[26]) ? trim($datos[26]) : "-";
                                $dt27= isset($datos[27]) ? trim($datos[27]) : "-";
                                $dt28= isset($datos[28]) ? trim($datos[28]) : "-";
                                $dt29= isset($datos[29]) ? trim($datos[29]) : "-";
                                $dt30= isset($datos[30]) ? trim($datos[30]) : "-";
                                $dt31= isset($datos[31]) ? trim($datos[31]) : "-";
                                $dt32= isset($datos[32]) ? trim($datos[32]) : "-";
                                $dt33= isset($datos[33]) ? trim($datos[33]) : "-";
                                $dt34= isset($datos[34]) ? trim($datos[34]) : "-";
                                $dt35= isset($datos[35]) ? trim($datos[35]) : "-";
                                $dt36= isset($datos[36]) ? trim($datos[36]) : "-";
                                $dt37= isset($datos[37]) ? trim($datos[37]) : "-";
                                $dt38= isset($datos[38]) ? trim($datos[38]) : "-";
                                $dt39= isset($datos[39]) ? trim($datos[39]) : "-";
                                $dt40= isset($datos[40]) ? trim($datos[40]) : "-";
                                $dt41= isset($datos[41]) ? trim($datos[41]) : "-";

                                $dt42= isset($datos[42]) ? trim($datos[42]) : "-";
                                $dt43= isset($datos[43]) ? trim($datos[43]) : "-";
                                $dt44= isset($datos[44]) ? trim($datos[44]) : "-";
                                $dt45= isset($datos[45]) ? trim($datos[45]) : "-";
                                $dt46= isset($datos[46]) ? trim($datos[46]) : "-";

                                $date = str_replace('/', '-', $dt44);
                                $fechaRegDiario= date('Y-m-d', strtotime($date));

                                $ht.="<tr>";
                                $ht.="<td><button title='Eliminar registro' class='btn btn-xs' type='button' onclick='deleteTr(this)'><i style='color:red' class='fa fa-trash'></i></button></td>";
                                $ht.="<td>".$c."</td>";
                                $ht.="<td><input type='hidden' name='distrito[]' value='$dt0'>$dt0</td>";
                                $ht.="<td><input type='hidden' name='localidad[]' value='$dt1'>$dt1</td>";
                                $ht.="<td><input type='hidden' name='establecimiento[]' value='$dt2'>$dt2</td>";
                                $ht.="<td><input type='hidden' name='zonasector[]' value='$dt3'>$dt3</td>";
                                $ht.="<td><input type='hidden' name='nmanzana[]' value='$dt4'>$dt4</td>";
                                $ht.="<td><input type='hidden' name='cerconroovi[]' value='$dt5'>$dt5</td>";
                                $ht.="<td><input type='hidden' name='totalviviendas[]' value='$dt6'>$dt6</td>";
                                $ht.="<td><input type='hidden' name='lote[]' value='$dt7'>$dt7</td>";
                                $ht.="<td><input type='hidden' name='viviendainstotal[]' value='$dt8'>$dt8</td>";
                                $ht.="<td><input type='hidden' name='cerrada[]' value='$dt9'>$dt9</td>";

                                $ht.="<td><input type='hidden' name='deshabitada[]' value='$dt10'>$dt10</td>";
                                $ht.="<td><input type='hidden' name='renuente[]' value='$dt11'>$dt11</td>";
                                $ht.="<td><input type='hidden' name='tanquealto_inspeccionado[]' value='$dt12'>$dt12</td>";
                                $ht.="<td><input type='hidden' name='tanquealto_positivo[]' value='$dt13'>$dt13</td>";
                                $ht.="<td><input type='hidden' name='tanquealto_tratados[]' value='$dt14'>$dt14</td>";
                                $ht.="<td><input type='hidden' name='barril_inspeccionado[]' value='$dt15'>$dt15</td>";
                                $ht.="<td><input type='hidden' name='barril_positivo[]' value='$dt16'>$dt16</td>";
                                $ht.="<td><input type='hidden' name='barril_tratados[]' value='$dt17'>$dt17</td>";
                                $ht.="<td><input type='hidden' name='balde_inspeccionado[]' value='$dt18'>$dt18</td>";
                                $ht.="<td><input type='hidden' name='balde_positivo[]' value='$dt19'>$dt19</td>";

                                $ht.="<td><input type='hidden' name='balde_tratados[]' value='$dt20'>$dt20</td>";
                                $ht.="<td><input type='hidden' name='ollas_inspeccionado[]' value='$dt21'>$dt21</td>";
                                $ht.="<td><input type='hidden' name='ollas_positivo[]' value='$dt22'>$dt22</td>";
                                $ht.="<td><input type='hidden' name='ollas_tratados[]' value='$dt23'>$dt23</td>";
                                $ht.="<td><input type='hidden' name='floreros_inspeccionado[]' value='$dt24'>$dt24</td>";
                                $ht.="<td><input type='hidden' name='floreros_positivo[]' value='$dt25'>$dt25</td>";
                                $ht.="<td><input type='hidden' name='floreros_tratados[]' value='$dt26'>$dt26</td>";
                                $ht.="<td><input type='hidden' name='llantas_inspeccionado[]' value='$dt27'>$dt27</td>";
                                $ht.="<td><input type='hidden' name='llantas_positivo[]' value='$dt28'>$dt28</td>";
                                $ht.="<td><input type='hidden' name='llantas_tratados[]' value='$dt29'>$dt29</td>";

                                $ht.="<td><input type='hidden' name='inservibles_inspeccionado[]' value='$dt30'>$dt30</td>";
                                $ht.="<td><input type='hidden' name='inservibles_positivo[]' value='$dt31'>$dt31</td>";
                                $ht.="<td><input type='hidden' name='inservibles_eliminados[]' value='$dt32'>$dt32</td>";
                                $ht.="<td><input type='hidden' name='otros_inspeccionado[]' value='$dt33'>$dt33</td>";
                                $ht.="<td><input type='hidden' name='otros_positivo[]' value='$dt34'>$dt34</td>";
                                $ht.="<td><input type='hidden' name='otros_tratados[]' value='$dt35'>$dt35</td>";
                                $ht.="<td><input type='hidden' name='totalinspeccionadox[]' value='$dt36'>$dt36</td>";
                                $ht.="<td><input type='hidden' name='totalpositivosx[]' value='$dt37'>$dt37</td>";
                                $ht.="<td><input type='hidden' name='totaltratados[]' value='$dt38'>$dt38</td>";
                                $ht.="<td><input type='hidden' name='totaleliminados[]' value='$dt39'>$dt39</td>";

                                $ht.="<td><input type='hidden' name='totalviviendastratadas[]' value='$dt40'>$dt40</td>";
                                $ht.="<td><input type='hidden' name='totalviviendaspositivas[]' value='$dt41'>$dt41</td>";
                                $ht.="<td><input type='hidden' name='consumogr[]' value='$dt42'>$dt42</td>";
                                $ht.="<td><input type='hidden' name='semanaentomologica[]' value='$dt43'>$dt43</td>";
                                $ht.="<td><input type='date' name='fintervencion[]' value='$fechaRegDiario'></td>";
                                $ht.="<td><input type='hidden' name='inspector[]' value='$dt45'>$dt45</td>";
                                $ht.="<td><input type='hidden' name='jbrigada[]' value='$dt46'>$dt46</td>";


                                // $ht.="<td><input type='hidden' name='fecha[]' value='".trim($datos[4])."'>".$datos[4]."</td>";
                                $ht.="</tr>";

                            }


                        }//endfila


                        $fila++;

                    }
                    $data=$ht;
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

    public function setForm(){
        $post=$this->input->post(null,true);
        $return["status"]=null;
        $data=null;
        if(sizeof($post) > 0){
            $distrito=$post["distrito"];
            $localidad=$post["localidad"];
            $establecimiento=$post["establecimiento"];
            $zonasector=$post["zonasector"];
            $nmanzana=$post["nmanzana"];
            $cerconroovi=$post["cerconroovi"];
            $totalviviendas=$post["totalviviendas"];
            $lote=$post["lote"];
            $viviendainstotal=$post["viviendainstotal"];
            $cerrada=$post["cerrada"];

            $deshabitada=$post["deshabitada"];
            $renuente=$post["renuente"];
            $tanquealto_inspeccionado=$post["tanquealto_inspeccionado"];
            $tanquealto_positivo=$post["tanquealto_positivo"];
            $tanquealto_tratados=$post["tanquealto_tratados"];
            $barril_inspeccionado=$post["barril_inspeccionado"];
            $barril_positivo=$post["barril_positivo"];
            $barril_tratados=$post["barril_tratados"];
            $balde_inspeccionado=$post["balde_inspeccionado"];
            $balde_positivo=$post["balde_positivo"];

            $balde_tratados=$post["balde_tratados"];
            $ollas_inspeccionado=$post["ollas_inspeccionado"];
            $ollas_positivo=$post["ollas_positivo"];
            $ollas_tratados=$post["ollas_tratados"];
            $floreros_inspeccionado=$post["floreros_inspeccionado"];
            $floreros_positivo=$post["floreros_positivo"];
            $floreros_tratados=$post["floreros_tratados"];
            $llantas_inspeccionado=$post["llantas_inspeccionado"];
            $llantas_positivo=$post["llantas_positivo"];
            $llantas_tratados=$post["llantas_tratados"];

            $inservibles_inspeccionado=$post["inservibles_inspeccionado"];
            $inservibles_positivo=$post["inservibles_positivo"];
            $inservibles_eliminados=$post["inservibles_eliminados"];
            $otros_inspeccionado=$post["otros_inspeccionado"];
            $otros_positivo=$post["otros_positivo"];
            $otros_tratados=$post["otros_tratados"];
            $totalinspeccionadox=$post["totalinspeccionadox"];
            $totalpositivosx=$post["totalpositivosx"];
            $totaltratados=$post["totaltratados"];
            $totaleliminados=$post["totaleliminados"];

            $totalviviendastratadas=$post["totalviviendastratadas"];
            $totalviviendaspositivas=$post["totalviviendaspositivas"];
            $consumogr=$post["consumogr"];
            $semanaentomologica=$post["semanaentomologica"];
            $fintervencion=$post["fintervencion"];
            $inspector=$post["inspector"];
            $jbrigada=$post["jbrigada"];
            $datains=null;



            for($i=0;$i<sizeof($zonasector);$i++){

                $date = str_replace('/', '-', $fintervencion[$i] );
                $newDate = date("Y-m-d", strtotime($date));
                $datains[]=array(
                 'idubigeo'=>$distrito[$i] ,
                'iddistrito'=>$distrito[$i],
                'idlocalidad'=>$localidad[$i] ,
                'ipress'=>$establecimiento[$i],
                'sector'=>$zonasector[$i],
                'nmanzana'=>$nmanzana[$i],
                'nroovitrampa'=>$cerconroovi[$i],
                'totalviviendas'=>$totalviviendas[$i],
                'lote'=>$lote[$i],
                'viviendainsptotal'=>$viviendainstotal[$i],
                'cerrada'=>$cerrada[$i],

                'deshabitada'=>$deshabitada[$i],
                'renuente'=>$renuente[$i],
                'tanquealto_inspeccionado'=>$tanquealto_inspeccionado[$i],
                'tanquealto_positivo'=>$tanquealto_positivo[$i],
                'tanquealto_tratados'=>$tanquealto_tratados[$i],
                'barril_inspeccionado'=>$barril_inspeccionado[$i],
                'barril_positivo'=>$barril_positivo[$i],
                'barril_tratado'=>$barril_tratados[$i],
                'balde_inspeccionado'=>$balde_inspeccionado[$i],
                'balde_positivo'=>$balde_positivo[$i],

                'balde_tratado'=>$balde_tratados[$i],
                'olla_inspeccionado'=>$ollas_inspeccionado[$i],
                'olla_positivo'=>$ollas_positivo[$i],
                'olla_tratado'=>$ollas_tratados[$i],
                'florero_inspeccionado'=>$floreros_inspeccionado[$i],
                'florero_positivo'=>$floreros_positivo[$i],
                'florero_tratado'=>$floreros_tratados[$i],
                'llantas_inspeccionado'=>$llantas_inspeccionado[$i],
                'llantas_positivo'=>$llantas_positivo[$i],
                'llantas_tratado'=>$llantas_tratados[$i],

                'inservibles_inspeccionado'=>$inservibles_inspeccionado[$i],
                'inservibles_positivo'=>$inservibles_positivo[$i],
                'inservibles_eliminado'=>$inservibles_eliminados[$i],
                'otros_inspeccionado'=>$otros_inspeccionado[$i],
                'otros_positivo'=>$otros_positivo[$i],
                'otros_tratados'=>$otros_tratados[$i],
                'tinspeccionados'=>$totalinspeccionadox[$i],
                'tpositivos'=>$totalpositivosx[$i],
                'ttratados'=>$totaltratados[$i],
                'teliminados'=>$totaleliminados[$i],

                'totalviviendastratadas'=>$totalviviendastratadas[$i],
                'totalviviendaspositivas'=>$totalviviendaspositivas[$i],
                'consumolarvicidagr'=>$consumogr[$i],
                'semanaentomologica'=>$semanaentomologica[$i],
                'fechaintervencion'=>$newDate,
                'inspector'=>$inspector[$i],
                'jefebrigada'=>$jbrigada[$i],

                'estado'=>1,
                'fechareg'=>$this->getfecha_actual(),
                'fechracrea'=>$this->getfecha_actual(),
                'usercrea'=>1,
                'fechaupdate'=>null,
                'userupdate'=>null,
                'fechadelete'=>null,
                'userdelete'=>null
            );

            }

            $this->db->insert_batch("detinspeccionviviendas",$datains);
            $return["status"]="ok";
            //$this->showErrors($datains);exit();
        }else{

        }
        echo json_encode($return);
    }

    public function getDataTable($anio=null,$provincia=null,$distrito=null,$ipress=null,$mesanio=null){
        $where="";
        $group="";
        $group="";
        $anio=urldecode($anio);
        $provincia=urldecode($provincia);
        $distrito=urldecode($distrito);
        $ipress=urldecode($ipress);
        $mesanio=urldecode($mesanio);
        if( $anio!=null){
            $where.=" and Year(detinspeccionviviendas.fechaintervencion)= '$anio'";
        }
        if( $provincia!=null){
            $where.=" and  detinspeccionviviendas.iddistrito like '$provincia'";
        }
        if( $ipress !=null){
            $where.=" and  detinspeccionviviendas.ipress like '$ipress'";
        }

        $query="SELECT
         /* DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') as mesanio,*/
         YEAR (detinspeccionviviendas.fechaintervencion)  as anio,
        detinspeccionviviendas.idlocalidad as localidad, 
        detinspeccionviviendas.iddistrito as distrito,
        detinspeccionviviendas.ipress as descipress,
         Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
            Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
            Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
            Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
            Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr
        FROM
        detinspeccionviviendas
        where  detinspeccionviviendas.estado >0  $where
        group  by detinspeccionviviendas.idlocalidad  
       /* group  by DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y')
        order by DATE_FORMAT(detinspeccionviviendas.fechaintervencion , '%Y-%m ') desc */
        
         ";

        $r=$this->db->query($query)->result_array();
        $result='{"data":'.json_encode($r).'}';
        //print_r($result);
        echo $result;
    }

    public function getDataTableAnioIpress($ipress,$aniomes){
        $query="SELECT
            detinspeccionviviendas.fechaintervencion,
            detinspeccionviviendas.idlocalidad AS localidad,
            detinspeccionviviendas.iddistrito AS distrito,
            detinspeccionviviendas.ipress AS descipress,
            Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
            Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
            Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
            Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
            Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr
            FROM
                    detinspeccionviviendas
            where ipress like '$ipress' and DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y')='$aniomes'
            and detinspeccionviviendas.estado>0
            GROUP BY  detinspeccionviviendas.fechaintervencion
            order by  detinspeccionviviendas.fechaintervencion  desc";

        $r=$this->db->query($query)->result_array();
        $result='{"data":'.json_encode($r).'}';
        //print_r($result);
        echo $result;
    }

    public function seeDetaill($tipo=null,$ipress=null,$fecha=null){
        if($tipo == 1){
            $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
            $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
            $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
            $this->template->add_js('base', 'underscore');
            $this->template->set_data("ipress",$ipress);
            $this->template->set_data("fecha",$fecha);
            $this->template->renderizar('inspecciones/viviendaconsolidado/ipressfecha');
        }elseif ($tipo == 2){
            $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
            $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
            $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
            $this->template->set_data("ipress",$ipress);
            $this->template->set_data("fecha",$fecha);
            $this->template->renderizar('inspecciones/viviendaconsolidado/ipressfecha');
        }
    }

    public function getDataDetaillInpsVivienda(){
        $post=$this->input->post(null,true);
        $return["status"]=null;
        $data=null;
        if(sizeof($post) > 0){
            $tipo=$post["tipo"];
            $nivel=$post["nivel"];
            $provincia=$post["provincia"];
            $distrito=$post["distrito"];
            $ipress=$post["ipress"];
            $where="";
            $selfecha="";
            $groupby="";

            if($tipo == "mesanio"){
            $mesanio=$post["mesanio"];
            $where="  and  DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') ='$mesanio'";
                $selfecha=" DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') as fecha ,";
            }

            if($nivel =="sector"){
                $query="SELECT
                        $selfecha
                    detinspeccionviviendas.sector,
                    YEAR (detinspeccionviviendas.fechaintervencion)  as anio,
                    detinspeccionviviendas.idlocalidad as localidad, 
                    detinspeccionviviendas.iddistrito as distrito,
                    detinspeccionviviendas.ipress as descipress,
                    Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
                    Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
                    Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
                    Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
                    Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr,
                       Sum( detinspeccionviviendas.cerrada) AS sumcerrada  ,
                        Sum(detinspeccionviviendas.deshabitada) AS sumdeshabitada  ,
                        Sum(detinspeccionviviendas.renuente) AS sumrenuente 
                    FROM
                    detinspeccionviviendas
                    where 
                      detinspeccionviviendas.ipress like '$ipress' 
                    and  detinspeccionviviendas.iddistrito like '$provincia' 
                    and  detinspeccionviviendas.idlocalidad like '$distrito'                     
                    and detinspeccionviviendas.estado>0
                     $where
                    GROUP BY detinspeccionviviendas.sector    
                     order by  detinspeccionviviendas.sector
                    ";
            }elseif($nivel =="manzana"){
                $query="SELECT
                        $selfecha
                        detinspeccionviviendas.sector,
                        detinspeccionviviendas.nmanzana,
                        YEAR (detinspeccionviviendas.fechaintervencion) AS anio,
                        detinspeccionviviendas.idlocalidad AS localidad,
                        detinspeccionviviendas.iddistrito AS distrito,
                        detinspeccionviviendas.ipress AS descipress,
                        Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
                        Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
                        Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
                        Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
                        Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr,
                           Sum( detinspeccionviviendas.cerrada) AS sumcerrada  ,
                        Sum(detinspeccionviviendas.deshabitada) AS sumdeshabitada  ,
                        Sum(detinspeccionviviendas.renuente) AS sumrenuente  
                        
                        FROM
                        detinspeccionviviendas
                        where 
                         detinspeccionviviendas.ipress like '$ipress' 
                        and  detinspeccionviviendas.iddistrito like '$provincia' 
                        and  detinspeccionviviendas.idlocalidad like '$distrito'                     
                        and detinspeccionviviendas.estado>0
                         $where
                        GROUP BY detinspeccionviviendas.sector,detinspeccionviviendas.nmanzana
                        order by  detinspeccionviviendas.sector asc ,
                        detinspeccionviviendas.nmanzana asc
                         ";
            }elseif($nivel =="adetalle"){
                $query="SELECT
                    detinspeccionviviendas.fechaintervencion,
                    detinspeccionviviendas.idlocalidad AS localidad,
                    detinspeccionviviendas.iddistrito AS distrito,
                    detinspeccionviviendas.ipress AS descipress,
                    detinspeccionviviendas.totalviviendas,
                    detinspeccionviviendas.viviendainsptotal,
                    detinspeccionviviendas.totalviviendastratadas,
                    detinspeccionviviendas.totalviviendaspositivas,
                    detinspeccionviviendas.consumolarvicidagr,
                    detinspeccionviviendas.sector,
                    detinspeccionviviendas.nmanzana,
                    detinspeccionviviendas.nroovitrampa,
                    detinspeccionviviendas.lote,
                    detinspeccionviviendas.cerrada,
                    detinspeccionviviendas.deshabitada,
                    detinspeccionviviendas.renuente,
                    detinspeccionviviendas.tanquealto_inspeccionado,
                    detinspeccionviviendas.tanquealto_positivo,
                    detinspeccionviviendas.tanquealto_tratados,
                    detinspeccionviviendas.barril_inspeccionado,
                    detinspeccionviviendas.barril_positivo,
                    detinspeccionviviendas.balde_inspeccionado,
                    detinspeccionviviendas.barril_tratado,
                    detinspeccionviviendas.balde_positivo,
                    detinspeccionviviendas.balde_tratado,
                    detinspeccionviviendas.olla_positivo,
                    detinspeccionviviendas.olla_inspeccionado,
                    detinspeccionviviendas.olla_tratado,
                    detinspeccionviviendas.florero_inspeccionado,
                    detinspeccionviviendas.florero_positivo,
                    detinspeccionviviendas.florero_tratado,
                    detinspeccionviviendas.llantas_inspeccionado,
                    detinspeccionviviendas.llantas_positivo,
                    detinspeccionviviendas.llantas_tratado,
                    detinspeccionviviendas.inservibles_inspeccionado,
                    detinspeccionviviendas.inservibles_positivo,
                    detinspeccionviviendas.inservibles_eliminado,
                    detinspeccionviviendas.otros_inspeccionado,
                    detinspeccionviviendas.otros_positivo,
                    detinspeccionviviendas.otros_tratados,
                    detinspeccionviviendas.tinspeccionados,
                    detinspeccionviviendas.tpositivos,
                    detinspeccionviviendas.ttratados,
                    detinspeccionviviendas.teliminados,
                    detinspeccionviviendas.semanaentomologica,
                    detinspeccionviviendas.inspector,
                    detinspeccionviviendas.jefebrigada
                    FROM
                            detinspeccionviviendas
                     where                     
                    detinspeccionviviendas.ipress like '$ipress' 
                    and  detinspeccionviviendas.iddistrito like '$provincia' 
                    and  detinspeccionviviendas.idlocalidad like '$distrito'                     
                    and detinspeccionviviendas.estado>0
                     $where
                    order by  detinspeccionviviendas.fechaintervencion  desc";
            }


            $r=$this->db->query($query)->result_array();
            $return=$r;

        }
        echo json_encode($return);
    }



    public function getReportVigilanciaByMesAnioIpress(){
        $post=$this->input->post(null,true);
        $return["status"]=null;
        $return["data"]=null;
        if(sizeof($post) > 0){
            $anio=$post["anio"];
            $provincia=$post["provincia"];
            $distrito=$post["distrito"];
            $ipress=$post["ipress"];
            $meses=$post["meses"];
            $return["data"]=$this->_getReportVigialanciaByMeAnioIpress_sectorViviendas($anio,$provincia,$distrito,$ipress,$meses);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }



    private function _getReportVigialanciaByMeAnioIpress_sectorViviendas($anio,$provincia,$distrito,$ipress,$meses){
        $where="";
        if($meses!=0){
            $where=" and   MONTH( detinspeccionviviendas.fechaintervencion) IN ($meses)";
        }
        $query="SELECT
                 detinspeccionviviendas.iddetinspeccionvivieda  ,
                 detinspeccionviviendas.ipress  ,
                 detinspeccionviviendas.idlocalidad  ,
                 detinspeccionviviendas.sector  ,
                Sum( detinspeccionviviendas.totalviviendas) AS sumtotalviviendas  ,
                Sum( detinspeccionviviendas.viviendainsptotal) AS sumtotalviviendasinsp  ,
                Sum( detinspeccionviviendas.cerrada) AS sumtotalviviendascerradas  ,
                Sum( detinspeccionviviendas.deshabitada) AS sumtotalviviendasdeshabitadas  ,
                Sum(detinspeccionviviendas.renuente) AS sumtotalviviendasrenuente ,
                
                Sum(detinspeccionviviendas.tanquealto_inspeccionado) as sumtanquealto_inspeccionado ,
                Sum(detinspeccionviviendas.tanquealto_positivo) as sumtanquealto_positivo,
                Sum(detinspeccionviviendas.tanquealto_tratados) as sumtanquealto_tratados ,
                
                Sum(detinspeccionviviendas.barril_inspeccionado) as sumbarril_inspeccionado ,
                Sum(detinspeccionviviendas.barril_positivo) as sumbarril_positivo ,
                Sum(detinspeccionviviendas.barril_tratado) as sumbarril_tratado ,
                
                Sum(detinspeccionviviendas.balde_inspeccionado) as sumbalde_inspeccionado ,
                Sum(detinspeccionviviendas.balde_positivo) as sumbalde_positivo ,
                Sum(detinspeccionviviendas.balde_tratado) as sumbalde_tratado ,
                
                Sum(detinspeccionviviendas.olla_inspeccionado) as sumolla_inspeccionado ,
                Sum(detinspeccionviviendas.olla_positivo) as sumolla_positivo ,
                Sum(detinspeccionviviendas.olla_tratado) as sumolla_tratado ,
                
                Sum(detinspeccionviviendas.florero_inspeccionado)as sumflorero_inspeccionado ,
                Sum(detinspeccionviviendas.florero_positivo)as sumflorero_positivo ,
                Sum(detinspeccionviviendas.florero_tratado)as sumflorero_tratado ,
                
                Sum(detinspeccionviviendas.llantas_inspeccionado)as sumllantas_inspeccionado ,
                Sum(detinspeccionviviendas.llantas_positivo)as sumllantas_positivo ,
                Sum(detinspeccionviviendas.llantas_tratado)as sumllantas_tratado ,
                
                Sum(detinspeccionviviendas.inservibles_inspeccionado)as suminservibles_inspeccionado ,
                Sum(detinspeccionviviendas.inservibles_positivo)as suminservibles_positivo ,
                Sum(detinspeccionviviendas.inservibles_eliminado)as suminservibles_eliminado ,
                
                Sum(detinspeccionviviendas.otros_inspeccionado)as sumotros_inspeccionado ,
                Sum(detinspeccionviviendas.otros_positivo)as sumotros_positivo ,
                Sum(detinspeccionviviendas.otros_tratados)as sumotros_tratados ,
                
                Sum(detinspeccionviviendas.tinspeccionados)as sumtinspeccionados ,
                Sum(detinspeccionviviendas.tpositivos)as sumtpositivos ,
                Sum(detinspeccionviviendas.ttratados)as sumttratados ,
                Sum(detinspeccionviviendas.teliminados)as sumteliminados ,
                
                Sum(detinspeccionviviendas.totalviviendastratadas)as sumtotalviviendastratadas ,
                Sum(detinspeccionviviendas.totalviviendaspositivas)as sumtotalviviendaspositivas ,
                Sum(detinspeccionviviendas.consumolarvicidagr) as sumconsumolarvicidagr
                 
                
                FROM
                 detinspeccionviviendas
                where detinspeccionviviendas.estado>0 
                 and detinspeccionviviendas.iddistrito='$provincia' 
                 and detinspeccionviviendas.idlocalidad='$distrito' 
                and detinspeccionviviendas.ipress='$ipress' 
                $where
                and YEAR ( detinspeccionviviendas.fechaintervencion)='$anio'
                
                GROUP BY                   
                detinspeccionviviendas.sector
                order by  CAST(detinspeccionviviendas.sector AS UNSIGNED)   ASC     
                ";
        $r=$this->db->query($query)->result_array();
        $query2="SELECT  MONTH( detinspeccionviviendas.fechaintervencion) as mes ,
                          min(detinspeccionviviendas.fechaintervencion) as fmin,
                             max(detinspeccionviviendas.fechaintervencion) as fmax
                  
                FROM
                    detinspeccionviviendas
                where detinspeccionviviendas.estado>0 
                 and detinspeccionviviendas.iddistrito='$provincia' 
                 and detinspeccionviviendas.idlocalidad='$distrito' 
                and detinspeccionviviendas.ipress='$ipress' 
                 $where
                and YEAR ( detinspeccionviviendas.fechaintervencion)='$anio'
                GROUP BY  MONTH( detinspeccionviviendas.fechaintervencion)
                    ";
        $r2=$this->db->query($query2)->result_array();
        return ["result"=>$r,"meses"=>$r2];
    }

    public function issetMesAnioForReg(){
        $post=$this->input->post(null,true);
        $return["status"]=null;
        $return["data"]=null;

        if(sizeof($post) > 0){
            $aniomes=$post["aniomes"];
            $ipress=$post["ipress"];
            $return["data"]=$this->_issetMesAnioForReg($aniomes,$ipress);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    private function _issetMesAnioForReg($aniomes,$ipress){
        $query="SELECT
                Count(detinspeccionviviendas.iddetinspeccionvivieda) as c
                FROM
                detinspeccionviviendas
                where detinspeccionviviendas.estado>0 and detinspeccionviviendas.ipress='$ipress' 
                and  DATE_FORMAT( detinspeccionviviendas.fechaintervencion , '%m-%Y')='$aniomes'";
        $r=$this->db->query($query)->result_array();
        return $r[0]["c"];
    }

    private function _aniosRegsInVigilancia(){
        $query="SELECT
                DISTINCT
                Year(detinspeccionviviendas.fechareg) as aniosregs
                FROM
                detinspeccionviviendas
                where detinspeccionviviendas.estado>0";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    private function _provinciaRegsInVigilancia(){
        $query="SELECT DISTINCT 
                detinspeccionviviendas.iddistrito as provincia
                FROM
                detinspeccionviviendas
                where detinspeccionviviendas.estado>0";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function getDataMesVigbyAnioUbigeo(){
        $post=$this->input->post(null,true);
        $return["status"]=null;

        if(sizeof($post) > 0){
            $anio=$post["anio"];
            $prov=$post["provincia"];
            $dist=$post["distrito"];
            $ipress=$post["ipress"];
            $return=$this->_getDataMesVigbyAnioUbigeo($anio,$prov,$dist,$ipress);

        }
        echo json_encode($return);
    }

    public function getDataDiasVigbyAnioUbigeo(){
        $post=$this->input->post(null,true);
        $return["status"]=null;

        if(sizeof($post) > 0){
            $anio=$post["anio"];
            $prov=$post["provincia"];
            $dist=$post["distrito"];
            $ipress=$post["ipress"];
            $aniomes=$post["mesanio"];
            $return=$this->_getDataDiasVigbyAnioUbigeo($anio,$prov,$dist,$ipress,$aniomes);

        }
        echo json_encode($return);
    }


    private function _getDataMesVigbyAnioUbigeo($anio,$prov,$dist,$ipress){
        $query="SELECT
          year(detinspeccionviviendas.fechaintervencion ) as anio, 
          DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') as fecha, 
         detinspeccionviviendas.iddistrito as distrito,
        detinspeccionviviendas.idlocalidad as localidad,        
        detinspeccionviviendas.ipress as descipress,
         Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
            Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
            Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
            Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
            Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr ,
            Sum( detinspeccionviviendas.cerrada) AS sumcerrada  ,
            Sum(detinspeccionviviendas.deshabitada) AS sumdeshabitada  ,
            Sum(detinspeccionviviendas.renuente) AS sumrenuente 
        FROM
        detinspeccionviviendas
        where  detinspeccionviviendas.estado >0 
         and      YEAR(detinspeccionviviendas.fechaintervencion) ='$anio'
         and      detinspeccionviviendas.iddistrito ='$prov'
        and detinspeccionviviendas.idlocalidad ='$dist'      
        and detinspeccionviviendas.ipress ='$ipress'
        group  by DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y')
        order by DATE_FORMAT(detinspeccionviviendas.fechaintervencion , '%Y-%m ') desc ";
        $r=$this->db->query($query)->result_array();
        return $r;

    }

    private function _getDataDiasVigbyAnioUbigeo($anio,$prov,$dist,$ipress,$mesanio){
        $query="SELECT
          DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') as mesanio, 
          detinspeccionviviendas.fechaintervencion as fecha ,
         detinspeccionviviendas.iddistrito as distrito,
        detinspeccionviviendas.idlocalidad as localidad,        
        detinspeccionviviendas.ipress as descipress,
         Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
            Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
            Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
            Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
            Sum(detinspeccionviviendas.consumolarvicidagr) AS sumconsumolarvicidagr ,
             Sum( detinspeccionviviendas.cerrada) AS sumcerrada  ,
            Sum(detinspeccionviviendas.deshabitada) AS sumdeshabitada  ,
            Sum(detinspeccionviviendas.renuente) AS sumrenuente 
           
        FROM
        detinspeccionviviendas
        where  detinspeccionviviendas.estado >0 
         and      YEAR(detinspeccionviviendas.fechaintervencion) ='$anio'
         and      detinspeccionviviendas.iddistrito ='$prov'
        and detinspeccionviviendas.idlocalidad ='$dist'      
        and detinspeccionviviendas.ipress ='$ipress'
        and  DATE_FORMAT(detinspeccionviviendas.fechaintervencion, '%m-%Y') ='$mesanio'
        group  by  detinspeccionviviendas.fechaintervencion 
        order by  detinspeccionviviendas.fechaintervencion   desc ";
        $r=$this->db->query($query)->result_array();
        return $r;

    }

    public function getDataVigSectorMazanabyAnioMesUbigeo(){
        $post=$this->input->post(null,true);
        $return["status"]=null;

        if(sizeof($post) > 0){
            $anio=$post["anio"];
            $prov=$post["provincia"];
            $dist=$post["distrito"];
            $ipress=$post["ipress"];
            $aniomes=$post["mesanio"];
            $nivel=$post["nivel"];
            if($nivel=='sector'){
                $query="";
            }elseif($nivel=='manzana'){
                $query="";
            }

            $return=$this->_getDataDiasVigbyAnioUbigeo($anio,$prov,$dist,$ipress,$aniomes);

        }
        echo json_encode($return);


    }


//////////////////////////////////////


    public function getReportVigilanciaBySemanas(){
        $post = $this->input->post(null, true);
        $return = null;
        if (sizeof($post) > 0) {
            $anio = $post["anio"];
            $provincia = $post["provincia"];
            $distrito = $post["distrito"];
            $ipress = $post["ipress"];
            $nivel=$post["nivel"];

            $return=$this->_getReportVigilanciaBySemanas($anio,$provincia,$distrito,$ipress,$nivel);
        }
        echo json_encode($return);

    }

    private function _getReportVigilanciaBySemanas($anio,$provincia,$distrito,$ipress,$nivel){
        $selectGroup="";
        $dx=null;

        if($nivel =="distrito") {
            $selectGroup="detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad ";

        }else if($nivel =="ipress") {
            $selectGroup="detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress   ";

        }else if($nivel =="sector"){
            $selectGroup="detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress,
                detinspeccionviviendas.sector";

       }else if($nivel =="manzana"){
            $selectGroup="detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress,
                detinspeccionviviendas.sector,
                detinspeccionviviendas.nmanzana";

          }
        $query="SELECT 
                $selectGroup          
                FROM
                detinspeccionviviendas
                where detinspeccionviviendas.estado > 0
                 and year(detinspeccionviviendas.fechaintervencion)=$anio
                and detinspeccionviviendas.iddistrito='$provincia'
                and detinspeccionviviendas.idlocalidad='$distrito'
                and detinspeccionviviendas.ipress='$ipress'
                GROUP BY 
                $selectGroup 
                order by  week(detinspeccionviviendas.fechaintervencion,3)  asc 
                ";
       $ret=$this->db->query($query)->result_array();
       $dd=null;
       foreach($ret as $k=>$i){
           $provinciax=null;
           $distritox=null;
           $ipressx=null;
           $sectorx=null;
           $nmanzanax=null;
        if($nivel =="distrito") {
            $arr["idubigeo"]=$i["idubigeo"];
            $arr["iddistrito"]=$i["iddistrito"];
            $arr["idlocalidad"]=$i["idlocalidad"];

            $provinciax=$i["iddistrito"];
            $distritox=$i["idlocalidad"];

       }else if($nivel =="ipress") {
            $arr["idubigeo"]=$i["idubigeo"];
            $arr["iddistrito"]=$i["iddistrito"];
            $arr["idlocalidad"]=$i["idlocalidad"];
            $arr["ipress"]=$i["ipress"];

            $provinciax=$i["iddistrito"];
            $distritox=$i["idlocalidad"];
            $ipressx=$i["ipress"];


       }else if($nivel =="sector"){
            $arr["idubigeo"]=$i["idubigeo"];
            $arr["iddistrito"]=$i["iddistrito"];
            $arr["idlocalidad"]=$i["idlocalidad"];
            $arr["ipress"]=$i["ipress"];
            $arr["sector"]=$i["sector"];

            $provinciax=$i["iddistrito"];
            $distritox=$i["idlocalidad"];
            $ipressx=$i["ipress"];
            $sectorx=$i["sector"];

       }else if($nivel =="manzana"){
            $arr["idubigeo"]=$i["idubigeo"];
            $arr["iddistrito"]=$i["iddistrito"];
            $arr["idlocalidad"]=$i["idlocalidad"];
            $arr["ipress"]=$i["ipress"];
            $arr["sector"]=$i["sector"];
            $arr["nmanzana"]=$i["nmanzana"];

            $provinciax=$i["iddistrito"];
            $distritox=$i["idlocalidad"];
            $ipressx=$i["ipress"];
            $sectorx=$i["sector"];
            $nmanzanax=$i["nmanzana"];
       }
           $arr["semanas"]=$this->__getReportVigilanciaBySemanas($anio,$provinciax,$distritox,$ipressx,$sectorx,$nmanzanax,$nivel);

           $dd[]=$arr;
       }
       return $dd;
    }


    private function __getReportVigilanciaBySemanas($anio=null,$provincia=null,$distrito=null,$ipress=null,$sector=null,$nmanzana=null,$nivel=null)
    {
        $selectGroup = "";
        $dx = null;

        if ($nivel == "distrito") {
            $selectGroup = "detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad, ";

        } else if ($nivel == "ipress") {
            $selectGroup = "detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress,   ";

        } else if ($nivel == "sector") {
            $selectGroup = "detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress,
                detinspeccionviviendas.sector,";

        } else if ($nivel == "manzana") {
            $selectGroup = "detinspeccionviviendas.idubigeo,
                detinspeccionviviendas.iddistrito,
                detinspeccionviviendas.idlocalidad,
                detinspeccionviviendas.ipress,
                detinspeccionviviendas.sector,
                detinspeccionviviendas.nmanzana,";

        }

        $where="";
        if($provincia != null){
            $where.="    and detinspeccionviviendas.iddistrito='$provincia' ";
        }
        if( $distrito !=null ){
            $where.="   and detinspeccionviviendas.idlocalidad='$distrito'";
        }
        if( $ipress !=null){
            $where.="    and detinspeccionviviendas.ipress='$ipress' ";
       }
       if( $sector !=null ){
           $where.="    and detinspeccionviviendas.sector='$sector' ";
       }
       if( $nmanzana !=null){
           $where.="    and detinspeccionviviendas.nmanzana='$nmanzana' ";
       }
        $query = "SELECT                 
                week(detinspeccionviviendas.fechaintervencion,3) as semana,
                Sum(detinspeccionviviendas.totalviviendas) AS sumtotalviviendas,
                Sum(detinspeccionviviendas.viviendainsptotal) AS sumviviendainsptotal,
               Sum(detinspeccionviviendas.cerrada) as cerrada ,
                 Sum(detinspeccionviviendas.totalviviendastratadas) AS sumtotalviviendastratadas,
                Sum(detinspeccionviviendas.totalviviendaspositivas) AS sumtotalviviendaspositivas,
                Sum(detinspeccionviviendas.totalviviendaspositivas)/Sum(detinspeccionviviendas.viviendainsptotal) as ia
                FROM
                detinspeccionviviendas
                where detinspeccionviviendas.estado > 0
                and year(detinspeccionviviendas.fechaintervencion)=$anio
                $where
                GROUP BY 
                $selectGroup
                week(detinspeccionviviendas.fechaintervencion,3) 
                order by   week(detinspeccionviviendas.fechaintervencion,3)  asc
                ";
        $ret = $this->db->query($query)->result_array();
        return $ret;
    }

    public function deteleteRegVigilanciaByDistritoIpresMesAnio(){
        $post=$this->input->post(null,true);
        $return["status"]="";
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $distrito =$post["distrito"];
            $localidad =$post["localidad"];
            $descipress =$post["descipress"];
            $fecha =$post["fecha"];

            $this->db->update('detinspeccionviviendas', ["estado"=>0],
                "  Year(fechaintervencion) = $anio and  iddistrito='$distrito'  and idlocalidad='$localidad' and ipress='$descipress' and DATE_FORMAT( detinspeccionviviendas.fechaintervencion , '%m-%Y')='$fecha'  ");
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function verOvitrampasVigilancia(){
        $post=$this->input->post(null,true);
        $return["status"]="";
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $distrito =$post["distrito"];
            $localidad =$post["localidad"];
            $descipress =$post["descipress"];
            $fecha =$post["fecha"];

            $query="SELECT DISTINCT
            labelcodovitrampa.idlabelcodovitrampa,
            detinspeccionviviendas.nroovitrampa,
            concat(labelcodovitrampa.idlabelcodovitrampa,'0',detinspeccionviviendas.nroovitrampa) AS codconceroovi,
            concat(labelcodovitrampa.idlabelcodovitrampa,detinspeccionviviendas.nroovitrampa) AS codsinceroovi,
            detinspeccionviviendas.idlocalidad,
            detinspeccionviviendas.iddistrito,
            detinspeccionviviendas.ipress
            FROM
            detinspeccionviviendas
            INNER JOIN labelcodovitrampa ON labelcodovitrampa.nombre = detinspeccionviviendas.ipress
            where 
            detinspeccionviviendas.estado>0
              and detinspeccionviviendas.iddistrito='$distrito'
            and detinspeccionviviendas.idlocalidad='$localidad'          
            and detinspeccionviviendas.ipress='$descipress'
            and year(detinspeccionviviendas.fechaintervencion)=$anio
            and DATE_FORMAT( detinspeccionviviendas.fechaintervencion , '%m-%Y')='$fecha'
              order by CAST(nroovitrampa AS UNSIGNED) asc";
            $ret = $this->db->query($query)->result_array();
            $dataRet=null;
            foreach($ret as $k=>$i){
                $dataOvisVigilancia=$this->_getDataOviByVigilanciaByDistLocIpOvi($anio,$distrito,$localidad,$descipress,$fecha,$i["idlabelcodovitrampa"],$i["nroovitrampa"]);
                $dataRet[]=array(
                "idlabelcodovitrampa"=>$i["idlabelcodovitrampa"],
                "nroovitrampa"=>$i["nroovitrampa"],
                "codconceroovi"=>$i["codconceroovi"],
                "codsinceroovi"=>$i["codsinceroovi"],
                "idlocalidad"=>$i["idlocalidad"],
                "iddistrito"=>$i["iddistrito"],
                "ipress"=>$i["ipress"],
                "data"   =>$dataOvisVigilancia
                );
            }

        }
        echo json_encode($dataRet);
    }

    private function _getDataOviByVigilanciaByDistLocIpOvi($anio,$distrito,$localidad,$descipress,$fecha,$idlabelcodovitrampa,$nroovitrampa){
        $query="SELECT 
            detinspeccionviviendas.fechaintervencion,
            sum(detinspeccionviviendas.viviendainsptotal) as sviviendainsptotal,
             sum(detinspeccionviviendas.cerrada) as scerrada,
             sum(detinspeccionviviendas.deshabitada) as sdeshabitada,
             sum(detinspeccionviviendas.renuente) as srenuente,
             sum(detinspeccionviviendas.consumolarvicidagr) as sconsumolarvicidagr,
             sum(detinspeccionviviendas.semanaentomologica) as ssemanaentomologica,
             sum(detinspeccionviviendas.totalviviendaspositivas) as stotalviviendaspositivas,
             sum(detinspeccionviviendas.totalviviendastratadas) as stotalviviendastratadas,                
             sum(detinspeccionviviendas.tanquealto_positivo) as stanquealto_positivo,
            sum( detinspeccionviviendas.tanquealto_inspeccionado) as stanquealto_inspeccionado,
            sum( detinspeccionviviendas.tanquealto_tratados) as stanquealto_tratados,
            sum( detinspeccionviviendas.barril_inspeccionado) as sbarril_inspeccionado,
             sum(detinspeccionviviendas.barril_positivo) as sbarril_positivo,
             sum(detinspeccionviviendas.barril_tratado) as sbarril_tratado,
             sum(detinspeccionviviendas.balde_inspeccionado) as sbalde_inspeccionado,
            sum( detinspeccionviviendas.balde_positivo) as sbalde_positivo,
            sum( detinspeccionviviendas.balde_tratado) as sbalde_tratado,
             sum(detinspeccionviviendas.olla_inspeccionado) as solla_inspeccionado,
             sum(detinspeccionviviendas.olla_positivo) as solla_positivo,
             sum(detinspeccionviviendas.olla_tratado) as solla_tratado,
             sum(detinspeccionviviendas.florero_inspeccionado) as sflorero_inspeccionado,
             sum(detinspeccionviviendas.florero_positivo) as sflorero_positivo,
             sum(detinspeccionviviendas.florero_tratado) as sflorero_tratado,
             sum(detinspeccionviviendas.llantas_inspeccionado) as sllantas_inspeccionado,
             sum(detinspeccionviviendas.llantas_positivo) as sllantas_positivo,
             sum(detinspeccionviviendas.llantas_tratado) as sllantas_tratado,
             sum(detinspeccionviviendas.inservibles_inspeccionado) as sinservibles_inspeccionado,
             sum(detinspeccionviviendas.inservibles_positivo) as sinservibles_positivo,
             sum(detinspeccionviviendas.inservibles_eliminado) as sinservibles_eliminado,
             sum(detinspeccionviviendas.otros_inspeccionado) as sotros_inspeccionado,
             sum(detinspeccionviviendas.otros_positivo) as sotros_positivo,
             sum(detinspeccionviviendas.otros_tratados) as sotros_tratados,
             sum(detinspeccionviviendas.tinspeccionados) as stinspeccionados, 
             sum(detinspeccionviviendas.tpositivos) as stpositivos,
             sum(detinspeccionviviendas.ttratados) as sttratados,
             sum(detinspeccionviviendas.teliminados) as steliminados 
               
            FROM
            detinspeccionviviendas
            INNER JOIN labelcodovitrampa ON labelcodovitrampa.nombre = detinspeccionviviendas.ipress
            where 
            detinspeccionviviendas.estado>0
              and detinspeccionviviendas.iddistrito='$distrito'
            and detinspeccionviviendas.idlocalidad='$localidad'          
            and detinspeccionviviendas.ipress='$descipress'
            and year(detinspeccionviviendas.fechaintervencion)=$anio
            and DATE_FORMAT( detinspeccionviviendas.fechaintervencion , '%m-%Y')='$fecha'
            and    labelcodovitrampa.idlabelcodovitrampa='$idlabelcodovitrampa'
            and  detinspeccionviviendas.nroovitrampa='$nroovitrampa'
            group by    detinspeccionviviendas.fechaintervencion 
            order by fechaintervencion  asc";
        $ret = $this->db->query($query)->result_array();
        return $ret;
    }

}