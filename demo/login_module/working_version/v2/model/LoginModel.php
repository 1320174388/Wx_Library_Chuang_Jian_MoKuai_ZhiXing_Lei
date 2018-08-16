<?php
/**
 *  版权声明 :  地老天荒科技有限公司
 *  文件名称 :  LoginModel.php
 *  创 建 者 :  Shi Guang Yu
 *  创建日期 :  2018/08/16 15:02
 *  文件描述 :  用户登录模型层
 *  历史记录 :  -----------------------
 */
namespace app\login_module\working_version\v2\model;
use think\Model;

class LoginModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '';

    // 设置当前模型对应数据表的主键
    protected $pk = '主键';

    // 加载配置数据表名
    protected function initialize()
    {
        $this->table = config('v2_tableName.数据表下标');
    }
}
