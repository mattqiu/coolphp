<?php
/*
 * 控制器父类
 * 1.初始化模板引擎
 * 2.实例化 分页类
 * */

namespace core\plugin;

class Controller
{

    //定义模板引擎初始为空。
    private static $Template = null;

    //控制器父类构造方法
    /*public function __construct(){}*/

    //初始化模板类
    protected function init_Template()
    {
        //调用模板引擎
        self::$Template = new \core\plugin\Template();
        //调用模板引擎父类Smarty的assign方法，设置网页模板根目录
        self::$Template->assign('WebSite', \core\tool\Tool::get_url());
    }

    //重写Smarty的assign方法
    protected function assign($variable, $value)
    {
        //判断是否初始化过模板引擎
        if (self::$Template == null) {
            $this->init_Template();
        }
        self::$Template->assign($variable, $value);
    }

    //重写Smarty的display方法
    protected function display($value)
    {
        //判断是否初始化过模板引擎
        if (self::$Template == null) {
            $this->init_Template();
        }
        self::$Template->display($value);
    }

    //设置分页
    protected function page($total, $listRows)
    {
        if (isset($_GET['page'])) {
            \core\tool\Tool::session('set', 'page', \core\tool\Tool::get('page', 'int', 1));
        } else {
            \core\tool\Tool::session('set', 'page', 1);
        }
        $page = new \core\plugin\Page($total, $listRows);
        $this->assign("num", $page->listRowsBegin());
        $this->assign("page", $page->display([0, 1, 2, 3, 4, 6]));
        return $page->limit;
    }

}