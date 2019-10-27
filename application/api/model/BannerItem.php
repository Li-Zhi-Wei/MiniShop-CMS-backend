<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;
    public function img()
    {

        // 调用了模型实例的belongsTo()方法，这个方法定义了当前模型与被关联模型Image是一种相对关系
        // 关联的内容是BannerItem模型里img_id属性的值与Image模型的id属性的值一致的记录。
        return $this->belongsTo('Image','img_id');
    }
}
