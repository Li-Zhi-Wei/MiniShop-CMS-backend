<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;
//顶层分组名为空，表示'/'
Route::group('', function () {
    Route::group('cms', function () {
        // 账户相关接口分组
        Route::group('user', function () {
            // 登陆接口
            Route::post('login', 'api/cms.User/login');
            // 刷新令牌
            Route::get('refresh', 'api/cms.User/refresh');
            // 查询自己拥有的权限
            Route::get('auths', 'api/cms.User/getAllowedApis');
            // 注册一个用户
            Route::post('register', 'api/cms.User/register');
            // 更新头像
            Route::put('avatar','api/cms.User/setAvatar');
            // 查询自己信息
            Route::get('information','api/cms.User/getInformation');
            // 用户更新信息
            Route::put('','api/cms.User/update');
            // 修改自己密码
            Route::put('change_password','api/cms.User/changePassword');
        });
        // 管理类接口
        Route::group('admin', function () {
            // 查询所有权限组
            Route::get('group/all', 'api/cms.Admin/getGroupAll');
            // 查询一个权限组及其权限
            Route::get('group/:id', 'api/cms.Admin/getGroup');
            // 删除一个权限组
            Route::delete('group/:id', 'api/cms.Admin/deleteGroup');
            // 更新一个权限组
            Route::put('group/:id', 'api/cms.Admin/updateGroup');
            // 新建权限组
            Route::post('group', 'api/cms.Admin/createGroup');
            // 查询所有可分配的权限
            Route::get('authority', 'api/cms.Admin/authority');
            // 删除多个权限
            Route::post('remove', 'api/cms.Admin/removeAuths');
            // 添加多个权限
            Route::post('/dispatch/patch', 'api/cms.Admin/dispatchAuths');
            // 查询所有用户
            Route::get('users', 'api/cms.Admin/getAdminUsers');
            // 修改用户密码
            Route::put('password/:uid', 'api/cms.Admin/changeUserPassword');
            // 删除用户
            Route::delete(':uid', 'api/cms.Admin/deleteUser');
            // 更新用户信息
            Route::put(':uid', 'api/cms.Admin/updateUser');

        });
        // 日志类接口
        Route::group('log',function (){
            Route::get('', 'api/cms.Log/getLogs');
            Route::get('users', 'api/cms.Log/getUsers');
            Route::get('search', 'api/cms.Log/getUserLogs');
        });
        //上传文件类接口
        Route::post('file','api/cms.File/postFile');
        // 自定义图片上传接口
        Route::post('file/image', 'api/cms.File/postCustomImage');
    });
    Route::group('v1', function () {
        // 轮播图
        Route::group('banner',function (){
            // 查询所有轮播图
            Route::get('','api/v1.Banner/getBanners');
            // 新增轮播图
            Route::post('','api/v1.Banner/addBanner');
            // 删除轮播图
            Route::delete('','api/v1.Banner/delBanner');
            // 编辑轮播图主体信息
            Route::patch(':id','api/v1.Banner/editBannerInfo');
            // 新增轮播图元素
            Route::post('item','api/v1.Banner/addBannerItem');
            // 编辑轮播图元素
            Route::put('item','api/v1.Banner/editBannerItem');
            // 删除轮播图元素
            Route::delete('item','api/v1.Banner/delBannerItem');
        });
        //主题
        Route::group('theme',function(){
            //查询主题列表
            Route::get('','api/v1.Theme/getSimpleList');
            //查询主题详情
            Route::get(':id','api/v1.Theme/getThemeById');
            //新增主题
            Route::post('','api/v1.Theme/addTheme');
            //删除主题
            Route::delete('','api/v1.Theme/delTheme');
            // 编辑精选主题信息
            Route::patch(':id','api/v1.Theme/updateThemeInfo');
            // 移除精选主题关联商品
            Route::delete('product/:id','api/v1.Theme/removeThemeProduct');
            // 新增精选主题关联商品
            Route::post('product/:id','api/v1.Theme/addThemeProduct');
        });
        // 分类相关接口
        Route::group('category', function () {
            // 查询所有分类
            Route::get('', 'api/v1.Category/getCategory');
            // 新增分类
            Route::post('', 'api/v1.Category/addCategory');
            // 编辑商品分类
            Route::put(':id', 'api/v1.Category/updateCategory');
            // 删除分类
            Route::delete('', 'api/v1.Category/delCategory');
        });
        // 商品管理相关接口
        Route::group('product', function () {
            // 分页查询所有商品
            Route::get('paginate', 'api/v1.Product/getProductsPaginate');
            // 查询所有可用商品
            Route::get('', 'api/v1.Product/getProducts');
            // 商品上架/下架
            Route::patch(':id','api/v1.Product/modifyStatus');
            // 新增商品
            Route::post('','api/v1.Product/addProduct');
            // 删除商品
            Route::delete('','api/v1.Product/delProduct');
            // 更新商品基础信息
            Route::put('','api/v1.Product/updateProduct');
            // 新增商品详情图
            Route::post('image','api/v1.Product/addProductImage');
            // 编辑商品详情图
            Route::put('image','api/v1.Product/updateProductImage');
            // 删除商品详情图
            Route::delete('image','api/v1.Product/delProductImage');
            // 新增商品属性
            Route::post('property','api/v1.Product/addProductProperty');
            // 编辑商品属性
            Route::put('property','api/v1.Product/updateProductProperty');
            // 删除商品属性
            Route::delete('property','api/v1.Product/delProductProperty');
            // 新增商品套餐
            Route::post('sku','api/v1.Product/addSku');
            // 编辑商品套餐
            Route::put('sku','api/v1.Product/updateSku');
            // 删除商品套餐
            Route::delete('sku','api/v1.Product/delSku');
        });
        Route::group('order', function () {
            // 分页查询所有订单
            Route::get('', 'api/v1.Order/getOrders');
            // 订单发货
            Route::post('shipment/:id', 'api/v1.Order/deliverGoods');
            // 查询订单支付状态
            Route::get('pay/:orderNo', 'api/v1.Order/getOrderPayStatus');
            // 订单退款
            Route::post('pay/refund','api/v1.Order/refund');
            // 查询退款详情
            Route::get('pay/refund/:orderNo','api/v1.Order/refundQuery');
            // 查询发货记录
            Route::get('shipment/record', 'api/v1.Order/getOrderDeliverRecord');
            // 关闭订单
            Route::put('close/:id', 'api/v1.Order/close');
            // 修改状态为已支付
            Route::put('paid/:id', 'api/v1.Order/changeToPaid');
        });
        // 物流管理相关接口
        Route::group('logistics', function () {
            // 分页查询所有订单
            Route::get(':orderNo', 'api/v1.Logistics/getLogistics');
        });
        // 会员管理相关接口
        Route::group('user', function () {
            // 查询会员列表
            Route::get('', 'api/v1.User/getUsersPaginate');
        });
        // 统计分析相关接口
        Route::group('analysis', function () {
            // 时间范围统计订单数据
            Route::get('order/base', 'api/v1.Statistics/getOrderBaseStatistics');
            // 时间范围统计新增会员数
            Route::get('user/base', 'api/v1.Statistics/getUserBaseStatistics');
        });
        Route::group('config', function (){
            // 查询设置
            Route::get('', 'api/v1.Config/getConfig');
            // 设置全场包邮条件
            Route::put('postage', 'api/v1.Config/setPostage');
            // 设置全场包邮状态开关
            Route::put('postageFlag', 'api/v1.Config/modifyPostageFlag');
            // 设置店铺开关状态
            Route::put('shopStatus', 'api/v1.Config/modifyShopStatus');
            // 设置cms首页提示对话框的显示开关
            Route::put('showDialog', 'api/v1.Config/modifyAboutDialog');
        });

    });
})->middleware(['Auth','ReflexValidate'])->allowCrossDomain();

