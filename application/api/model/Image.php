<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Image extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time','from','update_time'];
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
}
