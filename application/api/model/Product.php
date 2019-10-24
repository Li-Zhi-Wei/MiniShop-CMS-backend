<?php


namespace app\api\model;


class Product extends BaseModel
{
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }
}
