<?php


namespace app\api\validate\category;


use LinCmsTp5\validate\BaseValidate;

class CategoryForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chsDash',
        'description' => 'require|chsDash',
        'topic_img_id' => 'require|number',
    ];

    public function sceneEdit()
    {
        return $this->append('id', 'require|number')
            ->remove('description', 'require');
    }
}
