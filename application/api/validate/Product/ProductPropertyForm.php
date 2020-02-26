<?php


namespace app\api\validate\Product;


use LinCmsTp5\validate\BaseValidate;

class ProductPropertyForm extends BaseValidate
{
    protected $rule = [
        'property' => 'require|array|min:1|productProperty',
    ];

    public function sceneEdit()
    {
        return $this->remove('property', 'productProperty')
            ->append('property', 'requireId');
    }

    protected function productProperty($value)
    {
        if (!empty($value)) {
            foreach ($value as $v) {
                if (!isset($v['product_id']) || empty($v['product_id'])) {
                    return '商品属性所属商品id不能为空';
                }

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

    protected function requireId($value)
    {
        foreach ($value as $v) {
            if (!isset($v['id']) || empty($v['id'])) {
                return '商品属性主键id不能为空';
            }
            if (!isset($v['name']) || empty($v['name'])) {
                return '商品属性名称不能为空';
            }
            if (!isset($v['detail']) || empty($v['detail'])) {
                return '商品属性' . $v['name'] . '的详情不能为空';
            }
        }
        return true;
    }
}
