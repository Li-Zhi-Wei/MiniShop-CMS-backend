<?php


namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['delete_time', 'product_id'];

    public function img()
    {
        return $this->belongsTo('Image', 'img_id');
    }
}
