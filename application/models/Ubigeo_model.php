<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubigeo_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getUbigeoByDistrito($idubigeo=null,$seach=null){
        $w="";
        if($idubigeo){
            $w=" where  UBIGEO ='$idubigeo' ";
        }
        if($seach){
            $w=" where concat(ubigeo.DESC_DPTO,' ',ubigeo.DESC_PROV,' ',ubigeo.DESC_DIST ) like '%$seach%' ";
        }
    $query="SELECT
    concat(ubigeo.DESC_DPTO,'-',ubigeo.DESC_PROV,'-',ubigeo.DESC_DIST ) as label ,
    concat( ubigeo.DESC_DIST ) as value ,
    ubigeo.UBIGEO
    FROM
    ubigeo $w   ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }

    public function getUbigeoByDPD($dep,$prov,$dis){
        $query="SELECT
        ubigeo.DESC_DPTO,
        ubigeo.DESC_PROV,
        ubigeo.DESC_DIST,
        ubigeo.UBIGEO
        FROM
        ubigeo
        where ubigeo.DESC_DPTO  like '%$dep%' and ubigeo.DESC_PROV  like '%$prov%' and ubigeo.DESC_DIST  like '%$dis%' ";
        $res=$this->db->query($query)->result_array();
        return $res;
    }


}