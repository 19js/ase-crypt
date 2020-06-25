<?php
/**
 * Copyright (c) 2020 The 19js. All rights reserved.
 * @Author SheZhiShe
 * @Email  1069447298@qq.com
 * @Date  2020/6/25 下午12:10
 */
namespace SheZhiShe;
/**
 * [0] => AES-128-CBC
 * [1] => AES-128-CFB
 * [2] => AES-128-CFB1
 * [3] => AES-128-CFB8
 * [5] => AES-128-OFB
 * [6] => AES-192-CBC
 * [7] => AES-192-CFB
 * [8] => AES-192-CFB1
 * [9] => AES-192-CFB8
 * [11] => AES-192-OFB
 * [12] => AES-256-CBC
 * [13] => AES-256-CFB
 * [14] => AES-256-CFB1
 * [15] => AES-256-CFB8
 * [17] => AES-256-OFB
 * Class AesCrypt
 * @package SheZhiShe
 */
class AesCrypt
{
    /**
     * @var $key
     */
    protected $key;
    protected $iv;
    protected $method='AES-128-CBC';
    protected $res='';
    protected $base64=true;
    protected $base64Decode=true;
    protected $options=0;
    public function __construct($method=false,$key=false,$iv=false)
    {
        if($method){
            $this->method($method);
        }
        if($key){
            $this->key=$key;
        }
        if($iv){
            $this->iv=$iv;
        }
    }

    /**
     * 加密解密key
     * @param string $key
     * @return $this
     */
    public function key($key=''){
        if(strlen($key)>0){
            $this->key=$key;
        }else{
            $this->key=$this->createKey();
        }
        return $this;
    }

    /**
     * 设置向量
     * @param string $iv
     * @return $this
     */
    public function iv($iv=''){
        if(strlen($iv)>0){
            $this->iv=$iv;
        }else{
            $length=openssl_cipher_iv_length($this->method);
            $this->iv=openssl_random_pseudo_bytes($length);
        }
        return $this;
    }
    public function options($options=0){
        $this->options=$options;
        return $this;
    }
    /**
     * 获取iv
     * @return mixed
     */
    public function getIv()
    {
        return $this->iv;
    }
    public function getKey(){
        return $this->key;
    }
    /**
     * @param string $method
     * @return $this
     */
    public function method($method){
        $this->method=$method;
        return $this;
    }
    public function getMethod(){
        return $this->method;
    }

    /**
     * 生成key
     * @return string
     */
    public function createKey(){
        $key=md5(rand(0,99999999).'_'.time().'_SheZhiShe_aes');
        return $key;
    }
    public function encode($data){
        if(strlen($this->key)===0){
            $this->key();
        }
        if(strlen($this->iv)===0){
            $this->iv();
        }
        $res_iv=$this->checkIV();
        if($res_iv !== true){
            throw new \Exception('iv and method is not match');
        }
        if(is_array($data)){
            $data=json_encode($data);
        }
        if(!is_string($data)){
            throw new \Exception('The data is an array or string');
        }
        $res=openssl_encrypt($data,$this->method,$this->key,$this->options,$this->iv);
        if($this->base64){
           $res=base64_encode($res);
        }
        $this->res=$res;
        return $this;
    }

    /**
     * @return string
     */
    public function getRes()
    {
        return $this->res;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function checkIV(){
        $length=openssl_cipher_iv_length($this->method);
        if($length !== strlen($this->iv)){
            throw new \Exception('iv is wrong:'.$this->method.' need the length of iv is '.$length);
        }
        return true;
    }

    /**
     * 最后是否base64加密
     * @param bool $bool
     * @return $this
     */
    public function base64Encode($bool=true){
        $this->base64=$bool;
        return $this;
    }

    /**
     * 解密开始是否需要base64解密
     * @param bool $bool
     * @return $this
     */
    public function base64Decode($bool=true){
        $this->base64Decode=$bool;
        return $this;
    }
    public function decode($data){
        if(strlen($this->key) === 0){
           throw new \Exception('参数错误：请传入key值');
        }
        if(strlen($this->iv) === 0){
            throw new \Exception('参数错误：iv向量值为空');
        }
        $res_check=$this->checkIV();
        if($res_check !== true){
            throw new \Exception('参数错误：向量iv与算法method不匹配,当前method:'
                .$this->method.",iv:".$this->iv);
        }
        if($this->base64Decode){
            $data=base64_decode($data);
        }
        $this->res=openssl_decrypt($data,$this->method,$this->key,$this->options,$this->iv);
        return $this;
    }
}