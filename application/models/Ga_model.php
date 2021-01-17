<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ga_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function reportGaAreaResp($idpoi=null,$idArea=null,$mesIni=null,$mesEnd=null,$periodo=null){
        $idPoi=(int)$idpoi;
        $idAreaR=(int)$idArea;
        $mesInix=(int)$mesIni;
        $mesEndx=(int)$mesEnd;
        $dataFinal=array();
        $tipo="reporte";
        $dataFinal=$this->_getAccEstrategicasByPoiArea($idPoi,$idAreaR,$mesInix,$mesEndx,$tipo);
        $sumaGaactOp=0;
        $countActOpTotal=0;
        $GafinalArea=0;
        foreach ($dataFinal as $dataAcces){

            foreach ($dataAcces["dataactpre"] as $dataActpre){
                foreach ($dataActpre["dataactop"] as $dataActOp){
                    $countActOpTotal++;
                    $sumaGaactOp=$sumaGaactOp+floatval($dataActOp["gaop"]);

                }
            }
        }

        if($countActOpTotal>0){
            $GafinalArea=floatval($sumaGaactOp/$countActOpTotal);
        }


        $data=array("data"=>$dataFinal,
            "gaArea"=>$GafinalArea,
            "mesIni"=>$mesIni,
            "mesEnd"=>$mesEnd,
            "periodo"=>$periodo);
       // echo "<pre>";print_r($dataFinal);exit();
        return $data;
        //$this->load->view('reportes/reportGaArea',$data);
    }

    public function getDetalleAreabyPoi($idpoi=null,$idArea=null,$mesIni=null,$mesEnd=null,$tipo=null){
        $dataFinal=array();
        $GafinalArea=0;
        if( $idpoi !=null && $idArea != null ){
            $idPoi=(int)$idpoi;
            $idAreaR=(int)$idArea;
            $mesInix=(int)$mesIni;
            $mesEndx=(int)$mesEnd;
            $dataFinal=array();
            $dataFinal=$this->_getAccEstrategicasByPoiArea($idPoi,$idAreaR,$mesInix,$mesEndx,$tipo);
            $sumaGaactOp=0;
            $countActOpTotal=0;

            if($tipo != "evalua" || $tipo !="justify" || $tipo!="justifyhistory" || $tipo !="obs" ) {

                foreach ($dataFinal as $dataAcces){

                    foreach ($dataAcces["dataactpre"] as $dataActpre){
                        foreach ($dataActpre["dataactop"] as $dataActOp){
                            $countActOpTotal++;
                            $sumaGaactOp=$sumaGaactOp+floatval($dataActOp["gaop"]);

                        }
                    }
                }
                if($countActOpTotal>0){
                    $GafinalArea=floatval($sumaGaactOp/$countActOpTotal);
                }

            }

        }

        $dataFi=array("GaArea"=> $GafinalArea ,"dataArea"=>$dataFinal);
        return $dataFi;
        //echo json_encode($dataFinal);
        //echo "<pre>";print_r($dataFinal);
        //return $dataFinal;
        //$this->load->view('reportes/reportGaArea',$data);
    }



    //Paso1 :traeAccEstrategicas
    public function _getAccEstrategicasByPoiArea($idpoi,$idarea,$mesini,$mesend,$tipo){

        $idPoi=(int)$idpoi;
        $idArea=(int)$idarea;

        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status` =1 ";
        }

        if($tipo == "obs"){
            $queryEvalua="and detrespxactpre.`status` =2 ";
        }

        $joinJusty="";
        $queryJusty="";
        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado = 1 ";
        }

        if($tipo=="justifyhistory"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado > 1 ";
        }

        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }

        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";
        }

        $query="SELECT \n".
            "act.idactividad as idaccestra ,\n".
            "act.nombre as accestrategica, \n".
            "users.id as iduser ,\n".
            "users.iddependeciauser ,\n".
            "users.nombrearearesponsable \n".
            "FROM\n".
            "actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            " ".$joinJusty."\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "where act.estado=1 and actpadre.estado=1 and  detrespxactpre.estado=1 and users.`status`=1  and actvpresupuestaria.estado=1 and actpresupuestariareal.estado=1 and unidadmedida.estado=1  \n".
            "   ".$queryEvalua." ".$queryJusty."   ".$queryV."  ".$queryR."   \n".
            "and users.id=".$idArea."  \n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "and actpadre.idactividad=".$idPoi." \n".
            "GROUP BY act.idactividad ,\n".
            "users.id,\n".
            "users.iddependeciauser ,\n".
            "users.nombrearearesponsable ,\n".
            "act.nombre";

         //print_r($query);echo "ssss".$tipo;exit();

        $dataAccEstra=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataAccEstra as $dtAccEstra){
            $dataActPre=$this->_getActPreByAccEstra($idPoi,$idArea,$dtAccEstra['idaccestra'],$mesini,$mesend,$tipo);

            $count=0;
            $sum=0;
            $ga=0;
            if($tipo == "global"){
                foreach ($dataActPre as $dActPre){
                    foreach($dActPre["dataactop"] as $dActOp){
                        $count++;
                        $sum=$sum+$dActOp["gaop"];
                    }
                }
                if($count >0 && $sum >0){
                    $ga=floatval($sum/$count);
                }
            }


            $d=array(
                "accestrategica"=>$this->quitaSaltoLinea($dtAccEstra["accestrategica"]),
                "idaccestra"=>$dtAccEstra['idaccestra'],
                "nombrearearesponsable"=>$this->quitaSaltoLinea($dtAccEstra["nombrearearesponsable"]),
                "dataactpre"=>$dataActPre,
                "iduser"=>$dtAccEstra["iduser"],
                "iddependeciauser"=>$dtAccEstra["iddependeciauser"],
                "gaAccEstra"=>$ga
            );
            array_push($dataFinal,$d);
        }



        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;

    }



    // paso2: trae ActPresupuestarias
    public  function _getActPreByAccEstra($idpoi,$idarea,$idaccestra,$mesini,$mesend,$tipo){

        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status` =1";
        }

        if($tipo == "obs"){
            $queryEvalua="and detrespxactpre.`status` =2 ";
        }

        $joinJusty="";
        $queryJusty="";
        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado = 1 ";
        }
        if($tipo=="justifyhistory"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado > 1 ";
        }
        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";
        }
        $query="SELECT\n".
            "	actpresupuestariareal.id_actpresupuestariareal AS idactpre,\n".
            "	actpresupuestariareal.nombre AS actpre\n".
            "FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "WHERE\n".
            "	act.estado = 1\n".
            "AND detrespxactpre.estado = 1 ".$queryEvalua."  ".$queryJusty."  ".$queryV."  ".$queryR."  \n".
            "AND users.`status` = 1  \n".
            "AND actpadre.estado = 1 \n".
            "  ".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "AND act.idactividad = ".(int)$idaccestra."\n".
            "AND users.id = ".(int)$idarea."\n".
            "AND actpadre.idactividad = ".(int)$idpoi."\n".
            "GROUP BY\n".
            "	actpresupuestariareal.id_actpresupuestariareal  ,\n".
            "	actpresupuestariareal.nombre";
        //print_r($query);exit();
        $dataActPre=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataActPre as $dtActPre){

            $dataActOp=$this->_getActOpByActPre($idpoi,$idarea,$idaccestra,$dtActPre['idactpre'],$mesini,$mesend,$tipo);
              $d=array(
                "idactpre"=>$dtActPre['idactpre'],
                "actpre"=>$this->quitaSaltoLinea($dtActPre["actpre"]),
                "dataactop"=>$dataActOp
            );
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;

    }
    //paso3:trae Act Op
    public function _getActOpByActPre($idpoi,$idarea,$idaccestra,$idactpre,$mesini,$mesend,$tipo){
        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status` =1 ";
        }
        if($tipo == "obs"){
            $queryEvalua="and detrespxactpre.`status` =2 ";
        }
        $joinJusty="";
        $queryJusty="";

        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado =1";
        }
        if($tipo=="justifyhistory"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado > 1 ";
        }
        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";
        }
        $query="SELECT\n".
            "	actvpresupuestaria.id_actvpresupuestaria AS idactop,\n".
            "	actvpresupuestaria.nombre AS actop ,\n".
            "	unidadmedida.id_unidadmedida as idum ,\n".
            "	unidadmedida.nombre AS um\n".
            "FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "WHERE\n".
            "	act.estado = 1\n".
            "AND detrespxactpre.estado = 1 ".$queryEvalua." ".$queryJusty." ".$queryV."  ".$queryR." \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
            //"AND tareas.estado = 1\n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "AND actpresupuestariareal.id_actpresupuestariareal = ".(int)$idactpre."\n".
            "AND act.idactividad = ".(int)$idaccestra."\n".
            "AND users.id = ".(int)$idarea."\n".
            "AND actpadre.idactividad = ".(int)$idpoi."\n".
            "GROUP BY\n".
            "		actvpresupuestaria.id_actvpresupuestaria ,\n".
            "	actvpresupuestaria.nombre ,\n".
            "	unidadmedida.id_unidadmedida ,\n".
            "	unidadmedida.nombre ";
        //print_r($query);exit();
        $dataActOp=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataActOp as $dtActOp){
            //$dataTarea=$this->_getTareasByActOp($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],$mesini,$mesend,$tipo);
            $countTareas=0;
            $sumGaTareas=0;
            $gaOp=0;

            /*if($tipo != "evalua" || $tipo != "justify" || $tipo != "verificados"){
                 foreach ($dataTarea as $gaTareas){
                     $countTareas++;
                     $sumGaTareas=$sumGaTareas+$gaTareas["ga"][0]['porcenajeFinalejecAll']; //porcenajeFinalejecAll porcenajeFinal
                 }
                if($countTareas>0){
                    $gaOp=floatval($sumGaTareas/$countTareas);
                }
            }*/


            if($tipo == "global" || $tipo == "reporte"  ){
                $dataGaActOp=$this->_getGaActOp($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp["idactop"],"",$mesini,$mesend);
            }else{
                $dataGaActOp=0;
            }

            $d=array(
                "idactop"=>$dtActOp['idactop'],
                "actop"=>$this->quitaSaltoLinea($dtActOp["actop"]),
                "idum"=>$dtActOp["idum"],
                "um"=>$this->quitaSaltoLinea($dtActOp["um"]),
                "datatarea"=>"",
                "datagaop"=>$dataGaActOp,
                "gaop"=>$dataGaActOp[0]['porcenajeFinalejecAll']
            );

            if($tipo == "global" || $tipo == "evalua"  || $tipo == "justify"   || $tipo == "justifyhistory"  || $tipo == "reporte" || $tipo== "verificados" || $tipo=="obs"  ){
                $d["meses"]=$this->_getMetaEjecMesesByTarea($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],"tareas",$mesini,$mesend,$tipo);
            }
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();

        //echo "<pre>";print_r($dataFinal); exit();
        return $dataFinal;


    }


    public function _getActOpByActPre2($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$mesini,$mesend,$tipo){
        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status`in(1,2)";
        }
        $joinJusty="";
        $queryJusty="";

        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado =1 ";
        }
        if($tipo=="justifyhistory"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado > 1 ";
        }

        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";
        }
        $query="SELECT\n".
            "	actvpresupuestaria.id_actvpresupuestaria AS idactop,\n".
            "	actvpresupuestaria.nombre AS actop ,\n".
            "	unidadmedida.id_unidadmedida as idum ,\n".
            "	unidadmedida.nombre AS um\n".
            "FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "WHERE\n".
            "	act.estado = 1\n".
            "AND detrespxactpre.estado = 1 ".$queryEvalua." ".$queryJusty." ".$queryV."  ".$queryR." \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
            //"AND tareas.estado = 1\n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "AND actpresupuestariareal.id_actpresupuestariareal = ".(int)$idactpre."\n".
            "AND act.idactividad = ".(int)$idaccestra."\n".
            "AND users.id = ".(int)$idarea."\n".
            "AND actpadre.idactividad = ".(int)$idpoi."\n".
            "AND actvpresupuestaria.id_actvpresupuestaria = ".(int)$idactop."\n".
            "GROUP BY\n".
            "		actvpresupuestaria.id_actvpresupuestaria ,\n".
            "	actvpresupuestaria.nombre ,\n".
            "	unidadmedida.id_unidadmedida ,\n".
            "	unidadmedida.nombre ";
        //print_r($query);exit();
        $dataActOp=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataActOp as $dtActOp){
            //$dataTarea=$this->_getTareasByActOp($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],$mesini,$mesend,$tipo);
            $countTareas=0;
            $sumGaTareas=0;
            $gaOp=0;


            if($tipo == "global" || $tipo == "reporte"  ){
                $dataGaActOp=$this->_getGaActOp($idpoi,$idarea,$idaccestra,$idactpre,$idactop,"",$mesini,$mesend);
            }else{
                $dataGaActOp=0;
            }
            $d=array(
                "idactop"=>$dtActOp['idactop'],
                "actop"=>$this->quitaSaltoLinea($dtActOp["actop"]),
                "idum"=>$dtActOp["idum"],
                "um"=>$this->quitaSaltoLinea($dtActOp["um"]),
                "datagaop"=>$dataGaActOp,
                "gaop"=>$dataGaActOp[0]['porcenajeFinalejecAll']
            );

            if($tipo == "global" || $tipo == "evalua"  || $tipo == "justify" || $tipo == "reporte" || $tipo== "verificados"  ){
                $d["meses"]=$this->_getMetaEjecMesesByTarea($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],"tareas",$mesini,$mesend,$tipo);
            }
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;


    }
    //Paso 4::trae tareas
    public function _getTareasByActOp($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$mesini,$mesend,$tipo){
        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status`in(1,2)";
        }
        $joinJusty="";
        $queryJusty="";

        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado <> 0 ";
        }
        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";// para filtrar las tareas sin meta
        }

        $query="SELECT\n".
            "	tareas.idtarea ,\n".
            "	tareas.nombre AS tarea,\n".
            "	unidadmedida.id_unidadmedida ,\n".
            "	unidadmedida.nombre AS um\n".
            "FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "WHERE\n".
            "	act.estado = 1\n".
            "AND detrespxactpre.estado = 1  ".$queryEvalua."  ".$queryJusty."  ".$queryV."  ".$queryR." \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
            "AND tareas.estado = 1\n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "AND actvpresupuestaria.id_actvpresupuestaria = ".(int)$idactop."\n".
            "AND actpresupuestariareal.id_actpresupuestariareal = ".(int)$idactpre."\n".
            "AND act.idactividad = ".(int)$idaccestra."\n".
            "AND users.id = ".(int)$idarea."\n".
            "AND actpadre.idactividad = ".(int)$idpoi."\n".
            "GROUP BY\n".
            "	tareas.idtarea ,\n".
            "	tareas.nombre ";
        //print_r($query);exit();
        $dataTareas=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataTareas as $dtTareas){
            $dataGATarea=0;
            if($tipo == "global" || $tipo == "reporte"  ){
                $dataGATarea=$this->_getGATarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$dtTareas['idtarea'],$mesini,$mesend);
            }

            $d=array(
                "idtarea"=>$dtTareas['idtarea'],
                "tarea"=>$this->quitaSaltoLinea($dtTareas["tarea"]),
                "idum"=>$dtTareas['id_unidadmedida'],
                "um"=>$this->quitaSaltoLinea($dtTareas["um"]),
                "ga"=>$dataGATarea
            );
            if($tipo == "global" || $tipo == "evalua"  || $tipo == "justify" || $tipo == "reporte" || $tipo== "verificados"  ){
                $d["meses"]=$this->_getMetaEjecMesesByTarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$dtTareas['idtarea'],$mesini,$mesend,$tipo);
            }
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;
    }
    // paso 5 Trae Grado Avance Tareas
    public function _getGATarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$idtarea,$mesini,$mesend){
        $query2="Select \n".
            "sum(ejecutado) as ejecAll,\n".
            "sum(ejecutadoreal) as ejecParse ,\n".
            "(Sum(porcentual.por)/sum(cantidad) ) as ActPorcentual,\n".
            "sum(meta) as metax ,\n".
            "if((sum(ejecutado)/sum(meta)) >= 1 ,1,(sum(ejecutado)/sum(meta)) )as porcenajeFinalejecAll,\n".
            "if((sum(ejecutadoreal)/sum(meta)) >= 1 ,1,(sum(ejecutadoreal)/sum(meta)) )as porcenajeFinal\n".
            "from (SELECT\n".
            "			detrespxactpre.ejecutado,\n".
            "			if(detrespxactpre.ejecutado > detrespxactpre.meta ,detrespxactpre.meta,detrespxactpre.ejecutado ) as ejecutadoreal,\n".
            "			detrespxactpre.meta,\n".
            "			if(detrespxactpre.ejecutado/detrespxactpre.meta >= 1 ,1 ,detrespxactpre.ejecutado/detrespxactpre.meta ) as por,\n".
            "			1 as cantidad,\n".
            "			act.idactividad\n".
            "			FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "WHERE\n".
            "act.estado = 1\n".
            "and actpadre.estado = 1\n".
            "AND detrespxactpre.estado = 1 \n".
            //"AND detrespxactpre.status = 10 \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
            "AND tareas.estado = 1\n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "and  act.idactividad=".$idaccestra." \n".
            "and detrespxactpre.id_actpresupuestariareal=".$idactpre." \n".
            "and   detrespxactpre.id_actvpresupuestaria=".$idactop." \n".
            " and  detrespxactpre.idtarea=".$idtarea."  \n".
            " and detrespxactpre.id_user=".$idarea." \n".
            " and  detrespxactpre.meta <> 0  \n".
            " and detrespxactpre.estado=1 \n".
            " and users.role=18  ) as porcentual";
        $res=$this->db->query($query2)->result_array();
        return $res;

    }

    public function _getGaActOp($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$idtarea,$mesini,$mesend){
        $query2="Select \n".
            "sum(ejecutado) as ejecAll,\n".
            "sum(ejecutadoreal) as ejecParse ,\n".
            "(Sum(porcentual.por)/sum(cantidad) ) as ActPorcentual,\n".
            "sum(meta) as metax ,\n".
            "if((sum(ejecutado)/sum(meta)) >= 1 ,1,(sum(ejecutado)/sum(meta)) )as porcenajeFinalejecAll,\n".
            "if((sum(ejecutadoreal)/sum(meta)) >= 1 ,1,(sum(ejecutadoreal)/sum(meta)) )as porcenajeFinal\n".
            "from (SELECT\n".
            "			detrespxactpre.ejecutado,\n".
            "			if(detrespxactpre.ejecutado > detrespxactpre.meta ,detrespxactpre.meta,detrespxactpre.ejecutado ) as ejecutadoreal,\n".
            "			detrespxactpre.meta,\n".
            "			if(detrespxactpre.ejecutado/detrespxactpre.meta >= 1 ,1 ,detrespxactpre.ejecutado/detrespxactpre.meta ) as por,\n".
            "			1 as cantidad,\n".
            "			act.idactividad\n".
            "			FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "WHERE\n".
            "act.estado = 1\n".
            "and actpadre.estado = 1\n".
            "AND detrespxactpre.estado = 1 \n".
            //"AND detrespxactpre.status = 10 \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
           // "AND tareas.estado = 1\n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "and  act.idactividad=".$idaccestra." \n".
            "and detrespxactpre.id_actpresupuestariareal=".$idactpre." \n".
            "and   detrespxactpre.id_actvpresupuestaria=".$idactop." \n".
            //" and  detrespxactpre.idtarea=".$idtarea."  \n".
            " and detrespxactpre.id_user=".$idarea." \n".
            " and  detrespxactpre.meta <> 0  \n".
            " and detrespxactpre.estado=1 \n".
            " and users.role=18  ) as porcentual";
        $res=$this->db->query($query2)->result_array();
        return $res;

    }

    public function _getMetaEjecMesesByTarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$idtarea,$mesini,$mesend,$tipo){
        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status` = 1 ";
        }
        if($tipo == "obs"){
            $queryEvalua="and detrespxactpre.`status` =2 ";
        }
        $joinJusty="";
        $queryJusty="";
        $queryReporte="";
        if($tipo == "reporte" ){
            $queryReporte="and (detrespxactpre.id_tiempo >=".(int)$mesini." and detrespxactpre.id_tiempo <=".(int)$mesend.") ";
        }
        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado = 1  ";
        }
        if($tipo=="justifyhistory"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado > 1 ";
        }

        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $query="SELECT \n".
            "detrespxactpre.id_detrespxactpre,\n".
            "            detrespxactpre.meta,\n".
            "            detrespxactpre.ejecutado,\n".
            "            detrespxactpre.ejecutadosupuesto,\n".
            "            detrespxactpre.`status`,\n".
            "            detrespxactpre.isJusty,\n".
            "            detrespxactpre.isObs,\n".
            "            detrespxactpre.id_tiempo\n".
            "FROM\n".
            "actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "where act.estado=1 and  detrespxactpre.estado=1 and users.`status`=1  and actpadre.estado=1\n".
            "  ".$queryJusty."  \n".
            "and  act.idactividad=".$idaccestra."  ".$queryEvalua."  ".$queryReporte."  ".$queryV."    \n".
            "and detrespxactpre.id_actpresupuestariareal=".$idactpre." \n".
            "and   detrespxactpre.id_actvpresupuestaria=".$idactop." \n".
            //" and  detrespxactpre.idtarea=".$idtarea."  \n".
            " and detrespxactpre.id_user=".$idarea." \n".
            "GROUP BY detrespxactpre.id_detrespxactpre,\n".
            "            detrespxactpre.meta,\n".
            "            detrespxactpre.ejecutado,\n".
            "            detrespxactpre.ejecutadosupuesto,\n".
            "            detrespxactpre.`status`,\n".
            "            detrespxactpre.isJusty,\n".
            "            detrespxactpre.isObs,\n".
            "            detrespxactpre.id_tiempo  ";
        //echo "<pre>";print_r($query);exit();
        $dataMeses=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataMeses as $dtMeses){
            $dataPlazo="";
            $dataJustify="";
            if($tipo == "global" ){
                $dataPlazo=$this->_getStatusPlazo($idaccestra,$idactpre,$idactop,$idtarea,$idarea,$dtMeses['id_tiempo']);
            }
            if($tipo == "reporte" ){
               $dataJustify=$this->_getDataJustify($dtMeses['id_detrespxactpre'],$tipo);
            }

            $d=array(
                "id_detrespxactpre"=>$dtMeses['id_detrespxactpre'],
                "meta"=>$dtMeses['meta'],
                "ejecutado"=>$dtMeses['ejecutado'],
                "ejecutadosupuesto"=>$dtMeses['ejecutadosupuesto'],
                "status"=>$dtMeses["status"],
                "id_tiempo"=>$dtMeses['id_tiempo'],
                "isJusty"=>$dtMeses['isJusty'],
                "isObs"=>$dtMeses['isObs'],
                "plazo"=>$dataPlazo,
                "datajustify"=>$dataJustify
            );


            array_push($dataFinal,$d);
        }

        return $dataFinal;

    }







    public function _getActPreforArea($idareResp,$idpoi){// devuelve Actividades presupuestarias
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
            "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea  \n".
            "where users.`status`=1 and users.role=18  and detrespxactpre.estado=1 and actividades.estado=1 and actividades.idpadrepadre=".$idpoi." \n".
            "and users.id=".$idareResp." \n".
            "GROUP BY actividades.nombre,\n".
            "users.nombrearearesponsable,\n".
            "actpresupuestariareal.nombre,\n".
            "detrespxactpre.id_actpresupuestariareal,actividades.idactividad";
        $result=$this->db->query($query)->result_array();
        return $result;
    }

    public function _getActOpForActPre($idPoi,$idact,$idAreaR,$id_actpresupuestariareal,$mIni,$mEnd){
        $query="SELECT\n".
            "detrespxactpre.id_actvpresupuestaria,\n".
            "actvpresupuestaria.nombre\n".
            "FROM\n".
            "detrespxactpre\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea  \n".
            "where actividades.estado=1 and users.`status`=1 and users.role=18 and detrespxactpre.id_actpresupuestariareal=".$id_actpresupuestariareal."   and detrespxactpre.estado=1 and actividades.idpadrepadre=".$idPoi."\n".
            "and users.id=".$idAreaR." and actividades.idactividad=".$idact."  \n".
            "GROUP BY detrespxactpre.id_actvpresupuestaria,\n".
            "actvpresupuestaria.nombre";

        $result=$this->db->query($query)->result_array();
        $data=array();
        foreach ($result as $resActOp){
            $dataTarea=$this->_getTareasForActOp($idPoi,$idact,$idAreaR,$id_actpresupuestariareal,$resActOp['id_actvpresupuestaria'],$mIni,$mEnd);

            // $meses=$this->_getMesesMetaEject($idact,$id_actpresupuestariareal,$resActOp['id_actvpresupuestaria'],$idAreaR);
            $d=array("id_actvpresupuestaria"=>$resActOp["id_actvpresupuestaria"],
                "nameactvop"=>$this->quitaSaltoLinea($resActOp["nombre"]),
                "tareas"=>$dataTarea
               // "meses"=>$meses
            );

            array_push($data,$d);
        }

        return $data;

    }

    public function _getTareasForActOp($idPoi,$idact,$idAreaR,$id_actpresupuestariareal,$idActOp,$mIni,$mEnd){
        $query="SELECT\n".
            "detrespxactpre.idtarea,\n".
            "tareas.nombre as tarea,\n".
            "unidadmedida.nombre as um \n".
            "FROM\n".
            "detrespxactpre\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea  \n".
            "where actividades.estado=1 and users.`status`=1 and users.role=18 and detrespxactpre.id_actpresupuestariareal=".$id_actpresupuestariareal."   and detrespxactpre.estado=1 and actividades.idpadrepadre=".$idPoi."\n".
            "and users.id=".$idAreaR." and actividades.idactividad=".$idact."  and detrespxactpre.id_actvpresupuestaria=".$idActOp."     \n".
            "GROUP BY detrespxactpre.idtarea,\n".
            "tareas.nombre,\n".
            "unidadmedida.nombre";
        $result=$this->db->query($query)->result_array();
        $data=array();
        foreach ($result as $resTarea){
            $Ga=$this->_getGaForTarea($idact,$idAreaR,$id_actpresupuestariareal,$idActOp,$resTarea['idtarea'],$mIni,$mEnd);
            // $meses=$this->_getMesesMetaEject($idact,$id_actpresupuestariareal,$resActOp['id_actvpresupuestaria'],$idAreaR);
            $d=array("idtarea"=>$resTarea["idtarea"],
                "nametarea"=>$this->quitaSaltoLinea($resTarea["tarea"]),
                "um"=>$resTarea["um"],
                "ga"=>$Ga
                // "meses"=>$meses
            );

            array_push($data,$d);
        }

        return $data;

    }


    public function _getGaForActPre($IdAct,$IdAreaResp,$IdActpreReal,$IdActpre,$idtarea,$timeIni,$timeEnd){
        $idAct=(int)$IdAct;
        $idAreaResp=(int)$IdAreaResp;
        $idActPre=(int)$IdActpre;
        $idActPreReal=(int)$IdActpreReal;
        $idTarea=(int)$idtarea;
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
            "			where detrespxactpre.id_tiempo BETWEEN ".$timeIni." and ".$timeEnd." and  actividades.idactividad=".$idAct." and detrespxactpre.id_actpresupuestariareal=".$idActPreReal." and   detrespxactpre.id_actvpresupuestaria=".$idActPre."  and  detrespxactpre.idtarea=".$idTarea."  and detrespxactpre.id_user=".$idAreaResp." and  detrespxactpre.meta <> 0  and detrespxactpre.estado=1 \n".
            "			and actividades.estado=1 and users.role=18  ) as porcentual";
        $res=$this->db->query($query2)->result_array();
        return $res;

    }



    public function _getGaForTarea($IdAct,$IdAreaResp,$IdActpreReal,$IdActpre,$idtarea,$timeIni,$timeEnd){
        $idAct=(int)$IdAct;
        $idAreaResp=(int)$IdAreaResp;
        $idActPre=(int)$IdActpre;
        $idActPreReal=(int)$IdActpreReal;
        $idTarea=(int)$idtarea;
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
            "			where detrespxactpre.id_tiempo BETWEEN ".$timeIni." and ".$timeEnd." and  actividades.idactividad=".$idAct." and detrespxactpre.id_actpresupuestariareal=".$idActPreReal." and   detrespxactpre.id_actvpresupuestaria=".$idActPre."  and  detrespxactpre.idtarea=".$idTarea."  and detrespxactpre.id_user=".$idAreaResp." and  detrespxactpre.meta <> 0  and detrespxactpre.estado=1 \n".
            "			and actividades.estado=1 and users.role=18  ) as porcentual";
        $res=$this->db->query($query2)->result_array();
        return $res;
    }

    public function test(){
        return "hola";
    }

    public function getAreasbyIdpoi($idpoi){
        $idPoi=(int)$idpoi;
        $res=array();
        if($idPoi > 0){
            $queryAntesdeTarea="SELECT \n".
                "users.nombrearearesponsable,\n".
                "detrespxactpre.id_user\n".
                "FROM\n".
                "detrespxactpre\n".
                "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
                "INNER JOIN actividades AS actpoi ON actividades.idpadrepadre = actpoi.idactividad\n".
                "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
                "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
                "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
                "INNER JOIN tiempo ON tiempo.id_tiempo = detrespxactpre.id_tiempo\n".
                "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
                "where \n".
                "actpoi.estado=1 and actividades.estado=1 and detrespxactpre.estado=1\n".
                "and actpresupuestariareal.estado=1 and actvpresupuestaria.estado=1 and users.`status`=1\n".
                "and unidadmedida.estado=1\n".
                "and actpoi.idactividad=".$idPoi."   \n".
                "GROUP BY users.nombrearearesponsable,\n".
                "detrespxactpre.id_user ";

            $query="SELECT \n".
                "users.nombrearearesponsable,\n".
                "users.iddependeciauser ,\n".
                "detrespxactpre.id_user \n".
                "FROM\n".
                "detrespxactpre\n".
                "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
                "INNER JOIN actividades AS actpoi ON actividades.idpadrepadre = actpoi.idactividad\n".
                "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
                "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
                "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
                "INNER JOIN tiempo ON tiempo.id_tiempo = detrespxactpre.id_tiempo\n".
                "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
               // " INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea \n".
                "where \n".
                "actpoi.estado=1 and actividades.estado=1 and detrespxactpre.estado=1\n".
                "and actpresupuestariareal.estado=1 and actvpresupuestaria.estado=1 and users.`status`=1\n".
                "and unidadmedida.estado=1\n".
                "and actpoi.idactividad=".$idPoi."   \n".
                "GROUP BY users.nombrearearesponsable,\n".
                "users.iddependeciauser ,\n".
                "detrespxactpre.id_user order by users.nombrearearesponsable asc ";
            $res=$this->db->query($query)->result_array();
        }
        return $res;
    }

    public function reportEvaluacionPoi($idpoi,$idArea,$mesIni,$mesEnd,$tipo){
        $data=array();
        if($idArea == "evaluacion"){ // si envia evaluacion general
            $dataAreas=$this->getAreasbyIdpoi($idpoi);

            foreach ($dataAreas as $dtArea){
               $dataAreaAcc=$this->getDetalleAreabyPoi($idpoi,$dtArea["id_user"],$mesIni,$mesEnd,$tipo);

                if(sizeof($dataAreaAcc["dataArea"]) > 0){
                   $d=array("id_user"=>$dtArea["id_user"],
                       "iddependeciauser"=>$dtArea["iddependeciauser"],
                       "nombrearearesponsable"=>$this->quitaSaltoLinea($dtArea["nombrearearesponsable"]),
                       "DataArea"=>$dataAreaAcc,
                       "filename"=>"Reporte global areas"
                   );
                }else{
                    $d=array("id_user"=>null,
                        "iddependeciauser"=>null,
                        "nombrearearesponsable"=>null,
                        "DataArea"=>null,
                        "filename"=>"Reporte global areas"
                    );
                }
               array_push($data,$d);
            }
        }else{// si se envia evaluacuonde una sola area
            $dataAreaAcc=$this->getDetalleAreabyPoi($idpoi,$idArea,$mesIni,$mesEnd,$tipo);
            //print_r();exit();

            if(sizeof($dataAreaAcc["dataArea"]) > 0){
                $d=array("id_user"=>$idArea,
                    "iddependeciauser"=>$dataAreaAcc["dataArea"][0]["iddependeciauser"],
                    "nombrearearesponsable"=>$this->quitaSaltoLinea($dataAreaAcc["dataArea"][0]["nombrearearesponsable"]),
                    "DataArea"=>$dataAreaAcc,
                    "filename"=>$this->quitaSaltoLinea($dataAreaAcc["dataArea"][0]["nombrearearesponsable"])
                );
            }else{
                $d=array("id_user"=>null,
                    "nombrearearesponsable"=>null,
                    "iddependeciauser"=>null,
                    "DataArea"=>null,
                    "filename"=>"archivo"
                );
            }

            array_push($data,$d);
        }


        return $data ;

    }


    public function _getMesesMetaEject($idact,$idActPreReal,$idActPre,$idUser){
        $query="SELECT\n".
            "detrespxactpre.id_detrespxactpre,\n".
            "detrespxactpre.meta,\n".
            "detrespxactpre.ejecutado,\n".
            "detrespxactpre.ejecutadosupuesto,\n".
            "detrespxactpre.`status`,\n".
            "detrespxactpre.id_tiempo\n".
            "FROM\n".
            "detrespxactpre\n".
            "where detrespxactpre.id_actpresupuestariareal=".$idActPreReal." and detrespxactpre.estado=1 and detrespxactpre.idactividad=".$idact." and detrespxactpre.id_actvpresupuestaria=".$idActPre." and detrespxactpre.id_user=".$idUser;
        $result=$this->db->query($query)->result_array();
        $data=array();
        foreach ($result as $res){
            $dataPlazo=$this->_getStatusPlazo($idact,$idActPreReal,$idActPre,"",$idUser,$res["id_tiempo"]);
            if(sizeof($dataPlazo > 0 )){// para mostrar soolo los que no estan verificados
                $d=array(
                    "id_detrespxactpre"=>$res["id_detrespxactpre"],
                    "id_tiempo"=>$res["id_tiempo"],
                    "meta"=>$res["meta"],
                    "ejecutado"=>$res["ejecutado"],
                    "ejecutadosupuesto"=>$res["ejecutadosupuesto"],
                    "status"=>$res["status"],
                    "plazo"=> $dataPlazo
                );
            }
            array_push($data,$d);
        }
        return $data;
    }

    
    public function _getStatusPlazo($idact,$idActPreReal,$idActPre,$idTarea,$idUser,$id_tiempo){
        $this->db->select('*');
        $this->db->from("configuracion");
        $this->db->where(["estado"=>1,"tipo"=>"plazo"]);
        $plazo=$this->db->get()->result_array();
        $plazoTime=(int)$plazo[0]["valor"];
        $query="select\n".
            "*	,\n".
            "if( actpreUser.diasmeta > actpreUser.diaactualanio,'1','0'   ) as estadoplazobol,\n".
            "if( actpreUser.diasmeta > actpreUser.diaactualanio,'enplazo','Vencio plazo'   ) as estadoplazo\n".
            "	\n".
            " from( SELECT\n".
            "DAYOFYEAR(concat(actpoi.periodo,'-',detrespxactpre.id_tiempo,'-',DAYOFMONTH(LAST_DAY(concat(actpoi.periodo,'-',detrespxactpre.id_tiempo,'-','1') ) )     )) AS diasmeta,\n".
            "DAYOFYEAR(CURDATE()) AS diaactualanio,\n".
            "detrespxactpre.id_actvpresupuestaria,\n".
            "detrespxactpre.id_detrespxactpre,\n".
            "detrespxactpre.ejecutado,\n".
            "detrespxactpre.ejecutadosupuesto,\n".
            "detrespxactpre.meta,\n".
            "detrespxactpre.id_tiempo\n".
            "FROM\n".
            "actividades AS actpoi\n".
            "INNER JOIN actividades AS act ON actpoi.idactividad = act.idpadrepadre\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
           // "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea \n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "where detrespxactpre.`status`<> 10  and detrespxactpre.meta > 0  and act.estado =1  and detrespxactpre.estado=1 \n".
            " and detrespxactpre.idactividad=".$idact."  and detrespxactpre.id_actvpresupuestaria=".$idActPre." \n".
            " and detrespxactpre.id_tiempo=".$id_tiempo." and `users`.id=".$idUser." \n".
            " and detrespxactpre.id_actpresupuestariareal=".$idActPreReal." \n".
            //" and detrespxactpre.idtarea=".$idTarea." \n".
            ") as actpreUser where if( actpreUser.diasmeta + ".$plazoTime."  > actpreUser.diaactualanio,'enplazo','vencioplazo'   ) = 'vencioplazo'";

        $result=$this->db->query($query)->result_array();
        return $result;
    }


    public function _getDataJustify($iddet,$tipo="global"){
        /*$this->db->select('*');
        $this->db->from("justificaciones");
        $this->db->where(["estado"=>1,"id_detrespxactpre"=>(int)$iddet]);
        $this->db->order_by('id_detrespxactpre', 'asc');
        $data=$this->db->get()->result_array();*/
        $sql="";
        if($tipo=="reporte"){
            $sql="and justificaciones.estado =2";
        }
        if($tipo=="global"){
            $sql="and justificaciones.estado =2";
        }
        $query="SELECT\n".
            "justificaciones.motivo,\n".
            "justificaciones.id_justy,\n".
            "justificaciones.id_detrespxactpre,\n".
            "justificaciones.tipojusty,\n".
            "justificaciones.file_name\n".
            "FROM\n".
            "detrespxactpre\n".
            "INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre\n".
            "where detrespxactpre.meta > 0 and detrespxactpre.estado <> 0  ".$sql." 
             and detrespxactpre.id_detrespxactpre=".(int)$iddet;
       $data=$this->db->query($query)->result_array();
        return $data;
    }



    public function getActPreAndActOpbyIdArea($idPoi,$idArea){
        $idPOI=(int)$idPoi;
        $idAREA=(int)$idArea;
        if($idPOI >0 and $idAREA >0){
                $query="SELECT\n".
                    "detrespxactpre.id_user,\n".
                    "users.nombrearearesponsable,\n".
                    "detrespxactpre.idactividad ,\n".
                    "actividades.nombre as accestrategica,\n".
                    "actpresupuestariareal.nombre AS actpre,\n".
                    "actvpresupuestaria.nombre AS actop,\n".
                    "detrespxactpre.id_actpresupuestariareal,\n".
                    "detrespxactpre.id_actvpresupuestaria  ,\n".
                    "unidadmedida.nombre as um ,\n ".
                    "tareas.idtarea,\n".
                    "tareas.nombre as tarea ,\n".
                    "unidadmedida.id_unidadmedida\n ".
                    "FROM\n".
                    "detrespxactpre\n".
                    "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n".
                    "INNER JOIN actividades AS actpoi ON actividades.idpadrepadre = actpoi.idactividad\n".
                    "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
                    "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
                    "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
                    "INNER JOIN tiempo ON tiempo.id_tiempo = detrespxactpre.id_tiempo\n".
                    "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea \n".
                    "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
                    "where \n".
                    "actpoi.estado=1 and actividades.estado=1 and detrespxactpre.estado=1\n".
                    "and actpresupuestariareal.estado=1 and actvpresupuestaria.estado=1 and users.`status`=1\n".
                    "and unidadmedida.estado=1\n".
                    "and actpoi.idactividad=".$idPOI." and users.id=".$idAREA."\n".
                    "GROUP BY detrespxactpre.id_user,\n".
                    "users.nombrearearesponsable,\n".
                    "detrespxactpre.idactividad ,\n".
                    "actividades.nombre,\n".
                    "actpresupuestariareal.nombre,\n".
                    "actvpresupuestaria.nombre,\n".
                    "unidadmedida.id_unidadmedida ,\n ".
                    "detrespxactpre.id_actpresupuestariareal,\n".
                    "detrespxactpre.id_actvpresupuestaria";
            $resActpreActOp=$this->db->query($query)->result_array();
            $data=array();
            foreach ($resActpreActOp as $rActPreActOp){
                $meses=$this->getDataMesesForActPreAndActOp($idPOI,$rActPreActOp["idactividad"],$idAREA,$rActPreActOp["id_actpresupuestariareal"],$rActPreActOp["id_actvpresupuestaria"],$rActPreActOp["idtarea"]);
                $Ga=$this->_getGaForActPre($rActPreActOp["idactividad"],$idAREA,$rActPreActOp["id_actpresupuestariareal"],$rActPreActOp["id_actvpresupuestaria"],$rActPreActOp["idtarea"],1,12);//mes 1-12


                $d=array(
                    "id_user"=>$rActPreActOp["id_user"],
                    "nombrearearesponsable"=>$this->quitaSaltoLinea($rActPreActOp["nombrearearesponsable"]),
                    "idactividad"=>$rActPreActOp["idactividad"],
                    "accestrategica"=>$this->quitaSaltoLinea($rActPreActOp["accestrategica"]),
                    "actpre"=>$this->quitaSaltoLinea($rActPreActOp["actpre"]),
                    //"actop"=> str_replace( "\n", ' ', $rActPreActOp["actop"]),
                    "actop"=> $this->quitaSaltoLinea($rActPreActOp["actop"]),
                    "tarea"=> $this->quitaSaltoLinea($rActPreActOp["tarea"]),
                    "id_actpresupuestariareal"=>$rActPreActOp["id_actpresupuestariareal"],
                    "id_actvpresupuestaria"=>$rActPreActOp["id_actvpresupuestaria"],
                    "idtarea"=>$rActPreActOp["idtarea"],
                    "um"=>$this->quitaSaltoLinea($rActPreActOp["um"]),
                    "id_unidadmedida"=>$rActPreActOp["id_unidadmedida"],
                    "meses"=>$meses,
                    "Ga"=>$Ga
                );
                array_push($data,$d);
            }

        }else{
            $data["status"]="error";
        }

        return $data;
    }


    public function getDataMesesForActPreAndActOp($idpoi,$idacce,$idarea,$idactpre,$idactop,$idtarea){
        $idpoiX=(int)$idpoi;
        $idacceX=(int)$idacce;
        $idareaX=(int)$idarea;
        $idactpreX=(int)$idactpre;
        $idactopX=(int)$idactop;
        $idtareaX=(int)$idtarea;
        if($idpoiX > 0 and $idacceX>0 and $idareaX>0 and $idactpreX >0  and $idactopX>0  ) {
            $query = "SELECT\n" .
                "detrespxactpre.id_detrespxactpre,\n" .
                "detrespxactpre.ejecutado,\n" .
                "detrespxactpre.ejecutadosupuesto,\n" .
                "detrespxactpre.meta,\n" .
                "detrespxactpre.`status`,\n" .
                "detrespxactpre.id_tiempo\n" .
                "FROM\n" .
                "detrespxactpre\n" .
                "INNER JOIN actividades ON actividades.idactividad = detrespxactpre.idactividad\n" .
                "INNER JOIN actividades AS actpoi ON actividades.idpadrepadre = actpoi.idactividad\n" .
                "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n" .
                "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n" .
                "INNER JOIN users ON users.id = detrespxactpre.id_user\n" .
                "INNER JOIN tiempo ON tiempo.id_tiempo = detrespxactpre.id_tiempo\n" .
                "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea \n".
                "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n" .
                "where \n" .
                "actpoi.estado=1 and actividades.estado=1 and detrespxactpre.estado=1\n" .
                "and actpresupuestariareal.estado=1 and actvpresupuestaria.estado=1 and users.`status`=1\n" .
                "and unidadmedida.estado=1\n" .
                "and tareas.idtarea=".$idtareaX." and actpoi.idactividad=".$idpoiX." and detrespxactpre.idactividad=".$idacceX." and users.id=".$idareaX." and  detrespxactpre.id_actpresupuestariareal=".$idactpreX." and  detrespxactpre.id_actvpresupuestaria=".$idactopX."\n" .
                "ORDER BY detrespxactpre.id_tiempo Asc  ";

            $result = $this->db->query($query)->result_array();
            $data=array();
            foreach ($result as $res){
                $dataPlazo=$this->_getStatusPlazo($idacceX,$idactpreX,$idactopX,$idtareaX,$idareaX,$res["id_tiempo"]);
                if(sizeof($dataPlazo > 0 )){// para mostrar soolo los que no estan verificados
                    $d=array(
                        "id_detrespxactpre"=>$res["id_detrespxactpre"],
                        "id_tiempo"=>$res["id_tiempo"],
                        "meta"=>$res["meta"],
                        "ejecutado"=>$res["ejecutado"],
                        "ejecutadosupuesto"=>$res["ejecutadosupuesto"],
                        "status"=>$res["status"],
                        "plazo"=> $dataPlazo
                    );
                }
                array_push($data,$d);
            }
            return $data;
        }else{
            $result["error"]="xxx";
        }
        return $result;
    }

    public function getPlazoEntregaMedios($isjson=0){

        $this->db->select('*');
        $this->db->from("configuracion");
        $this->db->where(["estado"=>1,"tipo"=>"plazo"]);
        $plazo=$this->db->get()->result_array();
        if($isjson == 1){
            $plazoTime=$plazo;
        }else{
            $plazoTime=(int)$plazo[0]["valor"];
        }

        return $plazoTime;
    }

    public function getPlazoEntregaMediosforUser($isjson=0){

        $this->db->select('*');
        $this->db->from("configuracion");
        $this->db->where(["estado"=>1,"tipo"=>"plazo"]);
        $plazo=$this->db->get()->result_array();
        if($isjson == 1){
            $plazoTime=$plazo;
        }else{
            $plazoTime=(int)$plazo[0]["valormuestra"];
        }

        return $plazoTime;
    }
    /// datos fuera de plazo especial


    public function getDetalleAreabyPoi_Plazo($idpoi=null,$idArea=null,$mesIni=null,$mesEnd=null,$tipo=null){
        $dataFinal=array();
        $GafinalArea=0;
        if( $idpoi !=null && $idArea != null ){
            $idPoi=(int)$idpoi;
            $idAreaR=(int)$idArea;
            $mesInix=(int)$mesIni;
            $mesEndx=(int)$mesEnd;
            $this->db->select('*');
            $this->db->from("configuracion");
            $this->db->where(["estado"=>1,"tipo"=>"plazo"]);
            $plazo=$this->db->get()->result_array();
            $plazoTime=(int)$plazo[0]["valor"];
            $dataFinal=$this->_getAccEstrategicasByPoiArea_Plazo($idPoi,$idAreaR,$mesInix,$mesEndx,$tipo,$plazoTime);
            $GafinalArea=0;
        }
        $dataFi=array("GaArea"=> $GafinalArea ,"dataArea"=>$dataFinal);
        return $dataFi;
    }
        //paso1 :acciones estrategicas
    public function _getAccEstrategicasByPoiArea_Plazo($idPoi,$idAreaR,$mesIni,$mesEnd,$tipo,$plazotime){

        $query="SELECT\n".
            "	estadoplazo.nombrearearesponsable,\n".
            "	estadoplazo.id_user as iduser,\n".
            "	estadoplazo.accestrategica,\n".
            "	estadoplazo.idaccestra\n".
            "FROM\n".
            "	(\n".
            "		SELECT\n".
            "			actpadre.periodo,\n".
            "			act.idactividad,\n".
            "			detrespxactpre.id_tiempo,\n".
            "			DAYOFYEAR(\n".
            "				concat(\n".
            "					actpadre.periodo,\n".
            "					'-',\n".
            "					detrespxactpre.id_tiempo,\n".
            "					'-',\n".
            "					DAYOFMONTH(\n".
            "						LAST_DAY(\n".
            "							concat(\n".
            "								actpadre.periodo,\n".
            "								'-',\n".
            "								detrespxactpre.id_tiempo,\n".
            "								'-',\n".
            "								'1'\n".
            "							)\n".
            "						)\n".
            "					)\n".
            "				)\n".
            "			) AS diasmeta,\n".
            "			DAYOFYEAR(CURDATE()) AS diaanio,\n".
            "			CURDATE() AS diaactual,\n".
            "			DAYOFMONTH(LAST_DAY(CURDATE())) AS diames,\n".
            "			users.nombrearearesponsable,\n".
            "			detrespxactpre.id_user,\n".
            "			act.nombre AS accestrategica,\n".
            "			act.idactividad AS idaccestra\n".
            "		FROM\n".
            "			actividades AS act\n".
            "		INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "		INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "		INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "		INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "		INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "		INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
           // "		INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "		WHERE\n".
            "			detrespxactpre.`status` <> 10\n".
            "		AND detrespxactpre.estado = 1\n".
            "		AND detrespxactpre.meta > 0\n".
            "		AND act.estado = 1\n".
            "		AND actpadre.idactividad = ".$idPoi." \n".
            "		AND users.id = ".$idAreaR." \n".
            "	) AS estadoplazo\n".
            "WHERE\n".
            "IF (\n".
            "	estadoplazo.diasmeta + ".$plazotime." > estadoplazo.diaanio,\n".
            "	'enplazo',\n".
            "	'vencioplazo'\n".
            ") = 'vencioplazo'\n".
            "GROUP BY\n".
            "	estadoplazo.nombrearearesponsable,\n".
            "	estadoplazo.id_user,\n".
            "	estadoplazo.accestrategica,\n".
            "	estadoplazo.idaccestra";

        $dataAccEstra=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataAccEstra as $dtAccEstra){
            $dataActPre=$this->_getActPreByAccEstra_plazo($idPoi,$idAreaR,$dtAccEstra['idaccestra'],$mesIni,$mesEnd,$tipo,$plazotime);
            $d=array(
                "accestrategica"=>$this->quitaSaltoLinea($dtAccEstra["accestrategica"]),
                "idaccestra"=>$dtAccEstra['idaccestra'],
                "nombrearearesponsable"=>$this->quitaSaltoLinea($dtAccEstra["nombrearearesponsable"]),
                "dataactpre"=>$dataActPre,
                "iduser"=>$dtAccEstra["iduser"]
            );
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;
    }
    //paso2 Actpresupues
    public  function _getActPreByAccEstra_plazo($idpoi,$idarea,$idaccestra,$mesini,$mesend,$tipo,$plazotime){
         
        $query="SELECT\n".
            "	 estadoplazo.idactpre,\n".
            "	estadoplazo.actpre\n".
            "FROM\n".
            "	(\n".
            "		SELECT\n".
            "			actpadre.periodo,\n".
            "			act.idactividad,\n".
            "			detrespxactpre.id_tiempo,\n".
            "			DAYOFYEAR(\n".
            "				concat(\n".
            "					actpadre.periodo,\n".
            "					'-',\n".
            "					detrespxactpre.id_tiempo,\n".
            "					'-',\n".
            "					DAYOFMONTH(\n".
            "						LAST_DAY(\n".
            "							concat(\n".
            "								actpadre.periodo,\n".
            "								'-',\n".
            "								detrespxactpre.id_tiempo,\n".
            "								'-',\n".
            "								'1'\n".
            "							)\n".
            "						)\n".
            "					)\n".
            "				)\n".
            "			) AS diasmeta,\n".
            "			DAYOFYEAR(CURDATE()) AS diaanio,\n".
            "			CURDATE() AS diaactual,\n".
            "			DAYOFMONTH(LAST_DAY(CURDATE())) AS diames,\n".
            "			actpresupuestariareal.nombre as actpre,\n".
            "			actpresupuestariareal.id_actpresupuestariareal as idactpre\n".
            "		FROM\n".
            "			actividades AS act\n".
            "		INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "		INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "		INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "		INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "		INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "		INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"		INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "		WHERE\n".
            "			detrespxactpre.`status` <> 10\n".
            "		AND detrespxactpre.estado = 1\n".
            "		AND detrespxactpre.meta > 0\n".
            "		AND act.estado = 1\n".
            "		AND actpadre.idactividad = ".$idpoi." \n".
            "		AND users.id = ".$idarea." \n".
            "		AND act.idactividad = ".$idaccestra." \n".
            "	) AS estadoplazo\n".
            "WHERE\n".
            "IF (\n".
            "	estadoplazo.diasmeta + ".$plazotime." > estadoplazo.diaanio,\n".
            "	'enplazo',\n".
            "	'vencioplazo'\n".
            ") = 'vencioplazo'\n".
            "GROUP BY\n".
            "	estadoplazo.idactpre,\n".
            "	estadoplazo.actpre";
        //print_r($query);exit();
        $dataActPre=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataActPre as $dtActPre){
            $dataActOp=$this->_getActOpByActPre_plazo($idpoi,$idarea,$idaccestra,$dtActPre['idactpre'],$mesini,$mesend,$tipo,$plazotime);
            $d=array(
                "idactpre"=>$dtActPre['idactpre'],
                "actpre"=>$this->quitaSaltoLinea($dtActPre["actpre"]),
                "dataactop"=>$dataActOp
            );
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;

    }

    public function _getActOpByActPre_plazo($idpoi,$idarea,$idaccestra,$idactpre,$mesini,$mesend,$tipo,$plazotime){

        $query="SELECT\n".
            "	 estadoplazo.idactop,\n".
            "	estadoplazo.actop,\n".
            "	 estadoplazo.um,\n".
            "	estadoplazo.id_unidadmedida\n".
            "FROM\n".
            "	(\n".
            "		SELECT\n".
            "			actpadre.periodo,\n".
            "			act.idactividad,\n".
            "			detrespxactpre.id_tiempo,\n".
            "			DAYOFYEAR(\n".
            "				concat(\n".
            "					actpadre.periodo,\n".
            "					'-',\n".
            "					detrespxactpre.id_tiempo,\n".
            "					'-',\n".
            "					DAYOFMONTH(\n".
            "						LAST_DAY(\n".
            "							concat(\n".
            "								actpadre.periodo,\n".
            "								'-',\n".
            "								detrespxactpre.id_tiempo,\n".
            "								'-',\n".
            "								'1'\n".
            "							)\n".
            "						)\n".
            "					)\n".
            "				)\n".
            "			) AS diasmeta,\n".
            "			DAYOFYEAR(CURDATE()) AS diaanio,\n".
            "			CURDATE() AS diaactual,\n".
            "			DAYOFMONTH(LAST_DAY(CURDATE())) AS diames,\n".
            "			actvpresupuestaria.nombre as actop,\n".
            "			actvpresupuestaria.id_actvpresupuestaria as idactop,\n".
            "			unidadmedida.id_unidadmedida,\n".
            "			unidadmedida.nombre as um \n".
            "		FROM\n".
            "			actividades AS act\n".
            "		INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "		INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "		INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "		INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "		INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "		INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
           // "		INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "		WHERE\n".
            "			detrespxactpre.`status` <> 10\n".
            "		AND detrespxactpre.estado = 1\n".
            "		AND detrespxactpre.meta > 0\n".
            "		AND act.estado = 1\n".
            "		AND actpadre.idactividad = ".$idpoi."\n".
            "		AND users.id = ".$idarea." \n".
            "		AND act.idactividad = ".$idaccestra." \n".
            "		AND actpresupuestariareal.id_actpresupuestariareal = ".$idactpre." \n".
            "	) AS estadoplazo\n".
            "WHERE\n".
            "IF (\n".
            "	estadoplazo.diasmeta + ".$plazotime." > estadoplazo.diaanio,\n".
            "	'enplazo',\n".
            "	'vencioplazo'\n".
            ") = 'vencioplazo'\n".
            "GROUP BY\n".
            "	estadoplazo.actop,\n".
            "	estadoplazo.idactop,estadoplazo.um,estadoplazo.id_unidadmedida";

        //print_r($query);exit();
        $dataActOp=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataActOp as $dtActOp){
            //$dataTarea=$this->_getTareasByActOp_plazo($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],$mesini,$mesend,$tipo,$plazotime);

            $d=array(
                "idactop"=>$dtActOp['idactop'],
                "actop"=>$this->quitaSaltoLinea($dtActOp["actop"]),
                "um"=>$dtActOp['um'],
                "id_unidadmedida"=>$dtActOp['id_unidadmedida'],
                "gaop"=>0
            );
            $d["meses"]=$this->_getMetaEjecMesesByTarea_plazo($idpoi,$idarea,$idaccestra,$idactpre,$dtActOp['idactop'],"",$mesini,$mesend,$tipo,$plazotime);

            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;


    }

    public function _getTareasByActOp_plazo($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$mesini,$mesend,$tipo,$plazotime){

        $query="SELECT\n".
            "	 estadoplazo.tarea,\n".
            "	estadoplazo.idtarea,\n".
            "	 estadoplazo.um,\n".
            "	estadoplazo.id_unidadmedida\n".
            "FROM\n".
            "	(\n".
            "		SELECT\n".
            "			actpadre.periodo,\n".
            "			act.idactividad,\n".
            "			detrespxactpre.id_tiempo,\n".
            "			DAYOFYEAR(\n".
            "				concat(\n".
            "					actpadre.periodo,\n".
            "					'-',\n".
            "					detrespxactpre.id_tiempo,\n".
            "					'-',\n".
            "					DAYOFMONTH(\n".
            "						LAST_DAY(\n".
            "							concat(\n".
            "								actpadre.periodo,\n".
            "								'-',\n".
            "								detrespxactpre.id_tiempo,\n".
            "								'-',\n".
            "								'1'\n".
            "							)\n".
            "						)\n".
            "					)\n".
            "				)\n".
            "			) AS diasmeta,\n".
            "			DAYOFYEAR(CURDATE()) AS diaanio,\n".
            "			CURDATE() AS diaactual,\n".
            "			DAYOFMONTH(LAST_DAY(CURDATE())) AS diames,\n".
            "			unidadmedida.id_unidadmedida,\n".
            "			unidadmedida.nombre as um ,\n".
            "			tareas.nombre as tarea,\n".
            "			tareas.idtarea\n".
            "		FROM\n".
            "			actividades AS act\n".
            "		INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "		INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "		INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "		INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "		INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "		INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "		INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "		WHERE\n".
            "			detrespxactpre.`status` <> 10\n".
            "		AND detrespxactpre.estado = 1\n".
            "		AND detrespxactpre.meta > 0\n".
            "		AND act.estado = 1\n".
            "		AND actpadre.idactividad = ".$idpoi."\n".
            "		AND users.id = ".$idarea." \n".
            "		AND act.idactividad = ".$idaccestra." \n".
            "		AND actpresupuestariareal.id_actpresupuestariareal = ".$idactpre." \n".
            "		AND actvpresupuestaria.id_actvpresupuestaria = ".$idactop." \n".
            "	) AS estadoplazo\n".
            "WHERE\n".
            "IF (\n".
            "	estadoplazo.diasmeta + ".$plazotime." > estadoplazo.diaanio,\n".
            "	'enplazo',\n".
            "	'vencioplazo'\n".
            ") = 'vencioplazo'\n".
            "GROUP BY\n".
            "	 estadoplazo.tarea,\n".
            "	estadoplazo.idtarea,estadoplazo.um,estadoplazo.id_unidadmedida";
        //print_r($query);exit();
        $dataTareas=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataTareas as $dtTareas){

            $d=array(
                "idtarea"=>$dtTareas['idtarea'],
                "tarea"=>$this->quitaSaltoLinea($dtTareas["tarea"]),
                "idum"=>$dtTareas['id_unidadmedida'],
                "um"=>$this->quitaSaltoLinea($dtTareas["um"]),
                "ga"=>0
            );
            $d["meses"]=$this->_getMetaEjecMesesByTarea_plazo($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$dtTareas['idtarea'],$mesini,$mesend,$tipo,$plazotime);

            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;
    }

    public function _getMetaEjecMesesByTarea_plazo($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$idtarea,$mesini,$mesend,$tipo,$plazotime){

        $query="SELECT\n".
            " \n".
            "estadoplazo.id_detrespxactpre,\n".
            "			estadoplazo.meta,\n".
            "			estadoplazo.ejecutado,\n".
            "			estadoplazo.ejecutadosupuesto,\n".
            "			estadoplazo.id_tiempo,\n".
            "			estadoplazo.status \n".
            "FROM\n".
            "	(\n".
            "		SELECT\n".
            "			DAYOFYEAR(\n".
            "				concat(\n".
            "					actpadre.periodo,\n".
            "					'-',\n".
            "					detrespxactpre.id_tiempo,\n".
            "					'-',\n".
            "					DAYOFMONTH(\n".
            "						LAST_DAY(\n".
            "							concat(\n".
            "								actpadre.periodo,\n".
            "								'-',\n".
            "								detrespxactpre.id_tiempo,\n".
            "								'-',\n".
            "								'1'\n".
            "							)\n".
            "						)\n".
            "					)\n".
            "				)\n".
            "			) AS diasmeta,\n".
            "			DAYOFYEAR(CURDATE()) AS diaanio,\n".
            "			CURDATE() AS diaactual,\n".
            "			DAYOFMONTH(LAST_DAY(CURDATE())) AS diames,\n".
            "			detrespxactpre.id_detrespxactpre,\n".
            "			detrespxactpre.meta,\n".
            "			detrespxactpre.ejecutado,\n".
            "			detrespxactpre.ejecutadosupuesto,\n".
            "			detrespxactpre.id_tiempo,\n".
            "			detrespxactpre.status\n".
            "		FROM\n".
            "			actividades AS act\n".
            "		INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "		INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "		INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "		INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "		INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "		INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            //"		INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            "		WHERE\n".
            "			detrespxactpre.`status` <> 10\n".
            "		AND detrespxactpre.estado = 1\n".
            "		AND detrespxactpre.meta > 0\n".
            "		AND act.estado = 1\n".
            "		AND actpadre.idactividad = ".$idpoi."\n".
            "		AND users.id = ".$idarea." \n".
            "		AND act.idactividad = ".$idaccestra." \n".
            "		AND actpresupuestariareal.id_actpresupuestariareal = ".$idactpre." \n".
            "		AND actvpresupuestaria.id_actvpresupuestaria = ".$idactop." \n".
            //"		AND tareas.idtarea  = ".$idtarea." \n".
            "	) AS estadoplazo\n".
            "WHERE\n".
            "IF (\n".
            "	estadoplazo.diasmeta + ".$plazotime." > estadoplazo.diaanio,\n".
            "	'enplazo',\n".
            "	'vencioplazo'\n".
            ") = 'vencioplazo'\n".
            "GROUP BY\n".
            "	estadoplazo.id_detrespxactpre,\n".
            "			estadoplazo.meta,\n".
            "			estadoplazo.ejecutado,\n".
            "			estadoplazo.ejecutadosupuesto,\n".
            "			estadoplazo.id_tiempo ,estadoplazo.status order by  estadoplazo.id_tiempo  asc  ";
        //echo "<pre>";print_r($query);exit();
        $dataTareaMeses=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataTareaMeses as $dtTareasMeses){
            $dataPlazo=$this->_getStatusPlazo_plazo($idaccestra,$idactpre,$idactop,$idtarea,$idarea,$dtTareasMeses['id_tiempo'],$plazotime);
            $d=array(
                "id_detrespxactpre"=>$dtTareasMeses['id_detrespxactpre'],
                "meta"=>$dtTareasMeses['meta'],
                "ejecutado"=>$dtTareasMeses['ejecutado'],
                "ejecutadosupuesto"=>$dtTareasMeses['ejecutadosupuesto'],
                "status"=>$dtTareasMeses["status"],
                "id_tiempo"=>$dtTareasMeses['id_tiempo'],
                "plazo"=>$dataPlazo,
                "datajustify"=>""
            );
            array_push($dataFinal,$d);
        }

        return $dataFinal;

    }

    public function _getStatusPlazo_plazo($idact,$idActPreReal,$idActPre,$idTarea,$idUser,$id_tiempo,$plazotime){

        $query="select\n".
            "*	,\n".
            "if( actpreUser.diasmeta > actpreUser.diaactualanio,'1','0'   ) as estadoplazobol,\n".
            "if( actpreUser.diasmeta > actpreUser.diaactualanio,'enplazo','Vencio plazo'   ) as estadoplazo\n".
            "	\n".
            " from( SELECT\n".
            "DAYOFYEAR(concat(actpoi.periodo,'-',detrespxactpre.id_tiempo,'-',DAYOFMONTH(LAST_DAY(concat(actpoi.periodo,'-',detrespxactpre.id_tiempo,'-','1') ) )     )) AS diasmeta,\n".
            "DAYOFYEAR(CURDATE()) AS diaactualanio,\n".
            "detrespxactpre.id_actvpresupuestaria,\n".
            "detrespxactpre.id_detrespxactpre,\n".
            "detrespxactpre.ejecutado,\n".
            "detrespxactpre.ejecutadosupuesto,\n".
            "detrespxactpre.meta,\n".
            "detrespxactpre.id_tiempo\n".
            "FROM\n".
            "actividades AS actpoi\n".
            "INNER JOIN actividades AS act ON actpoi.idactividad = act.idpadrepadre\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
           // "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea \n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "where detrespxactpre.`status`<> 10  and detrespxactpre.meta > 0  and act.estado =1  and detrespxactpre.estado=1 \n".
            " and detrespxactpre.idactividad=".$idact."  and detrespxactpre.id_actvpresupuestaria=".$idActPre." \n".
            " and detrespxactpre.id_tiempo=".$id_tiempo." and `users`.id=".$idUser." \n".
            " and detrespxactpre.id_actpresupuestariareal=".$idActPreReal." \n".
            //" and detrespxactpre.idtarea=".$idTarea." \n".
            ") as actpreUser where if( actpreUser.diasmeta + ".$plazotime."  > actpreUser.diaactualanio,'enplazo','vencioplazo'   ) = 'vencioplazo'";

        $result=$this->db->query($query)->result_array();
        return $result;
    }

    public function _getGaAccionEstrategicabyPei($idPoi,$idArea,$idAccestra,$mesini,$mesfin,$tipo){

        $dataActPre=$this->_getActPreByAccEstra($idPoi,$idArea,$idAccestra,$mesini,$mesfin,$tipo);
        $count=0;
        $sum=0;
        $ga=0;
        $dataFinal=array();
        foreach ($dataActPre as $dActPre){
            foreach($dActPre["dataactop"] as $dActOp){
                $count++;
                $sum=$sum+$dActOp["gaop"];
            }
        }
        if($count >0 && $sum >0){
            $ga=floatval($sum/$count);
        }

         
        return $ga;
    }

    public function _getTareasByActOpbyTarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$idtarea,$mesini,$mesend,$tipo){
        $queryEvalua="";
        if($tipo == "evalua"){
            $queryEvalua="and detrespxactpre.`status`in(1,2)";
        }
        $joinJusty="";
        $queryJusty="";

        if($tipo == "justify"){
            $joinJusty="INNER JOIN justificaciones ON detrespxactpre.id_detrespxactpre = justificaciones.id_detrespxactpre";
            $queryJusty="and detrespxactpre.isJusty <> 0 and justificaciones.estado <> 0 ";
        }
        $queryV="";
        if($tipo == "verificados"){
            $queryV="and detrespxactpre.`status`= 10 ";
        }
        $queryR="";
        if($tipo == "reporte"){
            $queryR="and detrespxactpre.meta > 0";// para filtrar las tareas sin meta
        }

        $query="SELECT\n".
            "	tareas.idtarea ,\n".
            "	tareas.nombre AS tarea,\n".
            "	unidadmedida.id_unidadmedida ,\n".
            "	unidadmedida.nombre AS um\n".
            "FROM\n".
            "	actividades AS act\n".
            "INNER JOIN actividades AS actpadre ON act.idpadrepadre = actpadre.idactividad\n".
            "INNER JOIN detrespxactpre ON act.idactividad = detrespxactpre.idactividad\n".
            "INNER JOIN users ON users.id = detrespxactpre.id_user\n".
            "INNER JOIN actvpresupuestaria ON actvpresupuestaria.id_actvpresupuestaria = detrespxactpre.id_actvpresupuestaria\n".
            "INNER JOIN actpresupuestariareal ON actpresupuestariareal.id_actpresupuestariareal = detrespxactpre.id_actpresupuestariareal\n".
            "INNER JOIN unidadmedida ON unidadmedida.id_unidadmedida = detrespxactpre.id_unidadmedida\n".
            "INNER JOIN tareas ON tareas.idtarea = detrespxactpre.idtarea\n".
            " ".$joinJusty."\n".
            "WHERE\n".
            "	act.estado = 1\n".
            "AND detrespxactpre.estado = 1  ".$queryEvalua."  ".$queryJusty."  ".$queryV."  ".$queryR." \n".
            "AND users.`status` = 1\n".
            "AND actpadre.estado = 1\n".
            "AND tareas.estado = 1\n".
            " and  detrespxactpre.idtarea=".$idtarea."  \n".
            "and detrespxactpre.id_tiempo BETWEEN ".$mesini." and ".$mesend." \n".
            "AND actvpresupuestaria.id_actvpresupuestaria = ".(int)$idactop."\n".
            "AND actpresupuestariareal.id_actpresupuestariareal = ".(int)$idactpre."\n".
            "AND act.idactividad = ".(int)$idaccestra."\n".
            "AND users.id = ".(int)$idarea."\n".
            "AND actpadre.idactividad = ".(int)$idpoi."\n".
            "GROUP BY\n".
            "	tareas.idtarea ,\n".
            "	tareas.nombre ";
        //print_r($query);exit();
        $dataTareas=$this->db->query($query)->result_array();
        $dataFinal=array();
        foreach ($dataTareas as $dtTareas){
            $dataGATarea=0;
            if($tipo == "global" || $tipo == "reporte"  ){
                $dataGATarea=$this->_getGATarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$dtTareas['idtarea'],$mesini,$mesend);
            }
            $d=array(
                "idtarea"=>$dtTareas['idtarea'],
                "tarea"=>$this->quitaSaltoLinea($dtTareas["tarea"]),
                "idum"=>$dtTareas['id_unidadmedida'],
                "um"=>$this->quitaSaltoLinea($dtTareas["um"]),
                "ga"=>$dataGATarea
            );
            if($tipo == "global" || $tipo == "evalua"  || $tipo == "justify" || $tipo == "reporte" || $tipo== "verificados"  ){
                $d["meses"]=$this->_getMetaEjecMesesByTarea($idpoi,$idarea,$idaccestra,$idactpre,$idactop,$dtTareas['idtarea'],$mesini,$mesend,$tipo);
            }
            array_push($dataFinal,$d);
        }
        //print_r($query);exit();
        //echo $idPoi."-".$idArea;exit();
        return $dataFinal;
    }

    public function quitaSaltoLinea($string){
        $string2=str_replace(array("'",'"'), " ",$string);
        $sustituye = array("(\r\n)", "(\n\r)", "(\n)", "(\r)");
        $texto = preg_replace($sustituye, " ",$string2);
        return  $texto;
    }


    public function getMesesHabilesPlazo($periodo,$diasplazo){
        $mesactual=date("n");
        $fechaInicioPeriodo=$periodo.'-'.'1-1';
        $fechaInicioPeriodo = new DateTime($fechaInicioPeriodo);
        $fechaIni =$fechaInicioPeriodo->format('Y-m-j');
        $timeIniUnix=strtotime ( '0 day' , strtotime ( $fechaIni) )  ;
        $fechaIniReal= date ('Y-m-j' , $timeIniUnix );
        $mesIniReal= date ('m' , $timeIniUnix );

        $fechaFinPeriodo=($periodo+1).'-'.'1-1';
        $fechaFinPeriodo = new DateTime($fechaFinPeriodo);
        $fechaFin =$fechaFinPeriodo->format('Y-m-j');
        $fechaFinUnixZ=strtotime ( '-1 day' , strtotime ( $fechaFin) )  ;
        $fechaFinReal= date ('Y-m-j' , $fechaFinUnixZ );
        $diasplazofinalmes='+'.$diasplazo.' day';

        $timeFinUnix=strtotime ($diasplazofinalmes , strtotime ( $fechaFinReal) )  ;
        $fechaFinRealFinal=date ('Y-m-j' , $timeFinUnix );// fecha final para entrega de datos.




        $dt=$periodo.'-'.$mesactual.'-1';
        $obFechaAnterior = new DateTime($dt);
        $obFechaAnterior =$obFechaAnterior->format('Y-m-j');
        $UnixMesAnteirorZ=strtotime ( '-1 day' , strtotime ( $obFechaAnterior) )  ;
        $obFechaAnteriorFinal= date ('Y-m-j' , $UnixMesAnteirorZ );
        $obMesAnterior=date ('m' , $UnixMesAnteirorZ );

        $strDayPlazoMesAnterior='+'.($diasplazo).' day';
        $unixFechaLimitePlazoAnterior = strtotime ( $strDayPlazoMesAnterior, strtotime ( $obFechaAnteriorFinal) ) ;
        $fechaMaxPlazoMesAnterior = date ('Y-m-j' , $unixFechaLimitePlazoAnterior );
        //$mesMaxPlazoAnteiror=date ('m' , $unixFechaLimitePlazoAnterior );
        $unixFechaActual=strtotime(date('Y-m-j'));
        $anioActual=date("Y");
        $mesActual=date("m");
        //$mesIniHabilitado=$mesactual;
       // echo $fechaMaxPlazoMesAnterior."-".date('Y-m-j') ;exit();
        if( ($unixFechaLimitePlazoAnterior >= $unixFechaActual)  && ($unixFechaActual >= $timeIniUnix) && ($unixFechaActual <= $timeFinUnix)   ){
           if((int)$periodo == (int)$anioActual && ($mesActual == 1) ){
               $mesIniHabilitado=1;
           }else{
               $mesIniHabilitado=$obMesAnterior;
           }

        }else if( ($unixFechaLimitePlazoAnterior <= $unixFechaActual)  && ($unixFechaActual >= $timeIniUnix) && ($unixFechaActual <= $timeFinUnix)  ){

            if((int)$periodo == (int)$anioActual && ($mesActual == 1) ){
                $mesIniHabilitado=1;
            }else{
                $mesIniHabilitado=$mesactual;;
            }
        }else if($anioActual <= $periodo ){
            $mesIniHabilitado=1;
        }else{
            $mesIniHabilitado=13;
        }
        //echo $mesIniHabilitado; exit();
        $data=array("inicioMesesHabilitados"=>$mesIniHabilitado,"mesActual"=>$mesactual);
        return $data;
    }

    public function getMesesUserHabilitado(){
        $this->db->select('*');
        $this->db->from("configmesplazo");
        $this->db->where(["estado"=>2]);
        $this->db->order_by("idmesplazo","asc");
        $Meses=$this->db->get()->result_array();
        $mesHabilitado=[];
        foreach($Meses as $value){
            $mesHabilitado[]=(int)$value["idmesplazo"];
        }
        //print_r($mesHabilitado);exit();
        return $mesHabilitado;
    }

}