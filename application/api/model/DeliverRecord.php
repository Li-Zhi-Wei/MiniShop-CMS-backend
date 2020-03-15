<?php


namespace app\api\model;



class DeliverRecord extends BaseModel
{
    protected $hidden = ['update_time'];

    public static function getDeliverRecordPaginate($params)
    {
        // 需要判断是否存在的参数名
        $field = ['order_no', 'number', 'operator'];
        // 构造数组查询条件
        $query = self::equalQuery($field, $params);
        // paginate()方法用于根据url中的参数，计算查询要查询的开始位置和查询数量
        list($start, $count) = paginate();
        // 应用条件查询
        $courierOrderList = self::where($query);
        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $courierOrderList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果
        $orderList = $courierOrderList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        // 组装返回结果
        foreach ($orderList as $item) {
            $item->comp = config('logistics.comp')[$item->comp];
        }
        $result = [
            'collection' => $orderList,
            'total_nums' => $totalNums
        ];

        return $result;
    }

}
