<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductoKardex extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="tipoanimales";
    private $pk_idtable="idtipoanimal";
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
        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        //$this->template->add_js('view','index');
        $this->template->renderizar('productokardex/index');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
      //  $this->showErrors($result);
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

        $idd=(int)$id;
        $condicion="";
        if($id > 0) $condicion=" and productokardex.idproductokardex=".$idd;

        /*$this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);
       // $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();*/
        $query2="SELECT
                productokardex.idproductokardex,
                productokardex.idplanproduccion,
                tipoproduccion.nombre as tipoproducto,
                cultivo.nombre as cultivo,
                 cultivo.kgsaco  ,
                clasecultivo.nombre as clasecultivo,
                categoriacultivo.nombre as catcultivo,
                planproduccion.cultivar ,
                  planproduccion.anio ,
                planproduccion.fechaenvioalmacen ,
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
                and planproduccion.estado > 0 $condicion";
        $result=$this->db->query($query2)->result_array();
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
            $condicion=array($this->pk_idtable =>$id);
            $dataD=array(

                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            $return['status']='ok';

        }else{
           $return['status']='Fallo ';
        }
        echo json_encode($return);
    }


    public function getProductKardex(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
            if(sizeof($post) > 0){
                $id=$post["id"];
               $query="SELECT
                    clientes.nombre,
                    clientes.razonsocial,
                    detkardex.cantidad,
                    detkardex.precio,
                    kardex.fechareg,
                    kardex.idtipoflujo,
                    kardex.nrodoc,
                    detkardex.idproductokardex
                    FROM
                    kardex
                    INNER JOIN clientes ON clientes.idcliente = kardex.idcliente
                    INNER JOIN detkardex ON kardex.idkardex = detkardex.idkardex
                    where kardex.estado>0 and detkardex.estado>0 and detkardex.idproductokardex=$id 
                    order by kardex.fechareg asc
                    ";
              $return=$this->db->query($query)->result_array();

            }
            echo json_encode($return);
    }

}
