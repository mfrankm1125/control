<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventadolares extends CMS_Controller {
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

        $this->template->set_data('title',"Venta Dolares");
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');

        $this->template->set_data("monedas",$this->getMonedas());

        $this->template->renderizar('ventad/index');
    }



    public function getData(){

        $this->load->library('Datatables');
        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $this->datatables->select('
                idventad,                 
                 clientex,
                 detventaf,
                 fechaventa
                  ')->from('( Select dataventafinal.idventad,
dataventafinal.fechaventa,
dataventafinal.clientex,
concat(\'[\',dataventafinal.detcompraxxxx,\']\') as detventaf

 from (Select 
 ventad.idventad,
ventad.fechaventa,
cliente.nombre as clientex,
GROUP_CONCAT(\'{"iddetcomprad":"\',detpagox.iddetventad
 
,\'","montoventa":"\',detpagox.montoventa
,\'","montoventacambio":"\',detpagox.montoventacambio 
,\'","tasacambioventa":"\',detpagox.tasacambioventa 
,\'" }\') as detcompraxxxx
from 
ventad 
inner join cliente on ventad.idcliente=cliente.idcliente 
inner join (
SELECT
detventad.iddetventad,
detventad.idmoneda,
detventad.montoventa,
detventad.montoventacambio,
detventad.tasacambioventa,
detventad.idventad,
detventad.iddetproductod,
detcomprad.montocompra
FROM
detventad
inner join detcomprad on detventad.iddetproductod=detcomprad.iddetcomprad

where detventad.estado>0 
GROUP BY detventad.iddetventad )as detpagox 
on ventad.idventad = detpagox.idventad  
where ventad.estado>0 group by ventad.idventad order by ventad.idventad desc ) dataventafinal
     
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

    public function getClientes($id=null){

        $w="";
        if($id!=null){
          $w=" and idcliente=$id"  ;
        }
        $q="SELECT
            cliente.idcliente,
            cliente.wallet,
            cliente.nombre,
            cliente.apellidos,
            cliente.documento,
            cliente.estado,
            cliente.fechareg,
            cliente.idinstitucion,
            cliente.idusuario,
            cliente.idfortipoventa
            FROM
            cliente
            where estado>0 and idfortipoventa=1 $w
            order by  idcliente desc  ";
        $r=$this->db->query($q)->result_array();

        if($id!=null){
            return $r;
        }else{
            echo json_encode($r);
        }

    }

    public function getProductos(){
         $q="select * from (SELECT
moneda.abreviado,
moneda.nombre as nmoneda,
detcomprad.montocompra,
detcomprad.tasacambiocompra,
detcomprad.tasacambioventa,
detcomprad.iddetcomprad,
proveedor.razonsocial, 
proveedor.nombre, 
proveedor.idproveedor, 
detcomprad.montocompra-(SELECT
COALESCE(SUM(detventad.montoventa),0)as sumvendido
FROM
detventad
INNER JOIN ventad ON ventad.idventad = detventad.idventad
INNER JOIN detcomprad as dtc ON dtc.iddetcomprad = detventad.iddetproductod 
where detventad.estado > 0 and ventad.estado>0 and dtc.estado>0
and detventad.iddetproductod=detcomprad.iddetcomprad) as stock
FROM
comprad
INNER JOIN detcomprad ON comprad.idcomprad = detcomprad.idcomprad
INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda
INNER JOIN proveedor ON proveedor.idproveedor = comprad.idproveedor
where comprad.estado>0 and detcomprad.estado>0 ) as dataproducto where stock> 0";
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
            $idcliente=$post["idcliente"];
            $fecha=$post["fecha"];
            $fecha=$fecha." ".date("H:i:s");
            $detalleventa=$post["detalleventa"];


            $dataVenta=array(
                "idcliente"=>$idcliente,
                "nrodoc"=> "",
                "fechaventa"=>$fecha
            );

            if($isEdit == 0){
                $dataVenta["estado"]=1;
                $dataVenta["fechareg"]=$this->getfecha_actual();
                $this->db->trans_start();
                $this->db->insert("ventad", $dataVenta);
                $idMax = $this->db->insert_id();
                $this->db->trans_complete();
                $totalMontoDolaresx=0;
                $totalMontoxx=0;
                foreach($detalleventa as $k=>$i ){
                    $detVenta=array(

                        "idventad"=>$idMax  ,
                        "iddetproductod"=>$i["iddetproducto"],
                        "tasacambioventa"=>$i["tcambioventa"]   ,
                        "montoventa"=>$i["cantcompra"]   ,
                        "montoventacambio"=>""  ,
                        "fechareg"=>$this->getfecha_actual()  ,
                        "estado"=>1

                    );
                    $totalMontoxx=$totalMontoxx+(floatval($i["cantcompra"])* floatval($i["tcambioventa"]));
                    $totalMontoDolaresx=$totalMontoDolaresx+floatval($i["cantcompra"]);
                    $this->db->trans_start();
                    $this->db->insert("detventad", $detVenta);
                    $idMaxDet = $this->db->insert_id();
                    $this->db->trans_complete();


                }

                $idconcepto="3";
                $idmoneda="1";
                $montoencaja=$totalMontoxx;
                $fechaenviocaja=$fecha;
                $descripcionenviocaja="";
                $idref=$idMax;
                $tablaref="ventad";
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
                    "montodolar"=>$totalMontoDolaresx

                );
                $this->db->insert("movimientoscajad", $data);



                $return["status"]="ok";
            }else{
                $totalMontoxx=0;
                $totalMontoDolaresx=0;
                $this->db->update('ventad', $dataVenta, ["idventad"=>$idEdit]);

                $this->db->update('detventad',["estado"=>0], ["idventad"=>$idEdit]);
               // $this->showErrors($detalleventa);
                foreach($detalleventa as $k=>$i ){
                    $iddetventa=$i["iddetventa"];
                    $detVenta=array(

                        "idventad"=>$idEdit  ,
                        "iddetproductod"=>$i["iddetproducto"],
                        "tasacambioventa"=>$i["tcambioventa"]   ,
                        "montoventa"=>$i["cantcompra"]   ,
                        "montoventacambio"=>""  ,
                        "estado"=>1

                    );
                    $totalMontoxx=$totalMontoxx+(floatval($i["cantcompra"])* floatval($i["tcambioventa"]));

                    $totalMontoDolaresx=$totalMontoDolaresx+floatval($i["cantcompra"]);
                    if($iddetventa !=0 || $iddetventa !="0" ){
                        $this->db->update('detventad',$detVenta, ["iddetventad"=>$iddetventa]);
                    }else{
                        $detVenta["fechareg"]=$this->getfecha_actual();

                        $this->db->insert("detventad",$detVenta);
                        $iddetventa = $this->db->insert_id();
                        $this->db->trans_complete();

                    }


                   // $this->db->insert_batch("detpagoventad", $detPagoVenta);
                   /* $this->db->update('detpagocomprad',["estado"=>0], ["iddetcomprad"=>$iddetcompra]);
                    $this->db->insert_batch("detpagocomprad", $detPagoCompra);*/

                }
                $idMax=$idEdit;
                $c=$this->verificaMontoVentaComprabyId($idMax);
                if($c == 0){
                    $idconcepto="3"; // Por venta de dolares
                     $idmoneda="1";
                    $montoencaja=$totalMontoxx;
                    $fechaenviocaja=$fecha;
                    $descripcionenviocaja="";
                    $idref=$idMax;
                    $tablaref="ventad";
                    $data=array(
                        "idconceptocaja"=>$idconcepto,
                        "moneda"=>$idmoneda,
                        "monto"=>$montoencaja,
                        "fecharegistro"=>$fechaenviocaja." ".date( 'H:i:s'),
                        "descripcion"=>$descripcionenviocaja,
                        "estado"=>1,
                        "fechareg"=>$this->getfecha_actual(),
                        "idreferencia"=>$idref,
                        "tablareferencia"=>$tablaref,
                        "montodolar"=>$totalMontoDolaresx


                    );
                    $this->db->insert("movimientoscajad", $data);
                }else{
                    $idconcepto="3";// Por venta de dolares
                    $idmoneda="1";
                    $montoencaja=$totalMontoxx;
                    $fechaenviocaja=$fecha;
                    $descripcionenviocaja="";
                    $idref=$idMax;
                    $tablaref="ventad";

                    $dataCaja=array(
                        "idconceptocaja"=>$idconcepto,
                        "moneda"=>$idmoneda,
                        "monto"=>$montoencaja,
                        "fecharegistro"=>$fechaenviocaja." ".date( 'H:i:s'),
                        "descripcion"=>$descripcionenviocaja,
                        "idreferencia"=>$idref,
                        "tablareferencia"=>$tablaref,
                        "montodolar"=>$totalMontoDolaresx

                    );
                    $this->db->update('movimientoscajad',$dataCaja, ["idreferencia"=>$idref,"tablareferencia"=>$tablaref,'idconceptocaja'=>$idconcepto]);
                }

                $return["status"]="ok";
            }




        }
        echo json_encode($return);
    }

    private function verificaMontoVentaComprabyId($idventacompra){
        $q="SELECT
count(movimientoscajad.idreferencia) as c 
FROM
movimientoscajad
where 
estado>0 and movimientoscajad.tablareferencia='ventad' and movimientoscajad.idreferencia=$idventacompra";
        $r=$this->db->query($q)->result_array();
        return $r[0]["c"];
    }

    public function delete(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->update('ventad', ["estado"=>0], ["idventad"=>$id]);
            $this->db->where(["idreferencia"=>$id,"tablareferencia"=>"ventad"])->update("movimientoscajad",["estado"=>0]);

            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function getDataEdit(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $return["data"]=$this->_getDataVenta($id);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    private function _getDataVenta($id){

        $where="";
        if($id){
            $where=" and  ventad.idventad=$id"  ;
        }
        $query="SELECT
                DISTINCT
                ventad.idventad,
                ventad.idcliente,
                ventad.nrodoc,
                ventad.fechaventa,
                ventad.fechareg,
                ventad.estado
                FROM
                ventad
                INNER JOIN detventad ON ventad.idventad = detventad.idventad
                where ventad.estado>0 and detventad.estado>0  $where ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            $detalleVenta=$this->_detalleVenta($i["idventad"]);
           // $detallePago=$this->_detallePago($i["idcompra"]); ;
            $dt[]=array(
                "idventad"=>$i["idventad"],
                "fechaventa"=>$i["fechaventa"],
                "idcliente"=>$i["idcliente"],
                "detalleventa"=>$detalleVenta
            );
        }
        return $dt;
    }
    private function _detalleVenta($id=null){
        $where="";
        if($id){
            $where=" and  detventad.idventad=$id"  ;
        }
        $query="select ddd.*   from (SELECT\n".
            "detventad.iddetventad,\n".
            "detventad.idmoneda,\n".

            "detventad.montoventa,\n".
            "detventad.montoventacambio,\n".
            "detventad.tasacambioventa,\n".
            "detventad.idventad,\n".
            "detventad.iddetproductod,\n".
            "	m.nombre as detmoneda	,\n".
            "proveedor.nombre , ".
            "proveedor.razonsocial , ".
            "proveedor.idproveedor , ".
            "detcomprad.montocompra, \n".
            "detcomprad.tasacambiocompra    \n".
            "FROM\n".
            "detventad\n".

            "inner join detcomprad on detventad.iddetproductod=detcomprad.iddetcomprad\n".
            "inner join comprad on detcomprad.idcomprad=comprad.idcomprad\n".
            "inner join proveedor on comprad.idproveedor=proveedor.idproveedor\n".
            "INNER JOIN moneda as m ON detcomprad.idmoneda = m.idmoneda\n".

            "where  detventad.estado >0   $where  \n".
            "GROUP BY detventad.iddetventad ) as ddd";
        $r=$this->db->query($query)->result_array();
        return $r;
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



    /// stock total venta dolares
    public function getStockTotal(){
        $query="SELECT 
coalesce(sum(detventad.montoventa),0) as totalventamoneda
FROM
detventad
INNER JOIN ventad ON ventad.idventad = detventad.idventad
INNER JOIN detcomprad ON detcomprad.iddetcomprad = detventad.iddetproductod
INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda
INNER JOIN comprad ON comprad.idcomprad = detcomprad.idcomprad
where detcomprad.estado > 0 and comprad.estado>0 and detventad.estado>0 and ventad.estado>0
GROUP BY detcomprad.idmoneda
        ";
        $r=$this->db->query($query)->result_array();


        $query2="SELECT
coalesce(sum(detcomprad.montocompra),0) as totalcompradomoneda 
FROM
detcomprad
INNER JOIN comprad ON comprad.idcomprad = detcomprad.idcomprad
INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda
where detcomprad.estado>0 and comprad.estado>0
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

        echo json_encode(["stockTotal"=>$dd]);
    }


    public function getVentaR(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0) {
            $fini=$post["fini"];
            $fend=$post["fend"];
        $q = "SELECT
        DISTINCT
        ventad.idventad,
        cliente.nombre,
        cliente.apellidos,
        cliente.documento,
        ventad.fechaventa
        FROM
        cliente
        INNER JOIN ventad ON cliente.idcliente = ventad.idcliente
        INNER JOIN detventad ON ventad.idventad = detventad.idventad
        where ventad.estado>0 and ventad.estado>0 and date(ventad.fechaventa) between '$fini' and '$fend' ";
        $r=$this->db->query($q)->result_array();
        $dd=[];
        foreach ($r as $k=>$i) {
            $dataDetVenta=$this->getDetVentaR($i["idventad"]);
            $dd[]=array(
                  "idventad"=>$i["idventad"],
                 "nombre"=>$i["nombre"],
                 "apellidos"=>$i["apellidos"],
                 "documento"=>$i["documento"],
                 "fechaventa"=>$i["fechaventa"],
                "detventa"=>$dataDetVenta

            );
        }
        $return["data"]=$dd;
            $return["status"]="ok";
        }

        echo json_encode($return);
    }

    public function getDetVentaR($idventad){
        $q="select TT.*,(TT.psolesv - TT.psolesc) as ganancia from (SELECT
            detventad.iddetventad,
            detventad.iddetproductod, 
            detventad.montoventa,
            detventad.tasacambioventa,
            detcomprad.tasacambiocompra,
            moneda.nombre AS nmoneda,
            moneda.abreviado,
            detcomprad.montocompra,
            (detventad.montoventa*detventad.tasacambioventa) as psolesv,
            (detventad.montoventa*detcomprad.tasacambiocompra) as psolesc
            
            FROM
            detventad
            INNER JOIN detcomprad ON detcomprad.iddetcomprad = detventad.iddetproductod
            INNER JOIN comprad ON comprad.idcomprad = detcomprad.idcomprad
            INNER JOIN moneda ON moneda.idmoneda = detcomprad.idmoneda 
            where detventad.idventad=$idventad and 
            detventad.estado>0 and detcomprad.estado>0 and comprad.estado>0 
            ) as TT ";
        $r=$this->db->query($q)->result_array();
        return $r;

    }

    public function setFormCliente(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $nombres=$post["nombres"];
            $apellidos=$post["apellidos"];
            $wallet="";
            $documento=$post["documento"];
            $fregistro=$post["fregistro"];

            $ruc=$post["ruc"];
            $razon=$post["razsocial"];
            $dir=$post["direccion"];

            $nombres=str_replace( "\r\n", ' ', $nombres);
            $apellidos=str_replace( "\r\n", ' ', $apellidos);

            //--

            $data=array(
                "nombre"=>$nombres,
                "apellidos"=>$apellidos,
                "wallet"=>"",
                "documento"=>$documento,
                "fechareg"=>$fregistro,
                "ruc"=>$ruc,
                "razonsocial"=>$razon,
                "direccion"=>$dir,
                "idfortipoventa"=>1

            );


            $data['estado']=1;
            $this->db->trans_start();
            $this->db->insert("cliente", $data);
            $idMax=$this->db->insert_id();
            $this->db->trans_complete();
            $return['status']='ok';
            $return['data']= $this->getClientes($idMax) ;

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }
}
