<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['templates']['front']['default']=[
    'regions'=>['header','main_menu','sidebar','footer'],
    'scripts'=>[
        //['tipo'=>'base' ,'value'=>'template_script1','options'=>['charset'=>'utf8','defer'=>TRUE,'async'=>TRUE]]
        ['tipo'=>'base' ,'value'=>'bootstrap/bootstrap'],
        ['tipo'=>'base' ,'value'=>'bootstrap/ie10-viewport-bug-workaround'],
        ['tipo'=>'base' ,'value'=>'bootstrap/ie-emulation-modes-warning']
    ],
    'styles'=>[
        //['tipo'=>'base' ,'value'=>'template_CSS1','options'=>['media'=>'screen'] ]
        ['tipo'=>'base' ,'value'=>'bootstrap/css/bootstrap'],
        ['tipo'=>'base' ,'value'=>'bootstrap/css/navbar-static-top'],
        ['tipo'=>'base' ,'value'=>'bootstrap/css/ie10-viewport-bug-workaround']

    ]
];
$config['templates']['admin']['default']=[
    'regions'=>['header','main_menu','sidebar','footer'],
    'scripts'=>[
        ['tipo'=>'base' ,'value'=>'funciones']

    ],
    'styles'=>[
        //['tipo'=>'base' ,'value'=>'template_CSS1','options'=>['media'=>'screen'] ]
        

    ]
];