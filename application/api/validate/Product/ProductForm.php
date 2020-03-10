<?php


namespace app\api\validate\Product;


use LinCmsTp5\validate\BaseValidate;

class ProductForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'category_id' => 'number',
        'img_id' => 'require|number',
        'main_img_url' => 'require|url',
        'price' => 'require|float',
        'stock' => 'require|number',
        'image' => 'array|productImage',
        'property' => 'array|productProperty',
        'postage' => 'number',
        'status' => 'number',
        'sku' => 'array|sku',
    ];

    // 场景声明
    public function sceneEdit()
    {
        return $this->append('id', ['require', 'number']);
    }

    protected function productImage($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['img_id']) || empty($v['img_id'])) {
                    return '商品详情图不能为空';
                }
            }
        }
        return true;
    }

    protected function productProperty($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['name']) || empty($v['name'])) {
                    return '商品属性名称不能为空';
                }
                if (!isset($v['detail']) || empty($v['detail'])) {
                    return '商品属性' . $v['name'] . '的详情不能为空';
                }
            }
        }
        return true;
    }

    protected function sku($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['name']) || empty($v['name'])) {
                    return '商品套餐名称不能为空';
                }
                if (!isset($v['price']) || empty($v['price'])) {
                    return '商品套餐' . $v['name'] . '的价格不能为空';
                }
                if (!isset($v['stock']) || empty($v['stock'])) {
                    return '商品套餐' . $v['name'] . '的库存不能为空';
                }
                if (!isset($v['postage']) || empty($v['postage'])) {
                    return '商品套餐' . $v['name'] . '的运费不能为空';
                }
                if (!isset($v['status']) || empty($v['status'])) {
                    return '商品套餐' . $v['name'] . '的状态不能为空';
                }
                if (!isset($v['img_id']) || empty($v['img_id'])) {
                    return '商品套餐' . $v['name'] . '的图片不能为空';
                }
                if (isset($v['sale']) || !empty($v['sale'])) {
                    return '商品套餐' . $v['name'] . '不能设置销量';
                }
            }
        }
        return true;
    }
}
