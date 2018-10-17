<?php

//控制器命名空间
namespace core\app\index\controller;

//父类控制器
use core\plugin\Controller;
//模型
use core\app\index\model\indexModel;
//框架工具
use core\tool\Tool;

class indexController extends Controller
{
    public function main()
    {
        //使用index模型
        $indexModel = new indexModel();
        $welcome = $indexModel->welcome();
        parent::assign('smile', ':&nbsp;)');
        parent::assign('welcome', $welcome);
        parent::display('index/view/index.html');
    }

    public function test()
    {
        Tool::p('这里等于index/index/test，具体可以查看routing配置');
    }
}