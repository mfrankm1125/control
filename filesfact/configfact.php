<?php

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;


$see = new See();
$see->setService(SunatEndpoints::FE_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/extra/resources/cert.pem'));
$see->setCredentials('10709295760ROBINFAC', 'robinFact1_');


return $see;