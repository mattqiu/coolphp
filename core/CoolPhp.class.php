<?php
/*
 * CoolPHP框架的核心类
 * */
namespace core;

use core\tool\Tool;

class CoolPhp
{
    //框架运行方法run
    public static function run()
    {
        //初始化Log类
        \core\plugin\Log::init();
        //new 出路由类，获取url调用了什么控制器和控制器中的什么方法
        $routing = new \core\plugin\Routing();
        //判断使用多模块运行，还是单模块
        if ($routing->module) {
            //多模块
            self::multi_run($routing->module, $routing->controller, $routing->methods);
        } else {
            //单模块
            self::single_run($routing->controller, $routing->methods);
        }
    }

    //多模块运行
    public static function multi_run($routing_module, $routing_controller, $routing_methods)
    {
        //拼接模块文件夹
        $module = ROOT_PATH . '/core/app/' . $routing_module;
        //拼接控制器
        $controller_class = '\core\app\\' . $routing_module . '\controller\\' . $routing_controller . 'Controller';
        //拼接控制器文件
        $controller_class_file = ROOT_PATH . '/core/app/' . $routing_module . '/controller/' . $routing_controller . 'Controller.class.php';
        //获取使用什么方法
        $controller_action = $routing_methods;
        //判断模块存在吗
        if (is_dir($module) && $routing_module != 'general') {
            //设置模块session
            Tool::session('set', 'Module', $routing_module);
            //判断控制器文件存在吗
            if (is_file($controller_class_file)) {
                //设置控制器session
                Tool::session('set', 'Controller', $routing_controller);
                //控制器存在直接new出控制器
                $controller = new $controller_class();
                //method_exists()判断控制器中的一个方法是否存在
                if (method_exists($controller, $controller_action)) {
                    //设置方法session
                    Tool::session('set', 'Methods', $routing_methods);
                    //方法存在执行这个方法
                    $controller->$controller_action();
                    //打上日志，执行了什么控制器和控制器的什么方法
                    \core\plugin\Log::log('module->' . $routing_module . '   controller->' . $routing_controller . '   methods->' . $routing_methods);
                } else {
                    if (DEBUG) {
                        throw new \Exception($controller_action . '，是一个不存在的方法');
                    } else {
                        Tool::show404();
                    }
                }
            } else {
                if (DEBUG) {
                    throw new \Exception($controller_class . '，是一个不存在的控制器');
                } else {
                    Tool::show404();
                }
            }
        } else {
            if (DEBUG) {
                throw new \Exception($routing_module . '，是一个不存在的模块');
            } else {
                Tool::show404();
            }
        }
    }

    //单模块运行
    public static function single_run($routing_controller, $routing_methods)
    {
        //拼接控制器
        $controller_class = '\core\app\controller\\' . $routing_controller . 'Controller';
        //拼接控制器文件
        $controller_class_file = ROOT_PATH . '/core/app/controller/' . $routing_controller . 'Controller.class.php';
        //获取使用什么方法
        $controller_action = $routing_methods;
        //判断控制器文件存在吗
        if (is_file($controller_class_file)) {
            //设置控制器session
            Tool::session('set', 'Controller', $routing_controller);
            //控制器存在直接new出控制器
            $controller = new $controller_class();
            //method_exists()判断控制器中的一个方法是否存在
            if (method_exists($controller, $controller_action)) {
                //设置方法session
                Tool::session('set', 'Methods', $routing_methods);
                //方法存在执行这个方法
                $controller->$controller_action();
                //打上日志，执行了什么控制器和控制器的什么方法
                \core\plugin\Log::log('controller->' . $routing_controller . '   methods->' . $routing_methods);
            } else {
                if (DEBUG) {
                    throw new \Exception($controller_action . '，是一个不存在的方法');
                } else {
                    Tool::show404();
                }
            }
        } else {
            if (DEBUG) {
                throw new \Exception($controller_class . '，是一个不存在的控制器');
            } else {
                Tool::show404();
            }
        }
    }

    //自定义引入类函数
    public static function load($class)
    {
        $cool_class = str_replace('\\', '/', $class);
        if (is_file(ROOT_PATH . '/' . $cool_class . '.class.php')) {
            include_once ROOT_PATH . '/' . $cool_class . '.class.php';
        } elseif (is_file(ROOT_PATH . '/core/plugin/' . $cool_class . '.php')) {
            include_once ROOT_PATH . '/core/plugin/' . $cool_class . '.php';
        }
    }
}