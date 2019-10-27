<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class ProductProperty extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'update_time','product_id'];
}
