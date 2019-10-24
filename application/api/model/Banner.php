<?php


namespace app\api\model;


use think\Db;
use think\Exception;

class Banner extends BaseModel
{
    public function items()
    {
        // 调用了模型实例的hasMany（）方法，这个方法定义了当前模型与被关联模型BannerItem是一种一对多的关系
        // 关联的内容是BannerItem模型里banner_id属性的值与当前模型的id属性的值一致的记录。
        // 返回的是bannerItem对象
        return $this->hasMany('BannerItem','banner_id', 'id');
    }

    public static function add($params)
    {
        // 启动事务
        Db::startTrans();
        try {
            // 调用当前模型的静态方法create()，第一个参数为要写入的数据，第二个参数标识仅写入数据表定义的字段数据
            $banner = self::create($params, true);
            // 调用关联模型实现关联写入
            $banner->items()->saveAll($params['items']);
            // 提交事务
            Db::commit();
        } catch (Exception $ex) {
            // 回滚事务
            Db::rollback();
            throw new BannerException([
                'msg' => '新增轮播图失败',
                'error_code' => 70001
            ]);
        }
    }
}
