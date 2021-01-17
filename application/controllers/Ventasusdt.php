<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventasusdt extends CMS_Controller {
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

        $this->template->set_data('title',"Ventas");
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data("clientes",$this->getClientes());
        $this->template->set_data("bancos",$this->getBancoByMoneda());
        $this->template->set_data("fechasreport",$this->getFechasVentas());
        $this->template->set_data("listaempresas",$this->getEmpresas());
        $this->template->renderizar('ventasusdt/index');
    }

    public function getData(){
        $this->load->library('Datatables');
        $data=array("cliente"=>"rrr");
        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        /*$this->datatables->select('idventa,
                 cliente,
                 clientenombre,
                 wallet,
                 detalleVenta,
                 detallepagos,
                 fechaventa,
                 idreferencia,
                 idcliente
                  ')->from('(
                  
              Select
    dbventadet.idventa,
dbventadet.cliente ,
dbventadet.clientenombre,
dbventadet.idcliente,
dbventadet.wallet ,
dbventadet.fechaventa ,
dbventadet.idreferencia ,
concat("[",dbventadet.detventax,"]") as detalleVenta ,
concat("[",dbdetpago.detallePagosx,"]") as detallepagos

 from  (select vv.*,GROUP_CONCAT(concat(
                        \'{"idrefdetcompra":\',
                        detventa.idrefdetcompra,
                        \',"idventa":\',
                        detventa.idventa,
                        \',"montobtc":\',
                        detventa.montobtc,
                        \',"montosoles":\',
                        detventa.montosoles,
                        \',"precioventa":\',
                        detventa.precioventa,
                        \'}\'
                )) AS detventax 

 FROM (select 
venta.idventa,
venta.cliente,
venta.wallet,
venta.estado,
venta.fechaventa,
venta.idreferencia,
cliente.nombre as clientenombre,
cliente.idcliente
FROM
venta
inner join cliente on venta.idcliente=cliente.idcliente
where venta.estado>0 ) as vv inner join detventa on vv.idventa=detventa.idventa
where detventa.estado> 0 and  vv.estado >0
GROUP BY vv.idventa order by vv.idventa desc   )  as dbventadet   
INNER JOIN (
 SELECT 
                        detpagoventa.idventa,
                        GROUP_CONCAT(concat(
                            \'{"iddetpagoventa":\',
                            detpagoventa.iddetpagoventa,
                            \',"idventa":\',
                            detpagoventa.idventa,
                            \',"abreviatura":"\',
                            banco.abreviatura,
                            \'","banco":"\',
                            banco.nombre,
                            \'","monto":\',
                            detpagoventa.monto,
                            \',"tasacambio":\',
                            detpagoventa.tasacambio,
                            \',"ncuenta":"\',
                            cuentabanco.nombre,
                            \'","nmoneda":"\',
                            moneda.nombre,                           
                             \'","idcuentabanco":"\',
                            cuentabanco.idcuentabanco,
                             \'","nroopreacion":"\',
                            detpagoventa.nrooperacion,
                            \'"}\'
                        )) AS detallePagosx
                    FROM
                        detpagoventa
                    INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventa.idcuentabanco
                    INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
                    INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
                    WHERE
                        detpagoventa.estado > 0
                                        GROUP BY detpagoventa.idventa  
)as dbdetpago on dbventadet.idventa= dbdetpago.idventa order by  dbventadet.idventa desc

            )temp ')->order_by(" temp.idventa desc "); */

        $this->datatables->select('idventa,
                 cliente,
                 clientenombre,
                 wallet,
                 detalleVenta,
                 detallepagos,
                 fechaventa,
                 idreferencia,
                 idcliente
                  ')->from('(
                        SELECT
                        distinct 
            ventausdt.idventa,
            ventausdt.cliente,
            ventausdt.wallet,
            ventausdt.estado,
            ventausdt.fechaventa,
            ventausdt.idreferencia,
            cliente.nombre as clientenombre,
            cliente.idcliente ,
            "" as detalleVenta,
            "" as detallepagos                         
            FROM
            cliente
            INNER JOIN ventausdt ON cliente.idcliente = ventausdt.idcliente
            INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
            where ventausdt.estado>0 and detventausdt.estado>0

            )temp ')->order_by(" temp.idventa desc ");

        echo $this->datatables->generate();
    }



    public function getBancoByMoneda($moneda=null){
        $where="";
        if($moneda){
            $where=" and  lower(moneda.nombre)='$moneda'";
        }
        $q="SELECT
            moneda.nombre as moneda,
            banco.nombre as banco,
            banco.abreviatura,            
            cuentabanco.nombre as ncuenta,
            cuentabanco.nro,
            cuentabanco.idcuentabanco,
            cuentabanco.idbanco,
            cuentabanco.idmoneda,
             cuentabanco.estado
            FROM
            moneda
            INNER JOIN cuentabanco ON moneda.idmoneda = cuentabanco.idmoneda
            INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
            where moneda.estado>0 and banco.estado>0 and cuentabanco.estado=1 $where ";
        $r=$this->db->query($q)->result_array();
        return $r;
    }

    public function getXupdateListBancos(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $moneda=$post["moneda"];
            $r=$this->getBancoByMoneda($moneda);
            $return["status"]="ok";
            $return["data"]=$r;
        }
        echo json_encode($return);
    }


    public function setForm(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];

            $cliente=$post["cliente"];
            $wallet=$post["wallet"];
            $fecha=$post["fecha"];
            $wallet=$post["wallet"];
            $idreffecha=$post["idreffecha"];
            $dataVenta=array(
                "cliente"=>$cliente ,
                "fechaventa"=>$fecha ,
                "wallet"=>$wallet,
                "idcliente"=>$cliente,
                "idinstitucion"=>1 ,
                "idreferencia"=>$idreffecha

            );

            if($isEdit == 0){
                $dataVenta["fechareg"]=$this->getfecha_actual();
                $dataVenta["estado"]=  1;

                $this->db->trans_start();
                $this->db->insert("ventausdt",$dataVenta);
                $idMax = $this->db->insert_id();
                $this->db->trans_complete();

                $prodVenta=$post["prodVenta"];
                $pventa=$post["pventa"];
                $cantBtc=$post["cantBtc"];
                $cantSoles=$post["cantSoles"];


                $dtVenta=[];
                for($i=0 ; $i<count($prodVenta) ; $i++){
                    $dtVenta[]=array(
                        "idventa"=>$idMax,
                        "idrefdetcompra"=>$prodVenta[$i],
                        "montobtc"=>$cantBtc[$i],
                        "montosoles"=>$cantSoles[$i],
                        "precioventa"=>$pventa[$i],
                        "fechareg"=>$fecha,
                        "estado"=>1,
                        "idinstitucion"=>1,

                    );
                }
                if(sizeof($dtVenta) >0 ){
                    $this->db->insert_batch("detventausdt",$dtVenta);
                }
                $cuentabanco=$post["cuentabanco"];
                $nrooperacion=$post["nrooperacion"];
                $monto=$post["monto"];
                $tasaCambio=$post["tasaCambio"];
                $detPago=[];
                for($i=0 ;$i<sizeof($cuentabanco);$i++){
                    if( floatval($monto[$i]) > 0 ){
                        $detPago[]=array(
                            "idventa"=>$idMax ,
                            "idcuentabanco"=>$cuentabanco[$i] ,
                            "monto"=>$monto[$i] ,
                            "tasacambio"=>$tasaCambio,
                            "nrooperacion"=>$nrooperacion[$i],
                            "estado"=>1 ,
                            "fechareg"=>$fecha ,
                            "idinstitucion"=>1
                        );
                    }
                }
                $return["status"]="ok";
                $this->db->insert_batch("detpagoventausdt",$detPago);
            }else if($isEdit == 1 ){

                $this->db->update('ventausdt',$dataVenta, ["idventa"=>$idEdit]);
                $this->db->update('detpagoventausdt', ["estado"=>0], ["idventa"=>$idEdit]);
                $this->db->update('detventausdt', ["estado"=>0], ["idventa"=>$idEdit]);



                $prodVenta=$post["prodVenta"];
                $pventa=$post["pventa"];
                $cantBtc=$post["cantBtc"];
                $cantSoles=$post["cantSoles"];

                $idMax=$idEdit;
                $dtVenta=[];
                for($i=0 ; $i<count($prodVenta) ; $i++){
                    $dtVenta[]=array(
                        "idventa"=>$idMax,
                        "idrefdetcompra"=>$prodVenta[$i],
                        "montobtc"=>$cantBtc[$i],
                        "montosoles"=>$cantSoles[$i],
                        "precioventa"=>$pventa[$i],
                        "fechareg"=>$fecha,
                        "estado"=>1,
                        "idinstitucion"=>1,

                    );
                }
                if(sizeof($dtVenta) >0 ){
                    $this->db->insert_batch("detventausdt",$dtVenta);
                }
                $cuentabanco=$post["cuentabanco"];
                $nrooperacion=$post["nrooperacion"];
                $monto=$post["monto"];
                $tasaCambio=$post["tasaCambio"];
                $detPago=[];
                for($i=0 ;$i<sizeof($cuentabanco);$i++){
                    if( floatval($monto[$i]) > 0 ){
                        $detPago[]=array(
                            "idventa"=>$idMax ,
                            "idcuentabanco"=>$cuentabanco[$i] ,
                            "monto"=>$monto[$i] ,
                            "tasacambio"=>$tasaCambio,
                            "nrooperacion"=>$nrooperacion[$i],
                            "estado"=>1 ,
                            "fechareg"=>$fecha ,
                            "idinstitucion"=>1
                        );
                    }
                }
                $return["status"]="ok";
                $this->db->insert_batch("detpagoventausdt",$detPago);
            }



        }

        echo json_encode($return);
    }

    public function delete(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->update('ventausdt', ["estado"=>0], ["idventa"=>$id]);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function getProdStock(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
           $query="           select stocks.*, 
							stocks.total-stocks.vendido as stock

             from( SELECT
            detcomprausdt.iddetcompra,
            detcomprausdt.idcompra,
             cast(detcomprausdt.cantbtc as  DECIMAL(12,8)) AS total,            
						 if((	
							SELECT
							 Sum(cast(detventausdt.montobtc as  DECIMAL(12,8))) as sumvendido 
							FROM
							detventausdt INNER JOIN ventausdt on detventausdt.idventa=ventausdt.idventa
							where detventausdt.estado>0 and ventausdt.estado>0 and detventausdt.idrefdetcompra=detcomprausdt.idcompra

						) IS NULL ,0,(	
							SELECT
							 Sum(cast(detventausdt.montobtc as  DECIMAL(12,8))) as sumvendido 
							FROM
							detventausdt INNER JOIN ventausdt on detventausdt.idventa=ventausdt.idventa
							where detventausdt.estado>0 and ventausdt.estado>0  and detventausdt.idrefdetcompra=detcomprausdt.idcompra

						)  ) as vendido,
            detcomprausdt.preciounidadbtc,
            detcomprausdt.preciobaseventa,
            proveedor.nombre as proveedor
            FROM
            comprausdt
            INNER JOIN detcomprausdt ON comprausdt.idcompra = detcomprausdt.idcompra
            inner join proveedor on comprausdt.idproveedor=proveedor.idproveedor
            where detcomprausdt.estado>0 and comprausdt.estado>0  )as stocks  where (stocks.total-stocks.vendido)>0 ";

           $query="       select stocks.*, 
							stocks.total-stocks.vendido as stock

             from( SELECT
            detcomprausdt.iddetcompra,
            detcomprausdt.idcompra,
              detcomprausdt.cantbtc     AS total,            
						 if((	
							SELECT
							 Sum( detventausdt.montobtc  ) as sumvendido 
							FROM
							detventausdt INNER JOIN ventausdt on detventausdt.idventa=ventausdt.idventa
							where detventausdt.estado>0 and ventausdt.estado>0 and detventausdt.idrefdetcompra=detcomprausdt.iddetcompra

						) IS NULL ,0,(	
							SELECT
							 Sum( detventausdt.montobtc   ) as sumvendido 
							FROM
							detventausdt INNER JOIN ventausdt on detventausdt.idventa=ventausdt.idventa
							where detventausdt.estado>0 and ventausdt.estado>0  and detventausdt.idrefdetcompra=detcomprausdt.iddetcompra

						)  ) as vendido,
            detcomprausdt.preciounidadbtc,
            detcomprausdt.preciobaseventa,
            proveedor.nombre as proveedor
            FROM
            comprausdt
            INNER JOIN detcomprausdt ON comprausdt.idcompra = detcomprausdt.idcompra
            inner join proveedor on comprausdt.idproveedor=proveedor.idproveedor
            where detcomprausdt.estado>0 and comprausdt.estado>0  )as stocks  where (stocks.total-stocks.vendido)>0 ";

           $r=$this->db->query($query)->result_array();
           $return["status"]="ok";
           $return["data"]=$r;
        }
        echo json_encode($return);
    }

    public function getFechasVentas(){
        $query="SELECT
                DISTINCT
                DATE(ventausdt.fechaventa) as fechaventa
                FROM
                ventausdt
                where estado>0";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function reporte(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $fechaini=$post["fechaini"];
            $fechaend=$post["fechaend"];
            $r=$this->_venta(null,$fechaini,$fechaend);
            $return["data"]=$r;
            $return["status"]="ok";
        }
        echo json_encode($return);
    }
    private function _venta($id=null,$fechaini=null,$fechaend=null){
        $where="";
        if($id){
            $where.=" and ventausdt.idventa=$id ";
        }
        if($fechaini){
            $where.=" and ventausdt.fechaventa BETWEEN '$fechaini' AND '$fechaend';  ";
        }
        $query="SELECT
                DISTINCT
                ventausdt.idventa,
                ventausdt.cliente,
                ventausdt.wallet,
                ventausdt.fechaventa,
                ventausdt.idreferencia,
                cliente.idcliente,
                 cliente.nombre,
                cliente.apellidos
                FROM
                ventausdt inner join cliente on ventausdt.idcliente=cliente.idcliente
                inner join detventausdt on  ventausdt.idventa =detventausdt.idventa
                inner join detpagoventausdt on ventausdt.idventa=detpagoventausdt.idventa
                where ventausdt.estado > 0 and detventausdt.estado>0   $where   ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r  as $k=>$i){
            $detVenta=$this->_detVenta($i["idventa"]);
            $detPago=$this->_detPago($i["idventa"]);
            $dt[]=array("idventa"=>$i["idventa"] ,
                "idcliente"=>$i["idcliente"] ,
                "cliente"=>$i["cliente"] ,
                "wallet"=>$i["wallet"] ,
                "fechaventa"=>$i["fechaventa"],
                "idreferencia"=>$i["idreferencia"],
                "detVenta"=>$detVenta,
                "detPago"=>$detPago,
                "nombres"=>$i["nombre"] ,
                "apellidos"=>$i["apellidos"]
            );

        }
        return $dt;
    }
    private function _detVenta($idventa){
        $query="SELECT
            detventausdt.iddetventa,
            detventausdt.idventa,
            detventausdt.idrefdetcompra,
            detventausdt.montobtc,
            detventausdt.montosoles,
            detventausdt.precioventa,
    
            detcomprausdt.cantbtc AS cantbtccomprado,
            detcomprausdt.preciounidadbtc AS preciounidadbtccomprado,
            detcomprausdt.cantmoneda AS cantmonedacomprado,
            detcomprausdt.tipocambio AS tipocambiocomprado,
             detcomprausdt.preciobaseventa  ,
            comprausdt.proveedor
            FROM
            detventausdt
            INNER JOIN detcomprausdt ON detcomprausdt.iddetcompra = detventausdt.idrefdetcompra
            INNER JOIN comprausdt ON comprausdt.idcompra = detcomprausdt.idcompra
            where detcomprausdt.estado>0 and detventausdt.estado>0 and detventausdt.idventa = $idventa";
        $r=$this->db->query($query)->result_array();
        return $r;
    }
    private function _detpago($idventa){
       $query="SELECT
        detpagoventausdt.idventa,
        detpagoventausdt.idcuentabanco,
        detpagoventausdt.monto,
        detpagoventausdt.tasacambio,
        detpagoventausdt.nrooperacion,
        cuentabanco.nombre AS ncuenta,
        banco.nombre AS banco,
        banco.abreviatura AS abrebanco,
        cuentabanco.nro,
        moneda.nombre as nmoneda,
        moneda.abreviado as abremoneda
        FROM
        detpagoventausdt
        INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
        INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
        INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
        where detpagoventausdt.estado>0  and detpagoventausdt.idventa=$idventa ";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function validarIdRef(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=trim($post["id"]);
            $query="SELECT
                    COUNT(ventausdt.idreferencia) as c
                    FROM
                    ventausdt where estado>0 and ventausdt.idreferencia='$id' ";
            $r=$this->db->query($query)->result_array();
            $return["data"]=$r[0];
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function validarNroOperacion(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $nrooperacion=trim($post["id"]);
            $query="SELECT
                    count(detpagoventausdt.nrooperacion) as c
                    FROM
                    detpagoventausdt
                    INNER JOIN ventausdt ON ventausdt.idventa = detpagoventausdt.idventa
                    where detpagoventausdt.estado>0 and ventausdt.estado>0 and detpagoventausdt.nrooperacion='$nrooperacion'";
            $r=$this->db->query($query)->result_array();
            $return["data"]=$r[0];
            $return["status"]="ok";
        }
        echo json_encode($return);
    }



    ///--------------------------------------------------------------
    public function getDataDetalleVenta(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
         $id=$post["id"];
         $r=$this->_venta($id,null);
         $return["data"]=$r;
         $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function reporteByBanco(){

        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $fechaini=$post["fechaini"];
            $fechaend=$post["fechaend"];

            $r=$this->getDataBancoByVentaFecha($fechaini,$fechaend);

            $return["data"]=$r;
            $return["status"]="ok";
        }
        echo json_encode($return);
    }
    private function getDataBancoByVentaFecha($fini=null,$fend=null){
        $where="";

        if($fini){
            $where.=" and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend';  ";
        }
        $query="
        SELECT
        DISTINCT
        banco.nombre AS nbanco,
        banco.abreviatura,
        ventausdt.fechaventa,
        banco.idbanco
          
        FROM
        ventausdt
        INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
        INNER JOIN detpagoventausdt ON detventausdt.idventa = detpagoventausdt.idventa
        INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
        INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
        INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
        where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0 $where
                ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            $dataCuentaBanco=$this->detCuentaBanco($i["idbanco"],$fini,$fend);
            $dt[]=array(
            "nbanco"=>$i["nbanco"],
            "abreviatura"=>$i["abreviatura"],
            "fechaventa"=>$i["fechaventa"],
            "idbanco"=>$i["idbanco"],
            "detCuentaBanco"=>$dataCuentaBanco
            );
        }
        return $dt;
    }



    private function detCuentaBanco($idbanco,$fini,$fend){
        $where="";

        if($fini){
            $where.=" and banco.idbanco='$idbanco'  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend';  ";
        }
        $query="
        SELECT DISTINCT    
        cuentabanco.idcuentabanco,
        cuentabanco.idmoneda,
        cuentabanco.nombre as ncuentabanco,
        cuentabanco.idbanco    ,
        moneda.nombre as nmoneda ,
        moneda.abreviado as abremoneda
        FROM
        ventausdt
        INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
        INNER JOIN detpagoventausdt ON detventausdt.idventa = detpagoventausdt.idventa
        INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
        INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
        INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
        where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0 $where
                ";
				
		
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            
            $detPagoVenta=$this->detPagoVenta($i["idcuentabanco"],$fini,$fend);
			 
			// print_r($detPagoVenta);
			 
            $detPagoVentaGanacia=$this->detPagoVentaGanancia($i["idcuentabanco"],$fini,$fend);
			//  print_r($detPagoVentaGanacia);exit();
            $dt[]=array(
                "ncuentabanco" => $i["ncuentabanco"],
                "nmoneda" => $i["nmoneda"],
                "abremoneda" => $i["abremoneda"],
                "detPagoVenta"=>$detPagoVenta,
                "detPagoVentaGanancia"=>$detPagoVentaGanacia
            );  

        }
        return $dt;
    }
		
	  private function detPagoVentaGanancia($idcuentabanco,$fini,$fend){
        $where="";
        $where2="";
        if($fini){//and cuentabanco.idcuentabanco='$idcuentabanco'
            $where.="  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend'  ";
            $where2.="  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend'  ";
        }
        $query="select sum(gananciabanco) as sumganbanco,
   nrooperacion,
            montosoles,
            monto,
            tasacambio,
nmonedaabreviado,
      idcuentabanco
From (select 
nrooperacion,montosoles,monto,tasacambio,pventa,pcompra,
nmonedaabreviado,
(montosoles/pventa) as btcequivalentebanco,
 ((montosoles/pventa)*pcompra)  as  valSolCompra,
(montosoles-((montosoles/pventa)*pcompra)) as  gananciabanco,
idcuentabanco 
 
from ( SELECT
AVG(detventausdt.precioventa) as pventa , 
SUM(detventausdt.montobtc) as summventa,
AVG(detcomprausdt.preciounidadbtc) as pcompra, 
ventausdt.idventa
FROM
ventausdt
    
INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
INNER JOIN detcomprausdt ON detcomprausdt.iddetcompra = detventausdt.idrefdetcompra
where ventausdt.estado>0 and detventausdt.estado >0 and detcomprausdt.estado>0
$where2
GROUP BY ventausdt.idventa ) as ventabtc
Inner JOIN
( 
SELECT
banco.nombre AS nbanco,
banco.abreviatura AS nbancoabreviado,
moneda.nombre AS nmoneda,
moneda.abreviado AS nmonedaabreviado,
cuentabanco.nombre AS cuentabanco,
cuentabanco.nro,
detpagoventausdt.nrooperacion,
detpagoventausdt.monto,
if(moneda.abreviado = \"$\",detpagoventausdt.monto*detpagoventausdt.tasacambio,detpagoventausdt.monto) as montosoles ,
detpagoventausdt.idventa,
detpagoventausdt.tasacambio,
detpagoventausdt.idcuentabanco
FROM detpagoventausdt
		inner join  ventausdt on detpagoventausdt.idventa=ventausdt.idventa
		INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
		INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
		INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
		WHERE
			detpagoventausdt.estado > 0		 
			and ventausdt.estado>0
  
  $where
GROUP BY ventausdt.idventa,detpagoventausdt.idcuentabanco
 ) as bancobtc on ventabtc.idventa=bancobtc.idventa ) as tmpdBancoVenta where idcuentabanco='$idcuentabanco' GROUP BY nrooperacion";
        $r=$this->db->query($query)->result_array();

 
         return $r;

    }
	
		
     private function detPagoVentaGanancia__($idcuentabanco,$fini,$fend){
        $where="";
        $where2="";
        if($fini){
            $where.=" and cuentabanco.idcuentabanco='$idcuentabanco'  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend'  ";
            $where2.="  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend'  ";
        }
        $query="
			select 
			sum(gananciabanco) as sumganbanco,
			nrooperacion,
			montosoles,
			monto,
			tasacambio,
			nmonedaabreviado
			From (select 
			nrooperacion,montosoles,monto,tasacambio,pventa,pcompra,
			nmonedaabreviado,
			(montosoles/pventa) as btcequivalentebanco,
			((montosoles/pventa)*pcompra)  as  valSolCompra,
			(montosoles-((montosoles/pventa)*pcompra)) as  gananciabanco,
			idcuentabanco 

			from ( 
			SELECT
			AVG(detventausdt.precioventa) as pventa , 
			SUM(detventausdt.montobtc) as summventa,
			AVG(detcomprausdt.preciounidadbtc) as pcompra, 
			ventausdt.idventa
			FROM
			ventausdt
			INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
			INNER JOIN detcomprausdt ON detcomprausdt.iddetcompra = detventausdt.idrefdetcompra
			where ventausdt.estado>0 and detventausdt.estado >0 and detcomprausdt.estado>0
			$where2
			GROUP BY ventausdt.idventa ) as ventabtc
			Inner JOIN
			( 
			SELECT
			banco.nombre AS nbanco,
			banco.abreviatura AS nbancoabreviado,
			moneda.nombre AS nmoneda,
			moneda.abreviado AS nmonedaabreviado,
			cuentabanco.nombre AS cuentabanco,
			cuentabanco.nro,
			detpagoventa.nrooperacion,
			detpagoventa.monto,
			if(moneda.abreviado = \"$\",detpagoventa.monto*detpagoventa.tasacambio,detpagoventa.monto) as montosoles ,
			detpagoventausdt.idventa,
			detpagoventausdt.tasacambio,
			detpagoventausdt.idcuentabanco
			FROM
			detpagoventausdt
			inner join venta on detpagoventaxx.idventa=detpagoventa.idventa
			INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventa.idcuentabanco
			INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
			INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
			where detpagoventa.estado>0  $where
			GROUP BY   detpagoventa.nrooperacion
			 ) as bancobtc on ventabtc.idventa=bancobtc.idventa ) as tmpdBancoVenta
			 GROUP BY  nrooperacion   ";//fallo
        $r=$this->db->query($query)->result_array();
        return $r;

    }

    private function detPagoVenta($idcuentabanco,$fini,$fend){
         return 0;
        $where="";

        if($fini){
            $where.=" and cuentabanco.idcuentabanco='$idcuentabanco'  and date(ventausdt.fechaventa) BETWEEN '$fini' AND '$fend';  ";
        }
        $q="
        SELECT DISTINCT
        moneda.nombre AS nmoneda,
        moneda.abreviado AS abremoneda,    
        detpagoventausdt.monto,
        detpagoventausdt.nrooperacion,
        detpagoventausdt.tasacambio
        FROM
        ventausdt
        INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
        INNER JOIN detpagoventausdt ON detventausdt.idventa = detpagoventausdt.idventa
        INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
        INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
        INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
        where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0 $where
                ";
			 
        $r=$this->db->query($q)->result_array();
		
		 
        return $r;
    }


    private function getClientes($id=null){
        $where="";
        if($id){
            $where=" and cliente.idcliente=$id";
        }

        $query="SELECT
        *
        FROM
        cliente
        where cliente.estado>0 $where";
        $r=$this->db->query($query)->result_array();
        return $r;
    }

    public function setFormCliente(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $nombres=$post["nombres"];
            $apellidos=$post["apellidos"];
            $wallet=$post["wallet"];
            $documento=$post["documento"];
            $fregistro=$post["fregistro"];

            $nombres=str_replace( "\r\n", ' ', $nombres);
            $apellidos=str_replace( "\r\n", ' ', $apellidos);

            //--

            $data=array(
                "nombre"=>$nombres,
                "apellidos"=>$apellidos,
                "wallet"=>$wallet,
                "documento"=>$documento,
                "fechareg"=>$fregistro
            );


           $data['estado']=1;
            $this->db->trans_start();
            $this->db->insert("cliente", $data);
            $idMax=$this->db->insert_id();
            $this->db->trans_complete();
            $return['status']='ok';
            $return['data']=$this->getClientes($idMax);

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

   public function enviocaja(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idconcepto="32";
            $idmoneda="1";
            $montoencaja=$post["montoencaja"];
            $fechaenviocaja=$post["fechaenviocaja"];
            $descripcionenviocaja=$post["descripcionenviocaja"];
            $data=array(
            "idconceptocaja"=>$idconcepto,
            "moneda"=>$idmoneda,
            "monto"=>$montoencaja,
            "idreferencia"=>"",
            "tablareferencia"=>"",
            "fecharegistro"=>$fechaenviocaja,
            "descripcion"=>$descripcionenviocaja,
                "estado"=>1,
                "fechareg"=>$this->getfecha_actual()
            );

           // print_r($data);exit();
            $this->db->insert("movimientoscaja", $data);
            $return['status'] = 'ok';
        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function reportedeventaporcliente(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $fechaini=$post["fechaini"];
            $fechaend=$post["fechaend"];
            $r=$this->_cliente_venta($fechaini,$fechaend);
            $return["data"]=$r;
            $return["status"]="ok";
        }else {
        $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    private function _cliente_venta($fechaini,$fechaend){
        $query="SELECT
            DISTINCT
            cliente.nombre,
            cliente.apellidos,
            cliente.documento, 
            cliente.idcliente
            FROM
            cliente
            INNER JOIN ventausdt ON cliente.idcliente = ventausdt.cliente
            INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
            INNER JOIN detpagoventausdt ON ventausdt.idventa = detpagoventausdt.idventa
            where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0
            and date(ventausdt.fechaventa) BETWEEN '$fechaini' and '$fechaend'
            ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            $detpago=$this->_getDetallePagoBancoBYCliente($i["idcliente"],$fechaini,$fechaend);
            $dt[]=array(
                "nombre"=>$i["nombre"],
                 "apellidos"=>$i["apellidos"],
                 "documento"=>$i["documento"],
                 "idcliente"=>$i["idcliente"],
                 "detallePago"=>$detpago
            );
        }
        return $dt;
    }

    private function _getDetallePagoBancoBYCliente($idcliente,$fechaini,$fechaend){
        $query=" 
        Select banco.nombre AS nbanco,
	cuentabanco.nombre AS ncuentabanco,
	moneda.nombre AS nmoneda,
	moneda.abreviado AS nmonedaabreviado, 
  SUM(detpagoventausdt.monto) as montototal
 from (SELECT
		 DISTINCT
		 ventausdt.idventa,
		 cliente.nombre,
		 cliente.apellidos
	FROM
		ventausdt
	inner join cliente on ventausdt.idcliente=cliente.idcliente
	INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
	where ventausdt.estado>0 and detventausdt.estado>0
	AND ventausdt.idcliente = '$idcliente'
	AND date(ventausdt.fechaventa) BETWEEN '$fechaini' and '$fechaend'
) as dataventa 
INNER JOIN detpagoventausdt ON dataventa.idventa = detpagoventausdt.idventa
INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
where detpagoventausdt.estado > 0  
 group by banco.nombre ,
        cuentabanco.nombre ,
        moneda.abreviado,
        moneda.nombre
        order by nbanco,nmoneda "; 
        $r=$this->db->query($query)->result_array();
        return $r;
    }



    public function searchVenta(){

        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idcliente=$post["idcliente"];
            $fechaventa=$post["fecha"];
            $q="SELECT
            Count(ventausdt.idcliente) as c 
            FROM
            ventausdt
            INNER JOIN detpagoventausdt ON ventausdt.idventa = detpagoventausdt.idventa
            INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa 
            where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0
            and ventausdt.idcliente='$idcliente' and ventausdt.fechaventa='$fechaventa'
            GROUP BY ventausdt.idcliente,ventausdt.fechaventa
            ";
            $this->db->trans_start();
            $r=$this->db->query($q)->result_array();
            $this->db->trans_complete();

            if(sizeof($r)> 0){
                $r=floatval($r[0]["c"]) + 1;
            }else{
                $r=1;
            }
            $return["data"]=$r;
            $return["status"]="ok";
        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }


///-----
    public function reporteByProveedores(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $fechaini=$post["fechaini"];
            $fechaend=$post["fechaend"];
            $q="SELECT
            proveedor.nombre,
            proveedor.apellidos,
            proveedor.razonsocial,
            proveedor.ruc,
            proveedor.documento,
            proveedor.pais,
            proveedor.idproveedor,
            detcomprausdt.cantbtc,
            detcomprausdt.preciounidadbtc,
            ventausdt.fechaventa,
            detventausdt.montobtc,
            detventausdt.montosoles,
            detventausdt.precioventa
            FROM
            detventausdt
            INNER JOIN ventausdt ON ventausdt.idventa = detventausdt.idventa
            INNER JOIN detcomprausdt ON detcomprausdt.iddetcompra = detventausdt.idrefdetcompra
            INNER JOIN comprausdt ON comprausdt.idcompra = detcomprausdt.idcompra
            INNER JOIN proveedor ON proveedor.idproveedor = comprausdt.idproveedor
            where ventausdt.estado>0 and detventausdt.estado>0 and comprausdt.estado>0 and detcomprausdt.estado>0
            and ventausdt.fechaventa BETWEEN '$fechaini' and '$fechaend'
            order by  proveedor.idproveedor ,detcomprausdt.cantbtc
              ";
            $r=$this->db->query($q)->result_array();
            $return["data"]=$r;
            $return["status"]="ok";
        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }


 public function searchNroOperacion(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0) {
            $nop = $post["nop"];
            $nop=preg_replace('/\s+/', '', $nop);
            $q = "SELECT
detpagoventausdt.nrooperacion,
detpagoventausdt.monto,
detpagoventausdt.tasacambio,
ventausdt.fechaventa,
ventausdt.wallet,
moneda.abreviado,
cuentabanco.nombre as ncuenta,
banco.abreviatura as nbancoabreviatura,
cliente.nombre,
cliente.apellidos,
cliente.documento,
cliente.wallet
FROM
ventausdt
INNER JOIN cliente ON cliente.idcliente = ventausdt.cliente
INNER JOIN detventausdt ON ventausdt.idventa = detventausdt.idventa
INNER JOIN detpagoventausdt ON detventausdt.idventa = detpagoventausdt.idventa
INNER JOIN detcomprausdt ON detcomprausdt.iddetcompra = detventausdt.idrefdetcompra
INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventausdt.idcuentabanco
INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
where ventausdt.estado>0 and detventausdt.estado>0 and detpagoventausdt.estado>0 and detcomprausdt.estado>0 
and trim(detpagoventausdt.nrooperacion)='$nop'
order by fechaventa";
            $r=$this->db->query($q)->result_array();
            $return["data"]=$r;
            $return["status"]="ok";
        }
        echo json_encode($return);

    }


 public function getDetVenta_(){
        $post=$this->input->post(null,true);
        $id=$post["idventa"];
        echo json_encode($this->_detVenta($id));
    }

    public function getDetPago_(){
        $post=$this->input->post(null,true);
        $id=$post["idventa"];
        echo json_encode($this->_detpago($id));
    }


    public function getEmpresas($id=0,$isJson=false){
        $where="";
        if($id!=0){
            $where="and idempresa=$id"   ;
        }
        $q="select * from empresa where estado>0 $where ";
        $r=$this->db->query($q)->result_array();
        if($isJson){
            echo json_encode($r);
        }else{
            return $r;
        }

    }


    public function setNewCompro(){
        $return['status']=false;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
               $usuariogenera= $post["usuariogenera"] ;
            $tipocomprobante = $post["tipocomprobante"];
            $tipodocclientecompro =    $post["tipodocclientecompro"] ;
            $nroDocClienteCompro = $post["nroDocClienteCompro"] ;
            $clientecompro = $post["clientecompro"] ;
            $fechacompro = $post["fechacompro"];
            $horacompro=$post["horacompro"];
            if(empty($horacompro)){
                $horacompro=date("H:i:s");
            }
            $fechacompro=$fechacompro." ".$horacompro;

            $dirclientecompro = $post["dirclientecompro"] ;

            $depCompro = $post["depCompro"] ;
            $provCompro = $post["provCompro"];
            $distCompro = $post["distCompro"];
            $ubigeoCompro = $post["ubigeoCompro"];
            $emailCompro = $post["emailCompro"];

                //--
            $cantItemCompro = $post["cantItemCompro"];


            $unidadItemCompro = $post["unidadItemCompro"];
            //$unidadItemCompro=$unidadItemCompro[0];

            $descItemCompro = $post["descItemCompro"];
           // $descItemCompro=$descItemCompro[0];

            $precioUItemCompro = $post["precioUItemCompro"];
           // $precioUItemCompro=$precioUItemCompro[0];



            $subTotalUItemCompro = $post["subTotalUItemCompro"] ;
            $tipomonedacompro = $post["tipomonedacompro"];
            $totalVentaCompro = $post["totalVentaCompro"];



            $textRuc = $post["textRuc"];
            $arrTR=explode("-",$textRuc);
            $razonSocialEmpresa=$arrTR[1];
            $rucEmpresa=$arrTR[0];

            $TotalX=floatval($cantItemCompro)*floatval($precioUItemCompro);

            // data empresa
            $dtEmpresa=$this->getEmpresas($usuariogenera,false);
            $nombrecomercialempresa=$dtEmpresa[0]["nombrecomercial"];

            $departamentoEmpresa=$dtEmpresa[0]["departamento"];
            $provinciaEmpresa=$dtEmpresa[0]["provincia"];
            $distritoEmpresa=$dtEmpresa[0]["distrito"];
            $ubigeoEmpresa=$dtEmpresa[0]["ubigeo"];
            $direccionEmpresa=$dtEmpresa[0]["direccion"];

            $serieFact=$dtEmpresa[0]["seriefactura"];
            $serieBol=$dtEmpresa[0]["serieboleta"];
            $serieComm="";
            if($tipocomprobante == "01"){
                $serieComm=$serieFact;
            }else if($tipocomprobante == "03"){
                $serieComm =$serieBol;
            }

            $maxCorre=$this->getCorrelativoByRuc($tipocomprobante,$rucEmpresa);
            $maxCorre=$maxCorre+1;





            $dt=array(

            "idtipodoc"=>$tipocomprobante,
            "referencia"=>"venta",
            "idreferencia"=>"",
            "serie"=>$serieComm,
            "correlativonro"=>$maxCorre,
            "correlativo"=>str_pad($maxCorre, 8, "0", STR_PAD_LEFT),
            "seriecorrelativo"=>$serieComm."-".str_pad($maxCorre, 8, "0", STR_PAD_LEFT),
            //"isenviadosunat"=>
            //"codesunatrespuesta"=>
            //"xmlfile"=>
           // "msjsunat"=>
            //"ublvalido"=>
           // "msjubl"=>
            //"respuestacdrfile"=>
           // "existcdr"=>
            "numruc"=>$rucEmpresa,
           // "nombrearchxml"=>
           // "resumenvalue"=>
           // "resumenfirma"=>
            "tipdocucliente"=>$tipodocclientecompro,
            "numdocucliente"=>$nroDocClienteCompro,
            "razoncliente"=>$clientecompro,
            "direccioncliente"=>$dirclientecompro,
           // "xml_content"=>
            // "pdf_content"=>
           // "codsucursal"=>
            "gravado"=>0,
            "inafecto"=>0,
            "exonerado"=>$TotalX,
            "suma_igv"=>0,
            "suma_descuento"=>0,
            "descuento_global"=>0,
            "gratuito"=>0,
            //"imagen_qr"=>
           // "html_content"=>
            "estado"=>1,
            "fecharegistroorigen"=>$fechacompro,
            //"fechageneraxml"=>
           // "fechaenvio"=>
            "fechareg"=>date("Y-m-d H:i:s"),
            "emailcliente"=>$emailCompro,
            //"nroticket"=>
            //"fecharegistracdr"=>
            //"xmlgenerado"=>
            //"isdadobaja"=>
             "idempresa"=>$usuariogenera,
            //"descripcion"=>$descItemCompro,
            //"unidadmedida"=>$unidadItemCompro,
             "moneda"=>$tipomonedacompro,
            "ubigeocli"=>$ubigeoCompro,
            "departamentocli"=>$depCompro,
            "provinciacli"=>$provCompro,
            "distritocli"=>$distCompro,
            "razonsocialempresa"=>$razonSocialEmpresa,
            "ubigeoempresa"=>$ubigeoEmpresa,
            "departamentoempresa"=>$departamentoEmpresa,
            "provinciaempresa"=>$provinciaEmpresa,
            "distritoempresa"=>$distritoEmpresa,
            "direccionempresa"=>$direccionEmpresa,
             "nombrecomercialempresa"=>$nombrecomercialempresa,
              //cantPrecio
             // "cantidadprod"=>$cantItemCompro,
              //  "precioprod"=>$precioUItemCompro


            );
            $this->db->trans_start();
            $this->db->insert("bandejafacturacion",$dt);
            $idMax = $this->db->insert_id();
            $this->db->trans_complete();



            $dataDet=[];
            for($i=0;$i<count($cantItemCompro);$i++){
                $dataDet[]=array(
                    "idbandejafacturacion"=>$idMax ,
                    "idunidadmedida"=>$unidadItemCompro[$i] ,
                    "idproducto"=>rand(1,200) ,
                    "cantidad"=> $cantItemCompro[$i],
                    "descripcion"=>$descItemCompro[$i] ,
                    "precio"=>$precioUItemCompro[$i] ,
                    "estado"=> 1,
                    //"preciof"=> ,
                   // "cantidadf"=> ,
                );
            }

            $this->db->insert_batch("detallebandejafacturacion",$dataDet);
           // print_r($post);exit();
            $return["status"]=true;
        }
        echo json_encode($return);
    }

    public function getCorrelativoByRuc($tipocomprobante,$ruc){
        $q="SELECT
                    COALESCE(max(bandejafacturacion.correlativonro),0) as maxcorrelativo
                    FROM
                     bandejafacturacion
                    where bandejafacturacion.estado>0					
					 and bandejafacturacion.referencia='venta'
                     and numruc='$ruc'
                     and idtipodoc='$tipocomprobante'
                    order by bandejafacturacion.serie,bandejafacturacion.correlativonro";
        $r=$this->db->query($q)->result_array();
        return $r[0]["maxcorrelativo"];

    }

    //---------------------------------

    public function getFinalfechaComproForRucTipoDoc(){
        $return['status']=false;
        $return['data']=[];
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idempresa=$post["idempresa"];
            $idtipodoc=$post["idtipodoc"];
            $q="Select 
            date(dd.ultimocorre) as fecha ,
            time(dd.ultimocorre) as hora,
             DATE_FORMAT(dd.ultimocorre, '%d/%m/%Y') as fechaf,
            dd.ultimocorre , 
            ADDTIME(time(dd.ultimocorre),'00:01:00') as horamasunminuto from (                
                SELECT
                 MAX(bandejafacturacion.fecharegistroorigen) as ultimocorre
                FROM
                bandejafacturacion
                where 
                bandejafacturacion.estado>0
                and bandejafacturacion.idempresa=$idempresa
                and bandejafacturacion.idtipodoc='$idtipodoc'
            )as dd
             
            ";
            $r=$this->db->query($q)->result_array();
            if(sizeof($r)==0){
                $r=[];
            }else{
                $r=$r[0];
            }
            $return['status']=true;
            $return['data']=$r;
        }

        echo json_encode($return);

    }


    public function getUltimasTransacciones(){
        $post=$this->input->post(null,true);
        $fini=$post["fini"];
       // $fend=$post["fend"];

        $q="SELECT
        detallebandejafacturacion.codref,
        detallebandejafacturacion.idunidadmedida,
        detallebandejafacturacion.descripcion,
        detallebandejafacturacion.cantidad,
        detallebandejafacturacion.precio,
        bandejafacturacion.fecharegistroorigen,
       bandejafacturacion.seriecorrelativo,
       bandejafacturacion.moneda
        
       

        FROM
        bandejafacturacion
        INNER JOIN detallebandejafacturacion 
        ON bandejafacturacion.idbandejafacturacion = detallebandejafacturacion.idbandejafacturacion
        where 
        bandejafacturacion.estado>0 and detallebandejafacturacion.estado>0
        and date(bandejafacturacion.fecharegistroorigen)  =  '$fini'  ";

        $r=$this->db->query($q)->result_array();
        $return["status"]=true;
        $return["data"]=$r;
        echo json_encode($return);
    }

}
