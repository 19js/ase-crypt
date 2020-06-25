AES加密解密
===========
# 环境要求
1. php>=5.3
2. openssl扩展
# 测试
1. composer install
2. test/Test.php 简单测试demo
3. php test/Test.php
# 开始使用
``
composr require
``
+ 简单使用
````
use SheZhiShe\AesCrypt;
require_once __DIR__.'/../vendor/autoload.php';
$ase=new AesCrypt();
//加密
$res=$ase->encode('d12347477');
var_dump($res->getRes());
//解密
$dres=$ase->key($res->getKey())->iv($res->getIv())->decode($res->getRes());
var_dump($dres->getRes());
````
# LICENSE 
[MIT]('./LICENSE')
