<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'create_time', 'update_time','topic_img_id'];

    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id');
    }
}
