<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentasbancarias extends CMS_Controller {
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

        $this->template->set_data('title',"Cuentas bancarias");
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data('bancos',$this->getBancos());
        $this->template->set_data('monedas',$this->getMonedas());
        $this->template->renderizar('cuentasbancarias/index');
    }

    public function getData(){

        $this->load->library('Datatables');
        $this->datatables->select('
                   idcuentabanco,
                 idbanco,
                   idmoneda,
                    nbanco,
                    ncuentabanco,
                    nabreviaturabanco,
                    nmoneda,
                    nabrevitauramoneda,
                    monedadescripcion,
                    nro,
                    cci,
                    fechareg,
                    estado
                 ')->from('(
               SELECT
               cuentabanco.idcuentabanco,
                cuentabanco.idbanco,
                cuentabanco.idmoneda,
                banco.nombre as  nbanco,
                cuentabanco.nombre as ncuentabanco,
                banco.abreviatura as nabreviaturabanco,
                moneda.nombre as nmoneda,
                moneda.abreviado as nabrevitauramoneda,
                moneda.descripcion as monedadescripcion,
                cuentabanco.nro,
                cuentabanco.cci,
                cuentabanco.fechareg,
                 cuentabanco.estado
                FROM
                banco
                INNER JOIN cuentabanco ON banco.idbanco = cuentabanco.idbanco
                INNER JOIN moneda ON moneda.idmoneda = cuentabanco.idmoneda
                where cuentabanco.estado>0        
            )temp order by temp.idcuentabanco desc');

        echo $this->datatables->generate();
    }


    public function setForm(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){

            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $selBancos=$post["selBancos"];
            $selMonedas=$post["selMonedas"];
            $ncuenta=$post["ncuenta"];
            $nrocuenta=$post["nrocuenta"];
            $cci=$post["cci"];

           $ddx=array(
            "idbanco"=>$selBancos ,
            "idmoneda"=>$selMonedas ,
            "nombre"=>$ncuenta ,
            "nro"=>$nrocuenta ,
            "cci"=>$cci
           );

            if($isEdit == 0 ){
                $ddx["fechareg"]=$this->getfecha_actual();
                $ddx["estado"]=1;
                $this->db->insert("cuentabanco",$ddx);
            }else if($isEdit == 1 ){
                $id=$idEdit;
                $this->db->update('cuentabanco',$ddx,["idcuentabanco"=>$id]);
            }


          $return["status"]="ok";

        }

        echo json_encode($return);
    }

    public function delete(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->update('cuentabanco', ["estado"=>0], ["idcuentabanco"=>$id]);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function getBancos($id=null,$isJson=null){
        $where="";
        if($id !=null){
            $where=" and  banco.idbanco=$id";
        }
        $query="SELECT
                banco.idbanco,
                banco.abreviatura,
                banco.nombre,
                banco.fechareg
                FROM
                banco
                where banco.estado > 0 $where 
                ";
        $r=$this->db->query($query)->result_array();
        if($isJson==null ){
            return $r;
        }else{
           echo json_encode($r);
        }

    }

    public function getMonedas($id=null,$isJson=null){
        $where="";
        if($id !=null){
            $where=" and  moneda.idmoneda=$id";
        }
        $query="SELECT
                moneda.idmoneda,
                moneda.nombre,
                moneda.abreviado,
                moneda.descripcion,
                moneda.img,
                moneda.estado,
                moneda.fechareg
                FROM
                moneda
                where moneda.estado>0 $where ";
        $r=$this->db->query($query)->result_array();
        if($isJson==null ){
            return $r;
        }else{
            echo json_encode($r);
        }
    }

    /////
    public function setFormRegBanco(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $nombrebanco=$post["banco"];
            $abreviatura=$post["abreviado"];
            $dt=array(
                "nombre"=>$nombrebanco ,
                "abreviatura"=>$abreviatura ,
                "estado"=>1,
                "fechareg"=>$this->getfecha_actual()
            );

            $this->db->trans_start();
            $this->db->insert("banco",$dt);
            $idMax = $this->db->insert_id();
            $dRet=$this->getBancos($idMax);
            $this->db->trans_complete();
            $return["data"]=$dRet;
            $return["status"]="ok";
        }
        echo json_encode($return);
    }
    public function setFormRegMoneda(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $nombremoneda=$post["moneda"];
            $abreviatura=$post["abreviatura"];
            $dt=array(
                "nombre"=>$nombremoneda ,
                "abreviado"=>$abreviatura ,
                "descripcion"=>"",
                "estado"=>1,
                "fechareg"=>$this->getfecha_actual()
            );
            $this->db->trans_start();
            $this->db->insert("moneda",$dt);
            $idMax = $this->db->insert_id();
            $dRet=$this->getMonedas($idMax);
            $this->db->trans_complete();
            $return["data"]=$dRet;
            $return["status"]="ok";
        }
        echo json_encode($return);
    }


    ///
    ///
    public function setEstadoCuenta(){
        $post=$this->input->post(null,true);
        $return["status"]="fail";
        if(sizeof($post)>0){
            $id=$post["id"];
            $estado=(int)$post["estado"];
            $this->db->update('cuentabanco',["estado"=>$estado],["idcuentabanco"=>$id]);
            $return["status"]="ok";
        }
        echo json_encode($return);

    }

}
