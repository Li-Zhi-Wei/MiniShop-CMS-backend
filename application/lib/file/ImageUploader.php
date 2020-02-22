<?php


namespace app\lib\file;


use app\api\model\Image;
use app\lib\exception\file\FileException;
use LinCmsTp\File;
use think\facade\Config;
use think\facade\Env;

class ImageUploader extends File
{
    public function upload()
    {
        $ret = [];
        $this->storeDir = 'images';
        $host = Config::get('setting.img_prefix');
        foreach ($this->files as $key => $file) {

            $info = $file->move(Env::get('root_path') . '/' . 'public' . '/' . $this->storeDir);
            if ($info) {
                $path = str_replace('\\', '/', $info->getSaveName());
            } else {
                throw new FileException([
                    'msg' => $this->getError,
                    'error_code' => 60001
                ]);
            }

            $image = Image::create([
                'url' => '/' . $path,
                'from' => 1,
            ]);
            array_push($ret, [
                'id' => $image->id,
                'url' => $host . '/' . $path
            ]);
        }
        return $ret;
    }
}
