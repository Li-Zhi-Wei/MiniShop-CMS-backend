<?php


namespace app\api\controller\v1;

use app\api\model\Config as ConfigModel;
use think\facade\Request;

class Config
{
    /**
     * 获取全局设置
     */
    public function getConfig() {
        $config = ConfigModel::all();
        $result = [
            'postage' => $config[0]->detail, // 全局包邮条件
            'postageFlag' => $config[1]->detail, // 全局包邮开关
            'showDialog' => $config[2]->detail, // 首页显示版本提示对话框
            'shopStatus' => $config[3]->detail, // 店铺开关
        ];
        return $result;
    }

    /**
     * 设置全场包邮条件
     * @auth('店铺设置','店铺管理')
     * @param('postage','全场包邮条件','require|number')
     */
    public function setPostage() {
        $params = Request::put();
        $config = ConfigModel::get(1);
        $config->detail = $params['postage'];
        $config->save();
        return writeJson(201, [], '全场包邮条件已经修改');
    }

    /**
     * 设置全场包邮状态开关
     * @auth('店铺设置','店铺管理')
     */
    public function modifyPostageFlag() {
        $config = ConfigModel::get(2);
        $config->detail = !$config->detail;
        $config->save();
        return writeJson(201, [], '全场包邮状态已经修改');
    }

    /**
     * 设置店铺的开、关状态
     * @auth('店铺设置','店铺管理')
     */
    public function modifyShopStatus() {
        $config = ConfigModel::get(4);
        $config->detail = !$config->detail;
        $config->save();
        return writeJson(201, [], '店铺状态已经修改');
    }

    /**
     * 设置首页说明页面的显示
     * @auth('店铺设置','店铺管理')
     */
    public function modifyAboutDialog() {
        $config = ConfigModel::get(3);
        $config->detail = !$config->detail;
        $config->save();
        return writeJson(201, [], '首页说明对话框展示状态已经修改');
    }
}
