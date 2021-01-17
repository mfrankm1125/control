<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compradolares extends CMS_Controller {
    private $_list_perm;

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

        $this->template->set_data('title',"Compras");
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data('proveedores',$this->getProveedores());
        $this->template->set_data("monedas",$this->getMonedas());

        $this->template->renderizar('compradolares/index');
    }

    public function getData(){

        $this->load->library('Datatables');
        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $this->datatables->select('
                idcomprad,                 
                 proveedor,
                 detcompraf,
                 fechacompra
                  ')->from('(
Select datacomprafinal.idcomprad,
datacomprafinal.fechacompra,
datacomprafinal.proveedor,
concat(\'[\',datacomprafinal.detcompraxxxx,\']\') as detcompraf

 from (Select comprad.idcomprad,
comprad.fechacompra,
proveedor.nombre as proveedor, 

GROUP_CONCAT(\'{"iddetcomprad":"\',detpagox.iddetcomprad
,\'","monedax":"\',detpagox.monedax
 ,\'","monedaav":"\',detpagox.abreviado
,\'","montocompra":"\',detpagox.montocompra
,\'","montocambio":"\',detpagox.montocambio
,\'","tasacambiocompra":"\',detpagox.tasacambiocompra
,\'","tasacambioventa":"\',detpagox.tasacambioventa 
,\'"}\') as detcompraxxxx

from 
comprad 
inner join proveedor on comprad.idproveedor=proveedor.idproveedor 
inner join (SELECT
detcomprad.iddetcomprad,
detcomprad.idmoneda, 
detcomprad.montocompra,
detcomprad.montocambio,
detcomprad.tasacambiocompra,
detcomprad.tasacambioventa,
m.nombre as monedax,
m.abreviado,
detcomprad.idcomprad
FROM
detcomprad 
INNER JOIN moneda as m ON m.idmoneda = detcomprad.idmoneda
where detcomprad.estado>0 
GROUP BY detcomprad.iddetcomprad ) as detpagox 
on comprad.idcomprad = detpagox.idcomprad  
where comprad.estado>0 and comprad.isvisible=1
GROUP BY comprad.idcomprad order by comprad.idcomprad desc,comprad.fechacompra desc) datacomprafinal         
            )temp ');

        echo $this->datatables->generate();
    }

    public function getBancoByMoneda(){
        $q="SELECT
            moneda.abreviado,
            banco.nombre AS nbanco,
            cuentabanco.nombre AS ncuentabanco,
            cuentabanco.nro,
            banco.abreviatura,
            cuentabanco.idcuentabanco
            FROM
            cuentabanco
            INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
            INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
            where cuentabanco.estado>0
            order by moneda.abreviado desc  ";
        $r=$this->db->query($q)->result_array();
        echo json_encode($r);
    }

    public function getMonedas(){
        $q="SELECT
            moneda.nombre as moneda,
            moneda.idmoneda 
            FROM
            moneda             
            where moneda.estado>0 order by  moneda.idmoneda desc ";
        $r=$this->db->query($q)->result_array();
        return $r;
    }

    public function setForm(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $post=$post["dataForm"][0];
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $proveedor=$post["proveedor"];
            $fecha=$post["fecha"]." ".date("H:i:s");


            $detallecompra=$post["detallecompra"];


            $dataCompra=array(
                "idproveedor"=>$proveedor,
                "nrodoc"=> "",
                "fechacompra"=>$fecha,
                 "isvisible"=>$detallecompra[0]["isVisibleVenta_"]
            );

            if($isEdit == 0){
                $dataCompra["estado"]=1;
                $dataCompra["fechareg"]=$this->getfecha_actual();
                $this->db->trans_start();
                $this->db->insert("comprad", $dataCompra);
                $idMax = $this->db->insert_id();
                $this->db->trans_complete();
                $montocompraxxxx=0;
                $totalMontoDolaresx=0;
                $isregegresooxxx_=0;
                foreach($detallecompra as $k=>$i ){
                    $montocompraxxxx=$montocompraxxxx+(floatval($i["cantcompra"]) *floatval($i["tcambio"])) ;
                    $totalMontoDolaresx=$totalMontoDolaresx+floatval($i["cantcompra"]);
                    $detCompra=array(
                        "idcomprad"=>$idMax  ,
                        "idmoneda"=>$i["idmoneda"]   ,
                        "tasacambiocompra"=>$i["tcambio"]   ,
                        "tasacambioventa"=>$i["tcambioventa"]   ,
                        "montocompra"=>$i["cantcompra"]   ,
                        "montocambio"=>""  ,
                        "fechareg"=>$this->getfecha_actual()  ,
                        "estado"=>1,
                        "isvisible"=>$i["isVisibleVenta_"],
                        "isregegreso"=>$i["isRegEgreso"]
                    );
                    $isregegresooxxx_=$i["isRegEgreso"];
                    $this->db->trans_start();
                    $this->db->insert("detcomprad", $detCompra);
                    $idMaxDet = $this->db->insert_id();
                    $this->db->trans_complete();
                    /*$detPagoCompra=[];
                    foreach($i["detPago"] as $u=>$j){
                        $detPagoCompra[]=array(
                            "iddetcomprad"=>$idMaxDet  ,
                            "idcuentabanco"=>$j["idcuentabanco"]   ,
                            "nroop"=>$j["nroop"]   ,
                            "monto"=>$j["monto"]   ,
                            "estado"=>1 ,
                            "fechareg"=>$this->getfecha_actual()
                        );
                    }
                    $this->db->insert_batch("detpagocomprad", $detPagoCompra);
                     */
                }
                $idconcepto="4";
                $idmoneda="1";
                $montoencaja=$montocompraxxxx;
                $fechaenviocaja=$fecha;
                $descripcionenviocaja="";
                $idref=$idMax;
                $tablaref="comprad";
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
                    "montodolar"=>$totalMontoDolaresx,
                    "isregegreso"=>$isregegresooxxx_

                );
                $this->db->insert("movimientoscajad", $data);


                $return["status"]="ok";
            }else{
                $this->db->update('comprad', $dataCompra, ["idcomprad"=>$idEdit]);

                $this->db->update('detcomprad',["estado"=>0], ["idcomprad"=>$idEdit]);
                $montocompraxxxx=0;
                $totalMontoDolaresx=0;
                $isregegresooxxx_=0;
                foreach($detallecompra as $k=>$i ){
                    $totalMontoDolaresx=$totalMontoDolaresx+floatval($i["cantcompra"]);
                    $montocompraxxxx=$montocompraxxxx+(floatval($i["cantcompra"]) *floatval($i["tcambio"])) ;
                    $iddetcompra=$i["iddetcompra"];
                    $detCompra=array(
                        "idcomprad"=>$idEdit  ,
                        "idmoneda"=>$i["idmoneda"]   ,
                        "tasacambiocompra"=>$i["tcambio"]   ,
                        "tasacambioventa"=>$i["tcambioventa"]   ,
                        "montocompra"=>$i["cantcompra"]   ,
                        "montocambio"=>""  ,
                        "estado"=>1,
                        "isvisible"=>$i["isVisibleVenta_"],
                        "isregegreso"=>$i["isRegEgreso"]

                    );
                    $isregegresooxxx_=$i["isRegEgreso"];
                    if($iddetcompra !=0 || $iddetcompra !="0" ){
                        $this->db->update('detcomprad',$detCompra, ["iddetcomprad"=>$iddetcompra]);
                    }else{
                        $detCompra["fechareg"]=$this->getfecha_actual();

                        $this->db->insert("detcomprad",$detCompra);
                        $iddetcompra = $this->db->insert_id();
                        $this->db->trans_complete();

                    }

                   /* $this->db->update('detpagocomprad',["estado"=>0], ["iddetcomprad"=>$iddetcompra]);
                    foreach($i["detPago"] as $u=>$j){
                        $iddetpagocomprad=$j["iddetpagocomprad"];
                        $detPagoCompra=array(
                            "iddetcomprad"=>$iddetcompra  ,
                            "idcuentabanco"=>$j["idcuentabanco"]   ,
                            "nroop"=>$j["nroop"]   ,
                            "monto"=>$j["monto"]   ,
                            "estado"=>1

                        );

                        if($iddetpagocomprad !=0 || $iddetpagocomprad !="0" ){
                            $this->db->update('detpagocomprad',$detPagoCompra, ["iddetpagocomprad"=>$iddetpagocomprad]);
                        }else{
                            $detPagoCompra["fechareg"]=$this->getfecha_actual();
                            $this->db->insert("detpagocomprad",$detPagoCompra);
                        }
                    }*/
                   /* $this->db->update('detpagocomprad',["estado"=>0], ["iddetcomprad"=>$iddetcompra]);
                    $this->db->insert_batch("detpagocomprad", $detPagoCompra);*/

                }

                $idcompra=$idEdit;
                $c=$this->verificaMontoVentaComprabyId($idcompra);
                if($c == 0){
                    $idconcepto="4";
                    $idmoneda="1";
                    $montoencaja=$montocompraxxxx;
                    $fechaenviocaja=$fecha;
                    $descripcionenviocaja="";
                    $idref=$idcompra;
                    $tablaref="comprad";
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
                        "montodolar"=>$totalMontoDolaresx,
                        "isregegreso"=>$isregegresooxxx_
                    );
                    $this->db->insert("movimientoscajad", $data);
                }else{
                    $idconcepto="4";
                    $idmoneda="1";
                    $montoencaja=$montocompraxxxx;
                    $fechaenviocaja=$fecha;
                    $descripcionenviocaja="";
                    $idref=$idcompra;
                    $tablaref="comprad";
                    $dataCaja=array(
                        "idconceptocaja"=>$idconcepto,
                        "moneda"=>$idmoneda,
                        "monto"=>$montoencaja,
                        "fecharegistro"=>$fechaenviocaja,
                        "descripcion"=>$descripcionenviocaja,
                        "idreferencia"=>$idref,
                        "tablareferencia"=>$tablaref,
                        "montodolar"=>$totalMontoDolaresx,
                        "isregegreso"=>$isregegresooxxx_
                    );
                    $this->db->update('movimientoscajad',$dataCaja, ["idreferencia"=>$idref,"tablareferencia"=>$tablaref,'idconceptocaja'=>$idconcepto]);
                }


                $return["status"]="ok";
            }




        }
        echo json_encode($return);
    }

    public function delete(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->update('comprad', ["estado"=>0], ["idcomprad"=>$id]);
            $this->db->where(["idreferencia"=>$id,"tablareferencia"=>"comprad"])->update("movimientoscajad",["estado"=>0]);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function getDataEdit(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $return["data"]=$this->_getDataCompra($id);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    private function _getDataCompra($id){

        $where="";
        if($id){
            $where=" and  comprad.idcomprad=$id"  ;
        }
        $query="SELECT
                DISTINCT
                comprad.idcomprad,
                comprad.idproveedor,
                comprad.nrodoc,
                comprad.fechacompra,
                comprad.fechareg,
                comprad.estado
                FROM
                comprad
                INNER JOIN detcomprad ON comprad.idcomprad = detcomprad.idcomprad
                where comprad.estado>0 and detcomprad.estado>0  $where ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            $detalleCompra=$this->_detalleCompra($i["idcomprad"]);
           // $detallePago=$this->_detallePago($i["idcompra"]); ;
            $dt[]=array(
                "idcomprad"=>$i["idcomprad"],
                "fechacompra"=>$i["fechacompra"],
                "idproveedor"=>$i["idproveedor"],
                "detallecompra"=>$detalleCompra
            );
        }
        return $dt;
    }
    private function _detalleCompra($id=null){
        $where="";
        if($id){
            $where=" and  detcomprad.idcomprad=$id"  ;
        }
        $query="select ddd.*  from (SELECT\n".
            "detcomprad.iddetcomprad,\n".
            "detcomprad.montocompra,\n".
            "detcomprad.montocambio,\n".
            "detcomprad.tasacambiocompra,\n".
            "detcomprad.tasacambioventa,\n".
            "m.nombre as monedax,\n".
            "detcomprad.idcomprad , \n".
            "detcomprad.isvisible , \n".
            "detcomprad.idmoneda \n".
            "FROM\n".
            "detcomprad\n".
            "INNER JOIN moneda as m ON m.idmoneda = detcomprad.idmoneda\n".
            "where detcomprad.estado>0  $where   \n".
            "GROUP BY detcomprad.iddetcomprad ) as ddd";
        $r=$this->db->query($query)->result_array();
        return $r;
    }
    private function _detallePago($id=null ){
        $where="";
        if($id){
            $where=" and  compra.idcompra=$id"  ;
        }
        $query="SELECT
                banco.nombre as nbanco,
                moneda.nombre as nmoneda,
                moneda.abreviado as abreviadomoneda,
                banco.abreviatura as abreviadobanco,
                cuentabanco.nombre as ncuentabanco,
                cuentabanco.nro,
                cuentabanco.cci,
                cuentabanco.idbanco,
                cuentabanco.idmoneda,
                cuentabanco.idcuentabanco,
                detpagocompra.iddetpagocompra,
                detpagocompra.monto,
                detpagocompra.tasacambio,
                 detpagocompra.nrooperacion
                FROM
                detpagocompra
                INNER JOIN compra ON compra.idcompra = detpagocompra.idcompra
                INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagocompra.idcuentabanco
                INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
                INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
                and compra.estado>0 and detpagocompra.estado>0 $where ";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function getProv(){
        echo json_encode(["status"=>"ok","data"=>$this->getProveedores()]);
    }

    private function getProveedores($id=null){
        $where="";
        if($id){
            $where=" and proveedor.idproveedor=$id";
        }

        $query="SELECT
        proveedor.nombre,
        proveedor.razonsocial,
        proveedor.apellidos,
        proveedor.ruc,
        proveedor.documento,
        proveedor.pais,
        proveedor.idproveedor
        FROM
        proveedor
        where proveedor.estado>0 $where";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function setFormProv(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){


            $pais=$post['pais'];
            $nombres=$post['nombres'];
            $apellidos=$post['apellidos'];
            $documento=$post['documento'];
            $rsocial=$post['rsocial'];
            $fregistro=$post['fregistro'];
            $ruc=$post['ruc'];
            $nombres=str_replace( "\r\n", ' ', $nombres);
            $apellidos=str_replace( "\r\n", ' ', $apellidos);

            $data=array(
                'nombre' => $nombres,
                'apellidos' => $apellidos,
                'razonsocial' => $rsocial,
                'ruc' => $ruc,
                'documento' => $documento,
                'pais' => $pais,
                'fechareg' => $fregistro
            );

            $data['estado']=1;
            $this->db->trans_start();
            $this->db->insert("proveedor", $data);
            $idMax=$this->db->insert_id();
            $this->db->trans_complete();
            $return['status']='ok';
            $return['data']=$this->getProveedores($idMax);

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    private function verificaMontoVentaComprabyId($idventacompra){
        $q="SELECT
count(movimientoscajad.idreferencia) as c 
FROM
movimientoscajad
where 
estado>0 and movimientoscajad.tablareferencia='comprad' and movimientoscajad.idreferencia=$idventacompra";
        $r=$this->db->query($q)->result_array();
        return $r[0]["c"];
    }


    public function getStockSoles(){
        $post=$this->input->post(null,true);
        $fini=$post["fini"];

      //  $where=" and  date(movimientoscajad.fecharegistro)='$fini' ";
         $where="   ";
        $queryXX=" SELECT
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

        $query="  	 select COALESCE(SUM(if(cajax.nombre ='Ingreso',COALESCE(cajax.montos,0),0)),0)  as Ingreso,
					COALESCE(SUM(if(cajax.nombre ='Inversión',COALESCE(cajax.montos,0),0)),0) as Inversion	,
					COALESCE(SUM(if(cajax.nombre ='Egreso',COALESCE(cajax.montos,0),0)),0) as Egreso,
					cajax.nmoneda 
     from
	(
		SELECT
			tipoconceptocaja.nombre,
			COALESCE(SUM( IFNULL(movimientoscajad.monto,0)),0) AS montos,
			moneda.nombre AS nmoneda
		FROM
    movimientoscajad
    INNER JOIN conceptocajad ON conceptocajad.idconceptocaja = movimientoscajad.idconceptocaja
    INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocajad.idtipoconceptocaja
    INNER JOIN moneda ON moneda.idmoneda = movimientoscajad.moneda
    where movimientoscajad.estado>0   $where 
    group by tipoconceptocaja.nombre,moneda.nombre ) as cajax  
    GROUP BY cajax.nmoneda ";

   $query2="   select SUM(if(cajax.nombre ='Ingreso',cajax.montos,0)) as Ingreso,
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
    where movimientoscajad.estado>0  and   moneda.nombre='Soles'
    group by tipoconceptocaja.nombre,moneda.nombre ) as cajax  
GROUP BY cajax.nmoneda ";
		
       


        $r=$this->db->query($query2)->result_array();

        
//and date(movimientoscajad.fecharegistro) BETWEEN '2019-11-21' and '2019-11-21'


        if(sizeof($r)>0){
            $dtt=array(
                "stock"=>floatval(floatval($r[0]["Ingreso"])-floatval($r[0]["Egreso"]) )
            );
        }else{
            $dtt=array(
                "stock"=>0
            );
        }



        echo json_encode($dtt);

    }

}
