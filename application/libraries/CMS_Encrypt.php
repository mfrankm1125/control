<?php if(! defined('BASEPATH')) exit ('No direct script acces alolowed');
 

 class CMS_Encrypt extends CI_Encrypt {
     private $CI ;
     
     public function __construct() {
         parent::__construct();
         $this->CI= & get_instance();

     }
     
     
     public function password($data , $algo = "sha256"){
         
         if(! $this->CI->config->item("encryption_key")){
             
             show_error('Encryption Key not found');
             
         } 
         
         $hash = hash_init($algo, HASH_HMAC ,$this->CI->config->item("encryption_key"));
         hash_update($hash, $data);
         return hash_final($hash);
         }

     public function encrypt_decrypt($action, $string) {
         /*$output = false; solo para pruebas colocar en caso de proceso real
         $encrypt_method = "AES-256-CBC";
         $secret_key = 'This is my secret key';
         $secret_iv = 'This is my secret iv';
         // hash
         $key = hash('sha256', $secret_key);

         // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
         $iv = substr(hash('sha256', $secret_iv), 0, 16);

         if( $action == 'encrypt' ) {
             $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
             $output = base64_encode($output);
         }
         else if( $action == 'decrypt' ){
             $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
         }
            */
         $output=$string;
         return $output;
     }
     
     
 }


