<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodleche extends CMS_Controller
{
    private $_list_perm;

    public function __construct(){
        parent::__construct();
        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->load->model("global_model");
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);
    }

    public function index(){
        $dataClientes=$this->global_model->getDataCliente();
        $dataTipoProducto=$this->global_model->getDataTipoProductos();
        $dataAnimales=$this->global_model->getDataAnimalSalida();
        $valRecria=$this->global_model->getValRecria();
        $valPrecioLeche=$this->global_model->getPrecioVentaLeche();
        
        $this->template->set_data("tiposalida",$this->global_model->getDataTipoSalida());
        $this->template->set_data("clientes",$dataClientes);
        $this->template->set_data("tipoproducto",$dataTipoProducto);
        $this->template->set_data("animales",$dataAnimales);
        $this->template->set_data("valrecria",$valRecria);

        $this->template->set_data("valprecioleche",$valPrecioLeche);

        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme', 'plugins/chosen/chosen.min');


        $this->template->add_js('base', 'papaparse/papaparse');
        $this->template->add_js('base', 'underscore');

        $this->template->set_data("razasprodleche",$this->getDataRazasProd());
        $this->template->set_data("fechaprodlecheminmax",$this->fechaProdLecheMinMax());
        $this->template->renderizar('prodleche/index');
    }


    public function getDataTable(){

        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }


    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getData($id));
            echo $result;
        }

    }

    private function _getData($id){
        $campoEditaID="idprodleche";
        $idd=(int)$id;
        $condicion=array(
            'prodleche.estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('fechaprodleche,(sum(cantmaniana) + sum(canttarde)) as totalvendible,sum(cantrecria) as recria,(sum(cantmaniana) + sum(canttarde)+sum(cantrecria)) as total');
        $this->db->from("prodleche");
        $this->db->where($condicion);
        $this->db->group_by("fechaprodleche");
        $this->db->order_by('fechaprodleche', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }




    public function getAnimalLechero(){
        $result=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) >0  ) {
            $fecha=$post["fecha"];
            $query2 = "SELECT
            animales.codanimal,
            animales.codraza,
            animales.idanimal
            FROM
            animales
            INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
            INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
            where LOWER( claseanimal.nombre) ='vaca' and animales.idtiposalidaanimal = 0 and animales.estado = 1
            AND animales.idanimal NOT IN (
            SELECT animales.idanimal
            FROM animales
            INNER JOIN prodleche ON animales.idanimal = prodleche.idanimal
            WHERE	prodleche.estado = 1
            AND prodleche.fechaprodleche = '" . $fecha . "'
            GROUP BY animales.idanimal )";
            $result = $this->db->query($query2)->result_array();
        }
        echo json_encode($result);
    }


    //-------------------------------
    public function getDataProdLecheDiaria(){
        $return=null;
        $post=$this->input->post(null,true);
       // print_r($this->input->post(null,true));exit();
        if(sizeof($post>0)){
            $condicion=["prodleche.estado"=>1,"prodleche.fechaprodleche"=>$post["fecha"]];
            $this->db->select('prodleche.*,animales.codanimal');
            $this->db->from('prodleche');
            $this->db->join('animales', 'prodleche.idanimal = animales.idanimal', 'inner');
            $this->db->where($condicion);
            $this->db->order_by("prodleche.idprodleche","desc");
            $return = $this->db->get()->result_array();

        }
        echo json_encode($return);

    }

    public function saveProdLeche(){
        $post=$this->input->post(null,true);
        $status=null;
        if(sizeof($post)>0){

           // print_r($post);exit();
            $codanimal=$post["selAnimal"];
            $fechareg=$post["fechareg"];
            $cantmaniana=floatval($post["cantmaniana"]);
            $canttarde=floatval($post["canttarde"]);
            $cantrecria=0;
            if($post["isrecria"] == "si"){
                $valRecria=$this->global_model->getValRecria();
                $cantrecria=$valRecria;
            }

            $dataInsert=array(
                "idanimal"=>$codanimal,
                "idpersonal"=>"",
                "cantmaniana" =>$cantmaniana,
                "canttarde"=>$canttarde,
                "cantrecria"=>$cantrecria,
                "fechaprodleche"=>$fechareg,
                "estado"=>1
            );

            $this->db->insert("prodleche",$dataInsert);
            $montoTotalReg=$cantmaniana+$canttarde;
            $this->setStockProdLeche($fechareg,$montoTotalReg);
            $status["status"]="ok";
        }else{
            $status["status"]="Failpost";
        }
        echo json_encode($status);
    }


    public function setStockProdLeche($fecha,$montoTotalReg){
        $issetDataStockTotal=$this->global_model->ifExistDataStockProdLeche();
        $dataProdLeche=$this->global_model->getDataProdLeche();
        if(sizeof($issetDataStockTotal)>0){
            $totalmaniana=floatval($dataProdLeche[0]["totalmaniana"]);
            $totaltarde=floatval($dataProdLeche[0]["totaltarde"]);
            $total=floatval($dataProdLeche[0]["total"]);

            $this->db->set('totalmaniana',$totalmaniana, FALSE);
            $this->db->set('totaltarde',$totaltarde, FALSE);
            $this->db->set('total',$total, FALSE);
            $this->db->set('totalstock', '(totalstock + '.$montoTotalReg.' )' , FALSE);
            $this->db->where('idcod',"stock");
            $this->db->update('prodlechestock');

        }else{
            $dataUp=array(
                "totalmaniana"=>$dataProdLeche[0]["totalmaniana"] ,
                "totaltarde"=>$dataProdLeche[0]["totaltarde"] ,
                "total"=>$dataProdLeche[0]["total"] ,
                "totalstock"=>$dataProdLeche[0]["total"],
                "montosalida"=>0,
                "estado"=> 1 ,
                "idcod"=>"stock",
                "fechaupate"=>$this->getfecha_actual()
            );

            $this->db->insert("prodlechestock",$dataUp);
        }

    }


    public function deleteProdleche(){
        $return["status"]="X";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $id=(int)$post["id"];
            $res=$this->global_model->getDataProdLecheByIdProdLeche($id);
            $totalQuita=-($res[0]["cantmaniana"]+$res[0]["canttarde"]);

            $this->db->update('prodleche',["estado"=>0],["estado"=>1,"idprodleche"=>$id]);
            $this->setStockProdLeche("",$totalQuita);
            $return["status"]="ok";
            // print_r($res);exit();
        }

        echo json_encode($return);
    }

//-----Stock
//
//
    public function getStockActualProdLeche(){
        $data=$this->global_model->getDataStockActualProleche();
        $r["stockactual"]="";
        if(sizeof($data)>0){
            $r["stockactual"]=$data[0]["totalstock"];
        }else{
            $r["stockactual"]=0;
        }
        echo json_encode($r);
    }



    private function getDataRazasProd(){
        $result=null;
        $query="SELECT UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) as raza 
                    FROM
                    prodleche
                    INNER JOIN animales ON animales.idanimal = prodleche.idanimal
                    where prodleche.estado=1
                    GROUP BY  UPPER(replace(Replace(animales.codraza, '  ', ' '),' ',''))
                    order by UPPER(replace(Replace(animales.codraza, '  ', ' '),' ','')) desc
                    "  ;
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function fechaProdLecheMinMax(){
        $result=null;
        $query="SELECT 
                min(prodleche.fechaprodleche) as fechaprodmin,
                max(prodleche.fechaprodleche) as fechaprodmax
                FROM
                prodleche
                where prodleche.estado=1 "  ;
        $result = $this->db->query($query)->result_array();
        return $result[0];
    }
}