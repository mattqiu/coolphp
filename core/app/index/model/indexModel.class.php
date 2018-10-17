<?php

namespace core\app\index\model;

class indexModel
{
    //保存模型类的实例
    private $db;

    //构造方法调用单例模型类
    /*public function __construct()
    {
        $this->db = \core\plugin\Model::getInstance();
    }*/

    public function welcome()
    {
        return '欢迎使用 CoolPHP框架 V1.0';
    }


}