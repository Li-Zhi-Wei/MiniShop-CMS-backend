<?php


namespace app\api\validate\Product;


use LinCmsTp5\validate\BaseValidate;

class ProductImageForm extends BaseValidate
{
    protected $rule = [
        'image' => 'require|array|min:1|productImage',
    ];

    public function sceneEdit()
    {
        return $this->remove('image', 'productImage')
            ->append('image', 'requireId');
    }

    protected function productImage($value)
    {
        foreach ($value as $v) {
            if (!isset($v['product_id']) || empty($v['product_id'])) {
                return '商品详情图所属商品id不能为空';
            }

            if (!isset($v['img_id']) || empty($v['img_id'])) {
                return '商品详情图不能为空';
            }
        }
        return true;
    }

    protected function requireId($value)
    {
        foreach ($value as $v) {
            if (!isset($v['id']) || empty($v['id'])) {
                return '商品详情图主键id不能为空';
            }

            if (!isset($v['product_id']) || empty($v['product_id'])) {
                return '商品详情图所属商品id不能为空';
            }

            if (!isset($v['img_id']) || empty($v['img_id'])) {
                return '商品详情图不能为空';
            }
        }
        return true;
    }
}
