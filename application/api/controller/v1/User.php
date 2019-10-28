<?php


namespace app\api\controller\v1;


use think\facade\Request;
use app\api\model\User as UserModel;
use app\lib\exception\user\UserException;

class User
{
    /**
     * 分页查询会员列表（可传入nickname,page,count作为参数）
     * @auth('查看会员列表','会员管理')
     */
    public function getUsersPaginate()
    {
        $params = Request::get();
        $users = UserModel::getUsersPaginate($params);
        if ($users['total_nums'] === 0) {
            throw new UserException([
                'code' => 404,
                'msg' => '未查询到会员相关信息',
                'error_code' => 70013
            ]);
        }
        return $users;
    }
}
