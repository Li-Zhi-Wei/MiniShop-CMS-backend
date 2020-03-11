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
        $host = Config::get('setting.img_prefix');
        $storageDir = config('setting.upload_img_dir');
        foreach ($this->files as $key => $file) {
            $md5 = $this->generateMd5($file);
            $exists = Image::get(['md5' => $md5]);
            if ($exists) {
                array_push($ret, [
                    'id' => $exists['id'],
                    'url' => $exists['url']
                ]);
            } else {
                $size = $this->getSize($file);
                $info = $file->move($storageDir);
                if ($info) {
                    $extension = '.' . $info->getExtension();
                    $name = $info->getFilename();
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
                    'name' => $name,
                    'extension' => $extension,
                    'size' => $size,
                    'md5' => $md5,
                ]);
                array_push($ret, [
                    'id' => $image->id,
                    'url' => $host. '/' . $path
                ]);
            }

        }
        return $ret;
    }
}
