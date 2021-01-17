<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movcajadolares extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    public $table="movimientoscajad";
    public $pk_idtable="idmovimientoscajad";
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
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        //$this->template->add_js('view','index');
        $this->template->set_data("tipoConcepto",$this->getTipoConceptoCaja());
        $this->template->renderizar('movcaja/indexdolares');
    }


    public function getData($fini){
        $where="   "  ;
       //if($this->session->userdata('id_role') == 18){
           $where=" and date(movimientoscajad.fecharegistro) = '$fini' " ;
           $where="  " ;
       // }

        $this->load->library('Datatables');
        $this->datatables->select('
             nconceptocaja,
             ntipoconceptocaja,
             nmoneda,
             monedaabreviado,
             monto,            
             fecharegistro,
             idconceptocaja,            
             descmovcaja,
             idmovimientoscajad,            
             idmoneda,
             ingreso,
             inversion,
             egreso,
             montodolar,
             idtipoconceptocaja,
             idreferencia,
             tablareferencia
            
                 ')->from('(
                SELECT
conceptocajad.nombre as nconceptocaja,
tipoconceptocaja.nombre as ntipoconceptocaja,
moneda.nombre as nmoneda,
moneda.abreviado as monedaabreviado,
movimientoscajad.monto,
movimientoscajad.fecharegistro,
movimientoscajad.idreferencia,
movimientoscajad.tablareferencia,
tipoconceptocaja.idtipoconceptocaja,
movimientoscajad.descripcion as descmovcaja,
movimientoscajad.idmovimientoscajad,
movimientoscajad.idconceptocaja,
movimientoscajad.moneda as idmoneda,
movimientoscajad.montodolar,
if( lower(tipoconceptocaja.nombre) = "ingreso",concat(moneda.abreviado," ",movimientoscajad.monto),0) as ingreso,
if( lower(tipoconceptocaja.nombre) = "inversión",concat(moneda.abreviado," ",movimientoscajad.monto),0) as inversion,
if( lower(tipoconceptocaja.nombre) = "egreso",concat(moneda.abreviado," ",movimientoscajad.monto),0) as egreso
FROM
movimientoscajad
INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda 
where movimientoscajad.estado >0  '.$where.' 
order by   movimientoscajad.fecharegistro desc   
             
            )temp  ')->order_by(" temp.fecharegistro desc");

        echo $this->datatables->generate();
    }

    private function getMovCaja($fini=null,$fend=null){
        $where="";
        if($fini){
            $where.=" and date(movimientoscajad.fecharegistro) between '$fini' and '$fend' " ;
        }
        $query="SELECT
        conceptocajad.nombre as nconceptocaja,
        tipoconceptocaja.nombre as ntipoconceptocaja,
        moneda.nombre as nmoneda,
        moneda.abreviado as monedaabreviado,
        movimientoscajad.monto,
        movimientoscajad.fecharegistro,
        tipoconceptocaja.idtipoconceptocaja,
        movimientoscajad.descripcion as descmovcaja,
        movimientoscajad.idmovimientoscajad,
        movimientoscajad.idconceptocaja,
        movimientoscajad.moneda as idmoneda,
        movimientoscajad.montodolar,
        if( lower(tipoconceptocaja.nombre) = \"ingreso\",concat(moneda.abreviado,\" \",movimientoscajad.monto),0) as ingreso,
        if( lower(tipoconceptocaja.nombre) = \"inversión\",concat(moneda.abreviado,\" \",movimientoscajad.monto),0) as inversion,
        if( lower(tipoconceptocaja.nombre) = \"egreso\",concat(moneda.abreviado,\" \",movimientoscajad.monto),0) as egreso
        FROM
        movimientoscajad
        INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
        INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
        INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda 
        where movimientoscajad.estado >0 $where
        order by movimientoscajad.idmovimientoscajad asc";
                $r=$this->db->query($query)->result_array();
                return $r;
    }

    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            //echo $this->showErrors($post);

            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $tconcepto=$post["tconcepto"];
            $concepto=$post["concepto"];
            $monto=$post["monto"];
            $moneda=$post["moneda"];

            $tcc=$post["tcc"];
            $tcv=$post["tcv"];

            $descripcion=$post["descripcion"];
            $fecharegistro=$post["fecharegistro"];
            $fecharegistro=$fecharegistro." ".date("H:i:s");
            $data=array(
                "idconceptocaja"=>$concepto ,
                "moneda"=>$moneda ,
                 "monto"=>$monto,
                 "idreferencia"=>"",
                 "tablareferencia"=>"",
                "fecharegistro"=>$fecharegistro ,
                "descripcion"=>$descripcion,
                "fechareg"=>$fecharegistro,
                "tcambiocompra"=>$tcc,
                "tcambioventa"=>$tcv

            );

            if( $isEdit == 0 ){
                $data['estado']=1;

                if($concepto == 1 || $concepto ==  5){
                    if($moneda == "1"){
                        $montorealCaja=$monto;
                        $idconcepto=$concepto;
                        $idmoneda="1";
                        $montoencaja=0;
                        $fechaenviocaja=$fecharegistro;
                        $descripcionenviocaja="";
                        $idref="";
                        $tablaref="cajad";
                        $data=array(
                            "idconceptocaja"=>$idconcepto,
                            "moneda"=>$idmoneda,
                            "monto"=>$montorealCaja,
                            "fecharegistro"=>$fechaenviocaja,
                            "descripcion"=>$descripcionenviocaja,
                            "estado"=>1,
                            "fechareg"=>$this->getfecha_actual(),
                            "idreferencia"=>$idref,
                            "tablareferencia"=>$tablaref,
                            "montodolar"=>$montoencaja,
                            "isregegreso"=>0

                        );
                        $this->db->insert("movimientoscajad", $data);
                        $return['status']='ok';
                    }else{

                        $montorealCaja=0;
                        $dataCompra=array(
                            "idproveedor"=>99999,
                            "nrodoc"=> "",
                            "fechacompra"=>$fecharegistro,
                            "isvisible"=>0
                        );

                        $dataCompra["estado"]=1;
                        $dataCompra["fechareg"]=$this->getfecha_actual();
                        $this->db->trans_start();
                        $this->db->insert("comprad", $dataCompra);
                        $idMax = $this->db->insert_id();
                        $this->db->trans_complete();
                        $detCompra=array(
                            "idcomprad"=>$idMax  ,
                            "idmoneda"=>$moneda   ,
                            "tasacambiocompra"=>$tcc   ,
                            "tasacambioventa"=>$tcv  ,
                            "montocompra"=>$monto   ,
                            "montocambio"=>""  ,
                            "fechareg"=>$this->getfecha_actual()  ,
                            "estado"=>1,
                            "isvisible"=>1

                        );
                        $this->db->insert("detcomprad", $detCompra);
                        $idconcepto=$concepto;
                        $idmoneda="2";
                        $montoencaja=$monto;
                        $fechaenviocaja=$fecharegistro;
                        $descripcionenviocaja="";
                        $idref=$idMax;
                        $tablaref="comprad";
                        $data=array(
                            "idconceptocaja"=>$idconcepto,
                            "moneda"=>$idmoneda,
                            "monto"=>$montorealCaja,
                            "fecharegistro"=>$fechaenviocaja,
                            "descripcion"=>$descripcionenviocaja,
                            "estado"=>1,
                            "fechareg"=>$this->getfecha_actual(),
                            "idreferencia"=>$idref,
                            "tablareferencia"=>$tablaref,
                            "montodolar"=>$montoencaja,
                            "isregegreso"=>0

                        );
                        $this->db->insert("movimientoscajad", $data);
                        $return['status']='ok';

                    }



                }else if($concepto == 2){

                    $idconcepto="2";
                    $idmoneda="1";
                    $montoencaja=$monto;
                    $fechaenviocaja=$fecharegistro;
                    $descripcionenviocaja="";
                    $idref=0;
                    $tablaref="cajad";
                    $data=array(
                        "idconceptocaja"=>$idconcepto,
                        "moneda"=>$idmoneda,
                        "monto"=>$montoencaja,
                        "fecharegistro"=>$fechaenviocaja,
                        "descripcion"=>$descripcionenviocaja,
                        "estado"=>1,
                        "fechareg"=>$this->getfecha_actual(),
                        "idreferencia"=>$idref,
                        "tablareferencia"=>$tablaref,
                        "montodolar"=>0,
                        "isregegreso"=>0

                    );
                    $this->db->insert("movimientoscajad", $data);
                    $return['status']='ok';

                }else{
                    $this->db->insert($this->table, $data);
                    $return['status']='ok';
                }


            }
            if( $isEdit == 1 ){
                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function delete(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $tabla=$post['tabla'];
            $idref=$post['idref'];

            if($tabla == "cajad" ){
                $tablax="movimientoscajad";
                $idpk="idmovimientoscajad";
            }else if($tabla == "comprad"){
                $tablax="comprad";
                $idpk="idcomprad";

            }else if($tabla == "ventad"){
                $tablax="ventad";
                $idpk="idventad";

            }


            $condicion=array($idpk =>$idref);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update($tablax, $dataD);
            $this->db->where(["idmovimientoscajad"=>$id])->update("movimientoscajad", $dataD);

            //$this->db->update('comprad', ["estado"=>0], ["idcomprad"=>$id]);
            $return['status']='ok';
        }else{
           $return['satatus']='Fallo ';
        }
        echo json_encode($return);
    }

    public function getTipoConceptoCaja(){
        $query="SELECT
            tipoconceptocajad.nombre,
            tipoconceptocajad.descripcion,
            tipoconceptocajad.idtipoconceptocaja
            FROM
            tipoconceptocajad
            where tipoconceptocajad.estado>0";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function getConceptoCaja(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $d=$this->_getConceptoCaja($id);
            $return["data"]=$d;
            $return['status']='ok';
        }else{
            $return['satatus']='Fallo ';
        }
        echo json_encode($return);
    }

    private function _getConceptoCaja($idtipoconceptocaja=null,$idconcepto=null){
        $where="";
        if($idtipoconceptocaja){
            $where=" and conceptocajad.idtipoconceptocaja=$idtipoconceptocaja";
        }
        if($idconcepto){
            $where=" and conceptocajad.idconceptocaja=$idconcepto";
        }
        $query="SELECT
            conceptocajad.idconceptocaja,
            conceptocajad.idtipoconceptocaja,
            conceptocajad.nombre,
            conceptocajad.descripcion             
            FROM
            conceptocajad
            where conceptocajad.estado>0 and conceptocajad.isvisible=1 $where";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function setConceptoCaja(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){

            $ttconcepto=$post["ttconcepto"];
            $nconcepto=$post["nconcepto"];
            $dt=array(
                "idtipoconceptocaja"=>$ttconcepto,
                "nombre"=>$nconcepto,
                 "estado"=>1
            );

            $this->db->trans_start();
            $this->db->insert("conceptocajad",$dt);
            $idMax = $this->db->insert_id();
            $this->db->trans_complete();
            $d=$this->_getConceptoCaja(null,$idMax);



            $return["data"]=$d;
            $return['status']='ok';
        }else{
            $return['satatus']='Fallo ';
        }
        echo json_encode($return);

    }

   public function getTotalMovs(){
        $post=$this->input->post(null,true);
       $fini=$post["fini"];

       $where=" and  date(movimientoscajad.fecharegistro)='$fini' ";
        $where="  ";
        $queryXX="SELECT
    tipoconceptocaja.nombre,
    SUM(movimientoscajad.monto) as montos,
    moneda.nombre as nmoneda
    FROM
    movimientoscajad
    INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscajad.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
    where movimientoscajad.estado>0
    group by tipoconceptocaja.nombre,moneda.nombre   " ;

     $query="   select SUM(if(cajax.nombre ='Ingreso',cajax.montos,0)) as Ingreso,
					SUM(if(cajax.nombre ='Inversión',cajax.montos,0)) as Inversion	,
					SUM(if(cajax.nombre ='Egreso',cajax.montos,0)) as Egreso,
					cajax.nmoneda 
     from (SELECT
    tipoconceptocaja.nombre,
    SUM(movimientoscajad.monto) as montos,
    moneda.nombre as nmoneda
    FROM
    movimientoscajad
    INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
    where movimientoscajad.estado>0   $where 
    group by tipoconceptocaja.nombre,moneda.nombre ) as cajax  
GROUP BY cajax.nmoneda ";

       $r=$this->db->query($query)->result_array();

       $qwwe="SELECT
        movimientoscajad.montodolar, 
        movimientoscajad.idconceptocaja,
        conceptocajad.idtipoconceptocaja,
        movimientoscajad.moneda 
         
        FROM
            movimientoscajad
            INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
            INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
            INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
        where movimientoscajad.estado>0  $where ";
       $rxasd=$this->db->query($qwwe)->result_array();
       $ingresoD=0;
       $Egreso=0;
       foreach($rxasd as $kkk=>$iii){
           if($iii["idtipoconceptocaja"] == "1"){

               if($iii["idconceptocaja"] == 1 || $iii["idconceptocaja"] == 5){
                   $ingresoD+=floatval($iii["montodolar"]);
               }else if($iii["idconceptocaja"] == 3){
                   $Egreso+=floatval($iii["montodolar"]);
               }
           }else {
               if($iii["idconceptocaja"] == 4){
                   $ingresoD+=floatval($iii["montodolar"]);
               }


           }
       }

//and date(movimientoscajad.fecharegistro) BETWEEN '2019-11-21' and '2019-11-21'
        $dd["data"]=$r;
        $dd["data2"]=array(
            "ingresodolares"=>$ingresoD,
               "egresodolares"=>$Egreso
        );
       echo json_encode($dd);

   }

   private function getTasaCambioForCaja(){

   }

   public function reporte(){
       $return["status"]=null;
       $post=$this->input->post(null,true);
       if(sizeof($post) > 0){
           $tipo=$post["tipo"];
           $fechaini=$post["fechaini"];
           $fechaend=$post["fechaend"];
           $d=$this->getMovCaja($fechaini,$fechaend);
           $dddd=$this->getStockDolaresByfechaEnd($fechaini);
           $dsoles=$this->getStockSolesByfechaEnd($fechaini);
           $return["data"]=$d;
           $return["dataStockDolares"]=$dddd;
           $return["dataStockSoles"]=$dsoles;
           $return['status']='ok';
        }else{
        $return['satatus']='Fallo ';
        }
        echo json_encode($return);
   }

   public function getStockSolesByfechaEnd($fechaini){


       $fechaini=date("Y-m-d",strtotime($fechaini."- 1 days"));
      $where=" and date(movimientoscajad.fecharegistro) between '0000-00-00' and '$fechaini'  ";





       $query2="   select SUM(if(cajax.nombre ='Ingreso',cajax.montos,0)) as Ingreso,
                    SUM(if(cajax.nombre ='Inversión',cajax.montos,0)) as Inversion  ,
                    SUM(if(cajax.nombre ='Egreso',cajax.montos,0)) as Egreso,
                    cajax.nmoneda 
     from (SELECT
    tipoconceptocaja.nombre,
    SUM(movimientoscajad.monto) as montos,
    moneda.nombre as nmoneda
    FROM
    movimientoscajad
    INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
    where movimientoscajad.estado>0  and   moneda.nombre='Soles' $where
    group by tipoconceptocaja.nombre,moneda.nombre ) as cajax  
GROUP BY cajax.nmoneda ";




       $r=$this->db->query($query2)->result_array();


//and date(movimientoscajad.fecharegistro) BETWEEN '2019-11-21' and '2019-11-21'


       if(sizeof($r)>0){

               $stock=floatval(floatval($r[0]["Ingreso"])-floatval($r[0]["Egreso"]) );

       }else{

               $stock=0;

       }



       return   $stock ;
   }
   public function getStockDolaresByfechaEnd($fechaini){
       $fechaini=date("Y-m-d",strtotime($fechaini."- 1 days"));
           $query="SELECT 
coalesce(sum(detventad.montoventa),0) as totalventamoneda
FROM
detventad
INNER JOIN ventad ON ventad.idventad = detventad.idventad
INNER JOIN detcomprad ON detcomprad.iddetcomprad = detventad.iddetproductod
INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda
INNER JOIN comprad ON comprad.idcomprad = detcomprad.idcomprad
where detcomprad.estado > 0 and comprad.estado>0 and detventad.estado>0 and ventad.estado>0
 and date(ventad.fechaventa) between '0000-00-00' and '$fechaini'
GROUP BY detcomprad.idmoneda
        ";
           $r=$this->db->query($query)->result_array();


           $query2="SELECT
coalesce(sum(detcomprad.montocompra),0) as totalcompradomoneda 
FROM
detcomprad
INNER JOIN comprad ON comprad.idcomprad = detcomprad.idcomprad
INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda
where detcomprad.estado>0 and comprad.estado>0 and date(comprad.fechacompra) between '0000-00-00' and '$fechaini'
GROUP BY detcomprad.idmoneda
        ";
           $r2=$this->db->query($query2)->result_array();
           $ss1=0;
           $ss2=0;
           if(sizeof($r)>0){
               $ss1=floatval($r[0]["totalventamoneda"]);
               if(is_null($ss1)){
                   $ss1=0;
               }
           }else{
               $ss1=0;
           }
           if(sizeof($r2)>0){
               $ss2=floatval($r2[0]["totalcompradomoneda"]);
               if(is_null($ss2)){
                   $ss2=0;
               }
           }else{
               $ss2=0;
           }


           $dd=$ss2-$ss1 ;
         return  $dd ;

   }

   public function getReportPieByTipoConcepto(){
       $return["status"]=null;
       $post=$this->input->post(null,true);
       if(sizeof($post) > 0){

           $fechaini=$post["fechaini"];
           $fechaend=$post["fechaend"];
           $tipoconcepto=$post["tipo"];

           $q="SELECT 
            DISTINCT
            conceptocajad.idconceptocaja,
            conceptocajad.nombre,
            conceptocajad.idtipoconceptocaja
            FROM
            movimientoscajad
            INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
            INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
            where movimientoscajad.estado > 0 and tipoconceptocaja.nombre='$tipoconcepto' and date(movimientoscajad.fecharegistro) BETWEEN '$fechaini' and '$fechaend' ";
           $r=$this->db->query($q)->result_array();
           $dt=[];
           foreach($r as $k=>$i){
               $dataCalMontoByConcepto=$this->getCalcMontoByConcepto($i["idconceptocaja"],$fechaini,$fechaend);
               $dt[]=array(
                   "idconceptocaja"=>$i["idconceptocaja"],
                   "name"=>$i["nombre"],
                   "y"=>floatval($dataCalMontoByConcepto),
               );

           }

           $return["data"]=$dt;
           $return['status']='ok';
       }else{
           $return['satatus']='Fallo ';
       }
       echo json_encode($return);


   }

   private function getCalcMontoByConcepto($idconceptocaja,$fechaini,$fechaend){
        $q="SELECT DISTINCT
        moneda.abreviado,
        moneda.nombre,
        movimientoscajad.monto
        FROM
        movimientoscajad
        INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
        INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
        INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
        where movimientoscajad.estado > 0 and movimientoscajad.idconceptocaja=$idconceptocaja 
          and  date(movimientoscajad.fecharegistro) BETWEEN '$fechaini' and '$fechaend' ";
       $r=$this->db->query($q)->result_array();
       $montoTotal=0;
       foreach($r as $k=>$i){
         if($i["abreviado"] =="S/"){
             $montoTotal=$montoTotal+ floatval($i["monto"]);
         }else if($i["abreviado"] =="$"){
             $montoTotal=$montoTotal+ (floatval($i["monto"]) );
         }

       }

       return $montoTotal;
   }


   public function printReportCaja1($fechaini,$fechaend){
       $d=$this->getMovCaja($fechaini,$fechaend);
       $dddd=$this->getStockDolaresByfechaEnd($fechaini);
       $return["fini"]=$fechaini;
       $return["fend"]=$fechaend;
       $return["data"]=$d;
       $return["dataStockDolares"]=$dddd;
       $ht=$this->load->view('movcaja/bodyReport',$return, true);
       $this->load->library('pdf');
       $this->pdf->loadHtml($ht);
       $this->pdf->render();
       $this->pdf->setPaper('A4', 'portrait');
       return $this->pdf->stream("dompdf_out.pdf", array("Attachment" => false));
   }
}
?>