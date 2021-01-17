<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class  Users extends CMS_controller{

   public function login(){
       
        /*$this->load->library('encrypt');
       echo  $this->encrypt->password('1234');
       exit();*/

       if($this->input->post('login')== 1){
           $this->load->library('form_validation');
           $rules = [
               ['field' => 'user',
                 'Label' => 'lang:cms_general_label_user',
                 'rules' => 'trim|required|alpha_dash|max_length[20]'
               ],
               ['field' => 'password',
                   'Label' => 'lang:cms_general_label_password',
                   'rules' => 'required|max_length[20]'
               ]
           ];
           $this->form_validation->set_message('required','Este campo es necesario');
            $this->form_validation->set_rules($rules);
           if($this->form_validation->run() == TRUE){

               if($this->user->login($this->input->post('user'),$this->limpiaEspacios($this->input->post('password'))) === TRUE){

                    $this->session->set_userdata('user_id',$this->user->id);


                   redirect('admin');
               }

               $this->template->add_message(['error'=>$this->user->errors()]);
           }
       }
       $this->load->helper('form');
       $this->template->renderizar_web('users/login');
   }


    public function logout(){
        if($this->user->is_logged_in()){
                $this->session->sess_destroy();
        }

        redirect();

    }



}