<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acciones extends CMS_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
    }

    public function index()
    {


        $this->grocery_crud->set_theme('flexigrid');
        //
        $this->grocery_crud->set_table('acciones');
        $this->grocery_crud->columns('nombre','descripcion');
        $this->grocery_crud->fields('nombre','descripcion');
        $this->grocery_crud->where('acciones.estado',1);

        //$this->grocery_crud->unset_columns('estado');

        //$this->grocery_crud->unset_edit();
        $output = $this->grocery_crud->render();

        /*
        $crud->set_theme('datatables');
        $crud->where('estado',1);
        $crud->set_table('acciones');
        $crud->columns('nombre','descripcion');
        $crud->unset_columns('estado');
         $crud->display_as('salesRepEmployeeNumber','from Employeer')
            ->display_as('customerName','Name')
            ->display_as('contactLastName','Last Name');
        $crud->set_subject('Customer');
        $crud->set_relation('salesRepEmployeeNumber','employees','lastName'); */
        /*
        $crud->unset_add();
        $crud->unset_edit(); */
        //$output = $crud->render();

        $this->template->set_data('data',$output);

        $this->template->renderizar('acciones/index');

    }




}