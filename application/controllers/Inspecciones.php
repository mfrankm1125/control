<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspecciones extends CMS_Controller {
    private $_list_perm;

    private $table="almacenes";
    private $pk_idtable="id_almacen";
    private $nameClass="almacen";
    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);
        $this->load->model('inspecciones_model');
        $this->load->model('ubigeo_model');
    }

    public function index(){

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data("viviendas",$this->getDataListViviendas());
        $this->template->set_data("jefebrigada",$this->inspecciones_model->getUsersByRole("Jefe Brigada"));
        $this->template->set_data("inspector",$this->inspecciones_model->getUsersByRole("Inspector"));
           $this->template->set_data("estadovivienda",$this->inspecciones_model->getEstadoViviendaEnInspeccion());
        $this->template->renderizar('inspecciones/index');
    }

    public function getDataListViviendas(){
         return json_encode($this->inspecciones_model->getDataListViviendas());
    }

    public function getDataTable(){
        $result=json_encode($this->_getData(null));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $return=$this->inspecciones_model->getInspeccionesViviendas($id);
            echo json_encode($return);
        }

    }

    private function _getData($id){
        $return=$this->inspecciones_model->getInspeccionesViviendas($id);
        return $return;
    }

    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);


            $nombre=$this->input->post('name',true);
            $dirorubi=$this->input->post('direccion',true);
            $desc=$this->input->post('desc',true);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre ,
                'descripcion'=>$desc,
                'direccion'=>$dirorubi,
            );


            if( $isEdit == 0 ){
                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;

                $this->db->insert($this->table, $data);
                echo 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $data);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }

    public function deleteData(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array($this->pk_idtable =>$id,'usercrea'=>$this->getUserId());
            $dataD=array(
                'userdelete'=>$this->getUserId(),
                'fechadelete' =>$this->getfecha_actual(),
                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            echo json_encode('ok');

        }else{

        }
    }

    public function setIniRegInsOvi(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){

               $region =$post["region"] ;
               $provincia=$post["provincia"];
            $distrito=$post["distrito"];
            $localidad=$post["localidad"];
            $finstalacion=$post["finstalacion"];
            $inspector=$post["inspector"];
            $dtIns=array(
              "idtipoinspeccion"=>2 ,
              "idinspector"=>2 ,
              "estado"=>1 ,
              "region"=>$region,
              "provincia"=>$provincia,
              "distrito"=>$distrito,
              "localidad"=>$localidad,
              "fechainstalacion"=>$finstalacion,
              "fechainspeccionovi"=>2
            );
            $this->db->trans_start();
            $this->db->insert("inspeccion",$dtIns);
            $idMax = $this->db->insert_id();
            $this->db->trans_complete();
            $return["status"]="ok";
            $return["id"]=$idMax;
        }else{
            $return["status"]="error";
        }

        echo json_encode($return);
    }


    public function getDataDepProvbyDistri(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getDepartamentoProvinciaByDistrito($search);
        echo json_encode($r);
    }

    public function getRedSalud(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $return=$this->ubigeo_model->getRedSalud($post["region"]);
        }else{
            $return["status"]="error";
        }

        echo json_encode($return);
    }

    public function getEstablecimientoSalud(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $coddisa=$post["coddisa"];
            $codred=$post["codred"];
            $iddistrito=$post["iddistrito"];
            $return=$this->ubigeo_model->getEstablecimientoSalud($coddisa,$codred,$iddistrito);
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }

    public function setComienzaInspeccion(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){

            $selRedSalud= $post["selRedSalud"];
            $selJefeBrigada= $post["selJefeBrigada"];
            $provincia= $post["provincia"];
            $selEstablecimientoSalud=   $post["selEstablecimientoSalud"];
            $selInspector= $post["selInspector"];
            $distrito= $post["distrito"];
            $iddistrito=   $post["iddistrito"];
            $idubigeo= $post["idubigeo"];
            $sector= $post["sector"];
            $selTipoVigilancia= $post["selTipoVigilancia"];
            $localidad= $post["localidad"];
            $fintervencion= $post["fintervencion"];
            $selControl= $post["selControl"];
            $ddd=explode("-",$selRedSalud);
            $iddisa=$ddd[0];
            $idred=$ddd[1];
            $dt=array(
                  "idtipoinspeccion"=>1,
                  "idjefebrigada"=>$selJefeBrigada ,
                  "idinspector"=>$selInspector ,
                  "idestablecimientosalud"=>$selEstablecimientoSalud ,
                  "sector"=>$sector,
                  "fechaintervencion"=>$fintervencion,
                  "tipoactividad"=>$selTipoVigilancia ,
                  "nrocontrol"=>$selControl ,
                  "fechareg"=>$this->getfecha_actual() ,
                  "estado"=>1 ,
                  "localidad"=>$localidad ,
                  "idredsalud"=>$selRedSalud,
                  "iddisa"=>$iddisa,
                  "idred"=>$idred,
                  "idubigeo"=>$idubigeo
            );

            $this->db->trans_start();
            $this->db->insert("inspeccion",$dt);
            $idinspeccion = $this->db->insert_id();
            $this->db->trans_complete();
            $return["id"]=$idinspeccion;
            $return["status"]="ok";
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }


    public function getViviendas(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $iddistri=$post["iddistri"];
            $term=$post["term"];
            $return=$this->ubigeo_model->getViviendasByDistritoSector($iddistri,$term);
        }else{
            $return["status"]="errror";
        }

        echo json_encode($return);
    }

    public function setDetailInspeccion(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
           // $this->showErrors($post);
            $idinspeccion=$post["idinspeccion"];
            $idvivienda=$post["idvivienda"];
            $selEstadosViviendaInsp=$post["selEstadosViviendaInsp"];
            $larvicida=isset($post["larvicida"])?$post["larvicida"]:null;
            $c1i=isset($post["c1i"])?$post["c1i"]:null;
            $c1p=isset($post["c1p"])?$post["c1p"]:null;
            $c1t=isset($post["c1t"])?$post["c1t"]:null;

            $c2i=isset($post["c2i"])?$post["c2i"]:null;
            $c2p=isset($post["c2p"])?$post["c2p"]:null;
            $c2t=isset($post["c2t"])?$post["c2t"]:null;

            $c3i=isset($post["c3i"])?$post["c3i"]:null;
            $c3p=isset($post["c3p"])?$post["c3p"]:null;
            $c3t=isset($post["c3t"])?$post["c3t"]:null;

            $c4i=isset($post["c4i"])?$post["c4i"]:null;
            $c4p=isset($post["c4p"])?$post["c4p"]:null;
            $c4t=isset($post["c4t"])?$post["c4t"]:null;

            $c5i=isset($post["c5i"])?$post["c5i"]:null;
            $c5p=isset($post["c5p"])?$post["c5p"]:null;
            $c5t=isset($post["c5t"])?$post["c5t"]:null;

            $c6i=isset($post["c6i"])?$post["c6i"]:null;
            $c6p=isset($post["c6p"])?$post["c6p"]:null;
            $c6t=isset($post["c6t"])?$post["c6t"]:null;

            $c7i=isset($post["c7i"])?$post["c7i"]:null;
            $c7p=isset($post["c7p"])?$post["c7p"]:null;
            $c7e=isset($post["c7e"])?$post["c7e"]:null;

            $c8i=isset($post["c8i"])?$post["c8i"]:null;
            $c8p=isset($post["c8p"])?$post["c8p"]:null;
            $c8t=isset($post["c8t"])?$post["c8t"]:null;



            $dataDetInspeccion=array(
              "idinspeccion"=>$idinspeccion,
              "idvivienda"=>$idvivienda,
              "idestadoinspeccion"=>$selEstadosViviendaInsp,
              "ic1"=>$c1i,
              "pc1"=>$c1p,
              "tc1"=>$c1t,
              "ic2"=>$c2i,
              "pc2"=>$c2p,
              "tc2"=>$c2t,
              "ic3"=>$c3i,
              "pc3"=>$c3p,
              "tc3"=>$c3t,
              "i4"=>$c4i,
              "p4"=>$c4p,
              "t4"=>$c4t,
              "i5"=>$c5i,
              "p5"=>$c5p,
              "t5"=>$c5t,
              "i6"=>$c6i,
              "p6"=>$c6p,
              "t6"=>$c6t,
              "i7"=>$c7i,
              "p7"=>$c7p,
              "e7"=>$c7e,
              "i8"=>$c8i,
              "p8"=>$c8p,
              "t8"=>$c8t,
              "larvicidadaplicada"=>$larvicida,
              "estado"=>1,
              "fechareg"=>$this->getfecha_actual(),
              "fechacrea"=>$this->getfecha_actual()
            );
            $this->db->insert("detinspeccion",$dataDetInspeccion);
            $return["status"]="ok";
           // $return=$this->ubigeo_model->getViviendasByDistritoSector($iddistri,$term);
        }else{
            $return["status"]="errror";
        }

        echo json_encode($return);
    }

    public function getDetailInspeccionesVivienda(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idinpseccion=$post["idinpseccion"];
            $r=$this->inspecciones_model->getDetailInspeccionesVivienda($idinpseccion);
            $return=$r;
        }else{
        }
        echo json_encode($return);
    }

    public function deleteDetailIns(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $id=$post["id"];
            $this->db->where(["iddetinspeccion"=>$id])->update('detinspeccion',["estado"=>0]);
            $return["status"]="ok";
        }else{

        }
        echo json_encode($return);
    }

   public function deleteInsp(){
       $return["status"]=null;
       $post=$this->input->post(null,true);
       if(sizeof($post)>0){
           $id=$post["id"];
           $this->db->where(["idinspeccion"=>$id])->update('inspeccion',["estado"=>0]);
           $return["status"]="ok";
       }else{

       }
       echo json_encode($return);
   }
    ////REPORTES
    public function vermapa(){
        $this->template->renderizar('inspecciones/reporteVivienda');
    }

    public function getPoints(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $query="SELECT
                    viviendas.idvivienda,
                    viviendas.lat,
                    viviendas.lng,
                    detinspeccion.ic1+detinspeccion.ic2 + detinspeccion.ic3 + detinspeccion.i4+detinspeccion.i5 +detinspeccion.i6 + detinspeccion.i7 + detinspeccion.i8 as ti,
                    detinspeccion.pc1 + detinspeccion.pc2 + detinspeccion.pc3 + detinspeccion.p4 + detinspeccion.p5 + detinspeccion.p6 + detinspeccion.p7 +detinspeccion.p8 as tp,
                    detinspeccion.tc1 + detinspeccion.tc2 + detinspeccion.tc3 +detinspeccion.t4 +detinspeccion.t5 +detinspeccion.t6 +detinspeccion.t8  as tt,
                    detinspeccion.e7 as te,
                    inspeccion.sector
                    FROM
                    detinspeccion
                    INNER JOIN viviendas ON viviendas.idvivienda = detinspeccion.idvivienda
                    INNER JOIN inspeccion ON inspeccion.idinspeccion = detinspeccion.idinspeccion
                    where detinspeccion.estado>0 and inspeccion.estado>0 and inspeccion.idtipoinspeccion=1 and detinspeccion.idestadoinspeccion=1
                    and year(inspeccion.fechaintervencion)=2019";
            $return=$this->db->query($query)->result_array();
        }
        echo json_encode($return);
    }

    public function setNewEmployee(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){

            $name=$post["nombreins"];
            $apellidos=$post["apellidosins"];
            $dni=$post["dniins"];
            $email=$post["emailins"];
            $tel=$post["telcelins"];
            $insert=0;
            $role=0;
            if($post["tipo"] == "jefebrigada"){
                $insert=1;
                $role=21;
            }elseif ($post["tipo"]=="inspector"){
                $insert=1;
                $role=20;
            }
            $drr=array(
                "role"=>$role,
                "name"=>$name,
                "lastnames"=>$apellidos,
                "nombrearearesponsable"=>$name." ".$apellidos,
                "telefono1"=>$tel,
                "dni"=>$dni,
                "direccion"=>"",
                "ciudad"=>"",
                "email"=>$email,
                "user"=>md5(time()),
                "password"=>time(),
                "status"=>1,
                "active"=>1
            );

            if($insert == 1){
                $this->db->trans_start();
                $this->db->insert("users",$drr);
                $idMax = $this->db->insert_id();
                $this->db->trans_complete();
                if($post["tipo"] == "jefebrigada"){
                    $ret=$this->inspecciones_model->getUsersByRole("Jefe Brigada");
                }else{
                    $ret=$this->inspecciones_model->getUsersByRole("Inspector");
                }

                $return["status"]="ok";
                $return["id"]="$idMax";
                $return["data"]=$ret;
            }else{
                $return["status"]="Cs";
            }

        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }


}