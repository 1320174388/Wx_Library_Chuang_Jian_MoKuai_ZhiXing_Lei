<?php
/**
 *  版权声明 :  地老天荒科技有限公司
 *  文件名称 :  Module_Create_Library.php
 *  创 建 者 :  Shi Guang Yu
 *  创建日期 :  2018/08/16 10:20
 *  文件描述 :  Wx_小程序：创建模块执行类
 *  历史记录 :  -----------------------
 */
class Module_Create_Library
{
    /**
     * 名 称 : $ModuleConfig
     * 功 能 : 生成模块配置文件
     * 创 建 : 2018/08/16 10:21
     */
    private static $ModuleConfig = array(

        // 版本声明 : 默认（地老天荒科技有限公司）
        'VersionDeclaration' => '地老天荒科技有限公司',

    );

    /**
     * 名 称 : __construct()
     * 功 能 : 定义配置信息数据
     * 创 建 : 2018/08/16 10:22
     */
    private function __construct()
    {
        // TODO: 禁止外部实例化
    }

    /**
     * 名 称 : __clone()
     * 功 能 : 禁止外部克隆该实例
     * 创 建 : 2018/08/16 10:22
     */
    private function __clone()
    {
        // TODO: 禁止外部克隆该实例
    }

    /**
     * 名 称 : execCreateModule()
     * 功 能 : 执行创建模块功能
     * 输 入 : (String) $moduleName => '';
     * 创 建 : 2018/08/16 10:29
     */
    public static function execCreateModule($moduleName,$kaifaName,$notes,$vn)
    {
        // 1. 设置时间为中国标准时区
        date_default_timezone_set('PRC');

        // 2. MosuleMkdir 执行创建模块目录操作
        self::CreateMosuleMkdir($moduleName);

        // 3. ConfigMkdir 执行创建配置目录操作
        self::CreateConfigMkdir($moduleName);

        // 4. VersionMkdir 创建模块运行版本目录
        self::CreateVersionMkdir($moduleName,$kaifaName,$notes,$vn);
    }

    /**
     * 名 称 : CreateMosuleMkdir()
     * 功 能 : 创建模块目录
     * 创 建 : 2018/08/16 10:34
     */
    private static function CreateMosuleMkdir($moduleName)
    {
        // 执行创建目录
        self::createMkdir(
            './'.$moduleName.'_module',
            '模块目录已存在，请创建其他模块',
            '模块目录创建失败'
        );
    }

    /**
     * 名 称 : CreateConfigMkdir()
     * 功 能 : 创建模块配置目录
     * 创 建 : 2018/08/16 11:20
     */
    private static function CreateConfigMkdir($moduleName)
    {
        // 执行创建目录
        self::createMkdir(
            './'.$moduleName.'_module/config',
            '配置文件目录已存在',
            '配置文件目录创建失败'
        );
    }

    /**
     * 名 称 : CreateVersionMkdir()
     * 功 能 : 创建模块运行版本目录
     * 创 建 : 2018/08/16 11:24
     */
    private static function CreateVersionMkdir($moduleName,$kaifaName,$notes,$vn)
    {
        // 处理版本路径
        $working_version = './'.$moduleName.'_module/working_version';

        // 处理配置路劲
        $config_dir = './'.$moduleName.'_module/config';

        // 执行创建目录
        self::createMkdir(
            $working_version,
            '运行版本目录已存在',
            '运行版本目录创建失败'
        );

        // 处理版本子目录函数
        $Array  = ['controller','service','library','dao','model'];
        $Arrays = [
            'controller'=>'控制器',
            'service'   =>'逻辑层',
            'library'   =>'自定义类',
            'dao'       =>'数据层',
            'model'     =>'模型层',
        ];

        // 执行创建子目录
        for( $i=1; $i<=$vn; $i++ )
        {
            self::createMkdir(
                $working_version.'/v'.$i,
                '运行版本v'.$i.'目录已存在',
                '运行版本目录v'.$i.'创建失败'
            );
            foreach($Array as $k=>$v)
            {
                // 创建版本运行目录
                self::createMkdir(
                    $working_version.'/v'.$i.'/'.$v,
                    '运行版本v'.$i.'/'.$v.'目录已存在',
                    '运行版本目录v'.$i.'/'.$v.'创建失败'
                );
                // 创建版本运行内容
                self::createTouch(
                    $working_version.'/v'.$i.'/'.$v.'/'.
                    ucwords($moduleName).ucwords($v).'.php',
                    self::touchContent($moduleName,$kaifaName,$notes,$Arrays,$v,$i)
                );
                // 创建版本运行内容
                self::createTouch(
                    $working_version.'/v'.$i.'/'.$v.'/'.
                    ucwords($moduleName).'Interface.php',
                    self::createInterface($moduleName,$kaifaName,$notes,$i)
                );
                // 创建版本运行配置文件
                self::createTouch(
                    $config_dir.'/v'.$i.'_config.php',
                    self::touchConfig($kaifaName,$notes,$i)
                );
                // 创建版本运行配置文件
                self::createTouch(
                    $config_dir.'/v'.$i.'_tableName.php',
                    self::touchTableName($kaifaName,$notes,$i)
                );
                // 创建模块公共函数文件
                self::createTouch(
                    './'.$moduleName.'_module/common.php',
                    self::touchCommon($kaifaName,$notes)
                );
            }
        }
    }

    /**
     * 名 称 : createInterface()
     * 功 能 : 定义接口内容
     * 输 入 : (String) $kaifaName  = '开发者名称'
     * 输 入 : (String) $notes      = '文件描述'
     * 输 入 : (String) $notes      = '文件描述'
     * 创 建 : 2018/08/16 14:34
     */
    private static function touchCommon($kaifaName,$notes)
    {
        // 获取版权声明信息
        $version = self::$ModuleConfig['VersionDeclaration'];
        // 获取时间
        $time = date('Y/m/d H:i',time());
        // 处理文件内容
        $str =  "<?php
/**
 *  版权声明 :  {$version}
 *  文件名称 :  common.php
 *  创 建 者 :  {$kaifaName}
 *  创建日期 :  {$time}
 *  文件描述 :  {$notes}模块公共何函数文件
 *  历史记录 :  -----------------------
 */

// +----------------------------------
// : 自定义函数区域
// +----------------------------------
";
        return $str;
    }

    /**
     * 名 称 : createInterface()
     * 功 能 : 定义接口内容
     * 输 入 : (String) $kaifaName  = '开发者名称'
     * 输 入 : (String) $notes      = '文件描述'
     * 输 入 : (String) $notes      = '文件描述'
     * 创 建 : 2018/08/16 11:54
     */
    private static function createInterface($moduleName,$kaifaName,$notes,$i)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        // 获取版权声明信息
        $version = self::$ModuleConfig['VersionDeclaration'];
        // 获取时间
        $time = date('Y/m/d H:i',time());
        // 处理文件内容
        $str =  "<?php
/**
 *  版权声明 :  {$version}
 *  文件名称 :  {$ModuleName}Interface.php
 *  创 建 者 :  {$kaifaName}
 *  创建日期 :  {$time}
 *  文件描述 :  {$notes}_数据接口声明
 *  历史记录 :  -----------------------
 */
namespace app\\{$moduleName}_module\\working_version\\v{$i}\\dao;

interface {$ModuleName}Interface
{

}
";
        return $str;
    }

    /**
     * 名 称 : touchConfig()
     * 功 能 : 配置内容
     * 输 入 : (String) $kaifaName  = '开发者名称'
     * 输 入 : (String) $notes      = '文件描述'
     * 输 入 : (String) $notes      = '文件描述'
     * 创 建 : 2018/08/16 11:54
     */
    private static function touchConfig($kaifaName,$notes,$i)
    {
        // 获取版权声明信息
        $version = self::$ModuleConfig['VersionDeclaration'];
        // 获取时间
        $time = date('Y/m/d H:i',time());
        // 处理文件内容
        $str =  "<?php
/**
 *  版权声明 :  {$version}
 *  文件名称 :  v{$i}_config.php
 *  创 建 者 :  {$kaifaName}
 *  创建日期 :  {$time}
 *  文件描述 :  {$notes}_v{$i}_版本配置文件
 *  历史记录 :  -----------------------
 */
 
return [
    // 配置信息注释
    '配置信息下标' => '配置信息内容'
];
";
        return $str;
    }

    /**
     * 名 称 : touchTableName()
     * 功 能 : 配置内容
     * 输 入 : (String) $kaifaName  = '开发者名称'
     * 输 入 : (String) $notes      = '文件描述'
     * 输 入 : (String) $notes      = '文件描述'
     * 创 建 : 2018/08/16 14:09
     */
    private static function touchTableName($kaifaName,$notes,$i)
    {
        // 获取版权声明信息
        $version = self::$ModuleConfig['VersionDeclaration'];
        // 获取时间
        $time = date('Y/m/d H:i',time());
        // 处理文件内容
        $str =  "<?php
/**
 *  版权声明 :  {$version}
 *  文件名称 :  v{$i}_tableName.php
 *  创 建 者 :  {$kaifaName}
 *  创建日期 :  {$time}
 *  文件描述 :  {$notes}_v{$i}_版本数据表配置文件
 *  历史记录 :  -----------------------
 */
 
return [
    // 数据表注释
    '数据表下标' => '数据表表名'
];
";
        return $str;
    }


    /**
     * 名 称 : touchContent()
     * 功 能 : 文件内容
     * 输 入 : (String) $moduleName = '模块名称'
     * 输 入 : (String) $kaifaName  = '开发者名称'
     * 输 入 : (String) $notes      = '文件描述'
     * 输 入 : (String) $notes      = '文件描述'
     * 创 建 : 2018/08/16 11:54
     */
    private static function touchContent($moduleName,$kaifaName,$notes,$Arrays,$v,$i)
    {
        // 获取版权声明信息
        $version = self::$ModuleConfig['VersionDeclaration'];
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        // 获取时间
        $time = date('Y/m/d H:i',time());
        // 首字母大写
        $U = ucwords($v);

        // 处理文件内容
        $str =  "<?php
/**
 *  版权声明 :  {$version}
 *  文件名称 :  {$ModuleName}{$U}.php
 *  创 建 者 :  {$kaifaName}
 *  创建日期 :  {$time}
 *  文件描述 :  {$notes}{$Arrays[$v]}
 *  历史记录 :  -----------------------
 */
namespace app\\{$moduleName}_module\\working_version\\v{$i}\\{$v};
";
        $str .= self::contentCont($moduleName,$i,$v);

        return $str;
    }

    /**
     * 名 称 : contentCont()
     * 功 能 : 内容配置入口
     * 创 建 : 2018/08/16 13:13
     */
    private static function contentCont($moduleName,$i,$v)
    {
        // 控制器内容
        if($v=='controller'){
            return self::controllerContent($moduleName,$i);
        }
        // 逻辑层内容
        if($v=='service'){
            return self::serviceContent($moduleName,$i);
        }
        // 自定义类内容
        if($v=='library'){
            return self::libraryContent($moduleName);
        }
        // 数据层内容
        if($v=='dao'){
            return self::daoContent($moduleName,$i);
        }
        // 模型层内容
        if($v=='model'){
            return self::modelContent($moduleName,$i);
        }
    }

    /**
     * 名 称 : controllerContent()
     * 功 能 : 定义控制器内容
     * 创 建 : 2018/08/16 13:17
     */
    private static function controllerContent($moduleName,$i)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        return "use think\\Controller;
use app\\{$moduleName}_module\\working_version\\v{$i}\\service\\{$ModuleName}Service;

class {$ModuleName}Controller extends Controller
{

}
";
    }

    /**
     * 名 称 : serviceContent()
     * 功 能 : 定义逻辑层内容
     * 创 建 : 2018/08/16 13:17
     */
    private static function serviceContent($moduleName,$i)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        return "use app\\{$moduleName}_module\\working_version\\v{$i}\dao\\{$ModuleName}Dao;
use app\\{$moduleName}_module\\working_version\\v{$i}\\library\\{$ModuleName}Library;

class {$ModuleName}Service
{

}
";
    }

    /**
     * 名 称 : libraryContent()
     * 功 能 : 定义自定义类内容
     * 创 建 : 2018/08/16 13:32
     */
    private static function libraryContent($moduleName)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        return "
class {$ModuleName}Library
{

}
";
    }

    /**
     * 名 称 : daoContent()
     * 功 能 : 定义数据层内容
     * 创 建 : 2018/08/16 13:37
     */
    private static function daoContent($moduleName,$i)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        return "use app\\{$moduleName}_module\\working_version\\v{$i}\model\\{$ModuleName}Model;

class {$ModuleName}Dao implements {$ModuleName}Interface
{

}
";
    }

    /**
     * 名 称 : modelContent()
     * 功 能 : 定义模型层内容
     * 创 建 : 2018/08/16 13:40
     */
    private static function modelContent($moduleName,$i)
    {
        // 获取模块名称
        $ModuleName = ucwords($moduleName);
        return "use think\\Model;

class {$ModuleName}Model extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected ".'$table'." = '';

    // 设置当前模型对应数据表的主键
    protected ".'$pk'." = '主键';

    // 加载配置数据表名
    protected function initialize()
    {
        ".'$this->table'." = config('v{$i}_tableName.数据表下标');
    }
}
";
    }

    /**
     * 名 称 : createMkdir()
     * 功 能 : 创建目录函数
     * 创 建 : 2018/08/16 11:26
     */
    private static function createMkdir($mkdirName,$is_dir,$mkdir)
    {
        // 判断模块目录是否存在
        if(is_dir($mkdirName)){ print_r($is_dir); exit; }

        // 执行创建模块目录
        if (!mkdir ($mkdirName,0 ,true )){
            print_r($mkdir); exit;
        }
    }

    /**
     * 名 称 : createTouch()
     * 功 能 : 创建文件函数
     * 创 建 : 2018/08/16 11:54
     */
    private static function createTouch($touchName,$touchContents)
    {
        // 创建文件生成内容
        file_put_contents($touchName,$touchContents);
    }
}