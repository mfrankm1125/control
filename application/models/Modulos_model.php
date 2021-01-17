<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modulos_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getModulosPadreHijos(){
        $consulta="SELECT\n".
            "h.id_modulo,\n".
            "h.nombre as modulo_hijo ,\n".
            "h.url ,\n".
            "h.estado ,\n".
            "h.is_active ,\n".
            "p.id_modulo as id_modulo_padre,\n".
            "p.nombre as modulo_padre,\n".
            "p.icono,\n".
            "p.orden,\n".
            "p.estado as estado_padre,\n".
            "p.is_active as activo_padre,\n".
            "p.id_modulo_padre\n".
            "FROM\n".
            "modulos as p inner join modulos as h on p.id_modulo = h.id_modulo_padre\n".
            "where p.estado = 1 and h.estado=1\n".
            "ORDER BY  h.id_modulo desc ";
        $modulos=$this->db->query($consulta)->result_array();
        $sel_mod_p=$this->db->get_where('modulos',['url'=>'#'])->result_array();
        return ["modulos"=>$modulos,"sel_mod_p"=>$sel_mod_p];
    }

    public function permModulosByIdModulo($id){
        $consulta="SELECT\n".
            "acciones.nombre,\n".
            "permissions.id_accion,\n".
            "modulos.url,\n".
            "permissions.estado,\n".
            "permissions.id_modulo,\n".
            "permissions.id\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where acciones.estado= 1 and permissions.estado=1 and modulos.estado=1 and modulos.is_active=1 \n".
            "and permissions.id_modulo= ".$id ;
        $r= $this->db->query($consulta)->result_array() ;
        return $r;
    }

    public function selPermisosByIdModulo($id){
        $consulta="SELECT\n".
            "permissions.id,\n".
            "permissions.titulo,\n".
            "permissions.`name`,\n".
            "permissions.id_modulo,\n".
            "permissions.estado\n".
            "FROM permissions\n".
            "where permissions.estado = 1 and  permissions.`name` not in(SELECT \n".
            "permissions.`name`\n".
            "FROM permissions where permissions.estado = 1 and  permissions.id_modulo=".$id."  )";
        $r=$this->db->query($consulta)->result_array();
        return $r;
    }

}