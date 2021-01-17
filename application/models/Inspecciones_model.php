<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspecciones_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function getDataListCriaderos($id = null){
        $condicion=array("criadero.estado >"=>0);
        if($id!=null){
            $condicion["criadero.idcriadero"]=$id;
        }
        $this->db->select("criadero.*,tipocriadero.nombre as tipocriadero ");
        $this->db->from("criadero");
        $this->db->join("tipocriadero","criadero.idtipocriadero=tipocriadero.idtipocriadero","inner");
        $this->db->where($condicion);
        $return=$this->db->get()->result_array();
        return $return;
    }

    public function getDataListViviendas($id = null){

        $condicion=array("viviendas.estado > "=>0);
        if($id != null){
            $condicion["viviendas.idvivienda"]=$id;
        }
        $this->db->select("*");
        $this->db->from("viviendas");
        $this->db->where($condicion);
        $return=$this->db->get()->result_array();
        return $return;

    }
    public function getDataListInspectores($id = null){

        $condicion=array("viviendas.estado > "=>0);
        if($id != null){
            $condicion["viviendas.idvivienda"]=$id;
        }
        $this->db->select("*");
        $this->db->from("viviendas");
        $this->db->where($condicion);
        $return=$this->db->get()->result_array();
        return $return;

    }

    public function getUsersByRole($role=null)
    {   $condicion=array("users.status > "=>0);
        if($role != null){
            $condicion["roles.role"]=$role;
        }

        $this->db->select("users.id, 
                           roles.role,
                           users.nombrearearesponsable");
        $this->db->from("users");
        $this->db->join("roles","users.role = roles.id","inner");
        $this->db->where($condicion);
        $return=$this->db->get()->result_array();
        return $return;
    }

    public function getInspeccionOvitrampa()
    {   /*$condicion=array("users.status > "=>0);
        if($role != null){
            $condicion["roles.role"]=$role;
        }*/

        $this->db->select("dist.DESC_DPTO,
                    dist.DESC_DIST,
                    dist.DESC_PROV,
                    inspeccion.localidad,
                    inspeccion.idtipoinspeccion,
                    inspeccion.idubigeo,
                    inspeccion.iddistrito,
                    inspeccion.idipress,
                    inspeccion.estado,
                    inspeccion.fechareg,
                    users.id,
                    inspeccion.finspeccion,
                    WEEK(inspeccion.finspeccion,3) as semanainsp,
                    inspeccion.idinspeccion,
                    establec.DESC_ESTAB,
                    users.nombrearearesponsable as inspector");
        $this->db->from("inspeccion");
        $this->db->join("dist","inspeccion.idubigeo = dist.UBIGEO","inner");
        $this->db->join("users","inspeccion.idinspector = users.id","inner");
        $this->db->join("establec","inspeccion.idipress = establec.COD_ESTAB","inner");
         $this->db->where("inspeccion.idtipoinspeccion = 2 and inspeccion.estado >0 ");
        $this->db->order_by("inspeccion.finspeccion desc ");
        $return=$this->db->get()->result_array();
        return $return;
    }

    public function getOvitrampasByDistrito($iddistrito=null,$search=null){
        $query="SELECT
                ovitrampas.idovitrampa,
                viviendas.idvivienda,
                detovitrampavivienda.lat,
                detovitrampavivienda.lng,
                detovitrampavivienda.ubicacionenvivienda,
                viviendas.idsector,
                viviendas.idmanzana,
                viviendas.idbarrio,
                ovitrampas.codigoovitrampa,
                viviendas.direccion,
                viviendas.nro,              
                detovitrampavivienda.fechainstalacion,
                dist.DESC_DIST,
                dist.DESC_PROV,
                dist.DESC_DPTO,               
                dist.UBIGEO,
                ovitrampas.codigoovitrampa as value
                FROM
                ovitrampas
                INNER JOIN detovitrampavivienda ON ovitrampas.idovitrampa = detovitrampavivienda.idovitrampa
                INNER JOIN viviendas ON viviendas.idvivienda = detovitrampavivienda.idvivienda
                INNER JOIN dist ON dist.UBIGEO = viviendas.ubigeo 
                where detovitrampavivienda.isactual =1 and  ovitrampas.codigoovitrampa like '%$search%' and dist.UBIGEO='$iddistrito' and ovitrampas.estado >0  ";
        $return=$this->db->query($query)->result_array();
        return $return;
    }

    public function getDataDetalleInsOvitrampasBYInspLocalidad($idinspeccion=null){
        $query="SELECT
            detinspeccion.idvivienda,
            detinspeccion.idinspeccion,
            detinspeccion.iddetinspeccion,
            detinspeccion.estadoviviendainspeccion,
            detinspeccion.nrohuevosovitrampa,
            detinspeccion.fechainspeccionovitrampa,
            detinspeccion.altovitrampa,
            detinspeccion.lngovitrampa,
            detinspeccion.latovitrampa,
            detinspeccion.mzovitrampa,
            detinspeccion.sectorovitrampa,
            detinspeccion.ubicacionovitrampa,
            detinspeccion.codigoovitrampa,
            viviendas.direccion,
            viviendas.nro,
            `estadoviviendaeninspeccion`.nombre as estadoviviendaeninspeccion
            FROM
            detinspeccion
            INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
            INNER JOIN `estadoviviendaeninspeccion` ON `estadoviviendaeninspeccion`.idestadoinspeccion = detinspeccion.estadoviviendainspeccion
            where detinspeccion.estado>0 and detinspeccion.idinspeccion=$idinspeccion 
             order by detinspeccion.iddetinspeccion desc"  ;
        $return=$this->db->query($query)->result_array();
        return $return;
    }
    public function getEstadoViviendaEnInspeccion(){
        $this->db->select("*");
        $this->db->from("estadoviviendaeninspeccion");
        $this->db->where(["estado>"=>0]);
        $return=$this->db->get()->result_array();
        return $return;
    }

    public function getEstadoViviendaEnInspeccionOvi(){
        $this->db->select("*");
        $this->db->from("estadoviviendaeninspeccion");
        $this->db->where("estado> 0 and (descripcion='3' or descripcion='2' )");
        $return=$this->db->get()->result_array();
        return $return;
    }

    public function getInspeccionesViviendas($id=null){
        $sql="";
        if($id != null){
          $sql="inspeccion.idinspeccion=$id";
        }
        $query="SELECT
                inspeccion.idinspeccion,
                inspeccion.idredsalud,
                inspeccion.idtipoinspeccion,
                inspeccion.idjefebrigada,
                inspeccion.idinspector,
                inspeccion.idestablecimientosalud,
                inspeccion.sector,
                inspeccion.fechaintervencion,
                inspeccion.tipoactividad,
                inspeccion.nrocontrol,
                inspeccion.fechareg,
                inspeccion.estado,
                inspeccion.localidad,
                inspeccion.idubigeo,
                inspeccion.fechainstalacion,
                dist.DESC_DPTO,
                dist.DESC_PROV,
                dist.DESC_DIST,
                jbrigada.nombrearearesponsable AS jefebrigada,
                jbrigada.id AS idjefebrigada,
                jbrigada.lastnames AS lastjefe,
                inspector.nombrearearesponsable AS inspector,
                inspector.id AS idinspector,
                inspector.lastnames AS lastinpector,
                red.DESC_RED,
                red.COD_DISA,
                red.COD_RED,
                establec.DESC_ESTAB,
                dist.COD_DIST
                FROM
                inspeccion
                INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                INNER JOIN users AS jbrigada ON jbrigada.id = inspeccion.idjefebrigada
                INNER JOIN users AS inspector ON inspector.id = inspeccion.idinspector
                INNER JOIN red ON red.COD_DISA = inspeccion.iddisa AND inspeccion.idred = red.COD_RED
                INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idestablecimientosalud
                where inspeccion.idtipoinspeccion >0 and inspeccion.estado>0 ".$sql." order by inspeccion.idinspeccion desc";
        $dd=$this->db->query($query)->result_array();
        return $dd;
    }

    public function getDetailInspeccionesVivienda($idinspeccion){
         $query="SELECT
                viviendas.idbarrio,
                viviendas.idsector,
                viviendas.idmanzana,
                viviendas.direccion,
                viviendas.nro,
                estadoviviendaeninspeccion.nombre,
                estadoviviendaeninspeccion.descripcion,
                detinspeccion.iddetinspeccion,
                detinspeccion.idinspeccion,
                detinspeccion.idvivienda,
                detinspeccion.idestadoinspeccion,
                detinspeccion.ic1,
                detinspeccion.tc1,
                detinspeccion.pc1,
                detinspeccion.ic2,
                detinspeccion.pc2,
                detinspeccion.tc2,
                detinspeccion.ic3,
                detinspeccion.pc3,
                detinspeccion.i4,
                detinspeccion.tc3,
                detinspeccion.p4,
                detinspeccion.t4,
                detinspeccion.i5,
                detinspeccion.p5,
                detinspeccion.t5,
                detinspeccion.i6,
                detinspeccion.p6,
                detinspeccion.t6,
                detinspeccion.i7,
                detinspeccion.p7,
                detinspeccion.e7,
                detinspeccion.i8,
                detinspeccion.p8,
                detinspeccion.t8,
                detinspeccion.larvicidadaplicada
                FROM
                detinspeccion
                INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                INNER JOIN estadoviviendaeninspeccion ON estadoviviendaeninspeccion.idestadoinspeccion = detinspeccion.idestadoinspeccion
                where detinspeccion.estado>0 and detinspeccion.idinspeccion=$idinspeccion";
        $dd=$this->db->query($query)->result_array();
        return $dd;
    }

    public function getDataReportForOvitrampas(){
        $query="SELECT            
            viviendas.direccion,
            detinspeccion.latovitrampa as lat ,
            detinspeccion.lngovitrampa as lng,
            ovitrampas.codigoovitrampa,
            dist.DESC_DIST,
            dist.DESC_PROV,
            dist.DESC_DPTO,
            dist.UBIGEO,
            inspeccion.idtipoinspeccion,
            detinspeccion.fechainspeccionovitrampa,
            WEEK(detinspeccion.fechainspeccionovitrampa,3) as semana,
            
            viviendas.nro,
            detinspeccion.estadoviviendainspeccion,
            estadoviviendaeninspeccion.nombre,
            detinspeccion.nrohuevosovitrampa
            FROM
            detinspeccion
            INNER JOIN ovitrampas ON ovitrampas.codigoovitrampa = detinspeccion.codigoovitrampa
            INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
            INNER JOIN dist ON dist.UBIGEO = viviendas.ubigeo
            INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
            INNER JOIN estadoviviendaeninspeccion ON estadoviviendaeninspeccion.idestadoinspeccion = detinspeccion.estadoviviendainspeccion
            where inspeccion.estado>0 and detinspeccion.estado>0 and inspeccion.idtipoinspeccion =2
            order BY detinspeccion.fechainspeccionovitrampa desc ";
        $dd=$this->db->query($query)->result_array();
        return $dd;
    }

    public function getAniosRegOvitrampa(){
        $query="SELECT
                 DISTINCT Year(detinspeccion.fechainspeccionovitrampa) as aniosreg
                FROM
                detinspeccion
                where detinspeccion.estado>0  and  detinspeccion.fechainspeccionovitrampa IS NOT NULL ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }
    public function getAniosRegInspViviendas(){
        $query="SELECT
                 DISTINCT Year(detinspeccion.fechainspeccionovitrampa) as aniosreg
                FROM
                detinspeccion
                where detinspeccion.estado>0  and  detinspeccion.fechainspeccionovitrampa IS NOT NULL ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getReportMap($idubigeo=NULL,$selEstablecimientoSalud=NULL,$anio=NULL,$semana=NULL,$sector=NULL,$selEstadoVisita=NULL){
        $condi=" where detinspeccion.estado>0 ";
        if($idubigeo != null){
            $condi=$condi." and viviendas.ubigeo=$idubigeo";
        }
        if($selEstablecimientoSalud != null){
            $condi=$condi." ";
        }
        if($anio != null){
            $condi=$condi." and YEAR(fechainspeccionovitrampa)=$anio";
        }
        if($sector != null){
            $condi=$condi." and detinspeccion.sectorovitrampa=$sector ";
        }
        if($semana != null){
            $condi=$condi." and  WEEK(detinspeccion.fechainspeccionovitrampa,3)=$semana ";
        }
        if($selEstadoVisita != null){
            if($selEstadoVisita != "0"){
                $condi=$condi."  and detinspeccion.estadoviviendainspeccion=$selEstadoVisita ";
            }

        }



        $query="SELECT
            ovitrampas.codigoovitrampa,
            detinspeccion.sectorovitrampa,
            detinspeccion.ubicacionovitrampa,
            detinspeccion.estadoviviendainspeccion,
            detinspeccion.nrohuevosovitrampa ,
            detinspeccion.lngovitrampa as  lng ,
            detinspeccion.latovitrampa as lat ,
                        if(detinspeccion.nrohuevosovitrampa = 0 ,0,if(detinspeccion.nrohuevosovitrampa > 0 and detinspeccion.nrohuevosovitrampa <= 60 ,1,if(detinspeccion.nrohuevosovitrampa >60 and detinspeccion.nrohuevosovitrampa <=120 ,2,if(detinspeccion.nrohuevosovitrampa > 0 and detinspeccion.nrohuevosovitrampa <= 60 ,1,if(detinspeccion.nrohuevosovitrampa >120 and detinspeccion.nrohuevosovitrampa <=150 , 3 , 4) )))) as count ,
            detinspeccion.fechainspeccionovitrampa,
             WEEK(detinspeccion.fechainspeccionovitrampa,3) as semana,
             
            detinspeccion.mzovitrampa,
            viviendas.direccion,
            viviendas.nro,
            viviendas.ubigeo
            FROM
            detinspeccion
            INNER JOIN ovitrampas ON ovitrampas.codigoovitrampa = detinspeccion.codigoovitrampa
            INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda $condi";
            $res=$this->db->query($query)->result_array();
            return $res;
    }

    public function getOvitrampasConsolidado($anio,$idubigeo,$idipress){
        $query="SELECT DISTINCT
        detinspeccion.codigoovitrampa
        FROM
        detinspeccion
        INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion     
        WHERE
	    detinspeccion.codigoovitrampa IS NOT NULL  and 
	    Year(detinspeccion.fechainspeccionovitrampa) = $anio
        and inspeccion.idipress='$idipress'
	    and inspeccion.idubigeo='$idubigeo'";

        $res=$this->db->query($query)->result_array();
        $dt=null;
        foreach($res as $k=>$v){
            //$dataDetSemanaOvi=$this->getDataOviConsolidadoSemana($anio,$idubigeo,$idipress,$v["codigoovitrampa"]);
            $dx=$this->_getDataViviendasByOvi($anio,$idubigeo,$idipress,$v["codigoovitrampa"]);
            $dt[]=array(
                "codigoovitrampa"=>$v["codigoovitrampa"],
                "ipress"=>$idipress,
                 "idubigeo"=>$idubigeo,
                 "anio"=>$anio,
                //"semanal"=>$dataDetSemanaOvi,
                "dt"=>$dx
            );
        }

        /* $dt=null;
         foreach($res as $k=>$v){
             $dataDetSemanaOvi=$this->getDataOviConsolidadoSemana($anio,$idubigeo,$idipress,$v["codigoovitrampa"]);
             $dt[]=array(
                "codigoovitrampa"=>$v["codigoovitrampa"],
                 "semanal"=>$dataDetSemanaOvi,
             );
         }*/
         return $dt;
    }
    private function _getDataViviendasByOvi($anio,$idubigeo,$idipress,$codovi){
        $query="SELECT DISTINCT
                detinspeccion.idvivienda,
                viviendas.direccion,
                viviendas.nro,
                viviendas.lat,
	             viviendas.lng,               
                dist.DESC_DIST,
                establec.DESC_ESTAB ,
                detinspeccion.sectorovitrampa
                FROM
                detinspeccion
                INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress AND establec.COD_ESTAB = inspeccion.idipress
                where
                YEAR (
                    detinspeccion.fechainspeccionovitrampa
                ) = $anio
                AND inspeccion.idipress = '$idipress'
                AND inspeccion.idubigeo = '$idubigeo'
                AND detinspeccion.codigoovitrampa IS NOT NULL
                and detinspeccion.codigoovitrampa='$codovi'";
        $ret=$this->db->query($query)->result_array();
        $dt=null;
        foreach($ret as   $k=>$v ){
            $semana=$this->getDataOviConsolidadoSemana($anio,$idubigeo,$idipress,$codovi,$v["idvivienda"]);
            $dt[]=array(
                "idvivienda"=>$v["idvivienda"],
                "direccion"=>$v["direccion"],
                "nro"=>$v["nro"],
                "latovitrampa"=>$v["lat"],
                "lngovitrampa"=>$v["lng"],
                "DESC_DIST"=>$v["DESC_DIST"],
                "DESC_ESTAB"=>$v["DESC_ESTAB"],
               // "ubicacionovitrampa"=>$v["ubicacionovitrampa"],
                "sectorovitrampa"=>$v["sectorovitrampa"],
                "semanal"=>$semana
            );
        }

        return $dt;
    }

    private function getDataOviConsolidadoSemana($anio,$idubigeo,$idipress,$codOvi,$idvivienda=null){
        $where="";
        if($idvivienda != null){
            $where=" and  detinspeccion.idvivienda=$idvivienda ";
        }

        $query="SELECT
        detinspeccion.codigoovitrampa,
        detinspeccion.fechainspeccionovitrampa,
         WEEK(detinspeccion.fechainspeccionovitrampa,3) as semana,
        detinspeccion.nrohuevosovitrampa,
        detinspeccion.estadoviviendainspeccion
        FROM
        detinspeccion
         INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion   
        where 
         Year(detinspeccion.fechainspeccionovitrampa) = $anio
        and inspeccion.idipress='$idipress'
	    and inspeccion.idubigeo='$idubigeo'
        and detinspeccion.codigoovitrampa is not NULL
        and detinspeccion.codigoovitrampa='$codOvi'    $where
        order BY  WEEK(detinspeccion.fechainspeccionovitrampa,3) asc";
        $res=$this->db->query($query)->result_array();
        $ret=null;
        $soloNumeros = intval(preg_replace('/[^0-9]+/', '', $codOvi), 10);
        $soloLetras = preg_replace('/[0-9]/', '', $codOvi);
        $codovivalid=$soloLetras.$soloNumeros;

        foreach($res as $i){
            $dataVigilancia=$this->getDataVigilanciaByOvi($anio,$idubigeo,$idipress,$codovivalid,$idvivienda=null,$i["semana"]);
            $ret[]=array(
                "codigoovitrampa"=>$i["codigoovitrampa"],
                "fechainspeccionovitrampa"=>$i["fechainspeccionovitrampa"],
                "semana"=>$i["semana"],
                "nrohuevosovitrampa"=>$i["nrohuevosovitrampa"],
                "estadoviviendainspeccion"=>$i["estadoviviendainspeccion"],
                "datavigilancia"=>$dataVigilancia
            );
        }

        return $ret;
    }


    private function getDataVigilanciaByOvi($anio,$idubigeo,$idipress,$codovivalid,$idvivienda=null,$semana){
        $query="SELECT DISTINCT
        detinspeccionviviendas.fechaintervencion,
         detinspeccionviviendas.jefebrigada 
        FROM
        detinspeccionviviendas
        inner join  labelcodovitrampa on labelcodovitrampa.nombre=detinspeccionviviendas.ipress
        where concat(labelcodovitrampa.idlabelcodovitrampa,detinspeccionviviendas.nroovitrampa) like '$codovivalid' 
        and  WEEK(detinspeccionviviendas.fechaintervencion,3) =$semana
        and year(detinspeccionviviendas.fechaintervencion)=$anio
        and detinspeccionviviendas.estado>0
        
       
        order by  detinspeccionviviendas.fechaintervencion desc ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getListOvitrampasByIpress($ipress=null,$idinspeccion=null,$finspeccion=null,$iddetinspeccion=null){

           $query="Select * From( select * from (SELECT
detinspeccion.codigoovitrampa,
inspeccion.idipress,
viviendas.direccion,
viviendas.nro,
detinspeccion.ubicacionovitrampa as ubicacionenvivienda,
detinspeccion.sectorovitrampa as idsector,
detinspeccion.mzovitrampa as idmanzana, 
detinspeccion.latovitrampa as lat,
detinspeccion.lngovitrampa as lng,
detinspeccion.idvivienda,
1 isregistered,
detinspeccion.fechainspeccionovitrampa,
inspeccion.idinspeccion,
detinspeccion.iddetinspeccion,
detinspeccion.nrohuevosovitrampa,
detinspeccion.estadoviviendainspeccion,
if(inspeccion.idinspeccion = $idinspeccion  ,'registradorins','noregistradorins') as isregistradoins
FROM
detinspeccion
INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
where inspeccion.idtipoinspeccion=2 and inspeccion.estado>0 and detinspeccion.estado>0
and  week(inspeccion.finspeccion,3) = week('$finspeccion',3)    

) as dt
UNION  
select * from ( SELECT
        ovitrampas.codigoovitrampa,
        detovitrampavivienda.idipress,
        viviendas.direccion,
        viviendas.nro,
        detovitrampavivienda.ubicacionenvivienda,
        viviendas.idsector,
        viviendas.idmanzana,
        detovitrampavivienda.lat, 
        detovitrampavivienda.lng,      
        detovitrampavivienda.idvivienda,
				0 as isregistered,
				'No registrado' as fechainspeccionovitrampa,
				0 as idinspeccion,
				0 as iddetinspeccion,
				null as  nrohuevosovitrampa,
			   0 as estadoviviendainspeccion,
			   null as isregistradoins
        FROM
        detovitrampavivienda
        INNER JOIN viviendas ON viviendas.idvivienda = detovitrampavivienda.idvivienda
        INNER JOIN ovitrampas ON ovitrampas.idovitrampa = detovitrampavivienda.idovitrampa
        where detovitrampavivienda.estado > 0 and detovitrampavivienda.isactual=1 
         and detovitrampavivienda.idipress='$ipress'
         and ovitrampas.codigoovitrampa not in(
											SELECT
											DISTINCT detinspeccion.codigoovitrampa
											FROM
											detinspeccion
											INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
											and inspeccion.idtipoinspeccion=2 and inspeccion.estado>0 and detinspeccion.estado>0 
											and week(inspeccion.finspeccion,3) = week('$finspeccion',3)   
											)
      ) as dx ) as datax
  ORDER BY  datax.isregistered  desc, CONVERT(SUBSTRING_INDEX(datax.codigoovitrampa,'H',-1),UNSIGNED INTEGER) asc";
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getListValuesOvitrampasByOvi($anio,$idubigeo,$ipress,$codovi){
        $q="SELECT
            detinspeccion.codigoovitrampa,
            detinspeccion.fechainspeccionovitrampa,
            WEEK (
                detinspeccion.fechainspeccionovitrampa,
                3
            ) AS name,
         	CONVERT( detinspeccion.nrohuevosovitrampa ,UNSIGNED INTEGER) as y,         	
	        if(detinspeccion.nrohuevosovitrampa = 0 ,'#03a9f4',if(detinspeccion.nrohuevosovitrampa > 0 and detinspeccion.nrohuevosovitrampa <= 60 ,'#60C248',if(detinspeccion.nrohuevosovitrampa >60 and detinspeccion.nrohuevosovitrampa <=120 ,'#ffde17',if(detinspeccion.nrohuevosovitrampa > 120 and detinspeccion.nrohuevosovitrampa <= 150 ,'#F57E1F','#FF6C69'  )))) as color ,
            detinspeccion.estadoviviendainspeccion
        FROM
            detinspeccion
        INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
        WHERE
            YEAR (
                detinspeccion.fechainspeccionovitrampa
            ) = '$anio'
        AND inspeccion.idipress = '$ipress'
        AND inspeccion.idubigeo = '$idubigeo'
        AND detinspeccion.codigoovitrampa IS NOT NULL
        AND detinspeccion.estadoviviendainspeccion = 1 
        and detinspeccion.codigoovitrampa='$codovi'
        ORDER BY
            WEEK (
                detinspeccion.fechainspeccionovitrampa,
                3
            ) ASC";


        $res=$this->db->query($q)->result_array();
        $dt=[];
        $soloNumeros = intval(preg_replace('/[^0-9]+/', '', $codovi), 10);
        $soloLetras = preg_replace('/[0-9]/', '', $codovi);
        $codovivalid=$soloLetras.$soloNumeros;
        foreach($res as $k=>$i){
            $dd=$this->getDataVigilanciaByOvi($anio,$idubigeo,$ipress,$codovivalid,$idvivienda=null,$i["name"]);
            $dt[]=array(
                "codigoovitrampa"=>$i["codigoovitrampa"] ,
                "name"=>$i["name"],
                "y"=>$i["y"],
                "color"=>$i["color"],
                "estadoviviendainspeccion"=>$i["estadoviviendainspeccion"],
                "datavigi"=>$dd
            );
        }
        return $dt;
    }

    public function getOvitrampasConsolidadoBySector($anio,$idubigeo,$idipress){
        $where="";
        if($idipress != 0){
            $where= " AND inspeccion.idipress = '$idipress' ";
        }
        $query="SELECT  				
				dist.DESC_DIST,
                establec.DESC_ESTAB ,
                detinspeccion.sectorovitrampa,
                inspeccion.idipress,
                inspeccion.idubigeo						 
                FROM
                detinspeccion
                INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress AND establec.COD_ESTAB = inspeccion.idipress
                where
                YEAR (
                    detinspeccion.fechainspeccionovitrampa
                ) = $anio         
                $where      
                AND inspeccion.idubigeo = '$idubigeo'                 
				GROUP BY 
				dist.DESC_DIST,
                establec.DESC_ESTAB ,
                detinspeccion.sectorovitrampa			";
        $res=$this->db->query($query)->result_array();
        $dt=null;
        foreach($res as $k=>$i){
            $dataSemanasBySector=$this->_getDataSemanasBySector($anio,$idubigeo,$i["idipress"],$i["sectorovitrampa"]);
            $dt[]=array(
                "DESC_DIST"=>$i["DESC_DIST"],
                "DESC_ESTAB"=>$i["DESC_ESTAB"] ,
                "sectorovitrampa"=>$i["sectorovitrampa"],
                "idubigeo"=>$i["idubigeo"],
                "idipress"=>$i["idipress"],
                "semanas"=>$dataSemanasBySector
            );
        }

        return $dt ;

    }

    public function _getDataSemanasBySector($anio,$idubigeo,$idipress,$idsector){
        $query="Select dataSemanas.DESC_DIST,
                 dataSemanas.DESC_ESTAB ,
                dataSemanas.sectorovitrampa, 
				 dataSemanas.semana  ,
				 sum(dataSemanas.ispositive) as cantpositives,
                 sum(dataSemanas.isinspeccionado)	 as 	cantinspeccionados,		
                 sum(dataSemanas.ispositive)/sum(dataSemanas.isinspeccionado) as ipo,
                 sum(dataSemanas.nrohuevosovitrampa)/sum(dataSemanas.ispositive) as idh
                 					
				  from (							 
                                      SELECT  				
                                                    dist.DESC_DIST,
                                    establec.DESC_ESTAB ,
                                    detinspeccion.sectorovitrampa,
                                                    detinspeccion.codigoovitrampa,
                                                    if(detinspeccion.nrohuevosovitrampa > 0,1,0) as ispositive ,
                                                    if(estadoviviendainspeccion =1,1,0) as isinspeccionado,
                                     WEEK(detinspeccion.fechainspeccionovitrampa,3)  as  semana ,
                                       detinspeccion.nrohuevosovitrampa 
                                    FROM
                                    detinspeccion
                                    INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                                    INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                                    INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                                    INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress AND establec.COD_ESTAB = inspeccion.idipress
                                    where
                                    YEAR (
                                        detinspeccion.fechainspeccionovitrampa
                                    ) = $anio
                                    AND inspeccion.idipress = '$idipress' 
                                    AND inspeccion.idubigeo = '$idubigeo'
                                     and detinspeccion.sectorovitrampa=$idsector
                                     order by  WEEK(detinspeccion.fechainspeccionovitrampa,3) asc
                                 
                                    ) as dataSemanas 
                 GROUP BY 
                 dataSemanas.DESC_DIST,
                 dataSemanas.DESC_ESTAB ,
                 dataSemanas.sectorovitrampa,
                    dataSemanas.semana
                ";
        $res=$this->db->query($query)->result_array();
        return $res;

    }

   public function getOvitrampasConsolidadoByIpress($anio,$idubigeo,$idipress){
        $query="SELECT
                    dist.DESC_DIST,
                    establec.DESC_ESTAB,
                    inspeccion.idipress,
                    inspeccion.idubigeo
                FROM
                    detinspeccion
                INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress
                AND establec.COD_ESTAB = inspeccion.idipress
                WHERE
                    YEAR (
                        detinspeccion.fechainspeccionovitrampa
                    ) = $anio                
                AND inspeccion.idubigeo = '$idubigeo' 
                GROUP BY dist.DESC_DIST,
                         establec.DESC_ESTAB ";
       $res=$this->db->query($query)->result_array();
       $dt=null;
       foreach($res as $k=>$i){
           $dataSemanasByIpress=$this->_getDataSemanasByIPRESS($anio,$idubigeo,$i["idipress"]);
           $dt[]=array(
               "DESC_DIST"=>$i["DESC_DIST"],
               "DESC_ESTAB"=>$i["DESC_ESTAB"] ,
               "idubigeo"=>$i["idubigeo"],
               "idipress"=>$i["idipress"],
               "semanas"=>$dataSemanasByIpress
           );
       }

       return $dt ;
   }

    private function _getDataSemanasByIPRESS($anio,$idubigeo,$idipress){
        $query="Select dataSemanas.DESC_DIST,
                 dataSemanas.DESC_ESTAB ,          
				 dataSemanas.semana  ,
				 sum(dataSemanas.ispositive) as cantpositives,
                 sum(dataSemanas.isinspeccionado)	 as 	cantinspeccionados,		
                 sum(dataSemanas.ispositive)/sum(dataSemanas.isinspeccionado) as ipo,
                  sum(dataSemanas.nrohuevosovitrampa)/sum(dataSemanas.ispositive) as idh					
				  from (							 
                                  SELECT  				
                                   dist.DESC_DIST,
                                    establec.DESC_ESTAB ,
                               
                                                    detinspeccion.codigoovitrampa,
                                                    if(detinspeccion.nrohuevosovitrampa > 0,1,0) as ispositive ,
                                                    if(estadoviviendainspeccion =1,1,0) as isinspeccionado,
                                     WEEK(detinspeccion.fechainspeccionovitrampa,3)  as  semana,
                                          detinspeccion.nrohuevosovitrampa 
                                    FROM
                                    detinspeccion
                                    INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                                    INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                                    INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                                    INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress AND establec.COD_ESTAB = inspeccion.idipress
                                    where
                                    YEAR (
                                        detinspeccion.fechainspeccionovitrampa
                                    ) = $anio
                                    AND inspeccion.idipress = '$idipress' 
                                    AND inspeccion.idubigeo = '$idubigeo'                                 
                                     order by  WEEK(detinspeccion.fechainspeccionovitrampa,3) asc
                                 
                                    ) as dataSemanas 
                 GROUP BY 
                 dataSemanas.DESC_DIST,
                 dataSemanas.DESC_ESTAB ,          
                  dataSemanas.semana
                ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getOvitrampasConsolidadoByDistrito($anio,$idubigeo,$idipress){

        $query="SELECT
                    dist.DESC_DIST,                           
                    inspeccion.idubigeo
                FROM
                    detinspeccion
                INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress
                AND establec.COD_ESTAB = inspeccion.idipress
                WHERE
                    YEAR (
                        detinspeccion.fechainspeccionovitrampa
                    ) = $anio                
                AND inspeccion.idubigeo = '$idubigeo' 
                GROUP BY dist.DESC_DIST      ";
        $res=$this->db->query($query)->result_array();
        $dt=null;
        foreach($res as $k=>$i){
            $dataSemanasByDistrito=$this->_getDataSemanasByDistrito($anio,$idubigeo);
            $dt[]=array(
                "DESC_DIST"=>$i["DESC_DIST"],
                "idubigeo"=>$i["idubigeo"],
                "semanas"=>$dataSemanasByDistrito
            );
        }

        return $dt ;
    }

    private function _getDataSemanasByDistrito($anio,$idubigeo){
        $query="Select dataSemanas.DESC_DIST,                           
				 dataSemanas.semana  ,
				 sum(dataSemanas.ispositive) as cantpositives,
                 sum(dataSemanas.isinspeccionado)	 as 	cantinspeccionados,		
                 sum(dataSemanas.ispositive)/sum(dataSemanas.isinspeccionado) as ipo,
                 sum(dataSemanas.nrohuevosovitrampa) as sumnhuevos,
                 sum(dataSemanas.nrohuevosovitrampa)/sum(dataSemanas.ispositive) as idh
				  from (							 
                                   SELECT  				
                                        dist.DESC_DIST,                   
                                        detinspeccion.codigoovitrampa,
                                        if(detinspeccion.nrohuevosovitrampa > 0,1,0) as ispositive ,
                                        if(estadoviviendainspeccion =1,1,0) as isinspeccionado,
                                        detinspeccion.nrohuevosovitrampa,
                                        WEEK(detinspeccion.fechainspeccionovitrampa,3)  as  semana   
                                    FROM
                                    detinspeccion
                                    INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                                    INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                                    INNER JOIN dist ON dist.UBIGEO = inspeccion.idubigeo
                                    INNER JOIN establec ON establec.COD_ESTAB = inspeccion.idipress AND establec.COD_ESTAB = inspeccion.idipress
                                    where
                                    YEAR (
                                        detinspeccion.fechainspeccionovitrampa
                                    ) = $anio                                    
                                    AND inspeccion.idubigeo = '$idubigeo'                                 
                                     order by  WEEK(detinspeccion.fechainspeccionovitrampa,3) asc
                                 
                                    ) as dataSemanas 
                 GROUP BY 
                 dataSemanas.DESC_DIST,                         
                  dataSemanas.semana
                ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }


   public function getListOvitrampasByIpressByIddetinspeccion($ipress ,$idinspeccion,$finspeccion,$iddetins){
        $query="SELECT
        detinspeccion.codigoovitrampa,
        inspeccion.idipress,
        viviendas.direccion,
        viviendas.nro,
        detinspeccion.ubicacionovitrampa as ubicacionenvivienda,
        detinspeccion.sectorovitrampa as idsector,
        detinspeccion.mzovitrampa as idmanzana, 
        detinspeccion.latovitrampa as lat,
        detinspeccion.lngovitrampa as lng,
        detinspeccion.idvivienda,
        1 isregistered,
        detinspeccion.fechainspeccionovitrampa,
        inspeccion.idinspeccion,
        detinspeccion.iddetinspeccion,
        detinspeccion.nrohuevosovitrampa,
        detinspeccion.estadoviviendainspeccion,
        if(inspeccion.idinspeccion = $idinspeccion  ,'registradorins','noregistradorins') as isregistradoins
        FROM
        detinspeccion
        INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
        INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
        where inspeccion.idtipoinspeccion=2 and inspeccion.estado>0 and detinspeccion.estado>0
        and  week(inspeccion.finspeccion,3) = week('$finspeccion',3) and   detinspeccion.iddetinspeccion=$iddetins";
        $r=$this->db->query($query)->result_array();
        return $r;
   }

   public function _issetFechaInspeccion($selEstablecimientoSalud,$finspeccion){
        $query="SELECT
                count(inspeccion.idinspeccion) as c
                FROM
                inspeccion
                WHERE
                inspeccion.idipress='$selEstablecimientoSalud' and inspeccion.finspeccion='$finspeccion'";
        $r=$this->db->query($query)->result_array();
        return $r[0]["c"];
   }

}