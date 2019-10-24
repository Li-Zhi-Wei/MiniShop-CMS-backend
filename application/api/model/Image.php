<?php


namespace app\api\model;


class Image extends BaseModel
{
    protected $hidden = ['delete_time','from','update_time'];
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
}
