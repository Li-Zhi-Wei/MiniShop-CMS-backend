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
        $query = self::likeQuery($field, $params);
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

    /**
     * 指定时间范围统计订单基础数据
     */
    public static function getOrderStatisticsByDate($params,$format)
    {
        $query = [];
        // 查询时间范围
        $query[] = self::betweenTimeQuery('start', 'end', $params);
        // 查询status为2到4这个范围的记录
        // 2（已支付）,3（已发货）,4（已支付但缺货）
        $query[] = ['status', 'between', '2, 4'];

        $order = self::where($query)
            // 格式化create_time字段；做聚合查询
            ->field("FROM_UNIXTIME(create_time,'{$format}') as date,
                    count(*) as count,sum(total_price) as total_price")
            // 查询结果按date字段分组，注意这里因为在field()中给create_time字段起了别名date，所以用date
            ->group("date")
            ->select();
        return $order;
    }

}
