<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CMS_Controller
{
    private  $mesVar=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    private  $mesVarIn=["Enero"=>1,"Febrero"=>2,"Marzo"=>3,"Abril"=>4,"Mayo"=>5,"Junio"=>6,"Julio"=>7,"Agosto"=>8,"Septiembre"=>9,"Octubre"=>10,"Noviembre"=>11,"Diciembre"=>12];

    public function __construct()
    {
        parent::__construct();
        $this->load->model("global_model");

    }

    public  function  index(){
      $this->template->set_data("anios",$this->getAniosData());
      $this->template->renderizar('admin/index');

    }

    public function getAniosData(){
        $q="SELECT
            DISTINCT
            Year(venta.fechaventa) as anio
            FROM
            venta
            where venta.estado>0";
        $r=$this->db->query($q)->result_array();
        if(sizeof($r)==0){
            $r=["anio"=>date("Y")];
        }
        return $r;
    }

    public function chartGanaciaByMeses(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $meses=$this->mesesByGanacia($anio);
            $dt=[];
            foreach($meses as $mes){
                $dtGananciaBymes=$this->calGanaciaByMesAnio($anio,$mes["mes"]);
                $dt[]=array(
                    "name"=>$mes["mes"],
                    "y"=>floatval($dtGananciaBymes),
                );
            }
            $return=$dt;
        }
        echo json_encode($return);
    }

    private function calGanaciaByMesAnio($anio=null,$mes=null,$dias=null){
        $dt=$this->_venta($anio,$mes,$dias);
        $totalGanancia=0;
        foreach($dt as $venta){
            foreach($venta["detVenta"] as $dtVenta){
               $montoSolesVenta1=floatval($dtVenta["montobtc"])*floatval($dtVenta["precioventa"]);
               $montoSolesVenta2=floatval($dtVenta["montobtc"])*floatval($dtVenta["preciounidadbtccomprado"]);
               $ganancia=$montoSolesVenta1-$montoSolesVenta2;
                $totalGanancia=$totalGanancia+floatval($ganancia);
                //TotalGananciaGeneral+=ganancia;
            }
        }
        return $totalGanancia;
    }


    private function mesesByGanacia($anio=null,$mes=null){
        $where="";
        $sel="";

        if($anio){
            $where=" and Year(venta.fechaventa)=$anio ";
            $sel="  MONTH(venta.fechaventa) as mes ";
        }
        if($mes){
            $where=" and Year(venta.fechaventa)=$anio and month(venta.fechaventa)=$mes ";
            $sel="  day(venta.fechaventa) as dias ";
        }
        $q="SELECT
        DISTINCT
        $sel
        FROM
        venta
        INNER JOIN detventa ON venta.idventa = detventa.idventa
        INNER JOIN detpagoventa ON venta.idventa = detpagoventa.idventa
        where venta.estado>0 and detventa.estado>0 and detpagoventa.estado>0  $where
        order by  venta.fechaventa    asc";
        $r=$this->db->query($q)->result_array();
        return $r;

    }

    public function chartGanaciaByMesesXDia(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $mesd=$post["mes"];
            $dias=$this->mesesByGanacia($anio,$mesd);

            $dt=[];
            foreach($dias as $i){
                $dtGananciaBymes=$this->calGanaciaByMesAnio($anio,$mesd,$i["dias"]);
                $dt[]=array(
                    "name"=>$i["dias"],
                    "y"=>floatval($dtGananciaBymes),
                );
            }
            $return=$dt;
        }
        echo json_encode($return);
    }

    private function _venta($anio=null,$mes=null,$dia=null){
        $where="";

        if($anio){
            $where.=" and Year(venta.fechaventa)='$anio'  ";
        }
        if($mes){
            $where.=" and Month(venta.fechaventa)='$mes' ";
        }
        if($dia){
            $where.=" and day(venta.fechaventa)='$dia' ";
        }
        $query="SELECT
                DISTINCT
                venta.idventa,
                venta.cliente,
                venta.wallet,
                venta.fechaventa,
                venta.idreferencia,
                cliente.idcliente,
                 cliente.nombre,
                cliente.apellidos
                FROM
                venta inner join cliente on venta.idcliente=cliente.idcliente
                inner join detventa on  venta.idventa =detventa.idventa
                inner join detpagoventa on venta.idventa=detpagoventa.idventa
                where venta.estado > 0 and detventa.estado>0   $where   ";
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
            detventa.iddetventa,
            detventa.idventa,
            detventa.idrefdetcompra,
            detventa.montobtc,
            detventa.montosoles,
            detventa.precioventa,
    
            detcompra.cantbtc AS cantbtccomprado,
            detcompra.preciounidadbtc AS preciounidadbtccomprado,
            detcompra.cantmoneda AS cantmonedacomprado,
            detcompra.tipocambio AS tipocambiocomprado,
             detcompra.preciobaseventa  ,
            compra.proveedor
            FROM
            detventa
            INNER JOIN detcompra ON detcompra.iddetcompra = detventa.idrefdetcompra
            INNER JOIN compra ON compra.idcompra = detcompra.idcompra
            where detcompra.estado>0 and detventa.estado>0 and detventa.idventa = $idventa";
        $r=$this->db->query($query)->result_array();
        return $r;
    }
    private function _detpago($idventa){
        $query="SELECT
        detpagoventa.idventa,
        detpagoventa.idcuentabanco,
        detpagoventa.monto,
        detpagoventa.tasacambio,
        detpagoventa.nrooperacion,
        cuentabanco.nombre AS ncuenta,
        banco.nombre AS banco,
        banco.abreviatura AS abrebanco,
        cuentabanco.nro,
        moneda.nombre as nmoneda,
        moneda.abreviado as abremoneda
        FROM
        detpagoventa
        INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventa.idcuentabanco
        INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco
        INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
        where detpagoventa.estado>0  and detpagoventa.idventa=$idventa ";
        $r=$this->db->query($query)->result_array();
        return $r;
    }



   



    ///----------------------------------------



    ///------------------------------------------------------------------


    public function chartGanaciaByMesesXDiaDolares(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $mesd=$post["mes"];
            $dias=$this->mesesByGanaciaDolares($anio,$mesd);

            $dt=[];
            foreach($dias as $i){
                $dtGananciaBymes=$this->calGanaciaByMesAnioDolares($anio,$mesd,$i["dias"]);
                $dt[]=array(
                    "name"=>$i["dias"],
                    "y"=>floatval($dtGananciaBymes),
                );
            }
            $return=$dt;
        }
        echo json_encode($return);
    }

    public function chartGanaciaByMesesDolares(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $anio=$post["anio"];
            $meses=$this->mesesByGanaciaDolares($anio);
            $dt=[];
            foreach($meses as $mes){
                $dtGananciaBymes=$this->calGanaciaByMesAnioDolares($anio,$mes["mes"]);
                $dt[]=array(
                    "name"=>$mes["mes"],
                    "y"=>floatval($dtGananciaBymes),
                );
            }
            $return=$dt;
        }
        echo json_encode($return);
    }

    private function calGanaciaByMesAnioDolares($anio=null,$mes=null,$dias=null){
        $dt=$this->_ventaDolares($anio,$mes,$dias);
        $totalGanancia=0;
        foreach($dt as $venta){
            foreach($venta["detVenta"] as $dtVenta){
                $montoSolesVenta1=floatval($dtVenta["montoventa"])*floatval($dtVenta["tasacambioventa"]);
                $montoSolesVenta2=floatval($dtVenta["montoventa"])*floatval($dtVenta["tasacambiocompra"]);
                $ganancia=$montoSolesVenta1-$montoSolesVenta2;
                $totalGanancia=$totalGanancia+floatval($ganancia);
                //TotalGananciaGeneral+=ganancia;
            }
        }
        return $totalGanancia;
    }


    private function mesesByGanaciaDolares($anio=null,$mes=null){
        $where="";
        $sel="";

        if($anio){
            $where=" and Year(ventad.fechaventa)=$anio ";
            $sel="  MONTH(ventad.fechaventa) as mes ";
        }
        if($mes){
            $where=" and Year(ventad.fechaventa)=$anio and month(ventad.fechaventa)=$mes ";
            $sel="  day(ventad.fechaventa) as dias ";
        }
        $q="SELECT
        DISTINCT
        $sel
        FROM
        ventad
        INNER JOIN detventad ON ventad.idventad = detventad.idventad
        INNER JOIN detpagoventad ON detventad.iddetventad = detpagoventad.iddetventad
        where ventad.estado>0 and detventad.estado>0 and detpagoventad.estado>0  $where
        order by  ventad.fechaventa    asc";
        $r=$this->db->query($q)->result_array();
        return $r;

    }

    private function _ventaDolares($anio=null,$mes=null,$dia=null){
        $where="";

        if($anio){
            $where.=" and Year(ventad.fechaventa)='$anio'  ";
        }
        if($mes){
            $where.=" and Month(ventad.fechaventa)='$mes' ";
        }
        if($dia){
            $where.=" and day(ventad.fechaventa)='$dia' ";
        }
        $query="SELECT                
                ventad.idventad,
                ventad.idcliente,               
                ventad.fechaventa,                
                cliente.idcliente,
                cliente.nombre,
                cliente.apellidos
                FROM
                ventad inner join cliente on ventad.idcliente=cliente.idcliente
                inner join detventad on  ventad.idventad =detventad.idventad
                inner join detpagoventad on detventad.iddetventad=detpagoventad.iddetventad
                where ventad.estado > 0 and detventad.estado>0   $where group by ventad.idventad  ";
        $r=$this->db->query($query)->result_array();
        $dt=[];
        foreach($r  as $k=>$i){
            $detVenta=$this->_detVentaDolares($i["idventad"]);
            $dt[]=array(
                "detVenta"=>$detVenta
            );

        }
        return $dt;
    }
    private function _detVentaDolares($idventa){
        $query="select ddd.* , concat('[',ddd.pagodet,']') as detallepagox from (SELECT\n".
            "detventad.iddetventad,\n".
            "detventad.idmoneda,\n".
            "GROUP_CONCAT('{\"moneda\":\"',moneda.abreviado\n".
            ",' \",\"banco\":\"',banco.nombre\n".
            ",'\",\"iddetpagoventad\":\"',detpagoventad.iddetpagoventad\n".
            ",'\",\"cuentabanco\":\"',cuentabanco.nombre\n".
            ",'\",\"idcuentabanco\":\"',cuentabanco.idcuentabanco\n".
            ",'\",\"nroop\":\" ',detpagoventad.nroop\n".
            ",'\",\"monto\":\"',detpagoventad.monto,'\"}') AS pagodet,\n".
            "detventad.montoventa,\n".
            "detventad.montoventacambio,\n".
            "detventad.tasacambioventa,\n".
            "detventad.idventad,\n".
            "detventad.iddetproductod,\n".
            "	m.nombre as detmoneda	,\n".
            "detcomprad.montocompra,\n".
            "detcomprad.tasacambiocompra\n".
            "FROM\n".
            "detventad\n".
            "INNER JOIN detpagoventad ON detventad.iddetventad = detpagoventad.iddetventad\n".
            "inner join detcomprad on detventad.iddetproductod=detcomprad.iddetcomprad\n".
            "INNER JOIN moneda as m ON detcomprad.idmoneda = m.idmoneda\n".
            "INNER JOIN cuentabanco ON cuentabanco.idcuentabanco = detpagoventad.idcuentabanco\n".
            "INNER JOIN banco ON banco.idbanco = cuentabanco.idbanco\n".
            "INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda\n".
            "where  detventad.estado >0 and detpagoventad.estado>0 and detventad.idventad = $idventa  \n".
            "GROUP BY detventad.iddetventad ) as ddd";
        $r=$this->db->query($query)->result_array();
        return $r;
    }


}