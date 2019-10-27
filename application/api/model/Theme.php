<?php


namespace app\api\model;


use think\Db;
use think\Exception;
use think\model\concern\SoftDelete;

class Theme extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['topic_img_id', 'head_img_id','delete_time','update_time'];

    public function products()
    {
        // 等价于 return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
        return $this->belongsToMany('Product')->where('status', '=', 1);
    }

    public function topicImg()
    {
        // 等同于$this->belongsTo('Image', 'topic_img_id','id')
        return $this->belongsTo('Image', 'topic_img_id');
    }

    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id');
    }

    /**
     * @param $ids
     * @return bool
     */
    public static function delTheme($ids)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 对theme表记录做软删除
            self::destroy($ids);
            // 删除中间表中对应主题id的记录,注意这里是执行硬删除
            foreach ($ids as $id) {
                // 条件查询，theme_id字段等于$id的记录
                ThemeProduct::where('theme_id', $id)->delete();
            }
            Db::commit();
            return true;
        } catch (Exception $ex) {
            Db::rollback();
            return false;
        }
    }
}
