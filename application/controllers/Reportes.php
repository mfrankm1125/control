<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Reportes extends CMS_Controller  {
    private $_list_perm;
     
    public function __construct()
    {
        parent::__construct();
        /*if (!$this->acceso()) {
            redirect('errors/error/1');
        }*/
        $this->load->model('global_model');
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }


    public function index(){

        // $this->template->set_data('data',$data);

        //$this->load->view('reportes/reportExcelTest',["hola"=>1]);
    }


    public function reportProdLechex($tipotiempo=null,$tipodoc=null,$anio=null,$mesIni=null,$mesEnd=null){
        if($tipotiempo != null && $tipodoc !=null){
                    $dataReport=$this->global_model->getDataReportProdLeche($tipotiempo,$anio,$mesIni,$mesEnd);
                  // print_r($dataReport); exit();
                if($tipotiempo == "anio"){
                   
                   switch ($tipodoc) {
                            case 'word':
                                
                                break;
                            case 'excel':
                                
                                break;
                            case 'digital':
                                
                                break;
                            default:
                                 
                                break;
                        }     

                         $this->load->view('reportes/reportAnualProdLeche',["data"=>$dataReport,"anio"=>$anio]);
                }elseif ($tipotiempo == "aniomes") {
                        $this->load->view('reportes/reportMesProdLeche',["data"=>$dataReport,"anio"=>$anio]);
                }


        }else{
            redirect('errors/error/1');
        }
    }

    public function reportInventario($tipotiempo=null,$tipodoc=null,$tipoanimal=null,$fechaini=null,$fechaend=null){
        if($tipotiempo != null && $tipodoc !=null){
            libxml_use_internal_errors(true);
            if($tipotiempo =="actual"){
                $retInv=$this->dataInvGanado($tipoanimal,$fechaini,$fechaend);
                $dataSendView=["data"=>$retInv,"fechareport"=>$fechaend];
                $viewHtml=$this->load->view('reportes/reportinventario/invactual',$dataSendView,TRUE);
                //echo "".$viewHtml ; exit();
                 if($tipodoc == "word"){
                     $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"inventario"]);
                 }elseif($tipodoc == "excel"){
                     $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"inventario"]);

                 }
            }elseif ($tipotiempo =="nacimiento"){
                $data=$this->getReportNacimientoAnimal($tipoanimal,$fechaini,$fechaend);

                $dataSendView=["data"=>$data,"fechaini"=>$fechaini,"fechaend"=>$fechaend];
                $viewHtml=$this->load->view('reportes/reportinventario/nacimientoanimal',$dataSendView,TRUE);
                if($tipodoc == "word"){
                    $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"nacimiento"]);
                }elseif($tipodoc == "excel"){
                    $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"nacimiento"]);

                }
                //echo $viewHtml;
            }elseif ($tipotiempo =="salida"){

                $dataTipoSalida=$this->dataTipoSalida();
                $dataTipoProducto=$this->dataTipoProducto();
                $return=null;
                 foreach($dataTipoSalida as $k=>$v){

                     $dataProducto=null;
                     foreach ($dataTipoProducto as $kk=>$vv){
                         $idtiposalida=$v["idtiposalidaanimal"];
                         $txtsalida=strtolower($v["nombre"]);
                         $idtipoproducto=$vv["idtipoproducto"];
                         $txttipoproducto=strtolower($vv["nombre"]);
                         $dataSalida=$this->dataSalidaByIdsalidaIdTipoproducto($idtiposalida,$txtsalida,$idtipoproducto,$txttipoproducto,$fechaini,$fechaend);
                         $dataValida=null;
                            if(sizeof($dataSalida)>0){
                                $dataValida=$dataSalida;
                                $dataProducto[]=array(
                                    "tipoproducto"=>$txttipoproducto,
                                    "dataSalida"=>$dataValida
                                );
                            }


                     }
                     $txtsalida=$v["nombre"];
                     if(sizeof($dataProducto)>0){
                         $return[]=array(
                             "tiposalida"=>$txtsalida,
                             "dataproducto"=>$dataProducto
                         );
                     }




                 }

              // echo"<pre>";print_r($return);exit();
                $dataSendView=["data"=>$return,"fechaini"=>$fechaini,"fechaend"=>$fechaend];
                $viewHtml=$this->load->view('reportes/reportinventario/salidas',$dataSendView,TRUE);
                //echo $viewHtml;exit();
                if($tipodoc == "word"){
                    $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"regsalida"]);
                }elseif($tipodoc == "excel"){
                    $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"regsalida"]);
                }
                /*
               $data=$this->dataReportSalida($tipoanimal,$fechaini,$fechaend);
              // print_r($data);exit();
               $dataSendView=["data"=>$data,"fechaini"=>$fechaini,"fechaend"=>$fechaend];
               $viewHtml=$this->load->view('reportes/reportinventario/salidasanimal',$dataSendView,TRUE);
               //echo $viewHtml;exit();
               if($tipodoc == "word"){
                   $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"salida"]);
               }elseif($tipodoc == "excel"){
                   $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"salida"]);
               }   */


            }elseif ($tipotiempo =="vacaprod"){
                $data=$this->dataReportProdVaca($tipoanimal,$fechaini,$fechaend);
                // print_r($data);exit();
                $dataSendView=["data"=>$data,"fechaini"=>$fechaini,"fechaend"=>$fechaend];
                $viewHtml=$this->load->view('reportes/reportinventario/prodvaca',$dataSendView,TRUE);
                 //echo $viewHtml;exit();
                if($tipodoc == "word"){
                    $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"prodanimal"]);
                }elseif($tipodoc == "excel"){
                    $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"prodanimal"]);

                }
            }
        }
    }

    public function dataSalidaByIdsalidaIdTipoproducto($idtiposalida,$txtsalida,$idtipoproducto,$txttipoproducto,$fechaini,$fechaend){
        //echo "".$txtsalida;exit();
        $condicion=["idtiposalida"=>$idtiposalida,"idtipoproducto"=>$idtipoproducto,"estado"=>1];
        $this->db->select('*');
        $this->db->from("salida");
        $this->db->where($condicion);
        $this->db->where("salida.fechasalida BETWEEN '$fechaini' AND '$fechaend' ");
       // $this->db->order_by('tiposalidaanimal.idtiposalidaanimal', 'DESC');
        $result = $this->db->get()->result_array();
        $return=null;

        if($txtsalida =="venta"){
            if($txttipoproducto == "animal"){
                foreach($result as  $k=>$v){
                    $data=$this->querySalidaAnimal($idtiposalida,$idtipoproducto,$fechaini,$fechaend);

                    if(sizeof($data)>0){
                        $return=$data;

                    }

                }
            }else if($txttipoproducto == "leche"){
                $data=$this->querySalidaLeche($idtiposalida,$idtipoproducto,$fechaini,$fechaend);

                if(sizeof($data)>0){
                    $return=$data;

                }
            }

        }else if ("donación"){
            if($txttipoproducto == "animal"){
                foreach($result as  $k=>$v){
                    $data=$this->querySalidaAnimal($idtiposalida,$idtipoproducto,$fechaini,$fechaend);

                    if(sizeof($data)>0){
                        $return=$data;

                    }

                }
            }else if($txttipoproducto == "leche"){
                $data=$this->querySalidaLeche($idtiposalida,$idtipoproducto,$fechaini,$fechaend);

                if(sizeof($data)>0){
                    $return=$data;

                }
             }
        }else if ("muerte"){
            if($txttipoproducto == "animal"){
                foreach($result as  $k=>$v){
                    $data=$this->querySalidaAnimal($idtiposalida,$idtipoproducto,$fechaini,$fechaend);

                    if(sizeof($data)>0){
                        $return=$data;

                    }

                }
            }
        }


        return $return;
    }


    public  function querySalidaLeche($idtiposalida,$idtipoproducto,$fechaini,$fechaend){
        $where=" and salida.fechasalida BETWEEN '".$fechaini."'  and '".$fechaend."'  ";
        $query="SELECT
        clientes.nombre as cliente ,  
        salida.cantidad,
        salida.precio,
        
        salida.fechasalida
        FROM
        salida
        INNER JOIN tiposalidaanimal ON tiposalidaanimal.idtiposalidaanimal = salida.idtiposalida
        INNER JOIN tipoproducto ON tipoproducto.idtipoproducto = salida.idtipoproducto
        INNER JOIN clientes ON clientes.idcliente = salida.idcliente
        where salida.estado=1 and  salida.idtiposalida =".$idtiposalida."  and  salida.idtipoproducto= ".$idtipoproducto."    ".$where." order by salida.fechasalida desc   ";
        $result = $this->db->query($query)->result_array();
        return $result;

    }

    public function querySalidaAnimal($idtiposalida,$idtipoproducto,$fechaini,$fechaend){
        $query="SELECT
        motivosalidaanimal.nombre as motivosalida,
        animales.codraza,
        animales.sexo,
        animales.codanimal,
        animales.pureza,
        animales.proposito,
        claseanimal.nombre as claseanimal,        
        clientes.nombre AS cliente,
        salida.precio,
        salida.cantidad,
        salida.fechasalida
        FROM
        salida
        INNER JOIN motivosalidaanimal ON motivosalidaanimal.idmotivosalidaanimal = salida.idmotivosalidaanimal
        INNER JOIN animales ON animales.idanimal = salida.idanimal
        INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
        INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
        INNER JOIN clientes ON clientes.idcliente = salida.idcliente
        where detanimalxclaseanimal.isclaseactual=1 and salida.idtiposalida=".(int)$idtiposalida." and salida.idtipoproducto=".(int)$idtipoproducto."  and salida.fechasalida BETWEEN '".$fechaini. "' and '".$fechaend. "' 
        and salida.estado=1
        ";
        $result = $this->db->query($query)->result_array();
        return $result;

    }

    public function dataTipoSalida(){
        $this->db->select('*');
        $this->db->from("tiposalidaanimal");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('tiposalidaanimal.nombre', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function dataTipoProducto(){
        $this->db->select('*');
        $this->db->from("tipoproducto");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('tipoproducto.idtipoproducto', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function dataReportProdVaca($tipoanimal=null,$fechaini=null,$fechaend=null){
        $query="SELECT
            animales.codanimal,
            animales.codraza,
            sum(prodleche.cantmaniana) as summanina,
            sum(prodleche.canttarde) as sumtarde,
            sum(prodleche.cantrecria) as sumrecria ,
            sum(prodleche.cantmaniana)+sum(prodleche.canttarde)+sum(prodleche.cantrecria) as total,
            sum(prodleche.cantmaniana)+sum(prodleche.canttarde) as totalvendible
            FROM
            animales
            INNER JOIN prodleche ON animales.idanimal = prodleche.idanimal
            where animales.estado=1 and prodleche.estado=1 and prodleche.fechaprodleche BETWEEN '".$fechaini."' and '".$fechaend."'
            GROUP BY animales.codanimal
            order by (sum(prodleche.cantmaniana)+sum(prodleche.canttarde)+sum(prodleche.cantrecria)) desc";
        $result = $this->db->query($query)->result_array();
        return $result;

    }

    private function dataReportSalida($tipoanimal=null,$fechaini=null,$fechaend=null){
        $dataTipoanimal=$this->global_model->getDataTipoAnimal();
        $return=null;
        foreach($dataTipoanimal as $k=>$v){
            $dataSalida=$this->dataSalidabyTipoAnimal($v["idtipoanimal"],$fechaini,$fechaend);
            if(sizeof($dataSalida) > 0) {
                $return[] = array(
                    "idtipoanimal" => $v["idtipoanimal"],
                    "tipoanimal" => $v["nombre"],
                    "datasalida" => $dataSalida
                );
            }
        }

        return $return;
    }

    private function dataSalidabyTipoAnimal($idtipoanimal=null,$fechaini=null,$fechaend=null){
        $query="SELECT
        animales.codanimal,
        salida.idanimal,
        claseanimal.nombre as claseanimal,
        salida.precio,
        salida.fechasalida,
        clientes.nombre as cliente
        FROM
        salida
        INNER JOIN tiposalidaanimal ON tiposalidaanimal.idtiposalidaanimal = salida.idtiposalida
        INNER JOIN tipoproducto ON tipoproducto.idtipoproducto = salida.idtipoproducto
        INNER JOIN animales ON animales.idanimal = salida.idanimal
        INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
        INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
        INNER JOIN clientes ON clientes.idcliente = salida.idcliente
        where salida.estado=1 and detanimalxclaseanimal.isclaseactual=1 and LOWER(tipoproducto.nombre) ='animal'
        and lower(tiposalidaanimal.nombre) ='venta' and animales.idtipoanimal=".$idtipoanimal."  and salida.fechasalida BETWEEN '".$fechaini."' and '".$fechaend."'";
        $result = $this->db->query($query)->result_array();
        return $result;


    }

    public function dataInvGanado($tipoanimal=null,$fechaini=null,$fechaend=null){

        $dataTipoanimal=$this->global_model->getDataTipoAnimal($tipoanimal);
        $dfinal=null;
        foreach($dataTipoanimal as $k=>$v){
           $dataClases=$this->dataInvForTipoAnimal($v["idtipoanimal"],$fechaini,$fechaend);
            if(sizeof($dataClases) > 0) {
                $dfinal[] = array(
                    "idtipoanimal" => $v["idtipoanimal"],
                    "tipoanimal" => $v["nombre"],
                    "dataclases" => $dataClases
                );
            }
        }

        //echo "<pre>";print_r($dfinal);exit();
        return $dfinal;
    }

   public function dataInvForTipoAnimal($idclaseanimal,$fechaini=null,$fechaend=null){
        $dataClases=$this->global_model->getDataClaseAnimal($idclaseanimal);
        $dataReturn=null;
        foreach($dataClases as $key=>$value){
            $dataAnimal=$this->global_model->getDataAnimalByClase($value["idclaseanimal"]);
            if(sizeof($dataAnimal)>0){
                $dataReturn[]=array(
                    "idclaseanimal"=>$value["idclaseanimal"],
                    "claseanimal"=>$value["nombre"],
                    "animales"=>$dataAnimal
                );
            }
        }
       return $dataReturn;
    }

    public function reportProdLeche($tipotiempo=null,$tipodoc=null,$fechaini=null,$fechaend=null){
        if($tipotiempo == "dehasta"){
            libxml_use_internal_errors(true);
            $dataSendView=[
                "data"=>$this->getDataProdLecheByFechas($fechaini,$fechaend),
                "dataSalida"=>$this->getDataSalidaLeche($fechaini,$fechaend),
                "fechaini"=>$fechaini,
                "fechaend"=>$fechaend
            ];
            $viewHtml=$this->load->view('reportes/reportprodleche/bodyProdLeche',$dataSendView,TRUE);
            //echo $viewHtml;

            if($tipodoc == "word"){
                $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"prodleche"]);
            }elseif($tipodoc == "excel"){
                $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"prodleche"]);

            }
        }else{
            $dataSendView=[
                "data"=>$this->getDataProdLecheByRazas($fechaini,$fechaend),
                "dataSalida"=>"",
                "fechaini"=>$fechaini,
                "fechaend"=>$fechaend
            ];
        }

        //$this->load->view('reportes/reportinventario/tmpExcel',["data"=>"","file_name"=>"prodleche"]);

    }
    public function reportProdLecheRaza($tipotiempo=null,$tipodoc=null,$fechaini=null,$fechaend=null,$razas=null){
        $razasF=null;
        if($razas!=null){
            $razas=explode("-",$razas);
            foreach($razas as $kk){
                $razasF[]=str_replace("vv","/",$kk);
            }
        }else{
            $query="SELECT UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as raza 
                    FROM
                    prodleche
                    INNER JOIN animales ON animales.idanimal = prodleche.idanimal
                    where prodleche.estado=1
                    GROUP BY  UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))
                    order by UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) desc
                    "  ;
            $rest = $this->db->query($query)->result_array();
            foreach($rest as $k){
                $razasF[]=$k["raza"];
            }
        }

     //echo "<pre>";print_r($razasF);exit();

        if($tipotiempo == "dehasta"){
            libxml_use_internal_errors(true);
            $dataSendView=[
                "data"=>$this->getDataProdLecheByRazas($fechaini,$fechaend,$razasF),
                "dataSalida"=>"",
                "fechaini"=>$fechaini,
                "fechaend"=>$fechaend
            ];

           // echo "<pre>"; print_r($dataSendView);exit();
         
             $viewHtml=$this->load->view('reportes/reportprodleche/bodyProdLecheRaza',$dataSendView,TRUE);
            // echo $viewHtml;

            if($tipodoc == "word"){
                $this->load->view('reportes/reportinventario/tmpWord',["data"=>$viewHtml,"file_name"=>"prodleche"]);
            }elseif($tipodoc == "excel"){
                $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"prodleche"]);
            }else{
                echo $viewHtml;
            }
        }else{
           
        }

        //$this->load->view('reportes/reportinventario/tmpExcel',["data"=>"","file_name"=>"prodleche"]);

    }

    private function getDataProdLecheByRazas($fechaini =null,$fechaend=null ,$razas=null ){
        $result=null;
        $fecha1 = new DateTime($fechaini);
        $fecha2 = new DateTime($fechaend);
        $rrrr = $fecha1->diff($fecha2);
        $rrrr=($rrrr->days)+1;
        if(sizeof($razas) > 0) {
            $dataR=null;
            foreach($razas as $kx=> $ix){
                $dataR[]=array(
                    "raza"=>$ix,
                    "cantidad"=>0,
                    "diastrans"=>$rrrr,
                    "data"=>$this->_getDataProdLecheByRaza($fechaini,$fechaend,$ix)
                );
            }
        }
       // echo "<pre>";print_r($dataR);exit();

         /*    $this->db->select("UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as razas,
                                count(UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))) as cantidad");
             $this->db->from("animales");
             $this->db->where(" UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) in (
                                SELECT
                                UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as razas
                                FROM animales
                                inner JOIN  prodleche ON animales.idanimal = prodleche.idanimal
                                where prodleche.estado>0 and animales.estado > 0
                                GROUP BY 
                                UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')))  and  animales.estado > 0 ");

            if(sizeof($razas) > 0) {
                $this->db->where_in(" UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))", $razas);
            }
             $this->db->group_by("UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) ");
             $result2e = $this->db->get()->result_array();

             $dataR=null;
             foreach($result2e as $kx=> $ix){
                 $dataR[]=array(
                     "raza"=>$ix["razas"],
                     "cantidad"=>$ix["cantidad"],
                     "diastrans"=>$rrrr,
                     "data"=>$this->_getDataProdLecheByRaza($fechaini,$fechaend,$ix["razas"])
                 );
             }
            */
            //echo "<pre>";print_r($dataR);exit();

        return $dataR;

    }

    //--

    private function _getDataProdLecheByRaza($fechaini=null, $fechaend=null,$razas = null ){

        $animalesRaza="SELECT
                        animales.codanimal,
                        animales.idanimal
                        FROM
                        animales
                        INNER JOIN prodleche ON animales.idanimal = prodleche.idanimal
                        where UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) = '$razas' and prodleche.estado=1
                        GROUP BY animales.codanimal,animales.idanimal
                        ORDER BY codanimal";
        $dtAnimalsByRaza=  $this->db->query($animalesRaza)->result_array();
        $dtOut=null;
        foreach($dtAnimalsByRaza as $i){
             $queryx="SELECT
                    animales.codanimal,
                    ROUND(sum(prodleche.cantmaniana),2) as sprodmaniana,
                    ROUND(sum(prodleche.canttarde),2) as sprodtarde,
                    ROUND(sum(prodleche.cantrecria),2) as sprodrecria,
                    (ROUND(sum(prodleche.cantmaniana),2) + ROUND(sum(prodleche.canttarde),2))  as tsinrecria,
                    (ROUND(sum(prodleche.cantmaniana),2) + ROUND(sum(prodleche.canttarde),2) + ROUND(sum(prodleche.cantrecria),2) )  as tconrecria,
                    1 as avgconrecria,
                    1 as avgsinrecria,
                    UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as raza,        
                    DATEDIFF('$fechaend', '$fechaini')+1 as diasentre,      
                    (select count(fechasprodx.fechaagrupada) as xxxd from ( SELECT 
                                    prodleche.fechaprodleche as fechaagrupada
                                    FROM  animales
                                    inner JOIN  prodleche ON animales.idanimal = prodleche.idanimal
                                    where prodleche.estado>0 and prodleche.fechaprodleche >='$fechaini'  and prodleche.fechaprodleche <= '$fechaend'
                                    and animales.codanimal = '".$i["codanimal"]."'
                                     GROUP BY  prodleche.fechaprodleche) as fechasprodx )  as diasproducidos
                    FROM
                    animales
                    INNER JOIN prodleche ON animales.idanimal = prodleche.idanimal
                    where animales.codanimal='".$i["codanimal"]."'
                    and prodleche.fechaprodleche >='$fechaini'  and prodleche.fechaprodleche <= '$fechaend'
                    GROUP BY animales.codanimal";
            $dtResultProdAnimalesRazaByfechas=  $this->db->query($queryx)->result_array();

            if(sizeof($dtResultProdAnimalesRazaByfechas) > 0 ){
                $dtOut[]=array(
                    "codanimal"=>$i["codanimal"],
                    "idanimal"=>$i["idanimal"],
                    //"datafinal"=>$dtResultProdAnimalesRazaByfechas,
                    "sprodmaniana"=> $dtResultProdAnimalesRazaByfechas[0]["sprodmaniana"],
                     "sprodtarde" => $dtResultProdAnimalesRazaByfechas[0]["sprodtarde"],
                    "sprodrecria" => $dtResultProdAnimalesRazaByfechas[0]["sprodrecria"],
                    "tsinrecria" => $dtResultProdAnimalesRazaByfechas[0]["tsinrecria"],
                    "tconrecria" => $dtResultProdAnimalesRazaByfechas[0]["tconrecria"],
                    "avgconrecria" => $dtResultProdAnimalesRazaByfechas[0]["avgconrecria"],
                    "avgsinrecria" => $dtResultProdAnimalesRazaByfechas[0]["avgsinrecria"],
                    "raza2" => $dtResultProdAnimalesRazaByfechas[0]["raza"],
                    "diasentre" => $dtResultProdAnimalesRazaByfechas[0]["diasentre"],
                    "diasproducidos" => $dtResultProdAnimalesRazaByfechas[0]["diasproducidos"]
                );
            }

        }



        /*$this->db->select("round(sum( prodleche.cantmaniana),2) as sprodmaniana,
             round(sum(prodleche.canttarde),2) as sprodtarde,
             round(sum(prodleche.cantrecria),2) as sprodrecria,
             round(sum(prodleche.cantrecria),2) + round(sum(prodleche.canttarde),2)+round(sum( prodleche.cantmaniana),2) as tconrecria,
             round(sum(prodleche.canttarde),2)+round(sum( prodleche.cantmaniana),2) as tsinrecria,
             avg(prodleche.cantmaniana+ prodleche.canttarde + prodleche.cantrecria  ) as avgconrecria ,
             avg(prodleche.cantmaniana+ prodleche.canttarde    ) as avgsinrecria ,            
             UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as raza,        
             DATEDIFF('$fechaend', '$fechaini')+1 as diasentre,             
             
             (select count(fechasprodx.fechaagrupada) as xxxd from ( SELECT 
                prodleche.fechaprodleche as fechaagrupada
                FROM  animales
                inner JOIN  prodleche ON animales.idanimal = prodleche.idanimal
                where prodleche.estado>0 and prodleche.fechaprodleche >='$fechaini'  and prodleche.fechaprodleche <= '$fechaend'
                and UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) = '$razas'
                 GROUP BY  prodleche.fechaprodleche) as fechasprodx )  as diasproducidos,
                                                   
             ");
        $this->db->from("prodleche");
        $this->db->join("animales","animales.idanimal = prodleche.idanimal","inner");
        $this->db->where(["prodleche.estado"=>1]);
        if(sizeof($razas) > 0) {
            $this->db->where_in("UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))", $razas);
        }
        $this->db->where('prodleche.fechaprodleche >=', $fechaini);
        $this->db->where('prodleche.fechaprodleche <=', $fechaend);
        $this->db->group_by("UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) ");
        $this->db->order_by('tconrecria', 'DESC');
        $result = $this->db->get()->result_array();

        $query="SELECT
                animales.codanimal,
                ROUND(sum(prodleche.cantmaniana),2) as sprodmaniana,
                ROUND(sum(prodleche.canttarde),2) as sprodtarde,
                ROUND(sum(prodleche.cantrecria),2) as sprodrecria,
                (ROUND(sum(prodleche.cantmaniana),2) + ROUND(sum(prodleche.canttarde),2))  as tsinrecria,
                (ROUND(sum(prodleche.cantmaniana),2) + ROUND(sum(prodleche.canttarde),2) + ROUND(sum(prodleche.cantrecria),2) )  as tconrecria,
                1 as avgconrecria,
                1 as avgsinrecria,
                UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as raza,        
                DATEDIFF('$fechaend', '$fechaini')+1 as diasentre,      
                (select count(fechasprodx.fechaagrupada) as xxxd from ( SELECT 
                                prodleche.fechaprodleche as fechaagrupada
                                FROM  animales
                                inner JOIN  prodleche ON animales.idanimal = prodleche.idanimal
                                where prodleche.estado>0 and prodleche.fechaprodleche >='$fechaini'  and prodleche.fechaprodleche <= '$fechaend'
                                and UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) = '3/4GIRX1/4BS'
                                 GROUP BY  prodleche.fechaprodleche) as fechasprodx )  as diasproducidos
                FROM
                animales
                INNER JOIN prodleche ON animales.idanimal = prodleche.idanimal
                where prodleche.estado > 0  and UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) = '3/4GIRX1/4BS' 
                and prodleche.fechaprodleche >='$fechaini'  and prodleche.fechaprodleche <= '$fechaend'
                GROUP BY animales.codanimal, UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))";
        $resultx = $this->db->query($query)->result_array();  */
        return $dtOut;
    }
    //---

    private function getReportNacimientoAnimal($tipoanimal,$fechaini,$fechaend){
        $dfinal=null;
        $dataTipoanimal=$this->global_model->getDataTipoAnimal($tipoanimal);
        foreach($dataTipoanimal as $k=>$v){
            $dataAnimalNace=$this->getAnimalNacimiento($v["idtipoanimal"],$fechaini,$fechaend);

            if(sizeof($dataAnimalNace) > 0) {
                $dfinal[] = array(
                    "idtipoanimal" => $v["idtipoanimal"],
                    "tipoanimal" => $v["nombre"],
                    "dataanimales" => $dataAnimalNace
                );
            }
        }
        $return=$dfinal;
        return $return;
    }


    private function getAnimalNacimiento($tipoanimal=null,$fechaini=null,$fechaend=null){
        $this->db->select('animales.idanimal,animales.codanimal,animales.codraza,animales.fechanacimiento,animales.pesonace,animales.sexo,animales.idanimalpadre,animales.idanimalmadre');
        $this->db->from("animales");
        $this->db->where(["estado"=>1,"idtipoanimal"=>$tipoanimal]);
        $this->db->where('animales.fechanacimiento >=', $fechaini);
        $this->db->where('animales.fechanacimiento <=', $fechaend);
        $this->db->order_by('animales.fechanacimiento', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    //---

    private function getDataProdLecheByFechas($fechaini=null,$fechaend=null){
        $condicion=array(
            'prodleche.estado'=>1
        );
        $this->db->select('fechaprodleche,sum(cantmaniana) as tmaniana,sum(canttarde) as ttarde,(sum(cantmaniana) + sum(canttarde)) as totalvendible,sum(cantrecria) as recria,(sum(cantmaniana) + sum(canttarde)+sum(cantrecria)) as total');
        $this->db->from("prodleche");
        $this->db->where($condicion);
        $this->db->where('prodleche.fechaprodleche >=', $fechaini);
        $this->db->where('prodleche.fechaprodleche <=', $fechaend);
        $this->db->group_by("fechaprodleche");
        $this->db->order_by('fechaprodleche', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function getDataSalidaLeche($fechaini=null,$fechaend=null){
        $where=" and salida.fechasalida BETWEEN '".$fechaini."'  and '".$fechaend."'  ";
        $query="SELECT
        clientes.nombre as cliente ,  
        salida.cantidad,
        salida.precio,
        
        salida.fechasalida
        FROM
        salida
        INNER JOIN tiposalidaanimal ON tiposalidaanimal.idtiposalidaanimal = salida.idtiposalida
        INNER JOIN tipoproducto ON tipoproducto.idtipoproducto = salida.idtipoproducto
        INNER JOIN clientes ON clientes.idcliente = salida.idcliente
        where  lower(tipoproducto.nombre) ='leche' and   lower(tiposalidaanimal.nombre) in ('venta','donación')     ".$where." order by salida.fechasalida desc   ";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

//-----------------------------------------------------------------------------------------------------------------------------
    public function dataReportGaxAreaResp($idpoi=null,$idArea=null,$mesIni=null,$mesEnd=null,$periodo=null,$tipo=null){
        $tipox=trim($tipo);
        libxml_use_internal_errors(true);
            //$data=$this->ga_model->reportGaAreaResp($idpoi,$idArea,$mesIni,$mesEnd,$periodo);
            $data=$this->ga_model->reportEvaluacionPoi($idpoi,$idArea,$mesIni,$mesEnd,"reporte");
            //echo "<pre>";print_r($data);exit();
            switch ($tipox){
                case 'w':
                    $datax=array("dataR"=>$data,"mesIni"=> $mesIni,"mesEnd"=>$mesEnd ,"periodo"=>$periodo);
                    $this->load->view('reportes/phpWord',$datax);//reportGaAreaWordT  reportGaGlobalWord reportGaGlobalWord
                    break;
                case 'x':
                    $datax=array("dataR"=>$data,"mesIni"=> $mesIni,"mesEnd"=>$mesEnd ,"periodo"=>$periodo);
                    $this->load->view('reportes/reporteExcelEvaluacion',$datax);//reporteExcelEvaluacion.php reportGaAreaExcel
                    break;
                case 'd':
                    $this->template->set_data("idpoi",$idpoi);
                    $this->template->set_data("mesIni",$mesIni);
                    $this->template->set_data("mesEnd",$mesEnd);
                    $this->template->set_data("periodo",$periodo);
                    $this->template->set_data("dataR",$data);//json_encode($data)
                    $this->template->add_js('base', 'underscore');
                    $this->template->renderizar('reportes/reporteHtml');//phpWord.php  reporteTesting

                    break;
                default; break;
            }
                
        //print_r("##");exit();
       // echo "<pre>".$idArea." -".$mesIni."-".$mesEnd  ;print_r($data);exit();


    }
    public function dataReportGabyPOI($idpoi=null,$mesIni=null,$mesEnd=null,$periodo=null){
       // $data=$this->ga_model->reportGaAreaResp($idpoi,$idArea,$mesIni,$mesEnd,$periodo);
        //$this->load->view('reportes/reportGaAreaWord',$data);
    }

    public function reportHtml(){
        $this->template->set_data();
        $this->template->set_data();
        $this->template->add_js('base', 'highcharts/highcharts');
        $this->template->add_js('base', 'highcharts/modules/exporting');
        $this->template->add_js('base', 'highcharts/modules/offline-exporting');
        $this->template->renderizar('reportes/reportHtml');
    }


    public function deleteTmpFiles(){
        $dir=getcwd();//getcwd  APPPATH
        $doc=$dir."/*.odt";
        array_map( "unlink", glob( $doc ) );

        echo "ok";
    }
/*
    public function reportGaAreaResp($idpoi=null,$idArea=null,$mesIni=null,$mesEnd=null,$periodo=null){
        $idPoi=(int)$idpoi;
        $idAreaR=(int)$idArea;
        $mesInix=(int)$mesIni;
        $mesEndx=(int)$mesEnd;
        $dataFinal=array();
        $dataActPre=$this->_getActPreforArea($idAreaR,$idPoi);
        foreach ($dataActPre as $dtAcpre){
            $dataActOp=$this->_getActOpForActPre($idPoi,$dtAcpre["idactividad"],$idAreaR,$dtAcpre["id_actpresupuestariareal"],$mesInix,$mesEndx);
            $d=array(
                "accestrategica"=>$dtAcpre["accestrategica"],
                "idactividad"=>$dtAcpre["idactividad"],
                "nombrearearesponsable"=>$dtAcpre["nombrearearesponsable"],
                "actpre"=>$dtAcpre["actpre"],
                "id_actpresupuestariareal"=>$dtAcpre["id_actpresupuestariareal"],
                "actvops"=>$dataActOp
            );
            array_push($dataFinal,$d);
        }
        $data=array("data"=>$dataFinal,
                    "mesIni"=>$mesIni,
                    "mesEnd"=>$mesEnd,
                    "periodo"=>$periodo);
        //echo "<pre>";print_r($dataFinal);
        $this->load->view('reportes/reportGaAreaWord',$data);
        //$this->load->view('reportes/reportGaArea',$data);
    }

    private function _getActPreforArea($idareResp,$idpoi){
        $query="SELECT\n".
            "actividades.nombre as accestrategica,\n".
            "actividades.idactividad,\n".
            "users.nombrearearesponsable,\n".
            "actpresupuestariareal.nombre as actpre,\n".
            "detrespxactpre.id_actpresupuestariareal\n".
            "FROM\n".
            "detrespxactpre\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user \n".
            "where users.`status`=1 and users.role=18  and detrespxactpre.estado=1 and actividades.estado=1 and actividades.idpadrepadre=".$idpoi." \n".
            "and users.id=".$idareResp." \n".
            "GROUP BY actividades.nombre,\n".
            "users.nombrearearesponsable,\n".
            "actpresupuestariareal.nombre,\n".
            "detrespxactpre.id_actpresupuestariareal,actividades.idactividad";
        $result=$this->db->query($query)->result_array();
        return $result;
    }

    private function _getActOpForActPre($idPoi,$idact,$idAreaR,$id_actpresupuestariareal,$mIni,$mEnd){
        $query="SELECT\n".
            "detrespxactpre.id_actvpresupuestaria,\n".
            "actvpresupuestaria.nombre,\n".
            "unidadmedida.nombre as um \n".
            "FROM\n".
            "detrespxactpre\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "where actividades.estado=1 and users.`status`=1 and users.role=18 and detrespxactpre.id_actpresupuestariareal=".$id_actpresupuestariareal."   and detrespxactpre.estado=1 and actividades.idpadrepadre=".$idPoi."\n".
            "and users.id=".$idAreaR." and actividades.idactividad=".$idact."  \n".
            "GROUP BY detrespxactpre.id_actvpresupuestaria,\n".
            "actvpresupuestaria.nombre,\n".
            "unidadmedida.nombre";
            $result=$this->db->query($query)->result_array();
            $data=array();
            foreach ($result as $resActOp){
                $Ga=$this->_getGaForActPre($idact,$idAreaR,$id_actpresupuestariareal,$resActOp['id_actvpresupuestaria'],$mIni,$mEnd);
                $d=array("id_actvpresupuestaria"=>$resActOp["id_actvpresupuestaria"],
                          "nameactvop"=>$resActOp["nombre"],
                           "um"=>$resActOp["um"],
                           "ga"=>$Ga
                        );

                array_push($data,$d);
            }
        
        return $data;

    }

    private function _getGaForActPre($IdAct,$IdAreaResp,$IdActpreReal,$IdActpre,$timeIni,$timeEnd){
        $idAct=(int)$IdAct;
        $idAreaResp=(int)$IdAreaResp;
        $idActPre=(int)$IdActpre;
        $idActPreReal=(int)$IdActpreReal;
        $query2="Select \n".
            "sum(ejecutado) as ejecAll,\n".
            "sum(ejecutadoreal) as ejecParse ,\n".
            "(Sum(porcentual.por)/sum(cantidad) ) as ActPorcentual,\n".
            "sum(meta) as metax ,\n".
            "if((sum(ejecutadoreal)/sum(meta)) >= 1 ,1,(sum(ejecutadoreal)/sum(meta)) )as porcenajeFinal\n".
            "from (SELECT\n".
            "			detrespxactpre.ejecutado,\n".
            "			if(detrespxactpre.ejecutado > detrespxactpre.meta ,detrespxactpre.meta,detrespxactpre.ejecutado ) as ejecutadoreal,\n".
            "			detrespxactpre.meta,\n".
            "			if(detrespxactpre.ejecutado/detrespxactpre.meta >= 1 ,1 ,detrespxactpre.ejecutado/detrespxactpre.meta ) as por,\n".
            "			1 as cantidad,\n".
            "			actividades.idactividad\n".
            "			FROM\n".
            "			detrespxactpre\n".
            "			INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
            "			INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "			where detrespxactpre.id_tiempo BETWEEN ".$timeIni." and ".$timeEnd." and  actividades.idactividad=".$idAct." and detrespxactpre.id_actpresupuestariareal=".$idActPreReal." and detrespxactpre.meta <> 0 and  detrespxactpre.id_actvpresupuestaria=".$idActPre."  and detrespxactpre.id_user=".$idAreaResp." and  detrespxactpre.meta <> 0  and detrespxactpre.estado=1 \n".
            "			and actividades.estado=1 and users.role=18  ) as porcentual";
        $res=$this->db->query($query2)->result_array();
        return $res;

    }
*/
    public function reportGaAreaRespWord(){
        $this->load->view('reportes/reportGaAreaWord',"");
    }

    public function Test($url){

    }

    public  function chartAreas(){
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            //echo "<pre>";print_r($post);exit();
            $d=[];
            foreach($post["dataChart"] as $key=>$value){
                $d[$key]=array(
                    "name"=>$value["nombrearearesponsable"],
                    "y"=>floatval(round($value["ga"]*100,2))
                );
            }
           // echo "<pre>";print_r($d);exit();
            //$data=json_encode($post["dataChart"]);
            $data=json_encode($d);
           $html=$this->load->view('poi/chartArea',["dataAreaChart"=>$data,"iniChart"=>$post["iniChart"]],TRUE);
        }else{
            $html="Error";
        }

        echo $html;

    }


    public function imprimirProductoKardex($idprod){
        $kardex=$this->getDataKardex($idprod);
        $detKardex=$this->getProductKardex($idprod);
        $viewHtml=  $this->load->view('productokardex/tmpPrint',["kardex"=>$kardex,"detkardex"=>$detKardex],TRUE);
       // echo $viewHtml ;exit();
        $this->load->view('reportes/reportinventario/tmpExcel',["data"=>$viewHtml,"file_name"=>"prodleche"]);
    }

    private function getProductKardex($id){
            $id=$id;
            $query="SELECT
                    clientes.nombre,
                    clientes.razonsocial,
                    detkardex.cantidad,
                    detkardex.precio,
                    kardex.fechareg,
                    kardex.idtipoflujo,
                    kardex.nrodoc,
                    detkardex.idproductokardex
                    FROM
                    kardex
                    INNER JOIN clientes ON clientes.idcliente = kardex.idcliente
                    INNER JOIN detkardex ON kardex.idkardex = detkardex.idkardex
                    where kardex.estado>0 and detkardex.estado>0 and detkardex.idproductokardex=$id 
                    order by kardex.fechareg asc
                    ";
            $return=$this->db->query($query)->result_array();
        return $return;
    }

    private function getDataKardex($id){
        $query2="SELECT
                productokardex.idproductokardex,
                productokardex.idplanproduccion,
                tipoproduccion.nombre as tipoproducto,
                cultivo.nombre as cultivo,
                 cultivo.kgsaco  ,
                clasecultivo.nombre as clasecultivo,
                categoriacultivo.nombre as catcultivo,
                planproduccion.cultivar ,
                  planproduccion.anio ,
                planproduccion.fechaenvioalmacen ,
                planproduccion.nroloteproduccion ,
                planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje-planproduccion.descarteproceeje as semillavendible,
                planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje-planproduccion.descarteproceeje - if( ISNULL(prodventa.cantidad),0,prodventa.cantidad)  as stock,
               if( ISNULL(prodventa.cantidad),0,prodventa.cantidad) as prodvendido 
                FROM
                productokardex
                LEFT JOIN (                
                    SELECT
                    detkardex.idproductokardex,
                   COALESCE(SUM(detkardex.cantidad),0) as cantidad	 
                    FROM
                    detkardex 
                    INNER JOIN kardex ON kardex.idkardex = detkardex.idkardex
                    where kardex.estado > 0 and detkardex.estado > 0 
                    GROUP BY detkardex.idproductokardex
                ) AS prodventa 
                ON productokardex.idproductokardex = prodventa.idproductokardex
                INNER JOIN planproduccion ON planproduccion.idplanproduccion = productokardex.idplanproduccion
                INNER JOIN tipoproduccion ON tipoproduccion.idtipoproduccion = planproduccion.tipoproduccion
                INNER JOIN cultivo ON cultivo.idcultivo = planproduccion.idcultivo
                INNER JOIN clasecultivo ON clasecultivo.idclasecultivo = planproduccion.idclasecultivo
                INNER JOIN categoriacultivo ON categoriacultivo.idcategoriacultivo = planproduccion.idcategoriacultivo
                where prodventa.idproductokardex is null or prodventa.idproductokardex is not null  
                and planproduccion.estado > 0 and  productokardex.idproductokardex=$id";
        $result=$this->db->query($query2)->result_array();
        return $result;
    }





}
?>