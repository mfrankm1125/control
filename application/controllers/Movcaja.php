<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movcaja extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    public $table="movimientoscaja";
    public $pk_idtable="idmovimientoscaja";
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
        $this->template->renderizar('movcaja/index');
    }


    public function getData(){
        $this->load->library('Datatables');
        $this->datatables->select('
             nconceptocaja,
             ntipoconceptocaja,
             nmoneda,
             monedaabreviado,
             monto,            
             fecharegistro,
             idtipoconceptocaja,            
             descmovcaja,
             idmovimientoscaja,
             idconceptocaja,
             idmoneda,
             ingreso,
             inversion,
             egreso
            
                 ')->from('(
                SELECT
conceptocaja.nombre as nconceptocaja,
tipoconceptocaja.nombre as ntipoconceptocaja,
moneda.nombre as nmoneda,
moneda.abreviado as monedaabreviado,
movimientoscaja.monto,
movimientoscaja.fecharegistro,
tipoconceptocaja.idtipoconceptocaja,
movimientoscaja.descripcion as descmovcaja,
movimientoscaja.idmovimientoscaja,
movimientoscaja.idconceptocaja,
movimientoscaja.moneda as idmoneda,
if( lower(tipoconceptocaja.nombre) = "ingreso",concat(moneda.abreviado," ",movimientoscaja.monto),0) as ingreso,
if( lower(tipoconceptocaja.nombre) = "inversión",concat(moneda.abreviado," ",movimientoscaja.monto),0) as inversion,
if( lower(tipoconceptocaja.nombre) = "egreso",concat(moneda.abreviado," ",movimientoscaja.monto),0) as egreso
FROM
movimientoscaja
INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
INNER JOIN moneda ON moneda.idmoneda = movimientoscaja.moneda 
where movimientoscaja.estado >0
order by movimientoscaja.fecharegistro desc
             
            )temp  ')->order_by(" temp.fecharegistro desc ");

        echo $this->datatables->generate();
    }

    private function getMovCaja($fini=null,$fend=null){
        $where="";
        if($fini){
            $where.=" and movimientoscaja.fecharegistro between '$fini' and '$fend' " ;
        }
        $query="SELECT
        conceptocaja.nombre as nconceptocaja,
        tipoconceptocaja.nombre as ntipoconceptocaja,
        moneda.nombre as nmoneda,
        moneda.abreviado as monedaabreviado,
        movimientoscaja.monto,
        movimientoscaja.fecharegistro,
        tipoconceptocaja.idtipoconceptocaja,
        movimientoscaja.descripcion as descmovcaja,
        movimientoscaja.idmovimientoscaja,
        movimientoscaja.idconceptocaja,
        movimientoscaja.moneda as idmoneda,
        if( lower(tipoconceptocaja.nombre) = \"ingreso\",concat(moneda.abreviado,\" \",movimientoscaja.monto),0) as ingreso,
        if( lower(tipoconceptocaja.nombre) = \"inversión\",concat(moneda.abreviado,\" \",movimientoscaja.monto),0) as inversion,
        if( lower(tipoconceptocaja.nombre) = \"egreso\",concat(moneda.abreviado,\" \",movimientoscaja.monto),0) as egreso
        FROM
        movimientoscaja
        INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
        INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
        INNER JOIN moneda ON moneda.idmoneda = movimientoscaja.moneda 
        where movimientoscaja.estado >0 $where
        order by movimientoscaja.fecharegistro desc";
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
            $descripcion=$post["descripcion"];
            $fecharegistro=$post["fecharegistro"];
            $data=array(
                "idconceptocaja"=>$concepto ,
                "moneda"=>$moneda ,
                 "monto"=>$monto,
                 "idreferencia"=>"",
                 "tablareferencia"=>"",
                "fecharegistro"=>$fecharegistro ,
                "descripcion"=>$descripcion,
                "fechareg"=>$fecharegistro
            );

            if( $isEdit == 0 ){
                $data['estado']=1;
                $this->db->insert($this->table, $data);
                $return['status']='ok';
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
            $condicion=array($this->pk_idtable =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update($this->table, $dataD);
            $return['status']='ok';
        }else{
           $return['satatus']='Fallo ';
        }
        echo json_encode($return);
    }

    public function getTipoConceptoCaja(){
        $query="SELECT
            tipoconceptocaja.nombre,
            tipoconceptocaja.descripcion,
            tipoconceptocaja.idtipoconceptocaja
            FROM
            tipoconceptocaja
            where tipoconceptocaja.estado>0";
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
            $where=" and conceptocaja.idtipoconceptocaja=$idtipoconceptocaja";
        }
        if($idconcepto){
            $where=" and conceptocaja.idconceptocaja=$idconcepto";
        }
        $query="SELECT
            conceptocaja.idconceptocaja,
            conceptocaja.idtipoconceptocaja,
            conceptocaja.nombre,
            conceptocaja.descripcion             
            FROM
            conceptocaja
            where conceptocaja.estado>0 $where";
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
            $this->db->insert("conceptocaja",$dt);
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
        $queryXX="SELECT
    tipoconceptocaja.nombre,
    SUM(movimientoscaja.monto) as montos,
    moneda.nombre as nmoneda
    FROM
    movimientoscaja
    INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscaja.moneda
    where movimientoscaja.estado>0
    group by tipoconceptocaja.nombre,moneda.nombre   " ;

     $query='   select SUM(if(cajax.nombre ="Ingreso",cajax.montos,0)) as Ingreso,
					SUM(if(cajax.nombre ="Inversión",cajax.montos,0)) as Inversion	,
					SUM(if(cajax.nombre ="Egreso",cajax.montos,0)) as Egreso,
					cajax.nmoneda 
     from (SELECT
    tipoconceptocaja.nombre,
    SUM(movimientoscaja.monto) as montos,
    moneda.nombre as nmoneda
    FROM
    movimientoscaja
    INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscaja.moneda
    where movimientoscaja.estado>0
    group by tipoconceptocaja.nombre,moneda.nombre ) as cajax  
GROUP BY cajax.nmoneda ';

       $r=$this->db->query($query)->result_array();
       echo json_encode($r);

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
           $return["data"]=$d;
           $return['status']='ok';
        }else{
        $return['satatus']='Fallo ';
        }
        echo json_encode($return);
   }

   public function getReportPieByTipoConcepto(){
       $return["status"]=null;
       $post=$this->input->post(null,true);
       if(sizeof($post) > 0){

           $fechaini=$post["fechaini"];
           $fechaend=$post["fechaend"];
           $tipoconcepto=$post["tipo"];
           $tasacambio=$post["tasacambio"];
           $q="SELECT 
            DISTINCT
            conceptocaja.idconceptocaja,
            conceptocaja.nombre,
            conceptocaja.idtipoconceptocaja
            FROM
            movimientoscaja
            INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
            INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
            where movimientoscaja.estado > 0 and tipoconceptocaja.nombre='$tipoconcepto' and movimientoscaja.fechareg BETWEEN '$fechaini' and '$fechaend' ";
           $r=$this->db->query($q)->result_array();
           $dt=[];
           foreach($r as $k=>$i){
               $dataCalMontoByConcepto=$this->getCalcMontoByConcepto($i["idconceptocaja"],$fechaini,$fechaend,$tasacambio);
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

   private function getCalcMontoByConcepto($idconceptocaja,$fechaini,$fechaend,$tasacambio){
        $q="SELECT DISTINCT
        moneda.abreviado,
        moneda.nombre,
        movimientoscaja.monto
        FROM
        movimientoscaja
        INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = movimientoscaja.idconceptocaja
        INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
        INNER JOIN moneda ON moneda.idmoneda = movimientoscaja.moneda
        where movimientoscaja.estado > 0 and movimientoscaja.idconceptocaja=$idconceptocaja 
          and movimientoscaja.fechareg BETWEEN '$fechaini' and '$fechaend' ";
       $r=$this->db->query($q)->result_array();
       $montoTotal=0;
       foreach($r as $k=>$i){
         if($i["abreviado"] =="S/"){
             $montoTotal=$montoTotal+ floatval($i["monto"]);
         }else if($i["abreviado"] =="$"){
             $montoTotal=$montoTotal+ (floatval($i["monto"])*floatval($tasacambio));
         }

       }

       return $montoTotal;
   }

}
?>