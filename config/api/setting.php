<?php

use think\facade\Env;

return [
    //图片路径
    'img_prefix' => 'http://z.cn/images',
//    'img_prefix' => '',
    // 图片上传时储存路径
    'upload_img_dir' => Env::get('root_path') . '../server/public/images',
];
