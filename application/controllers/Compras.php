<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CMS_Controller {
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
        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data('proveedores',$this->getProveedores());
        $this->template->set_data("bancos",$this->getBancoByMoneda());
        $this->template->renderizar('compras/index');
    }

    public function getData(){
 $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $this->load->library('Datatables');
        $this->datatables->select('idcompra,
                 wallet,
                 proveedor,
                 cantbtc,
                 cantmoneda,
                 preciounidadbtc,
                 preciobaseventa,
                 fechacompra,
                 nrodoc,
                 nproveedor,
                 detallepago')->from('(
            SELECT
                compra.idcompra,
                compra.wallet,
                compra.proveedor,
                detcompra.cantbtc,
                detcompra.cantmoneda,
                detcompra.preciounidadbtc,
                detcompra.preciobaseventa,
                compra.fechacompra,
                compra.nrodoc,
                proveedor.nombre as nproveedor,
                GROUP_CONCAT(cuentabancox.ctdetcuentabanco) detallepago
                FROM
                compra
                inner join proveedor on compra.idproveedor=proveedor.idproveedor
                INNER JOIN detcompra ON compra.idcompra = detcompra.idcompra
                inner JOIN (SELECT
                CONCAT(banco.abreviatura,"-",moneda.abreviado,"-",detpagocompra.monto,"-",detpagocompra.tasacambio,"-",cuentabanco.nombre) as ctdetcuentabanco,
                detpagocompra.idcompra
                FROM
                detpagocompra
                INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagocompra.idcuentabanco
                INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
                INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
                where detpagocompra.estado>0   
                    ) as cuentabancox on compra.idcompra = cuentabancox.idcompra
                where compra.estado>0 and detcompra.estado>0 
                GROUP BY compra.idcompra  
                order by   compra.idcompra desc        
            )temp ');

        echo $this->datatables->generate();
    }

    public function getBancoByMoneda(){
        $q="SELECT
            moneda.nombre as moneda,
            banco.nombre as banco,
            banco.abreviatura,            
            cuentabanco.nombre as ncuenta,
            cuentabanco.nro,
            cuentabanco.idcuentabanco,
            cuentabanco.idbanco,
            cuentabanco.idmoneda
            FROM
            moneda
            INNER JOIN cuentabanco ON moneda.idmoneda = cuentabanco.idmoneda
            INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
            where moneda.estado>0 and banco.estado>0 and cuentabanco.estado>0 ";
        $r=$this->db->query($q)->result_array();
        return $r;
    }

    public function setForm(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $proveedor=$post["proveedor"];
            $pcbtc=$post["pcbtc"];
            $cantbtc=$post["cantbtc"];
            $cantsoles=$post["cantsoles"];
            $pventa=$post["pventa"];
            $tasaCambio=$post["tasaCambio"];
            $wallet=$post["wallet"];
            $fecha=$post["fecha"];

            $cuentabanco=$post["cuentabanco"];
            $monto=$post["monto"];
             $comision=$post["pcomision"];
            $pcbtcSinComision=$post["pcbtcsincomision"];

            $dtCompra=array(
            "proveedor"=>$proveedor,
            "wallet"=>$wallet,
            "nrodoc"=>"",
            "fechacompra"=>$fecha,
            "idproveedor"=>$proveedor
            );

            if($isEdit == 0){
                $dtCompra["fechareg"]=date("Y-m-d");
                $dtCompra["estado"]=1;
                $dtCompra["idinstitucion"]=1;
                $dtCompra["idusuario"]=1;

                $this->db->trans_start();
                $this->db->insert("compra",$dtCompra);
                $idMax = $this->db->insert_id();
                $this->db->trans_complete();

                $detCompra=array(
                    "idcompra"=>$idMax ,
                    "idmoneda"=>0,
                    "cantbtc"=>$cantbtc,
                    "cantmoneda"=>$cantsoles ,
                    "tipocambio"=>1 ,
                    "preciounidadbtc"=>$pcbtc ,
                    "preciobaseventa"=>$pventa ,
                    "fechareg"=> date("Y-m-d"),
                    "fechacompra"=> $fecha,
                    "estado"=>1 ,
                    "idinstitucion"=>1 ,
                    "idusuario"=> 1,
                    "comision"=>$comision,
                    "preciounidadbtcsincomision"=>$pcbtcSinComision

                );
                $this->db->insert("detcompra",$detCompra);
                $detPago=[];
                $nroopreacion=$post["nrooperacion"];
                for($i=0 ;$i<sizeof($cuentabanco);$i++){
                    if( floatval($monto[$i]) > 0 ){
                        $detPago[]=array(
                            "idcompra"=>$idMax ,
                            "idcuentabanco"=>$cuentabanco[$i] ,
                            "monto"=>$monto[$i] ,
                            "tasacambio"=>$tasaCambio,
                            "estado"=>1 ,
                            "fechareg"=>$fecha ,
                            "idinstitucion"=>1,
                            "nrooperacion"=>$nroopreacion[$i]
                        );
                    }
                }
                $return["status"]="ok";
                $this->db->insert_batch("detpagocompra",$detPago);
            }else if($isEdit == 1){
                $this->db->update('compra',$dtCompra, ["idcompra"=>$idEdit]);
                $this->db->update('detpagocompra', ["estado"=>0], ["idcompra"=>$idEdit]);
                $idMax=$idEdit;
                $detCompra=array(
                    "idcompra"=>$idMax ,
                    "idmoneda"=>0,
                    "cantbtc"=>$cantbtc,
                    "cantmoneda"=>$cantsoles ,
                    "tipocambio"=>1 ,
                    "preciounidadbtc"=>$pcbtc ,
                    "preciobaseventa"=>$pventa ,
                    "fechacompra"=> $fecha,
                    "estado"=>1 ,
                    "idinstitucion"=>1 ,
                    "idusuario"=> 1,
                     "comision"=>$comision,
                       "preciounidadbtcsincomision"=>$pcbtcSinComision
                );
                $this->db->update('detcompra',$detCompra, ["idcompra"=>$idEdit]);
                $nroopreacion=$post["nrooperacion"];
                $detPago=[];
                for($i=0 ;$i<sizeof($cuentabanco);$i++){
                    if( floatval($monto[$i]) > 0 ){
                        $detPago[]=array(
                            "idcompra"=>$idMax ,
                            "idcuentabanco"=>$cuentabanco[$i] ,
                            "monto"=>$monto[$i] ,
                            "tasacambio"=>$tasaCambio,
                            "estado"=>1 ,
                            "fechareg"=>$fecha ,
                            "idinstitucion"=>1,
                            "nrooperacion"=>$nroopreacion[$i]
                        );
                    }
                }
                $return["status"]="ok";
                $this->db->insert_batch("detpagocompra",$detPago);

            }





        }

        echo json_encode($return);
    }

    public function delete(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->update('compra', ["estado"=>0], ["idcompra"=>$id]);
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
            $where=" and  compra.idcompra=$id"  ;
        }
        $query="SELECT
                compra.idcompra,
                compra.wallet,
                compra.nrodoc,
                compra.fechareg,
                compra.fechacompra,
                compra.estado,
                proveedor
              
                FROM
                compra
                where compra.estado>0 $where ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r as $k=>$i){
            $detalleCompra=$this->_detalleCompra($i["idcompra"]);
            $detallePago=$this->_detallePago($i["idcompra"]); ;
              $dt[]=array(
                  "idcompra"=>$i["idcompra"],
                   "wallet"=>$i["wallet"],
                   "nrodoc"=>$i["nrodoc"],
                   "fechareg"=>$i["fechareg"],
                   "fechacompra"=>$i["fechacompra"],
                   "proveedor"=>$i["proveedor"],
                   "detallecompra"=>$detalleCompra,
                  "detallepago"=>$detallePago

              );
        }
        return $dt;
    }
    private function _detalleCompra($id=null){
        $where="";
        if($id){
          $where=" and  detcompra.idcompra=$id"  ;
        }
        $query="SELECT
                detcompra.iddetcompra,
                detcompra.idcompra,
                detcompra.idmoneda,
                detcompra.cantbtc,
                detcompra.cantmoneda,
                detcompra.tipocambio,
                detcompra.preciounidadbtc,
                detcompra.preciobaseventa,
                 detcompra.comision,
                 detcompra.preciounidadbtcsincomision 
                FROM
                compra
                INNER JOIN detcompra ON compra.idcompra = detcompra.idcompra
                where compra.estado>0 and detcompra.estado>0  $where ";
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

}
