<?php


namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\model\BannerItem as BannerItemModel;
use app\lib\exception\banner\BannerException;
use think\facade\Hook;
use think\facade\Request;

class Banner
{
    public function getBanners()
    {
        $result = BannerModel::with('items.img')->select();
        return $result;
    }

    /**
     * 新增轮播图接口
     * @validate('BannerForm') //注解验证器
     * @return \think\response\Json
     */
    public function addBanner()
    {
        $params = Request::post();
        BannerModel::add($params);
        return writeJson(201,[],'新增成功！');
    }

    /**
     * @auth('删除轮播图','轮播图管理') //权限控制
     * @param('ids','待删除的轮播图id列表','require|array|min:1') //注解验证器
     * @return \think\response\Json
     */
    public function delBanner()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {
            // 查询指定id的轮播图记录
            $banner = BannerModel::get($id,'items');
            // 指定id的轮播图不存在则抛异常
            if (!$banner) throw new BannerException(['msg' => 'id为' . $id . '的轮播图不存在']);
            // 执行关联删除
            $banner->together('items')->delete();
        }, $ids);
        // 记录本次行为的日志
        Hook::listen('logger', '删除了id为' . implode(',', $ids) . '的轮播图');
        return writeJson(201, [], '轮播图删除成功！');
    }

    /**
     * 编辑轮播图基础信息
     * @param $id
     * @param('id','轮播图id','require|number')
     * @param('name','轮播图名称','require')
     */
    public function editBannerInfo($id)
    {
        $bannerInfo = Request::patch();
        $banner = BannerModel::get($id);
        if (!$banner) throw new BannerException(['msg' => 'id为' . $id . '的轮播图不存在']);
        $banner->save($bannerInfo);
        return writeJson(201, [], '轮播图基础信息更新成功！');
    }

    /**
     * 新增轮播图元素
     * @validate('BannerItemForm.add')
     */
    public function addBannerItem()
    {
        $data = Request::post('items');
        foreach ($data as $key => $value) {
            BannerItemModel::create($value);
        }
        return writeJson(201, [], '新增轮播图元素成功！');
    }

    /**
     * 编辑轮播图元素
     * @validate('BannerItemForm.edit')
     */
    public function editBannerItem()
    {
        $data = Request::put('items');

        $bannerItem = new BannerItemModel;
        # allowField(true)表示只允许写入数据表中存在的字段。
        # saveAll()接收一个数组，用于批量更新或者新增。通过判断传入的数组中是否设置了id属性，如果有则视为更新，无则视为新增
        $bannerItem->allowField(true)->saveAll($data);
        return writeJson(201, [], '编辑轮播图元素成功！');
    }

    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     */
    public function delBannerItem()
    {
        $ids = Request::delete('ids');
        // 传入多个id组成的数组进行批量删除
        BannerItemModel::destroy($ids);
        return writeJson(201, [], '轮播图元素删除成功！');
    }
}
