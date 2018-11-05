<?php
/**
 *  版权声明 :  地老天荒科技有限公司
 *  文件名称 :  install.php
 *  创 建 者 :  Shi Guang Yu
 *  创建日期 :  2018/08/15 20:27
 *  文件描述 :  Wx_小程序：执行函数生成类
 *  历史记录 :  -----------------------
 */
include('./library/Function_Create_Library.php');

Function_Create_Library::execCreateFunction([
    // 传值类型 : (GET/POST/PUT/DELETE)
    'dataType' => 'POST',
    // 函数名称 : 默认 __function
    'name'     => 'ModuleName',
    // 函数说明 : 默认 新创建函数
    'explain'  => '添加用户数据',
    // 函数输入 : 示例 [
    //  '$get['UserName']  => '用户名称';',
    //]
    'input'    => [
        '(String) $post[\'UserName\']  => \'用户名称\';',
    ],
]);
