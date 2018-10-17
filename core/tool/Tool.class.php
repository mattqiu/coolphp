<?php
/*
 * CoolPHP框架 - 函数库
 * */
namespace core\tool;

class Tool
{
    //获取网站域名
    public static function get_url()
    {
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
            return 'http://' . $_SERVER['HTTP_HOST'] . '/cool';
        } else {
            return 'http://' . $_SERVER['HTTP_HOST'];
        }
    }

    //显示404
    public static function show404()
    {
        header('HTTP/1.1 404 Not Found');
        header("status: 404 Not Found");
        exit;
    }

    //返回上一页
    public static function go_prior($str = null, $prior = -1)
    {
        if ($str === null) {
            echo "<script>history.go(" . $prior . ");</script>";
        } else {
            echo "<script>alert('$str');history.go(" . $prior . ");</script>";
        }
        exit;
    }

    //更漂亮的数组或变量的展现方式
    public static function p($var)
    {
        //is_bool()检测变量是否是布尔型
        if (is_bool($var)) {
            var_dump($var);
        } else if (is_null($var)) {
            var_dump(null);
        } else {
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#e7ffe9;border:1px solid #aaa;font-size:17px;line-height:22px;opacity:0.9;font-weight:bold;font-family: \"STHeiti\",sans-serif;'>" . print_r($var, true) . "</pre>";
        }
    }

    //调试函数
    public static function debug($value, $dump = false, $exit = true)
    {
        /*
         * 调试函数
         * $value 要调试的数据
         * $dump 是否启用var_dump调试
         * $exit 是否在调试后设置断点
         */
        //判断调试的时候用什么函数
        if ($dump) {
            $func = 'var_dump';
        } else {
            if (is_array($value) || is_object($value)) {
                $func = 'print_r';
            } else {
                $func = 'printf';
            }
        }
        //输出html
        echo '<pre>调试输出:<hr/>';
        $func($value);
        echo '</pre>';
        //是否断点
        if ($exit) {
            exit;
        }
    }

    //获取当前时间
    public static function get_now_time($format = null)
    {
        if ($format === null) {
            return time();
        } else {
            return date($format, time());
        }
    }

    //获取文件的后缀名
    public static function get_file_suffix($file)
    {
        return end(explode('.', $file));
    }

    //跳转方法
    public static function jump($url, $str = null)
    {
        //判断，如果只有url那么就转跳，如果有URL和str那么就是弹框加转跳
        if (isset($url) && $str === null) {
            echo "<script>window.location='$url';</script>";
        } elseif (isset($url) && isset($str)) {
            echo "<script>alert('$str');window.location='$url';</script>";
        }
        exit;
    }

    //处理搜索使用的关键字
    public static function keyword_deal_with($keyword, $filter = 3)
    {
        /*
         * 默认保留3个搜索关键字
         * 可以传入 (int)$filter 参数自定义关键词个数
         * */
        return array_slice(explode(" ", $keyword), 0, $filter);
    }

    //输出json
    public static function json($array)
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($array);
    }

    //获取get数据
    public static function get($str = null, $filter = '', $default = false)
    {
        /*
         * 获取get数据
         * $str 要获取的变量名
         * $filter 过滤类型 只支持int类型
         * $default 默认值 当获取不到值时,所返回的默认值
         */
        //判断 有没有传入要获取的get参数，如果没有传入，就直接返回全部的$_GET数据
        if ($str !== null) {
            //判断要获取的get参数存在不
            $get = isset($_GET[$str]) ? $_GET[$str] : false;
            //判断返回什么值
            if ($get !== false) {
                switch ($filter) {
                    case 'int':
                        //is_numeric()函数判断参数是不是数字或者字符串的数字
                        if (!is_numeric($get)) {
                            return $default;
                        }
                        break;
                    default:
                        //htmlspecialchars()函数当碰到HTML标签<>的时候直接当字符串输出，提高安全
                        $get = htmlspecialchars($get);
                }
                return $get;
            } else {
                return $default;
            }
        } else {
            return $_GET;
        }
    }

    //获取post数据
    public static function post($str = null, $filter = '', $default = false)
    {
        /*
         * 获取post数据
         * $str 要获取的变量名
         * $filter 过滤类型 只支持int类型
         * $default 默认值 当获取不到值时,所返回的默认值
         */
        //判断 有没有传入要获取的post参数，如果没有传入，就直接返回全部的$_POST数据
        if ($str !== null) {
            //判断要获取的post参数存在不
            $post = isset($_POST[$str]) ? $_POST[$str] : false;
            //判断返回什么值
            if ($post !== false) {
                switch ($filter) {
                    case 'int':
                        //is_numeric()函数判断参数是不是数字或者字符串的数字
                        if (!is_numeric($post)) {
                            return $default;
                        }
                        break;
                    case 'array':
                        //is_array()函数判断参数是不是数组
                        if (!is_array($post)) {
                            return $default;
                        }
                        break;
                    default:
                        if (!is_array($post)) {
                            //htmlspecialchars()函数当碰到HTML标签<>的时候直接当字符串输出，提高安全
                            $post = htmlspecialchars($post);
                        }
                }
                return $post;
            } else {
                return $default;
            }
        } else {
            return $_POST;
        }
    }

    //session操作
    public static function session($perform, $session_name = null, $value = null)
    {
        //判断执行那种session操作
        switch ($perform) {
            case 'set':
                if ($session_name !== null && $value !== null) {
                    return $_SESSION[$session_name] = $value;
                } else {
                    if (DEBUG) {
                        throw new \Exception('请正确传入session()方法，需要的值');
                    } else {
                        self::show404();
                    }
                }
                break;
            case 'get':
                if ($session_name !== null) {
                    return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
                } else {
                    if (DEBUG) {
                        throw new \Exception('请正确传入session()方法，需要的值');
                    } else {
                        self::show404();
                    }
                }
                break;
            case 'delete':
                if ($session_name !== null) {
                    if (isset($sessionName)) {
                        unset($_SESSION[$sessionName]);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (DEBUG) {
                        throw new \Exception('请正确传入session()方法，需要的值');
                    } else {
                        self::show404();
                    }
                }
                break;
            case 'clear':
                if (isset($_SESSION)) {
                    session_unset();
                    return true;
                }
                break;
            default:
                if (DEBUG) {
                    throw new \Exception('请传入需要执行那种session()操作，set，get，delete，clear');
                } else {
                    self::show404();
                }
        }
    }

    //创建文件
    public static function create_file($file_name, $file_content)
    {
        //判断文件存在不，存在就删除
        if (file_exists($file_name)) {
            unlink($file_name);
        }
        /*创建文件 fopen($URL地址，W) 函数打开文件或者 URL
        "w" 写入方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。*/
        $file_handle = fopen($file_name, "w");
        //判断写权限，is_writable()函数判断指定的文件是否可写。
        if (!is_writable($file_name)) {
            return false;
        }
        //写入文件,fwrite()函数写入文件
        if (!fwrite($file_handle, $file_content)) {
            return false;
        }
        //关闭指针,fclose()函数关闭一个打开文件
        fclose($file_handle);
        //返回文件名
        return true;
    }

    /*
     * 发邮件方法
     * $Sender 发件人姓名（昵称）
     * $Rmailbox 收件人邮箱地址
     * $Mailtitle 邮件的主题
     * $Mailcontent 邮件正文
     * */
    public static function send_mail($Sender, $Rmailbox, $Mailtitle, $Mailcontent)
    {
        require_once "phpmailer/class.phpmailer.php";
        require_once "phpmailer/class.smtp.php";
        $mail = new \core\tool\phpmailer\PHPMailer();
        $mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.qq.com';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';
        $mail->FromName = $Sender;
        $mail->Username = 'xxx@qq.com';
        $mail->Password = 'xxx';
        $mail->From = 'xxx@qq.com';
        $mail->isHTML(true);
        $mail->addAddress($Rmailbox, $Sender);
        $mail->Subject = $Mailtitle;
        $mail->Body = "$Mailcontent";
        $status = $mail->send();
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //加密get参数
    public static function encryption_get($url, $key)
    {
        $encrypt_key = md5(mt_rand(0, 100));
        $ctr = 0;
        $tmps = "";
        for ($i = 0; $i < strlen($url); $i++) {
            if ($ctr == strlen($encrypt_key))
                $ctr = 0;
            $tmps .= substr($encrypt_key, $ctr, 1) . (substr($url, $i, 1) ^ substr($encrypt_key, $ctr, 1));
            $ctr++;
        }

        $encrypt_key = md5($key);
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($tmps); $i++) {
            if ($ctr == strlen($encrypt_key))
                $ctr = 0;
            $tmp .= substr($tmps, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }

        return rawurlencode(base64_encode($tmp));
    }

    //解密get参数
    public static function decryption_get($string, $key)
    {
        $txts = base64_decode(rawurldecode($string));
        $encrypt_key = md5($key);
        $ctr = 0;
        $txt = "";
        for ($s = 0; $s < strlen($txts); $s++) {
            if ($ctr == strlen($encrypt_key))
                $ctr = 0;
            $txt .= substr($txts, $s, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }

        $strs = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = substr($txt, $i, 1);
            $i++;
            $strs .= (substr($txt, $i, 1) ^ $md5);
        }

        $url_array = explode('&', $strs);
        if (is_array($url_array)) {
            foreach ($url_array as $var) {
                $var_array = explode("=", $var);
                $vars[$var_array[0]] = $var_array[1];
            }
        }

        return $vars;
    }

}