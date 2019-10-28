<?php


namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    /**
     * 分页查询会员列表
     */
    public static function getUsersPaginate($params)
    {
        $field = ['nickname'];
        $query = self::equalQuery($field, $params);

        list($start, $count) = paginate();
        // 应用条件查询
        $userList = self::where($query);
        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $userList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果
        $userList = $userList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        // 组装返回结果
        $result = [
            'collection' => $userList,
            'total_nums' => $totalNums
        ];

        return $result;
    }

}
