<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semillas extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="";
    public  $htact="";
    public $costeRec=0;
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


       $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');

        $this->template->add_js('base', 'underscore');
        $this->template->set_data("dataCultivo",$this->getCultivo());
        $this->template->set_data("dataCategoria",$this->getCategoriaCultivo());
        $this->template->set_data("dataLocalidad",$this->getLocalidad());
        $this->template->renderizar('semillas/indexsemilla');
    }

    public function getCultivo(){
        $this->db->select('*');
        $this->db->from("cultivo");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('idcultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getCategoriaCultivo(){
        $this->db->select('*');
        $this->db->from("categoriacultivo");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('idcategoriacultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getLocalidad(){
        $this->db->select('*');
        $this->db->from("localidad");
        $this->db->where(["estado"=>1]);
     //   $this->db->order_by('idcategoriacultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getFactoresAdversos(){
        $this->db->select('*');
        $this->db->from("factoresadversos");
        $this->db->where(["estado"=>1]);
        //   $this->db->order_by('idcategoriacultivo', 'asc');
        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }

    public function getVariedadCultivo(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        //print_r($post);exit();
        if(sizeof($post) > 0){
            $idcultivo=(int)$post["idcultivo"];
            $this->db->select('*');
            $this->db->from("variedadcultivo");
            $this->db->where(["estado"=>1,"idcultivo"=>$idcultivo]);
            $this->db->order_by('idvariedadcultivo', 'asc');
            $return = $this->db->get()->result_array();
        }else{
            $return['status']="Error post";

        }
        echo json_encode($return);
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
        $campoEditaID="plancampania.idplancampania";
        $idd=(int)$id;
        $condicion=array(
            'plancampania.estado'=>1

        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('plancampania.*,cultivo.nombre as cultivo,categoriacultivo.nombre as categoriacultivo,variedadcultivo.nombre as variedadcultivo ,localidad.nombre as localidad  ');
        $this->db->from("plancampania");
        $this->db->join('cultivo', 'plancampania.idcultivo = cultivo.idcultivo',"inner");
        $this->db->join('categoriacultivo', 'plancampania.idcategoriacultivo = categoriacultivo.idcategoriacultivo',"inner");
        $this->db->join('variedadcultivo', 'plancampania.idvariedadcultivo = variedadcultivo.idvariedadcultivo',"inner");
        $this->db->join('localidad', 'plancampania.idlocalidad = localidad.idlocalidad',"inner");
        $this->db->where($condicion);
        $this->db->order_by('plancampania.idplancampania', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $nombre=$this->input->post('name',true);
            $apellidos=$this->input->post('lastname',true);
            $direccion=$this->input->post('address',true);
            $telefono=$this->input->post('phone',true);
            $doc=$this->input->post('doc',true);
            //$desc=$this->input->post('desc',true);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre ,
                'apellidos'=>$apellidos ,
                'direccion' =>$direccion ,
                'telefono'=> $telefono,
                'documento'=>$doc,
                'descripcion'=>null,
                'ruc'=>null,
                'razonsocial'=>null
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

                $condicion=array('id_cliente'=>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $data);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }

    public function deleteData(){
        $return["status"]="failx";
        if(sizeof($this->input->post()) > 0){
            $id=(int)$this->input->post('id',true);
            $condicion=array('idplancampania'=>$id);
            $dataD=array(
                'estado'=>0
            );

            $this->db->where($condicion)->update("plancampania", $dataD);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }


    ///--------------------

    public function getDataActividadCostePlantilla(){
        $this->db->select('*');
        $this->db->from("actividadcoste");
        $this->db->where(["estado"=>1,"ispadre"=>1]);
        $this->db->order_by('orden', 'DESC');
        $result = $this->db->get()->result_array();
        $dataHtml="";
        $dataHtml.="<table class='table table-condensed table-bordered'>";
        $dataHtml.="<tr><td>Actividad</td><td>Unidad Medida</td><td>Cantidad</td><td>Costo Unitario</td><td>Costo Total</td> <td>Observaciones</td> </tr>";
        if(sizeof($result)>0){
            $nivel=1;
            $dataHtml.=$this->recursivoActividadCoste($result,$nivel);
          //  $dt=$this->recursivoActividadCoste($result,$nivel);
           // $dataHtml.=$dt["ht"];
        }else{
            $dataHtml.="Sin data";
        }
        $dataHtml.="</table>";
        echo $dataHtml;
    }

    public function recursivoActividadCosteX($result,$nivel){
        $htx="";
        $monto=0;
        if(sizeof($result)>0){

            foreach ($result as $k=>$v){
                $this->db->select('*');
                $this->db->from("actividadcoste");
                $this->db->where(["estado"=>1,"idactividadcostepadre"=>$v["idactividadcoste"]]);
                $this->db->order_by('orden', 'DESC');
                $dt = $this->db->get()->result_array();
                if(sizeof($dt)>0){
                    $nivel++;
                    $this->costeRec=0;
                    $htm=$this->recursivoActividadCoste($dt,$nivel);

                    $this->costeRec= $this->costeRec+$htm["monto"];
                    $htx.="<tr id='".$v["idactividadcoste"]."' ><td>".($k+1)."-|".$v["nombre"]."</td> <td colspan='3'></td><td>".$this->costeRec."</td><td></td> </tr>";
                    $htx.=$htm["ht"];
                }else{

                    $sbtotal=$v["cantidad"]*$v["costounitario"];
                    $monto=$monto+$sbtotal;
                    $htx.="<tr id='".$v["idactividadcoste"]."' ><td>".$v["nombre"]."</td><td></td><td><input type='text'  ></td><td><input type='text'  ></td><td>".$sbtotal." </td> <td></td> </tr>";

                }

            }
        }


        return ["ht"=>$htx,"monto"=>$monto];
    }

    public function recursivoActividadCoste($result,$nivel){
        if(sizeof($result)>0){

            foreach ($result as $k=>$v){
                $this->db->select('*');
                $this->db->from("actividadcoste");
                $this->db->where(["estado"=>1,"idactividadcostepadre"=>$v["idactividadcoste"]]);
                $this->db->order_by('orden', 'asc');
                $dt = $this->db->get()->result_array();
                if(sizeof($dt)>0){
                    $costo=$this->recursivoCoste($dt);
                    $this->htact.="<tr id='".$v["idactividadcoste"]."' ><td>".($k+1)."-|".$v["nombre"]."</td> <td colspan='3'></td><td><input type='hidden' name='nivel".$v["ispadre"]."[]' value='".$costo."'  >".$costo."</td><td></td> </tr>";
                    $nivel++;
                    $this->recursivoActividadCoste($dt,$nivel);
                }else{
                    $nivel--;
                    $sbtotal=$v["cantidad"]*$v["costounitario"];
                    $this->htact.="<tr id='".$v["idactividadcoste"]."' >
                    <td>".$v["nombre"].
                        "<input type='hidden' name='id[]' value='".$v["idactividadcoste"]."' > <input type='hidden' name='idpadre[]' value='".$v["idactividadcostepadre"]."'  >   </td>
                    <td></td>
                    <td>
                    <input style='text-align: right' type='text' name='cantidad[]' onkeyup='calCosto(this)' value='".$v["cantidad"]."' ></td>
                    <td>
                    <input style='text-align: right' type='text' onkeyup='calCosto(this)'  name='costounitario[]' value='".$v["costounitario"]."'  >
                    </td>
                    <td>".$sbtotal." </td> 
                    <td></td> 
                    </tr>";

                }

            }
        }
        return $this->htact;
    }


    public function recursivoCoste($result){
        $monto=0;
        foreach ($result as $kk =>$vv){
            $this->db->select('*');
            $this->db->from("actividadcoste");
            $this->db->where(["estado"=>1,"idactividadcostepadre"=>$vv["idactividadcoste"]]);
            $this->db->order_by('orden', 'DESC');
            $hijos = $this->db->get()->result_array();

            if(sizeof($hijos)>0){
                 $monto=$monto+$this->recursivoCoste($hijos);
            }else{
              $monto=$monto+($vv["cantidad"]*$vv["costounitario"]);
             }
        }
        return $monto;
    }



    ////----------------



    public function setValCantCostoActCosto(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        //print_r($post);exit();
        if(sizeof($post) > 0){
            $cantidad=(float)$post["cantidad"];
            $costo=(float)$post["costo"];
            $idupd=$post["idupd"];
            $condicion=["idactividadcoste"=>$idupd ,"estado"=>1];
            $dataD=array(
                "cantidad"=>$cantidad,
                "costounitario"=>$costo
            );
            $this->db->where($condicion)->update("actividadcoste", $dataD);
            $return['status']="ok";
        }else{
            $return['status']="Error post";

        }
        echo json_encode($return);
      }

    public function setDataForm(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        //print_r($post);exit();
        if(sizeof($post) > 0){
           //print_r($post);print_r($_FILES);exit();
           // $idplancampania=$post["idplancampania"];
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];

            $idcultivo=$post["selCultivo"];
            $idcategoria=$post["selCategoria"];
            $idvariedad=$post["selVariedad"];
            $idlocalidad=$post["selLocalidad"];
            $area=$post["area"];
            $campania=$post["campania"];
            $fechasiembra=$post["fechasiembra"];
            $fechacosecha=$post["fechacosecha"];
            $plancostofile="";
            $cdirecto=$post["cdirecto"];
            $cindirecto=$post["cindirecto"];
            $rendimiento=$post["rendimiento"];
            $preciomin=$post["preciominimo"];
            $preciomax=$post["preciomax"];
            $fechareg=$this->getfecha_actual();

            $dataG=array(
               //"idplancampania"=>$idplancampania,
                "idcultivo"=>$idcultivo,
                "idcategoriacultivo"=>$idcategoria,
                "idvariedadcultivo"=>$idvariedad,
                "idlocalidad"=>$idlocalidad,
                "area"=>$area,
                "campania"=>$campania,
                "fechasiembra"=>$fechasiembra,
                "fechacosecha"=>$fechacosecha,
                "cdirecto"=>$cdirecto,
                "cindirecto"=>$cindirecto,
                "rendimiento"=>$rendimiento,
                "preciomin"=>$preciomin,
                "preciomax"=>$preciomax

            );

            if(!empty($_FILES) ){
                //echo "<pre>";print_r($_FILES);exit();
                $nameInput="costo";
                //echo "<pre>";print_r($fileplancosto);exit();
                $rt=$this->uploadFile($nameInput);

                if($rt["status"]=="ok"){
                    $dataG["plancostofile"]=$rt["namefile"];
                }

                $nameInput="ferti";

                $rtx=$this->uploadFile($nameInput);
                if($rt["status"]=="ok"){
                    $dataG["planferti"]=$rtx["namefile"];
                }


            }

            if($isEdit == 0 && $idEdit == 0){
                $dataG["fechareg"]=$fechareg;
                $dataG["estado"]=1;
                $this->db->insert("plancampania", $dataG);
                $return['status']="ok";
            }elseif ($isEdit == 1 && $idEdit > 0){
                $condicion=["idplancampania"=>$idEdit];
                $this->db->where($condicion)->update("plancampania", $dataG);
                $return['status']="ok";
            }
        }else{
            $return['status']="Error post";
        }
        echo json_encode($return);
    }


    //---------
    public function uploadFile($nameInput){
        $data=null;
        //print_r($FILES);exit();
        if (!empty($nameInput) ) {

            $_FILES['filex']['name'] = $_FILES['file']['name'][$nameInput];
            $_FILES['filex']['type'] = $_FILES['file']['type'][$nameInput];
            $_FILES['filex']['tmp_name'] = $_FILES['file']['tmp_name'][$nameInput];
            $_FILES['filex']['error'] = $_FILES['file']['error'][$nameInput];
            $_FILES['filex']['size'] = $_FILES['file']['size'][$nameInput];


            $config['upload_path'] = './assets/uploads/archivos';
            $config['allowed_types'] = '*';
            $config['max_size'] = 5000 * 1024;

            $this->load->library('upload', $config);
            $table="";
            if (!$this->upload->do_upload("filex")) {
                $data = array('status' => $this->upload->display_errors());

            } else {
                $data = $this->upload->data();
                $nameFile= htmlentities($data['file_name']);
                $data['status'] = "ok";
                $data['namefile'] = $nameFile;


            }
        } else {
            $data['status'] = "No cargo archivos";
        }
        //echo "<pre>";print_r( $data);
        return $data ;
    }



/*
    public function uploadFile_respaldo($FILES){
        $data=null;
        //print_r($FILES);exit();
        if (!empty($FILES) ) {

            $new_name = time() . $FILES["file"]['name'];
            $config['upload_path'] = './assets/uploads/archivos';
            $config['allowed_types'] = '*';
            $config['file_name'] = htmlentities($new_name);
            $config['max_size'] = 5000 * 1024;

            $this->load->library('upload', $config);
            $table="";
            if (!$this->upload->do_upload('file')) {
                $data = array('status' => $this->upload->display_errors());
            } else {
                $data = $this->upload->data();
                $nameFile= htmlentities($data['file_name']);
                $data['status'] = "ok";
                $data['namefile'] = $nameFile;
            }
        } else {
            $data['status'] = "No cargo archivos";
        }
        return $data ;
    }
*/

    public function setProduccionSemilla(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
       // echo"<pre>";print_r($post);exit();
        if(sizeof($post) > 0){
            $idplancampaniaRegProd=$post["idplancampaniaRegProd"];

            $areaejecutada=$post["areaejecutada"];
            $fechaSiembraEjec=$post["fechaSiembraEjec"];
            $fechaCosechaEjec=$post["fechaCosechaEjec"];
            $selLocalidadEjec=$post["selLocalidadEjec"];
            $costoTotalEjec=$post["costoTotalEjec"];
            $rendimientoEjec=$post["rendimientoEjec"];
            $pesoBrutoEjec=$post["pesoBrutoEjec"];
            $semillaEjec=$post["semillaEjec"];
            $consumoEjec=$post["consumoEjec"];
            $descarteEjec=$post["descarteEjec"];
            $mermaEjec=$post["mermaEjec"];
            $observacion=$post["observacion"];

            $factoradverso=(isset($post["factor"]))?$post["factor"]:null;
            if(sizeof($factoradverso)>0){
                $condicion=["idplancampania"=>$idplancampaniaRegProd,"estado"=>1];
                $this->db->where($condicion)->update("detplancampaniaxfactoradverso",["estado"=>0]);
                $dtFactor=null;
                foreach($factoradverso as $i){
                    $dtFactor[]=array(
                        "idplancampania"=>$idplancampaniaRegProd,
                        "idfactoresadversos"=>$i,
                        "estado"=>1
                    );
                }
                $this->db->insert_batch("detplancampaniaxfactoradverso",$dtFactor);
            }



            $dataG=array(
                "pesosemilla"=>$semillaEjec,
                "pesoconsumo"=>$consumoEjec,
                "pesodescarte"=>$descarteEjec,
                "pesomerma"=>$mermaEjec,
                "areaejecutada"=>$areaejecutada,
                "fechasiembraejecutada"=>$fechaSiembraEjec,
                "fechacosechaejecutada"=>$fechaCosechaEjec,
                "rendimientoejecutado"=>$rendimientoEjec,
                "observacionejecutado"=>$observacion,
                "idlocalidadeject"=>$selLocalidadEjec
            );

            $condicion=["idplancampania"=>$idplancampaniaRegProd];
            $this->db->where($condicion)->update("plancampania", $dataG);
            $return["status"]="ok";
           // print_r($factoradverso);
        }

        echo json_encode($return);
    }


    // Factor

    public function setNewFactorAdversoProduccion(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        //print_r($post);exit();
        if(sizeof($post) > 0){
        $data['nombre']=$post["factorAdverso"];
        $data['estado']=1;

        $this->db->insert("factoresadversos", $data);
            $return["status"]="ok";
        }else{
            $return["status"]="Error post";
        }

        echo json_encode($return);
    }

    public function getFactorAdversoByPlanCampania(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idplancampania=(int)$post["idplancampaniaRegProd"];
            $this->db->select('detplancampaniaxfactoradverso.*');
            $this->db->from("detplancampaniaxfactoradverso");
            $this->db->join('factoresadversos', 'detplancampaniaxfactoradverso.idfactoresadversos = factoresadversos.idfactoresadversos',"inner");
            $this->db->where(["detplancampaniaxfactoradverso.estado"=>1,"detplancampaniaxfactoradverso.idplancampania"=>$idplancampania]);
            //$this->db->order_by('plancampania.idplancampania', 'DESC');
            $return = $this->db->get()->result_array();
        }else{
            $return["status"]="Error post";
        }
        echo json_encode($return);
    }


}

