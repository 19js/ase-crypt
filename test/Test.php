<?php
namespace SheZhiShe\test;
use SheZhiShe\AesCrypt;

require_once __DIR__.'/../vendor/autoload.php';
$ase=new AesCrypt();
//加密
$res=$ase->encode('d12347477');
var_dump($res->getRes());
//解密
$dres=$ase->key($res->getKey())->iv($res->getIv())->decode($res->getRes());
var_dump($dres->getRes());