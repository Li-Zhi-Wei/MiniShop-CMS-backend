<?php


namespace app\api\validate\Product;


use LinCmsTp5\validate\BaseValidate;

class SkuForm extends BaseValidate
{
    protected $rule = [
        'sku' => 'require|array|min:1|sku',
    ];

    public function sceneEdit()
    {
        return $this->remove('sku', 'sku')
            ->append('sku', 'requireId');
    }

    protected function sku($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['name']) || empty($v['name'])) {
                    return '商品套餐名称不能为空';
                }
                if (!isset($v['product_id']) || empty($v['product_id'])) {
                    return '商品套餐' . $v['name'] . '所属商品id不能为空';
                }
                if (!isset($v['price']) || empty($v['price'])) {
                    return '商品套餐' . $v['name'] . '的价格不能为空';
                }
                if (!isset($v['stock'])) {
                    return '商品套餐' . $v['name'] . '的库存不能为空';
                }
                if (!isset($v['postage'])) {
                    return '商品套餐' . $v['name'] . '的运费不能为空';
                }
                if (!isset($v['status'])) {
                    return '商品套餐' . $v['name'] . '的状态不能为空';
                }
                if (!isset($v['img_id']) || empty($v['img_id'])) {
                    return '商品套餐' . $v['name'] . '的图片不能为空';
                }
                if (isset($v['sale'])) {
                    return '商品套餐' . $v['name'] . '不能设置销量';
                }
            }
        }
        return true;
    }

    protected function requireId($value)
    {
        foreach ($value as $v) {
            if (!isset($v['id']) || empty($v['id'])) {
                return '商品套餐主键id不能为空';
            }
            if (isset($v['sale'])) {
                return '商品套餐' . $v['name'] . '不能设置销量';
            }
            if (isset($v['product_id'])) {
                return '商品套餐' . $v['name'] . '不能改变所属商品';
            }
        }
        return true;
    }

}
