<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Order extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time'];
    // 告诉模型这个字段是json格式的数据
    protected $json = ['snap_address', 'snap_items'];
    // 设置json数据返回时以数组格式返回
    protected $jsonAssoc = true;

    /**
     * 分页查询订单，可以以时间范围，订单号，收货人为条件
     */
    public static function getOrdersPaginate($params)
    {
        $field = ['order_no', ['name', 'snap_address->name']];
        $query = self::equalQuery($field, $params);
        $query[] = self::betweenTimeQuery('start','end', $params);
        // paginate()方法用于根据url中的参数，计算查询要查询的开始位置和查询数量
        list($start, $count) = paginate();
        // 应用条件查询
        $orderList = self::where($query);
        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $orderList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果
        $orderList = $orderList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        // 组装返回结果
        $result = [
            'collection' => $orderList,
            'total_nums' => $totalNums
        ];
        return $result;
    }

}
