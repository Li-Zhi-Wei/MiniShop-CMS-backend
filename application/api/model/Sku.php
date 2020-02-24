<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Sku extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'update_time','create_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'img_id');
    }
}
