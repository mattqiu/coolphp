<?php
/*
 * 树结构处理类
 * */
namespace core\plugin;

class Tree
{

    public $icon = array('│', '├─', '└─');
    public $nbsp = "&nbsp;";

    //使用静态初始化字符串，否则递归无法进行正常的字符串拼接
    public static $select_tree = '';

    /*
     * 得到树结构的数组
     * */
    public function get_tree_array($data, $parent_id, $layer = 1)
    {
        //定义一个空的数组，用于存放处理好树结构数组
        $tree = array();
        foreach ($data as $key => $value) {
            //判断是不是父级
            if ($value['ParentId'] == $parent_id) {
                //写入层级
                $value['layer'] = $layer;
                //通过自身id递归方法寻找有没有子类，直到找不到子类为止
                $value['child'] = $this->get_tree_array($data, $value['MenuId'], $layer + 1);
                //写入数组
                $tree[] = $value;
            }
        }
        return array_reverse($tree);
    }

    /*
     * 得到基础树结构
     *
     * │安徽
     *    └─合肥
     *      └─合肥北
     * │北京
     *    └─海淀
     *      ├─中关村
     *      └─上地
     * │河北
     *    └─石家庄
     * */
    public function get_tree($arr)
    {
        //使用静态初始化字符串，否则递归无法进行正常的字符串拼接
        static $tree = '';
        //获取树结构最后一位key
        $n = count($arr) - 1;
        foreach ($arr as $k => $v) {
            //父样式
            $f_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[0];
            //子样式
            $c_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[1];
            //最后的样式
            $c_last_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[2];
            if (!empty($v['child'])) {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    $style = $f_style;
                } else {
                    if ($k != $n) {
                        $style = $c_style;
                    } else {
                        $style = $c_last_style;
                    }
                }
                //拼接树结构
                $tree .= $style . $v['Name'] . "<br/>";
                //此结构有子结构，继续递归调用，生成树结构
                $this->get_tree($v['child']);
            } else {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    $style = $f_style;
                } else {
                    if ($k != $n) {
                        $style = $c_style;
                    } else {
                        $style = $c_last_style;
                    }
                }
                $tree .= $style . $v['Name'] . "<br/>";
            }
        }
        return $tree;
    }


    public function get_select_tree($arr, $selected = false)
    {
        //使用静态初始化字符串，否则递归无法进行正常的字符串拼接
        static $tree = '';
        //获取树结构最后一位key
        $n = count($arr) - 1;
        foreach ($arr as $k => $v) {
            //父样式
            $f_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[0];
            //子样式
            $c_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[1];
            //最后的样式
            $c_last_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[2];
            if (!empty($v['child'])) {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    $selecteds = $selected == $v['MenuId'] ? 'selected="selected"' : '';
                    //拼接树结构
                    $tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . '">' . $f_style . $v['Name'] . "</option>";
                } else {
                    if ($k != $n) {
                        $tree .= '<optgroup label="' . $c_style . $v['Name'] . '"></optgroup>';
                    } else {
                        $tree .= '<optgroup label="' . $c_last_style . $v['Name'] . '"></optgroup>';
                    }
                }
                //此结构有子结构，继续递归调用，生成树结构
                $this->get_select_tree($v['child']);
            } else {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    $selecteds = $selected == $v['MenuId'] ? 'selected="selected"' : '';
                    //拼接树结构
                    $tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . '">' . $f_style . $v['Name'] . "</option>";
                } else {
                    if ($k != $n) {
                        $tree .= '<optgroup label="' . $c_style . $v['Name'] . '"></optgroup>';
                    } else {
                        $tree .= '<optgroup label="' . $c_last_style . $v['Name'] . '"></optgroup>';
                    }
                }
            }
        }
        return $tree;
    }

    //获取普通select
    public function get_ordinary_select_tree($arr, $selected = false)
    {
        //获取树结构最后一位key
        $n = count($arr) - 1;
        foreach ($arr as $k => $v) {
            //父样式
            $f_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[0];
            //子样式
            $c_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[1];
            //最后的样式
            $c_last_style = str_repeat($this->nbsp . $this->nbsp . $this->nbsp . $this->nbsp, $v['layer']) . $this->icon[2];
            $selecteds = $selected ? 'selected="selected"' : '';
            if (!empty($v['child'])) {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    //拼接树结构
                    self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $f_style . $v['Name'] . "</option>";
                } else {
                    if ($k != $n) {
                        self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $c_style . $v['Name'] . '</option>';
                    } else {
                        self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $c_last_style . $v['Name'] . '</option>';
                    }
                }
                //此结构有子结构，继续递归调用，生成树结构
                $this->get_ordinary_select_tree($v['child'], $selected);
            } else {
                //处理结构样式
                if ($v['ParentId'] == 0) {
                    //拼接树结构
                    self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $f_style . $v['Name'] . "</option>";
                } else {
                    if ($k != $n) {
                        self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $c_style . $v['Name'] . '</option>';
                    } else {
                        self::$select_tree .= '<option ' . $selecteds . ' value="' . $v['MenuId'] . ',' . $v['ParentId'] . '">' . $c_last_style . $v['Name'] . '</option>';
                    }
                }
            }
        }
        return self::$select_tree;
    }

    //获得后台左侧树形结构菜单 ul li
    public function get_backstage_menu($tree, $url, $i = true)
    {
        $html = '';
        foreach ($tree as $key => $value) {
            //判断菜单是否是被选中的状态
            $active = '';
            if ($_SESSION['Module'] == $value['Module']) {
                if ($_SESSION['Controller'] == $value['Controller']) {
                    $active = 'active';
                }
            }
            //判断子数组，是否存在子
            if (count($value['child']) <= 0 && $value['ParentId'] == 0) {
                $Module = $value['Module'] != '' ? $value['Module'] . '/' : '';
                $Controller = $value['Controller'] != '' ? $value['Controller'] . '/' : '';
                $html .= '<li class="' . $active . '"><a href="' . $url . '/cool/' . $Module . $Controller . $value['Methods'] . '" class="' . $value['Icon'] . '">' . $value['Name'] . '</a></li>';
            } elseif (count($value['child']) <= 0) {
                $Module = $value['Module'] != '' ? $value['Module'] . '/' : '';
                $Controller = $value['Controller'] != '' ? $value['Controller'] . '/' : '';
                $html .= '<li class="' . $active . '"><a href="' . $url . '/cool/' . $Module . $Controller . $value['Methods'] . '">' . $value['Name'] . '</a></li>';
            } else {
                $open = '';
                //判断后台左侧菜单是否展开
                $cm_menu_toggle = \core\plugin\Cookie::get_common('cm-menu-toggled');
                if ($cm_menu_toggle == 'false' || $cm_menu_toggle == '') {
                    //判断是否有子菜单是选中的
                    foreach ($value['child'] as $k => $v) {
                        if ($_SESSION['Module'] == $v['Module']) {
                            if ($_SESSION['Controller'] == $v['Controller']) {
                                $open = ' open';
                            }
                        }
                    }
                }
                $html .= '<li class="cm-submenu' . $open . '"><a class="' . $value['Icon'] . '">' . $value['Name'] . ' <span class="caret"></span></a>';
                $html .= $this->get_backstage_menu($value['child'], $url, false);
                $html = $html . '</li>';
            }
        }
        if ($i) {
            return $html ? '<ul class="cm-menu-items">' . $html . '</ul>' : $html;
        } else {
            return $html ? '<ul>' . $html . '</ul>' : $html;
        }
    }

}