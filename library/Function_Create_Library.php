<?php
/**
 *  版权声明 :  地老天荒科技有限公司
 *  文件名称 :  Function_Create_Library.php
 *  创 建 者 :  Shi Guang Yu
 *  创建日期 :  2018/08/17 10:19
 *  文件描述 :  Wx_小程序：执行函数生成类
 *  历史记录 :  -----------------------
 */
class Function_Create_Library
{
    /**
     * 名 称 : $FunctionConfig
     * 功 能 : 函数生成配置信息
     * 创 建 : 2018/08/17 10:20
     */
    private static $FunctionConfig = array(

        // 传值类型 : (GET/POST/PUT/DELETE)
        'dataType' => '传值类型',

        // 函数名称 : 默认 __function
        'name'     => '__function',

        // 函数说明 : 默认 新创建函数
        'explain'  => '新创建函数',

        // 函数输入 : 示例 [
        //  '(String) $name => "名字"',
        //  '(String) $name => "名字"'
        //]
        'input'    => array(),

    );

    /**
     * 名 称 : __construct()
     * 功 能 : 定义配置信息数据
     * 创 建 : 2018/08/17 10:20
     */
    private function __construct()
    {
        // TODO: 禁止外部实例化
    }

    /**
     * 名 称 : __clone()
     * 功 能 : 禁止外部克隆该实例
     * 创 建 : 2018/08/17 10:20
     */
    private function __clone()
    {
        // TODO: 禁止外部克隆该实例
    }

    /**
     * 名 称 : execCreateFunction()
     * 功 能 : 执行创建模块功能
     * 创 建 : 2018/08/17 10:21
     */
    public static function execCreateFunction($FunctionConfig)
    {
        try {
            // 1. 设置时间为中国标准时区
            date_default_timezone_set('PRC');

            // 2. 验证传值内容是否正确
            self::inputValidate($FunctionConfig);

            // 3. Route 生成路由文件
            self::RouteCreate($FunctionConfig);

            // 4. Controller 执行生成控制器代码
            self::controllerCreate($FunctionConfig);

            // 6. Service 执行生成逻辑函数代码
            self::serviceCreate($FunctionConfig);

            // 7. Service 执行生成逻辑函数代码
            self::validateCreate($FunctionConfig);

            // 8. Library 执行生成自定义类代码
            self::libraryCreate($FunctionConfig);

            // 9. Interface 执行生成Dao层接口代码
            self::interfaceCreate($FunctionConfig);

            // 10. Dao 执行生成Dao层数据代码
            self::daoCreate($FunctionConfig);

            // 11. Model 创建模型
            self::modelCreate($FunctionConfig);

            // 12. print_r 打印数据
            print_r('Function Create Success');
        } catch (\Exception $e) {
            // 13. print_r 打印数据
            print_r('Function Create Error');
        }
    }

    /**
     * 名 称 : inputValidate()
     * 功 能 : 验证传值内容是否正确
     * 创 建 : 2018/08/17 11:03
     */
    private static function inputValidate($FunctionConfig)
    {
        // 判断配置信息是否存在
        if(empty($FunctionConfig)){
            print_r('请发送配置数组，数据与属性配置一样'); exit;
        }

        // 判断传值状态是否存在
        if(empty($FunctionConfig['dataType'])){
            print_r('请发送传值状态，参考类属性发送状态'); exit;
        }

        // 将传值状态转化成大写
        $dataType = strtoupper($FunctionConfig['dataType']);
        // 判断传值状态是否正确
        if(
            ($dataType!=='GET') &&
            ($dataType!=='POST')&&
            ($dataType!=='PUT') &&
            ($dataType!=='DELETE')
        ){
            print_r('传值类型不存在'); exit;
        }

        // 判断函数名称是否存在
        if(empty($FunctionConfig['name'])){
            print_r('请输入函数名称'); exit;
        }

        // 判断请输入函数说明是否存在
        if(empty($FunctionConfig['explain'])){
            print_r('请输入函数说明'); exit;
        }

        // 判断请输入函数说输入是否存在
        if(!is_array($FunctionConfig['input'])){
            print_r('请正确写入输入，没有输入发送空数组'); exit;
        }
    }

    /**
     * 名 称 : RouteCreate()
     * 功 能 : 执行生成路由文件代码
     * 创 建 : 2018/08/20 14:13
     */
    private static function RouteCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataTypeL = strtolower($FunctionConfig['dataType']);
        $DataTypeL = ucfirst($dataTypeL);
        // 将传值状态转化成大写
        $dataTypeU = strtoupper($FunctionConfig['dataType']);
        // 获取名字
        $name =  $FunctionConfig['name'];
        $Name =  ucfirst($FunctionConfig['name']);
        // 处理内容
        $String = "
/**
 * 传值方式 : {$dataTypeU}
 * 路由功能 : {$FunctionConfig['explain']}
 */
Route::{$dataTypeL}(
    ':v/RouteName_module/{$name}_route',
    'RouteName_module/:v.controller.{$Name}Controller/{$name}{$DataTypeL}'
);
";
        // 判断文件是否存在
        if(file_exists("../route/RouteName_route_v1_api.php")){
            file_put_contents(
                '../route/RouteName_route_v1_api.php',
                $String, FILE_APPEND
            );
        }
    }

    /**
     * 名 称 : controllerCreate()
     * 功 能 : 执行生成控制器代码
     * 创 建 : 2018/08/17 10:24
     */
    private static function controllerCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理输出
        if($dataType == 'get'){
            $tishixinxi = '请求成功';
            $output = '{"errNum":0,"retMsg":"请求成功","retData":"请求数据"}';
        }else{
            $tishixinxi = '';
            $output = '{"errNum":0,"retMsg":"提示信息","retData":true}';
        }
        // 处理名字
        $name = $FunctionConfig['name'].ucfirst($dataType);
        // 处理明细
        if($dataType == 'get')   {$names = 'Show';}
        if($dataType == 'post')  {$names = 'Add'; }
        if($dataType == 'put')   {$names = 'Edit';}
        if($dataType == 'delete'){$names = 'Del'; }
        // 处理控制器注释信息
        $String = self::notesCreate(
            $name,
            $FunctionConfig['explain'].'接口',
            $FunctionConfig['input'],
            $output
        );
        // 处理内容
        $String.= "
    public function {$name}(\\think\\Request \$request)
    {
        // 实例化Service层逻辑类
        \$".$FunctionConfig['name']."Service = new ".ucfirst($FunctionConfig['name'])."Service();
        
        // 获取传入参数
        \${$dataType} = \$request->{$dataType}();
        
        // 执行Service逻辑
        \$res = \$".$FunctionConfig['name'].'Service->'.$FunctionConfig['name'].$names."(\${$dataType});
        
        // 处理函数返回值
        return \\RSD::wxReponse(\$res,'S','".$tishixinxi."');
    }
}";
        // 判断文件是否存在
        if(file_exists("../controller/".ucfirst($FunctionConfig['name'])
            .'Controller'.".php")){
            file_put_contents(
                "../controller/".ucfirst($FunctionConfig['name'])
                .'Controller'.".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../controller/".ucfirst($FunctionConfig['name'])
                    .'Controller'.".php"
                ))
            );
        }else{
            file_put_contents(
                "../controller/".ucfirst($FunctionConfig['name'])
                .'Controller'.".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameController.php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : libraryCreate()
     * 功 能 : 执行生成自定义类代码
     * 创 建 : 2018/08/17 10:25
     */
    private static function libraryCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理输出
        if($dataType == 'get'){
            $output = "['msg'=>'success','data'=>'返回数据']";
        }else{
            $output = "['msg'=>'success','data'=>'提示信息']";
        }
        // 处理名字
        $name = $FunctionConfig['name'].'Lib'.ucfirst($dataType);
        // 处理自定义类注释信息
        $String = self::notesCreate(
            $name,
            $FunctionConfig['explain'].'函数类',
            $FunctionConfig['input'],
            $output
        );
        // 处理内容
        $String.= "
    public function {$name}(\${$dataType})
    {
        // TODO : 执行函数处理逻辑
        
        // TODO : 返回函数输出数据
        return ['msg'=>'success','data'=>'返回数据'];
    }
}";
        // 判断文件是否存在
        if(file_exists("../library/".ucfirst($FunctionConfig['name'])
            .'Library'.".php")){
            file_put_contents(
                "../library/".ucfirst($FunctionConfig['name'])
                .'Library'.".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../library/".ucfirst($FunctionConfig['name'])
                    .'Library'.".php"
                ))
            );
        }else{
            file_put_contents(
                "../library/".ucfirst($FunctionConfig['name'])
                .'Library'.".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameLibrary.php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : validateCreate()
     * 功 能 : 执行生成验证器代码
     * 创 建 : 2018/08/17 10:25
     */
    private static function validateCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理自定义类注释信息
        $String = "
    /**
     * 名  称 : \$rule
     * 功  能 : 验证规则";
        // 处理注释输入
        if(!empty($FunctionConfig['input'])){
            foreach ($FunctionConfig['input'] as $v)
            {
                $String.= "
     * 输  入 : {$v}";
            }
        }else{
            $String.= "
     * 输  入 : --------------------------------------";
        }
        $String.= "
     * 创  建 : ".date('Y/m/d H:i',time())."
     */
    protected \$rule =   [
        'name'  => 'require|max:25',
        'age'   => 'number|between:1,120',
        'email' => 'email',
    ];

    /**
     * 名  称 : \$message()
     * 功  能 : 设置验证信息
     * 创  建 : ".date('Y/m/d H:i',time())."
     */
    protected \$message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',
    ];
}";
        // 处理数据
        $arrays = [
            'post'  => 'post',
            'get'   => 'get',
            'put'   => 'put',
            'delete'=> 'delete',
        ];
        unset($arrays[$dataType]);
        // 生成其他验证器内容
        foreach($arrays as $v){
            if(!file_exists("../validator/".ucfirst($FunctionConfig['name'])
                .'Validate'.ucfirst($v).".php")){
                file_put_contents(
                    "../validator/".ucfirst($FunctionConfig['name'])
                    .'Validate'.ucfirst($v).".php",
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameValidate"
                            .ucfirst($v).".php"
                        )
                    )
                );
            }
        }
        // 判断文件是否存在
        if(file_exists("../validator/".ucfirst($FunctionConfig['name'])
            .'validator'.".php")){
            file_put_contents(
                "../library/".ucfirst($FunctionConfig['name'])
                .'Validate'.ucfirst($dataType).".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../validator/".ucfirst($FunctionConfig['name'])
                    .'Validate'.ucfirst($dataType).".php"
                ))
            );
        }else{
            file_put_contents(
                "../validator/".ucfirst($FunctionConfig['name'])
                .'Validate'.ucfirst($dataType).".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameValidate"
                            .ucfirst($dataType).".php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : serviceCreate()
     * 功 能 : 执行生成逻辑函数代码
     * 创 建 : 2018/08/17 10:25
     */
    private static function serviceCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理输出
        if($dataType == 'get'){
            $output = "['msg'=>'success','data'=>'返回数据']";
        }else{
            $output = "['msg'=>'success','data'=>'提示信息']";
        }
        // 处理明细
        if($dataType == 'get')   {$names = 'Show';$namen = 'Select';}
        if($dataType == 'post')  {$names = 'Add'; $namen = 'Create';}
        if($dataType == 'put')   {$names = 'Edit';$namen = 'Update';}
        if($dataType == 'delete'){$names = 'Del'; $namen = 'Delete';}
        // 处理名字
        $name = $FunctionConfig['name'].$names;
        // 处理自定义类注释信息
        $String = self::notesCreate(
            $name,
            $FunctionConfig['explain'].'逻辑',
            $FunctionConfig['input'],
            $output
        );
        // 处理内容
        $String.= "
    public function {$name}(\${$dataType})
    {
        // 实例化验证器代码
        \$validate  = new ".ucfirst($FunctionConfig['name'])."Validate".ucfirst($dataType)."();
        
        // 验证数据
        if (!\$validate->scene('edit')->check(\${$dataType})) {
            return ['msg'=>'error','data'=>\$validate->getError()];
        }
        
        // 实例化Dao层数据类
        \${$FunctionConfig['name']}Dao = new ".ucfirst($FunctionConfig['name'])."Dao();
        
        // 执行Dao层逻辑
        \$res = \$".$FunctionConfig['name'].'Dao->'.$FunctionConfig['name'].$namen."(\${$dataType});
        
        // 处理函数返回值
        return \\RSD::wxReponse(\$res,'D');
    }
}";
        // 判断文件是否存在
        if(file_exists("../service/".ucfirst($FunctionConfig['name'])
            .'Service'.".php")){
            file_put_contents(
                "../service/".ucfirst($FunctionConfig['name'])
                .'Service'.".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../service/".ucfirst($FunctionConfig['name'])
                    .'Service'.".php"
                ))
            );
        }else{
            file_put_contents(
                "../service/".ucfirst($FunctionConfig['name'])
                .'Service'.".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameService.php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : interfaceCreate()
     * 功 能 : 执行生成Dao层接口代码
     * 创 建 : 2018/08/17 10:26
     */
    private static function interfaceCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理输出
        if($dataType == 'get'){
            $output = "['msg'=>'success','data'=>'返回数据']";
        }else{
            $output = "['msg'=>'success','data'=>'提示信息']";
        }
        // 处理明细
        if($dataType == 'get')   {$names = 'Select';}
        if($dataType == 'post')  {$names = 'Create'; }
        if($dataType == 'put')   {$names = 'Update';}
        if($dataType == 'delete'){$names = 'Delete'; }
        // 处理名字
        $name = $FunctionConfig['name'].$names;
        // 处理自定义类注释信息
        $String = self::notesCreate(
            $name,
            '声明:'.$FunctionConfig['explain'].'数据处理',
            $FunctionConfig['input'],
            $output
        );
        // 处理内容
        $String.= "
    public function {$name}(\${$dataType});
}";
        // 判断文件是否存在
        if(file_exists("../dao/".ucfirst($FunctionConfig['name'])
            .'Interface'.".php")){
            file_put_contents(
                "../dao/".ucfirst($FunctionConfig['name'])
                .'Interface'.".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../dao/".ucfirst($FunctionConfig['name'])
                    .'Interface'.".php"
                ))
            );
        }else{
            file_put_contents(
                "../dao/".ucfirst($FunctionConfig['name'])
                .'Interface'.".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameInterface.php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : daoCreate()
     * 功 能 : 执行生成Dao层接口代码
     * 创 建 : 2018/08/17 10:26
     */
    private static function daoCreate($FunctionConfig)
    {
        // 将传值状态转化成小写
        $dataType = strtolower($FunctionConfig['dataType']);
        // 处理输出
        if($dataType == 'get'){
            $output = "['msg'=>'success','data'=>'返回数据']";
        }else{
            $output = "['msg'=>'success','data'=>'提示信息']";
        }
        // 处理明细
        if($dataType == 'get')   {$names = 'Select';}
        if($dataType == 'post')  {$names = 'Create'; }
        if($dataType == 'put')   {$names = 'Update';}
        if($dataType == 'delete'){$names = 'Delete'; }
        // 处理名字
        $name = $FunctionConfig['name'].$names;
        // 处理自定义类注释信息
        $String = self::notesCreate(
            $name,
            $FunctionConfig['explain'].'数据处理',
            $FunctionConfig['input'],
            $output
        );
        // 处理内容
        $String.= "
    public function {$name}(\${$dataType})
    {
        // TODO :  ".ucfirst($FunctionConfig['name'])."Model 模型
    }
}";
        // 判断文件是否存在
        if(file_exists("../dao/".ucfirst($FunctionConfig['name'])
            .'Dao'.".php")){
            file_put_contents(
                "../dao/".ucfirst($FunctionConfig['name'])
                .'Dao'.".php",
                preg_replace("/}$/", $String,file_get_contents(
                    "../dao/".ucfirst($FunctionConfig['name'])
                    .'Dao'.".php"
                ))
            );
        }else{
            file_put_contents(
                "../dao/".ucfirst($FunctionConfig['name'])
                .'Dao'.".php",
                preg_replace("/}$/", $String,
                    preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                        file_get_contents(
                            "./default/ModuleNameDao.php"
                        )
                    )
                )
            );
        }
    }

    /**
     * 名 称 : modelCreate()
     * 功 能 : 执行Model代码生成
     * 创 建 : 2018/08/17 10:26
     */
    private static function modelCreate($FunctionConfig)
    {
        // 判断文件是否存在
        if(!file_exists("../model/".ucfirst($FunctionConfig['name'])
            .'Model'.".php")){
            file_put_contents(
                "../model/".ucfirst($FunctionConfig['name'])
                .'Model'.".php",
                preg_replace("/ModuleName/", ucfirst($FunctionConfig['name']),
                    file_get_contents(
                        "./default/ModuleNameModel".".php"
                    )
                )
            );
        }
    }

    /**
     * 名 称 : notesCreate()
     * 功 能 : 执行代码注释函数
     * 创 建 : 2018/08/17 10:26
     */
    private static function notesCreate($name,$explain,$input,$output)
    {
        // 处理注释信息
        $String = "
    /**
     * 名  称 : {$name}()
     * 功  能 : {$explain}
     * 变  量 : --------------------------------------";
        // 处理注释输入
        if(!empty($input)){
            foreach ($input as $v)
            {
                $String.= "
     * 输  入 : {$v}";
            }
        }else{
            $String.= "
     * 输  入 : --------------------------------------";
        }
            $String.= "
     * 输  出 : {$output}
     * 创  建 : ".date('Y/m/d H:i',time())."
     */";
        // 返回最终处理成功注释
        return $String;

    }
}