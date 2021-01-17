<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model{
private $mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getYearsbyProdLeche(){
        $query="SELECT Year(prodleche.fechaprodleche) anio
                FROM
                prodleche
                where prodleche.estado=1
                GROUP BY Year(prodleche.fechaprodleche)";
        $result=$this->db->query($query)->result_array();
        return $result;        
    }

    public function getDataReportProdLeche($tipotiempo,$anio,$mesIni,$mesEnd){
        $data=null;
        if($tipotiempo=="anio"){
            $data=$this->getMesByAnioProdLeche($anio);

        }elseif($tipotiempo == "aniomes"){
            $res=$this->getMesByAnioProdLeche($anio,$mesIni);
            $data=[];
            foreach ($res as $key => $value) {
                 $dataDias=$this->getDiasByMesProdLeche($anio,$mesIni);   
                 $data[]=array(
                        "mes"=>$value["mes"],
                        "totalmaniana"=>$value["totalmaniana"],
                        "totaltarde"=>$value["totaltarde"],
                        "total"=>$value["total"],
                        "dataMes"=>$dataDias
                        );
            }

        }
        return $data;
    }

    public function getMesByAnioProdLeche($anio,$mesini=null){
        $where="";
        if($mesini !=null){
            $where="MONTH(prodleche.fechaprodleche)=".(int)$mesini." and ";
        }
        $query="SELECT
                MONTH(prodleche.fechaprodleche) as  mes,
                SUM(prodleche.cantmaniana) as totalmaniana,
                SUM(prodleche.canttarde) as totaltarde ,
                (SUM(prodleche.cantmaniana) +  SUM(prodleche.canttarde)) as total
                FROM
                prodleche
                where ".$where."  prodleche.estado=1 and Year(prodleche.fechaprodleche)  =".(int)$anio." GROUP BY MONTH(prodleche.fechaprodleche)
                order by MONTH(prodleche.fechaprodleche)";
        $result=$this->db->query($query)->result_array();
        return $result;          
    }

     public function getDiasByMesProdLeche($anio,$mesini){
        $query="SELECT
            prodleche.fechaprodleche,
            SUM(prodleche.cantmaniana) as totalmaniana,
            SUM(prodleche.canttarde) as totaltarde ,
            (SUM(prodleche.cantmaniana) +  SUM(prodleche.canttarde)) as total
            FROM
            prodleche
            where prodleche.estado=1 and 
            Year(prodleche.fechaprodleche)  = ".$anio." and MONTH(prodleche.fechaprodleche)=".$mesini."
            GROUP BY  prodleche.fechaprodleche
            order by  prodleche.fechaprodleche";
        $result=$this->db->query($query)->result_array();
        return $result;          
    }


    public function getDataStockProdLechebyFechaDia($fecha){
        $query="SELECT
                sum(prodleche.canttarde) as totaltarde,
                sum(prodleche.cantmaniana) as totalmaniana,
                sum(prodleche.canttarde) + sum(prodleche.cantmaniana) as totaldia
                FROM
                prodleche
                where prodleche.estado=1 and prodleche.fechaprodleche = '".$fecha."'";
         $result=$this->db->query($query)->result_array();
         return $result ;      
    }

    


    public function ifExistDataStockProdLecheByFechaDia($fecha){
        $query="SELECT
                 stockprodleche.fecha 
                FROM
                stockprodleche
                where  stockprodleche.estado=1 and stockprodleche.fecha='".$fecha."' ";
         $result=$this->db->query($query)->result_array();
         return $result;

    }

    public function getDataProdLecheByIdProdLeche($id=null){
        $data=null;
        if($id !=null ){
        $this->db->select('*');
        $this->db->from('prodleche');
        $this->db->where('estado', "1");
        $this->db->where('idprodleche',$id );        
        $data = $this->db->get()->result_array();
        }
       
       return $data;
    }

//---
    public function ifExistDataStockProdLeche(){
        $query="SELECT                
                prodlechestock.totalstock
                FROM
                prodlechestock
                where prodlechestock.estado=1"; 
         $result=$this->db->query($query)->result_array();
         return $result ;      
    }

    public function getDataProdLeche(){
        $query="SELECT
                sum(prodleche.canttarde) as totaltarde,
                sum(prodleche.cantmaniana) as totalmaniana,
                sum(prodleche.canttarde) + sum(prodleche.cantmaniana) as total
                FROM
                prodleche
                where prodleche.estado=1 ";
         $result=$this->db->query($query)->result_array();
         return $result ;      
    }

      public function getDataStockActualProleche(){
            $this->db->select('*');
            $this->db->from("prodlechestock");
            $this->db->where('estado', "1");
            $this->db->where('idcod', "stock");         
            $result=$this->db->get()->result_array();
            return $result ;      
        }

 //----------Reg Salida------
 
       public function getDataTipoSalida(){
            $this->db->select('*');
            $this->db->from("tiposalidaanimal");
            $this->db->where('estado', "1");                 
            $result=$this->db->get()->result_array();
            return $result ;      
        } 

        public function getPrecioLecheRef(){
            $this->db->select('*');
            $this->db->from("configuracion");
            $this->db->where('estado', "1");    
            $this->db->where('nombre', "precioleche");               
            $result=$this->db->get()->result_array();
            return $result ;      
        }
//---
        public  function getDataTipoAnimal($idwhere=null){
            $condicion["estado"]=1;
            if($idwhere !=null )$condicion["idtipoanimal"]=$idwhere;
            $this->db->select('*');
            $this->db->from("tipoanimales");
            $this->db->where($condicion);
            $this->db->order_by("nombre","desc");
            $result=$this->db->get()->result_array();
            return $result ;
        }

    public  function getDataTipoSalidaAnimal(){
        $this->db->select('*');
        $this->db->from("tiposalidaanimal");
        $this->db->where('estado', "1");
        $this->db->order_by("orden","asc");
        $result=$this->db->get()->result_array();
        return $result ;
    }
    public  function getDataTipoIngresoAnimal(){
        $this->db->select('*');
        $this->db->from("tipoingresoanimal");
        $this->db->where('estado', "1");
        $result=$this->db->get()->result_array();
        return $result ;
    }

    public  function getDataCliente(){
        $this->db->select('*');
        $this->db->from("clientes");
        $this->db->where('estado', "1");
        $result=$this->db->get()->result_array();
        return $result ;
    }

    public  function getDataProveedor(){
        $this->db->select('*');
        $this->db->from("proveedores");
        $this->db->where('estado', "1");
        $result=$this->db->get()->result_array();
        return $result ;
    }

    public  function getDataSexoAnimal(){
        $this->db->select('*');
        $this->db->from("sexo");
        $this->db->where('estado', "1");
        $this->db->order_by("nombre","desc");
        $result=$this->db->get()->result_array();
        return $result ;
    }

    public function getDataAnimalEdit($idanimal){
       
            $id=(int)$idanimal;
            $query="(SELECT
                        animales.idanimal,animales.codanimal
                    FROM
                        animales
                    INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal
                    WHERE
                        animales.estado = 1
                    AND animales.idtiposalidaanimal <= 0 
                    ORDER BY
                        codanimal DESC)                    
                    UNION
                    (SELECT
                        animales.idanimal,animales.codanimal
                    FROM
                        animales
                    INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal
                    WHERE
                        animales.estado = 1
                    AND animales.idanimal= ".$id." )";
            $return=$this->db->query($query)->result_array();
       return $return;
    }

    public function getDataAnimalSalida(){
        $condicion=["animales.estado"=>1,"animales.idtiposalidaanimal <="=>0];
        $this->db->select('animales.* , tipoanimales.nombre as tipoanimal');
        $this->db->from("animales");
        $this->db->join('tipoanimales', 'animales.idtipoanimal = tipoanimales.idtipoanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('codanimal', 'DESC');
        $dataanimal = $this->db->get()->result_array();
        $data=null;
        foreach ($dataanimal as $key=>$value ){

            $dataClases=$this->getDataClasesByAnimal($value["idanimal"]);

            $dataCol = array_column($dataClases, 'isclaseactual');
            $keyCol = array_search('1', $dataCol);
            $claseActual=$dataClases[$keyCol]["claseanimal"];
            $data[]=array(
                "idanimal"=>$value["idanimal"],
                "idtipoanimal"=>$value["idtipoanimal"],
                "idanimalpadre" =>$value["idanimalpadre"],
                "idanimalmadre"=>$value["idanimalmadre"],
                "codanimal"=>$value["codanimal"] ,
                "codraza"=>$value["codraza"],
                "apodo"=>"",
                "descripcion"=>"",
                "fechanacimiento"=>$value["fechanacimiento"] ,
                "sexo"=>$value["sexo"],
                "isHijo"=>null,
                "isPadre"=>null,
                "isMadre"=>null,
                "idtipoingresoanimal"=>$value["idtipoingresoanimal"],
                "idtiposalidaanimal"=>$value["idtiposalidaanimal"],
                "idcliente"=>$value["idcliente"],
                "idproveedor"=>$value["idproveedor"],
                "compradoa"=>$value["compradoa"],
                "vendidoa"=>$value["vendidoa"],
                "pureza"=>$value["pureza"] ,
                "proposito"=>$value["proposito"] ,
                "preciocompra"=>$value["preciocompra"] ,
                "precioventa"=>$value["precioventa"]  ,
                "pesonace"=>$value["pesonace"]  ,

                "estado"=>$value["estado"],
                "tipoanimal"=>$value["tipoanimal"],

                "dataclases"=>$dataClases,
                "claseactual"=>$claseActual
            );

        }
        return $data;
    }

    public function getDataClasesByAnimal($idanimal=null){
        $condicion=["detanimalxclaseanimal.idanimal"=>$idanimal
            ,"detanimalxclaseanimal.estado"=>1];

        $this->db->select('detanimalxclaseanimal.*,claseanimal.nombre as claseanimal ');
        $this->db->from("detanimalxclaseanimal");
        $this->db->join('claseanimal', 'detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('claseanimal.orden', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getDataClaseAnimalByTipoAnimalSexo($idtipoanimal,$idsexo){
        $this->db->select('* , claseanimal.nombre as claseanimal');
        $this->db->from("claseanimal");
        $this->db->where(["estado"=>1,"idtipoanimal"=>$idtipoanimal,"idsexo"=>$idsexo]);
        $this->db->order_by("orden","desc");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public  function getDataTipoProductos(){
        $this->db->select('*');
        $this->db->from("tipoproducto");
        $this->db->where('estado', "1");
        $this->db->order_by("nombre","desc");
        $result=$this->db->get()->result_array();
        return $result ;
    }

    public function getDataClaseAnimal($idwhere=null){
        $condicion["estado"]=1;
        if($idwhere)$condicion["idtipoanimal"]=$idwhere;
        $this->db->select('* , claseanimal.nombre as claseanimal');
        $this->db->from("claseanimal");
        $this->db->where($condicion);
        $this->db->order_by("claseanimal.idclaseanimal","asc");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getDataClaseAnimalbyTipoanimal($idwhere=null){
        $condicion["estado"]=1;
        if($idwhere)$condicion["idtipoanimal"]=$idwhere;
        $this->db->select('* , claseanimal.nombre as claseanimal');
        $this->db->from("claseanimal");
        $this->db->where($condicion);
        $this->db->order_by("claseanimal.idclaseanimal","asc");
        $result = $this->db->get()->result_array();
        $dd=null;
        foreach ($result as $k=>$v){
            $dataAnimal=$this->getAnimalesByClase($v["idclaseanimal"]);
            $dd[]=array(
                "idclaseanimal"=>$v["idclaseanimal"],
                "idtipoanimal"=>$v["idtipoanimal"],
                "idsexo"=>$v["idsexo"],
                "nombre"=>$v["nombre"],
                "descripcion"=>$v["descripcion"],
                "animales"=>$dataAnimal
            );
        }
        return $dd;
    }

    private function getAnimalesByClase($idclaseanimal=null){
        $query="SELECT
        count(animales.codraza) as cantidad     
        FROM
        animales
        INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
        where detanimalxclaseanimal.estado=1 and detanimalxclaseanimal.isclaseactual=1 
        and animales.estado=1 and detanimalxclaseanimal.idclaseanimal=".$idclaseanimal;
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getDataAnimalByClase($idclase=null){
        $condicion=["detanimalxclaseanimal.idclaseanimal"=>$idclase,
                    "detanimalxclaseanimal.estado"=>1,
                    "detanimalxclaseanimal.isclaseactual"=>1,
                    "animales.idtiposalidaanimal "=>0
        ];

        $this->db->select('detanimalxclaseanimal.* ,animales.codanimal,animales.fechanacimiento ,animales.codraza');
        $this->db->from("detanimalxclaseanimal");
        $this->db->join('animales', 'detanimalxclaseanimal.idanimal = animales.idanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('animales.codanimal', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }



    public function getAniosProdLeche(){
        $query="SELECT
                DISTINCT
                Year(prodleche.fechaprodleche) as anio
                FROM
                prodleche
                where prodleche.estado=1
                ORDER BY Year(prodleche.fechaprodleche) desc";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function getMesesProdLecheByAnio($anio){
         $d=null;   
        for($i=1 ; $i<=12 ;$i++){
            $dataMes=$this->getProdLecheByMesAnio($i,$anio);
            $c=sizeof($dataMes);
            if($c > 0  ){
                $prodMes=floatval($dataMes[0]["totaltotal"]);
                $d[]=array(
                    "name"=>$this->mes[$i],
                    "y"=>$prodMes
                );
            }
        }
        return $d;
    }

//----------------------------------------------

    public function getProdTotalDiaLecheByMesAnio($mes,$anio){
        $query="SELECT         
                DAY(fechaprodleche) as name ,       
               sum(prodleche.cantmaniana) + sum(prodleche.canttarde) + sum(prodleche.cantrecria) as y                 
              
                FROM
                prodleche
                where prodleche.estado=1 and Year(fechaprodleche)='".$anio."' and MONTH(fechaprodleche)=".$mes."
                GROUP BY prodleche.fechaprodleche order by DAY(fechaprodleche) asc";
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function getProdTotalDiaLecheSinRecriaByMesAnio($mes,$anio){
        $query="SELECT                 
                sum(prodleche.cantmaniana) + sum(prodleche.canttarde)  as y,                
                DAY(fechaprodleche) as name
                FROM
                prodleche
                where prodleche.estado=1 and Year(fechaprodleche)='".$anio."' and MONTH(fechaprodleche)=".$mes."
                GROUP BY prodleche.fechaprodleche order by DAY(fechaprodleche) asc";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function getProdTotalDiaLecheByMesAnio2($mes,$anio){
        $query="SELECT                 
                sum(prodleche.cantrecria) as valrecria,
                sum(prodleche.cantmaniana) + sum(prodleche.canttarde)  as totalsinrecria,                
                DAY(fechaprodleche) as dia
                FROM
                prodleche
                where prodleche.estado=1 and Year(fechaprodleche)='".$anio."' and MONTH(fechaprodleche)=".$mes."
                GROUP BY prodleche.fechaprodleche order by DAY(fechaprodleche) asc";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function getChartColum($anio){
        $data=null;
        for($i=1 ; $i<=12 ;$i++){
                $dtDba=$this->getProdLecheByMesAnioProd($i,$anio);
                $d[]=[$this->mes[$i]];
                $vendiblex=floatval($dtDba[0]["totalsincria"]);
                 $recria=floatval($dtDba[0]["recria"]);
                $dataVendible[]=$vendiblex;
                $dataRecria[]=$recria;
        }
        $data["xasis"]=$d;
        $data["vendible"]=$dataVendible;
        $data["recria"]=$dataRecria;
        return $data;
    }


//------------------------------------------------
    private function getProdLecheByMesAnio($mes,$anio){
        $query="SELECT 
        sum(prodleche.cantmaniana) as summaniana,
        sum(prodleche.canttarde) as sumtarde,
        sum(prodleche.cantrecria) as sumrecria,
        sum(prodleche.cantmaniana) + sum(prodleche.canttarde) + sum(prodleche.cantrecria) as totaltotal
        FROM
        prodleche
        where prodleche.estado=1 and month(prodleche.fechaprodleche)=".$mes." 
        and year(prodleche.fechaprodleche)=".$anio;
        $result = $this->db->query($query)->result_array();
        return $result;
    }




    public function getProdLecheByMesAnioProd($mes,$anio){
        $query="SELECT         
        cast(sum(prodleche.cantmaniana) + sum(prodleche.canttarde) as DECIMAL(10,2))  as totalsincria,
        sum(prodleche.cantrecria) as recria
        FROM
        prodleche
        where prodleche.estado=1 and month(prodleche.fechaprodleche)=".$mes." 
        and year(prodleche.fechaprodleche)=".$anio;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

//--------------

    public function getAniosSalida(){
        $query="SELECT
                DISTINCT
                year(salida.fechasalida) as anio
                FROM
                salida 
                where salida.estado =1 
                ORDER BY year(salida.fechasalida) desc";
        $result = $this->db->query($query)->result_array();
        return $result;

    }


    public function getMesesMontoSalidasByAnio($anio){
        $d=null;
        for($i=1 ; $i<=12 ;$i++){
            $dataMes=$this->getMontoSalidaByMesAnio($i,$anio);
            $c=sizeof($dataMes);
            if($c > 0  ){
                $prodMes=floatval($dataMes[0]["total"]);
                $d[]=array(
                    "name"=>$this->mes[$i],
                    "y"=>$prodMes
                );
            }
        }
        return $d;
    }

    private function getMontoSalidaByMesAnio($mes,$anio){
        $query="SELECT
        sum(salida.cantidad * salida.precio) as total
        FROM
        salida
        where salida.estado =1 and year(salida.fechasalida) =".$anio." and month(salida.fechasalida)=".$mes;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    //---Configuracion

    public function getPrecioVentaLeche(){
        $this->db->select('valor');
        $this->db->from("configuracion");
        $this->db->where(["idcod"=>"precioleche"]);
        $result = $this->db->get()->result_array();
        return $result[0]["valor"];
    }

    public function getValRecria(){
        $this->db->select('valor');
        $this->db->from("configuracion");
        $this->db->where(["idcod"=>"recria"]);
        $result = $this->db->get()->result_array();
        return $result[0]["valor"];
    }





}