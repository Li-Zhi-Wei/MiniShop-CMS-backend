<?php


namespace app\api\model;


use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
    //开启软删除
    use SoftDelete;
    /**
     * 自定义获取器，将图片url补全
     * @param $value
     * @param $data
     * @return string
     */
    protected function prefixImgUrl($value,$data){
        $finalUrl = $value;
        if($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}
