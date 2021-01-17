<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kardex extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;

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

        $this->template->set_data("tipoconceptocaja",$this->getTipoConceptoCaja());
        $this->template->set_data("conceptocaja",$this->getConceptoCaja());
        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        //$this->template->add_js('view','index');
        $this->template->renderizar('kardex/index');
    }

    private function getTipoConceptoCaja(){
        $this->db->select('*');
        $this->db->from('tipoconceptocaja');
        $this->db->where("estado = 1");
        // $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function getConceptoCaja(){
        $this->db->select('*');
        $this->db->from('conceptocaja');
        $this->db->where("estado = 1");
        // $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return json_encode($result);
    }
    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=$post['id'];
            $result=json_encode($this->_getData($id));
        }
        echo $result;
    }

    private function _getData($id){
        $campoEditaID="idkardex";
        $idd=(int)$id;
        $condicion="";
        if($id > 0) $condicion=" kardex.idkardex=$id" ;

        /*$this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);*/
        $query="SELECT
                clientes.nombre,
                clientes.razonsocial,
                kardex.fechareg,
                kardex.nrodoc,
                kardex.descripcion,
                kardex.idtipoflujo,
                kardex.idconceptoflujo,
                conceptocaja.idconceptocaja,
                tipoconceptocaja.idtipoconceptocaja,
                tipoconceptocaja.nombre AS tipoconceptoflujo,
                conceptocaja.descripcion AS conceptoflujo,
                kardex.idkardex,
                clientes.ruc,
                clientes.dni,
                sum(detkardex.cantidad * detkardex.precio)   as totaldet 
                FROM
                kardex
                INNER JOIN clientes ON clientes.idcliente = kardex.idcliente
                INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = kardex.idconceptoflujo
                INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
                Inner JOIN detkardex ON kardex.idkardex = detkardex.idkardex
                where kardex.estado > 0  and detkardex.estado>0  $condicion
                GROUP BY kardex.idkardex  order by kardex.idkardex desc  ";
       // $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombre=$post['name'];
            $nombre=str_replace( "\r\n", ' ', $nombre);
            //--

            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre

            );

            if( $isEdit == 0 ){

                $data['estado']=1;

                $this->db->insert($this->table, $data);
                $return['status']='ok';
            }
            if( $isEdit == 1 ){
                //$data['userupdate']=$userId;
                //$data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function deleteData(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array("idkardex" =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("kardex", $dataD);
            $return['status']='ok';
        }else{
           $return['status']='Fallo ';
        }
        echo json_encode($return);
    }
    public function deleteDataDetail(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array("iddetallekardex" =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("detkardex", $dataD);
            $return['status']='ok';
        }else{
            $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    public function getProductosVenta(){
        /*$this->db->select("planproduccion.fechaenvioalmacen,
                        planproduccion.totalingresoplantaeje,
                        planproduccion.pesosecoproceeje,
                        planproduccion.impurezaproceeje,
                        planproduccion.descarteproceeje,
                        (planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje - planproduccion.descarteproceeje ) as pesosemillaprocesada,
                        planproduccion.tipoproduccion,
                        planproduccion.cultivar,
                        productokardex.montoactual,
                        cultivo.nombre AS cultivo,
                        clasecultivo.nombre AS clasecultivo,
                        categoriacultivo.nombre AS catcultivo,
                        productokardex.idplanproduccion,
                        productokardex.idproductokardex,
                        productokardex.fehareg");
        $this->db->from("productokardex");
        $this->db->join("planproduccion","planproduccion.idplanproduccion = productokardex.idplanproduccion","inner");
        $this->db->join("cultivo","cultivo.idcultivo = planproduccion.idcultivo","inner");
        $this->db->join("clasecultivo","clasecultivo.idclasecultivo = planproduccion.idclasecultivo","inner");
        $this->db->join("categoriacultivo","categoriacultivo.idcategoriacultivo = planproduccion.idcategoriacultivo","inner");
        $this->db->where(" planproduccion.estado > 0 ");*/
        $query="select productok.*,  productok.pesosemillaprocesada- cantvendida as stock  from (SELECT
                COALESCE(SUM(detkardex.cantidad),0) AS cantvendida,
                planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje - planproduccion.descarteproceeje as pesosemillaprocesada,
                planproduccion.cultivar,
                cultivo.nombre AS cultivo,
                clasecultivo.nombre AS clasecultivo,
                categoriacultivo.nombre AS catcultivo,
                productokardex.idproductokardex
                FROM
                productokardex
                left  JOIN detkardex ON productokardex.idproductokardex = detkardex.idproductokardex
                INNER JOIN planproduccion ON planproduccion.idplanproduccion = productokardex.idplanproduccion
                INNER JOIN cultivo ON cultivo.idcultivo = planproduccion.idcultivo
                INNER JOIN clasecultivo ON clasecultivo.idclasecultivo = planproduccion.idclasecultivo
                INNER JOIN categoriacultivo ON categoriacultivo.idcategoriacultivo = planproduccion.idcategoriacultivo
                INNER JOIN kardex ON kardex.idkardex = detkardex.idkardex
                where productokardex.estado > 0 and detkardex.estado>0 and kardex.estado > 0
                GROUP BY planproduccion.pesosecoproceeje,
                planproduccion.impurezaproceeje,
                planproduccion.descarteproceeje,
                planproduccion.cultivar,
                productokardex.idproductokardex  ) as productok;";

        $query2="SELECT
                productokardex.idproductokardex,
                productokardex.idplanproduccion,
                tipoproduccion.nombre as tipoproducto,
                cultivo.nombre as cultivo,
                clasecultivo.nombre as clasecultivo,
                categoriacultivo.nombre as catcultivo,
                planproduccion.cultivar ,
                 planproduccion.nroloteproduccion ,
                planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje-planproduccion.descarteproceeje as semillavendible,
                planproduccion.pesosecoproceeje - planproduccion.impurezaproceeje-planproduccion.descarteproceeje - if( ISNULL(prodventa.cantidad),0,prodventa.cantidad)  as stock,
               if( ISNULL(prodventa.cantidad),0,prodventa.cantidad) as prodvendido 
                FROM
                productokardex
                LEFT JOIN (                
                    SELECT
                    detkardex.idproductokardex,
                   COALESCE(SUM(detkardex.cantidad),0) as cantidad	 
                    FROM
                    detkardex 
                    INNER JOIN kardex ON kardex.idkardex = detkardex.idkardex
                    where kardex.estado > 0 and detkardex.estado > 0 
                    GROUP BY detkardex.idproductokardex
                ) AS prodventa 
                ON productokardex.idproductokardex = prodventa.idproductokardex
                INNER JOIN planproduccion ON planproduccion.idplanproduccion = productokardex.idplanproduccion
                INNER JOIN tipoproduccion ON tipoproduccion.idtipoproduccion = planproduccion.tipoproduccion
                INNER JOIN cultivo ON cultivo.idcultivo = planproduccion.idcultivo
                INNER JOIN clasecultivo ON clasecultivo.idclasecultivo = planproduccion.idclasecultivo
                INNER JOIN categoriacultivo ON categoriacultivo.idcategoriacultivo = planproduccion.idcategoriacultivo
                where prodventa.idproductokardex is null or prodventa.idproductokardex is not null 
                and planproduccion.estado > 0 ";
        $result=$this->db->query($query2)->result_array();
        echo json_encode($result);
    }

    public function listClientes(){
        $search = trim($this->input->get('term',TRUE));
        $result=null;
        if ($search){
            $this->db->select("clientes.*,clientes.nombre as value");
            $this->db->from("clientes");
            $this->db->where("(clientes.nombre like '%$search%' or clientes.razonsocial like '%$search%'
                        or clientes.ruc like '%$search%' 
                        or clientes.dni like '%$search%')
                        and clientes.estado > 0");

            $result=$this->db->get()->result_array();
        }
        echo json_encode($result)  ;
    }

    public function setKardex(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
           // $this->showErrors($post);
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $idcliente=isset($post["idcliente"])?$post["idcliente"]: null;
            $idtipoflujo=$post["tipoconceptocaja"];
            $idconceptoflujo=$post["conceptocaja"];
            $nrodoc=$post["nrodoc"];
            $fechak=$post["fechak"];
            $telcli=isset($post["telcli"])?$post["telcli"]: null;
            $dircli=isset($post["dircli"])?$post["dircli"]: null;
            $producto=isset($post["producto"])? $post["producto"]: null;
            $cantidad=isset($post["cantidad"])? $post["cantidad"]: null;
            $precio=isset($post["precio"])? $post["precio"]: null;

            $data=array(
              "idcliente"=>$idcliente,
              "idtipoflujo"=>$idtipoflujo ,
              "idconceptoflujo"=>$idconceptoflujo ,
              "nrodoc"=>$nrodoc,
              "codigo"=>"" ,
              "fechareg"=>$fechak  ,
              "descripcion"=>""  ,
              "lote"=>""  ,
              "monto"=>""  ,
              "dir"=>$dircli ,
              "tel"=>$telcli ,
              "estado"=>1  ,
                );

            if($isEdit == 1){
                $idkardex=$idEdit;
                $this->db->where(["idkardex"=>$idkardex])->update("kardex",$data);
            }else{
                $data["fechacrea"]=$this->getfecha_actual();
                $this->db->insert("kardex",$data);
                $idkardex=$this->db->insert_id();
            }



            if(sizeof($producto)>0){
                $dt=null;
                for($i=0 ;$i<sizeof($producto);$i++){
                    $dt[]=array(
                       "idkardex"=>$idkardex  ,
                      "idproductokardex"=>$producto[$i] ,
                      "descripcion"=>null  ,
                      "cantidad"=>$cantidad[$i],
                      "precio"=>$precio[$i]  ,
                      "estado"=>1  ,
                      "fechareg"=>$this->getfecha_actual() ,
                    );
                }
                if(sizeof($dt)>0){
                    if($isEdit = 1){
                        $this->db->where(["idkardex"=>$idkardex])->update("detkardex",["estado"=>0]);
                    }
                    $this->db->insert_batch("detkardex",$dt);
                    $return["status"]="ok";

                }


            }

           // $this->showErrors($dt);


       // $this->showErrors($post);
        }
        echo json_encode($return);
    }


    public function getDetKardex(){
        $post=$this->input->post(null,true);
        $id=$post["id"];
        $query="SELECT
                clientes.nombre,
                clientes.razonsocial,
                kardex.fechareg,
                kardex.nrodoc,
                kardex.descripcion,
                kardex.idtipoflujo,
                kardex.idconceptoflujo,
                conceptocaja.idconceptocaja,
                tipoconceptocaja.idtipoconceptocaja,
                tipoconceptocaja.nombre AS tipoconceptoflujo,
                conceptocaja.descripcion AS conceptoflujo,
                kardex.idkardex,
                clientes.ruc,
                 clientes.idcliente,
                clientes.dni,
                sum(detkardex.cantidad * detkardex.precio)   as totaldet 
                FROM
                kardex
                INNER JOIN clientes ON clientes.idcliente = kardex.idcliente
                INNER JOIN conceptocaja ON conceptocaja.idconceptocaja = kardex.idconceptoflujo
                INNER JOIN tipoconceptocaja ON tipoconceptocaja.idtipoconceptocaja = conceptocaja.idtipoconceptocaja
                Inner JOIN detkardex ON kardex.idkardex = detkardex.idkardex
                where kardex.estado > 0  and detkardex.estado>0  and kardex.idkardex=$id
                GROUP BY kardex.idkardex order by kardex.idkardex desc ";
        // $this->db->order_by('fechacrea', 'DESC');
        //$result = $this->db->query($query)->result_array();

              $fields=$this->db->query($query)->list_fields();
              $result=$this->db->query($query)->result_array();
              $dt=null;
              $dtt=null;
                foreach($result as $re){
                    foreach($fields as $fi ){
                    $dt[$fi]=$re[$fi];
                    }
                    $dt["data"]=$this->getDetailKardexByid($re["idkardex"]);
                    $dtt[]=$dt;
                }
            $dd[]=array("jj"=>"dd");


        echo json_encode($dtt);
    }

    public function getDetailKardexByid($idkardex){
        $query="SELECT
                detkardex.iddetallekardex,
                detkardex.cantidad,
                detkardex.precio,
                productokardex.idproductokardex,
                planproduccion.cultivar,
                cultivo.nombre AS cultivo,
                tipoproduccion.nombre AS tipoproduccion,
                clasecultivo.nombre AS clasecultivo,
                categoriacultivo.nombre AS catcultivo,
                planproduccion.idplanproduccion
                FROM
                detkardex
                INNER JOIN productokardex ON productokardex.idproductokardex = detkardex.idproductokardex
                INNER JOIN planproduccion ON planproduccion.idplanproduccion = productokardex.idplanproduccion
                INNER JOIN cultivo ON cultivo.idcultivo = planproduccion.idcultivo
                INNER JOIN tipoproduccion ON tipoproduccion.idtipoproduccion = planproduccion.tipoproduccion
                INNER JOIN clasecultivo ON clasecultivo.idclasecultivo = planproduccion.idclasecultivo
                INNER JOIN categoriacultivo ON categoriacultivo.idcategoriacultivo = planproduccion.idcategoriacultivo
                where detkardex.estado>0 and  detkardex.idkardex=$idkardex";
        $result=$this->db->query($query)->result_array();
        return $result;
    }


}
?>