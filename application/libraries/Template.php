<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template{
    protected $CI;
    private $configs;
    private $data;
    private $js;
    private $css;
    private $table;
    private $id;
    private $name;
    private $default_id;
    private $default_name;
    private $message;
    private $panel;
    private $assetJS="xxx";

    public function __construct(){
        $this->CI= & get_instance();//permite acceder a todas las funciones del framkeor
        $this->CI->load->config('templates');
        $this->configs=$this->CI->config->item('templates');

        $this->table='templates';
        $this->data=[];
        $this->js=[];
        $this->css=[];
        $this->id=null;
        $this->default_id=null;
        $this->name=null;
        $this->default_name=null;
        $this->message=[];
        $this->panel=$this->CI->admin_panel() ? 'b' : 'f';// si es verdadero b sino f y se sabra si se trabaja en Backend o front
        //print_r($this->panel);exit();
       // print_r($this->configs);exit();
    }

    public function set_data($key,$value){
        if(!empty($key)){
           $this->data[$key]=$value;
        }

    }

    public function add_js($type,$value,$charset=null ,$defer=null,$async=null){
        $this->_add_asset($type,$value,['charset'=>$charset,'defer'=>$defer,'async'=>$async],'script');
    }

    public function add_js_footer($type,$value,$charset=null ,$defer=null,$async=null){
        $this->_add_asset($type,$value,['charset'=>$charset,'defer'=>$defer,'async'=>$async],'script');
    }

    public function add_css($type,$value,$media=null){
        $this->_add_asset($type,$value,['media'=>$media],'style');
    }

    public function add_message($message,$type = null){
        $this->_add_message($message,$type);
    }

    public function set_flash_message(array $message){
         if(sizeof($message) >0){
             $this->CI->session->set_flashdata('_message_',$message);
         }
    }

    public  function renderizar($view=null,$isHtml=False){
        $template='templates/admin/default/template.php';
        $routes=[];

        //echo "<pre>";print_r($this->css);print_r($this->js);

        if(! empty($view)){
            if(! is_array($view)){
                $view=[$view];
            }
            foreach ($view as $file){

                if( $this->CI->session->userdata('user_id')  <= 0  ){
                    redirect("users/login");
                }
                $route='admin/';


                $route.=$this->name.'/html/'.str_replace('admin/','',$file);

                $varAPPATH=APPPATH."views/templates/{$route}.php";
                $varAPPATH_default=APPPATH."views/{$file}.php";
                if(file_exists($varAPPATH)){
                    $routes[]=$varAPPATH;
                }elseif (file_exists($varAPPATH_default)){
                    $routes[]=$varAPPATH_default;

                }else{
                    show_error('view error');
                }
            }
        }
        $this->_set_assets();
        $this->_set_messages();
       // $this->data['_admin_panel_uri']=$this->CI->admin_panel_uri();

        $modulos_padre=$this->_c_modulo_padre();
        $modulos_hijo=$this->_c_modulos_hijos();

        $this->data['_menu_padre']=$modulos_padre;
        $this->data['_menu_hijo']=$modulos_hijo;
        $this->data['_content']=$routes;
        $isHtmlx=$isHtml;
        
        $this->CI->load->view($template,$this->data,$isHtmlx);

    }



    public  function renderizar_web($view=null){
        $template='templates/default/template.php';
        //$template=$this->_route();
        $routes=[];
       // print_r($template);
        //echo "<pre>";print_r($this->css);print_r($this->js);

        if(! empty($view)){
            if(! is_array($view)){
                $view=[$view];
            }
            foreach ($view as $file){
                $route='';
                $route.=$this->name.'/html/'.str_replace('admin/','',$file);
                $varAPPATH=APPPATH."views/templates/{$route}.php";
                $varAPPATH_default=APPPATH."views/{$file}.php";
                if(file_exists($varAPPATH)){
                    $routes[]=$varAPPATH;
                }elseif (file_exists($varAPPATH_default)){
                    $routes[]=$varAPPATH_default;

                }else{
                    show_error('view error');
                }
            }
        }
        $this->_set_assets();
        $this->_set_messages();
        // $this->data['_admin_panel_uri']=$this->CI->admin_panel_uri();
        /*$modulos_padre=$this->CI->db->get_where('modulos',['estado'=>'1' ,'url'=>'#'])->result_array();
        $modulos_hijo=$this->CI->db->get_where('modulos',['estado'=>'1' ,'url !='=>'#'])->result_array();
        $modulos_data=$this->CI->db->query("SELECT\n".
            "permissions.id_modulo,\n".
            "role_permission.value,\n".
            "permissions.estado\n".
            "FROM\n".
            "permissions\n".
            "INNER JOIN role_permission ON role_permission.permission = permissions.id\n".
            "INNER JOIN roles ON role_permission.role = roles.id \n".
            "where roles.id = 1 and role_permission.value =1 \n".
            "Union\n".
            "SELECT \n".
            "permissions.id_modulo,\n".
            "user_permission.value ,\n".
            "permissions.estado\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "where user_permission.user = 1 and user_permission.value =1  " )->result_array();
           $this->data['_menu_padre']=$modulos_padre;
        $this->data['_menu_hijo']=$modulos_hijo;
        $this->data['_menu_data']=$modulos_data;
        */

        // echo "<pre>" ;print_r($modulos_hijo); exit();

        //print_r($route);


        $this->data['_content']=$routes;
        $this->CI->load->view($template,$this->data);

    }



    private function _route(){ // devuelve ruta de templates
        $route='templates/';
        
        if(empty($this->name)){
            $template=$this->CI->db->select(['id','nombre'])->get_where($this->table,['panel'=>$this->panel,'default'=>1])->row();

            if(sizeof($template)== 0 || empty($template->nombre)){
                show_error('Template error');
            }
                $this->name=$template->nombre;//$template->name viene desde la base ded atos como objeto
        }
        $route.=$this->panel == 'b' ? 'admin/' : '';
        $route.="{$this->name}/template.php";
        //print_r($route) ;
        if(!file_exists(APPPATH."views/{$route}")){
            show_error("No template");
        }

        //print_r($route) ;
        return $route;
    }



    private function _add_asset($type,$value,$options=array(),$asset_type){

        if( ! empty($type)){
            $asset=[];
            if(is_array($value)){
                foreach ($value as $val){
                    $asset[]=['tipo'=>$type,'value'=>$val,'options'=>$options ];
                }
            }else{
                $asset[]=['tipo'=>$type,'value'=>$value,'options'=>$options ];
            }
        }


        if($asset_type == 'script'){
            $this->js=array_merge($this->js,$asset);
        }elseif ($asset_type =='style'){
            $this->css=array_merge($this->css,$asset);


        }

    }

    private function _set_assets(){

        if(! empty($this->name)){
            $panel=$this->panel == 'b' ? 'admin' : 'front';

            $varConfigs=$this->configs[$panel][$this->name]['scripts'];
            $varConfigs2=$this->configs[$panel][$this->name]['styles'];

            if(isset($varConfigs)  && sizeof($varConfigs) >0 ){
                $scripts=$this->js;
                $this->js=[];

                foreach ($varConfigs as $script){
                    $this->_add_asset($script['tipo'],$script['value'],isset($script['options']) ? $script['options']:[],'script');
                }
                $this->js=array_merge($this->js,$scripts);
            }
            


            if( isset($this->configs[$panel][$this->name]['styles'])  && sizeof($this->configs[$panel][$this->name]['styles']) > 0 ){
                $styles=$this->css;
                $this->css=[];

                foreach ($this->configs[$panel][$this->name]['styles'] as $style){
                    $this->_add_asset($style['tipo'], $style['value'], isset($style['options']) ? $style['options'] : [], 'style');
                }

                $this->css=array_merge($this->css,$styles);
            }
        }
       // echo '<pre>';print_r($this->css);
        $_css=$_js= '';
        $panel=$this->panel == 'b' ? 'admin/' : '';

        if(sizeof($this->js ) > 0){
            foreach ( $this->js as $js){

                $defer=$async=$charset = '';

                if(isset ($js['options'])){
                    $defer =isset($js['options']['defer']) ? 'defer' : '' ;
                    $async =isset($js['options']['async']) ? 'async' : '' ;
                    $charset =isset($js['options']['charset']) ? 'charset="'.$js['options']['charset'].'"' : '' ;

                }

                $src =base_url().'assets/scripts/';

                switch ($js['tipo']){

                    case 'base':
                        $src .= $js['value'] . '.js';
                        break;

                    case 'template':
                        $src.='templates/'.$panel.$this->name.'/'.$js['value'].'.js';
                        break;

                    case 'vista':
                        $src.= 'views/'.$js['value'] . '.js';
                        break;

                    case 'view':
                        $src= base_url(). 'assets/scripts/views/'.$this->CI->uri->segment(1) .'/'.$js['value'] . '.js';
                        break;

                    case 'url':
                        $src = $js['value'];
                        break;

                    case 'url_theme':
                        $src =base_url().'assets/theme/'.$js['value'].'.js';
                        break;

                    default:
                        $src='';

                }

                $_js.=sprintf('<script type="text/javascript" src="%s" %s %s %s ></script>',$src,$charset,$defer,$async);
            }
            
        }
      //  echo "<pre>";print_r($this->css) ;
        if(sizeof($this->css) > 0){

            foreach ($this->css as $css){

                $media ='';

                if(isset ($css['options'])){

                    $media =isset($css['options']['media']) ? 'media="'.$css['options']['media'].'"' : '' ;

                }

                $href = base_url().'assets/styles/';

                switch ($css['tipo']){
                    case 'base':
                        $href .=$css['value'] . '.css';
                        break;

                    case 'template':
                        $href.='templates/'.$panel.$this->name.'/'.$css['value'].'.css';
                        break;

                    case 'view':
                        $href.= 'views/'.$css['value'] . '.css';
                        break;

                    case 'url':
                        $href = $css['value'];
                        break;
                    case 'url_theme':
                        $href = base_url().'assets/theme/'.$css['value'].'.css';
                        break;

                    default:
                        $href='';

                }

                $_css.=sprintf('<link type="text/css" rel="stylesheet"  href="%s" %s >',$href,$media);
            }
        }

        $this->assetJS=$_js;

        $this->data['_js']=$_js;
        $this->data['_js_footer']="hola";
        $this->data['_css']=$_css;

    }



    public function getAssetsJS(){
        $this->_set_assets();
        return $this->assetJS;
    }

    private function _add_message($message,$type=null){

        if(! empty($message)){
            $types=['warning','success','error','info'];
            $check_type=function($_type) use ($types){
                return (empty($_type) || ! in_array($_type,$types)) ? 'warning' : $_type ;
            };
            // tipo  de mmensaje  ['error'=>[''] ['']]
            if(is_array($message)){
                foreach ($message as $type=>$msg){

                        if( ! empty($msg)){

                            $type=$check_type($type);

                            if(is_array($msg)){
                                foreach ($msg as $_msg){
                                    if(! empty($_msg) ){
                                        $this->message[$type][]=(string) $_msg;
                                    }
                                }
                            }else{
                                $this->message[$type][]=(string) $msg;
                            }

                        }
                }
            }else{
                $type=$check_type($type);
                $this->message[$type][]=(string) $message;
            }
        }

    }

    private function _set_messages(){
        $this->add_message($this->CI->session->flashdata('_message_'));
        
        $this->data['_warning']=isset($this->message['warning'])?  $this->message['warning'] : [];
        $this->data['_success']=isset($this->message['success'])?  $this->message['success'] : [];
        $this->data['_error']=isset($this->message['error'])?  $this->message['error'] : [];
        $this->data['_info']=isset($this->message['info'])?  $this->message['info'] : [];
    }

    private function _c_modulos_hijos(){
        // devuelve datos de modulos hijos con permisos
        $sql="Select * from (SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.id=".(int)$this->CI->session->userdata('user_id')." and users.`status`=1 and permissions.estado=1 and user_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.icono,\n".
            "users.`name`,\n".
            "modulos.id_modulo \n".
            "union ALL\n".
            "SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "where roles.id=".(int)$this->CI->session->userdata('id_role')." and roles.estado=1 and permissions.estado=1 and role_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre) as modulos_d  \n".
            "GROUP BY  modulos_d.nombre,\n".
            " modulos_d.url,\n".
            "modulos_d.orden,\n".
            "modulos_d.icono,\n".
            "modulos_d.id_modulo,\n".
            "modulos_d.id_modulo_padre ORDER BY modulos_d.nombre";

        $r=$this->CI->db->query($sql)->result_array();
        return $r;
    }

    private function _c_modulo_padre(){

        $sql="select modulos.id_modulo,modulos.nombre,modulos.url from (Select * from (SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "user_permission\n".
            "INNER JOIN users ON user_permission.`user` = users.id\n".
            "INNER JOIN permissions ON user_permission.permission = permissions.id\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "where users.id=".(int)$this->CI->session->userdata('user_id')." and users.`status`=1 and permissions.estado=1 and user_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.icono,\n".
            "users.`name`,\n".
            "modulos.id_modulo \n".
            "union ALL\n".
            "SELECT\n".
            "modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre\n".
            "FROM\n".
            "role_permission\n".
            "INNER JOIN permissions ON role_permission.permission = permissions.id\n".
            "INNER JOIN acciones ON permissions.id_accion = acciones.id_accion\n".
            "INNER JOIN modulos ON permissions.id_modulo = modulos.id_modulo\n".
            "INNER JOIN roles ON role_permission.role = roles.id\n".
            "where roles.id=".(int)$this->CI->session->userdata('id_role')." and roles.estado=1 and permissions.estado=1 and role_permission.`value`=1\n".
            "and modulos.estado=1 and acciones.estado=1\n".
            "GROUP BY modulos.nombre,\n".
            "modulos.url,\n".
            "modulos.orden,\n".
            "modulos.icono,\n".
            "modulos.id_modulo,\n".
            "modulos.id_modulo_padre) as modulos_d  \n".
            "GROUP BY  modulos_d.nombre,\n".
            " modulos_d.url,\n".
            "modulos_d.orden,\n".
            "modulos_d.icono,\n".
            "modulos_d.id_modulo,\n".
            "modulos_d.id_modulo_padre ORDER BY modulos_d.nombre ) as modhijo \n".
            "inner join modulos on modhijo.id_modulo_padre=modulos.id_modulo\n".
            "where modulos.estado=1 \n".
            "GROUP BY modulos.id_modulo,modulos.nombre,modulos.url\n".
            "ORDER BY modulos.nombre ";
        $r=$this->CI->db->query($sql)->result_array();

        return $r;


    }
    
}