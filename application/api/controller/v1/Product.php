<?php


namespace app\api\controller\v1;


use app\lib\exception\product\ProductException;
use app\api\model\Product as ProductModel;
use app\api\model\ProductImage as ProductImageModel;
use app\api\model\ProductProperty as ProductPropertyModel;
use app\api\model\Sku as SkuModel;
use think\facade\Hook;
use think\facade\Request;

class Product
{
    /**
     * 查询所有商品，分页,可模糊搜索product_name
     * @param('page','查询页码','require|number')
     * @param('count','单页查询数量','require|number|between:1,15')
     */
    public function getProductsPaginate()
    {
        $params = Request::get();
        $products = ProductModel::getProductsPaginate($params);
        if ($products['total_nums'] === 0) {
            throw new ProductException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'error_code' => '70006'
            ]);
        }
        return $products;
    }

    /**
     * 查询所有可用商品，用于给前端某些功能的选项列表使用
     */
    public function getProducts()
    {
        $products = ProductModel::where('status', 1)->select();
        if ($products->isEmpty()) {
            throw new ProductException([
                'code' => 404,
                'msg' => '未查询到相关商品'
            ]);
        }
        return $products;
    }

    /**
     * 商品上架/下架
     * @auth('商品上架/下架','商品管理')
     * @param('id','商品id','require|number')
     */
    public function modifyStatus($id)
    {
        $product = ProductModel::get($id);
        if (!$product) {
            throw new ProductException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'error_code' => '70006'
            ]);
        }
        $product->status = !$product->status;
        $product->save();
        return writeJson(201, [], '状态已经修改');
    }

    /**
     * 新增商品
     * @auth('新增商品','商品管理')
     * @validate('ProductForm')
     */
    public function addProduct()
    {
        $params = Request::post();
        $params['main_img_url'] = explode(config('setting.img_prefix'), $params['main_img_url'])[1];
        $product = ProductModel::create($params, true);
        if (!$product) {
            throw new ProductException([
                'msg' => '商品创建失败'
            ]);
        }
        $product->image()->saveAll($params['image']);
        $product->property()->saveAll($params['property']);
        $product->sku()->saveAll($params['sku']);
        return writeJson(201, [], '商品新增成功');
    }

    /**
     * 删除商品
     * @auth('删除商品','商品管理')
     * @param('ids','待删除的商品id列表','require|array|min:1')
     */
    public function delProduct()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {
            // get()方法第二个参数传入关联模型的方法名实现关联查询
            $product = ProductModel::get($id, 'image,property,sku');
            // 如果product存在，做关联删除
            if ($product){
                // 在delete()之前调用together()并传入关联模型方法名实现关联删除
                $product->together('image,property,sku')->delete();
            }
        }, $ids);
        Hook::listen('logger', '删除了id为' . implode(',', $ids) . '的商品');
        return writeJson(201, [], '商品删除成功');
    }

    /**
     * 更新商品基础信息
     * @auth('编辑商品','商品管理')
     * @validate('ProductForm.edit')
     */
    public function updateProduct()
    {
        $params = Request::put();
        $params['main_img_url'] = explode(config('setting.img_prefix'), $params['main_img_url'])[1];
        ProductModel::update($params, [], true);
        return writeJson(201, '商品信息更新成功');
    }

    /**
     * 添加商品详情图
     * @auth('编辑商品','商品管理')
     * @validate('ProductImageForm')
     */
    public function addProductImage()
    {
        $params = Request::post('image');
        (new ProductImageModel())->saveAll($params);

        return writeJson(201, '商品详情图新增成功');
    }

    /**
     * 编辑商品详情图
     * @auth('编辑商品','商品管理')
     * @validate('ProductImageForm.edit')
     * @param('image','商品详情图数组','require|array|min:1')
     */
    public function updateProductImage()
    {
        $params = Request::put('image');
        (new ProductImageModel())->saveAll($params);

        return writeJson(201, '商品详情图编辑成功');
    }

    /**
     * 删除商品详情图
     * @auth('编辑商品','商品管理')
     * @param('ids','待删除的商品详情图id列表','require|array|min:1')
     */
    public function delProductImage()
    {
        $ids = Request::delete('ids');
        ProductImageModel::destroy($ids);
        return writeJson(201, '商品详情图删除成功');
    }

    /**
     * 添加商品的商品属性
     * @auth('编辑商品','商品管理')
     * @validate('ProductPropertyForm')
     */
    public function addProductProperty()
    {
        $params = Request::post('property');
        (new ProductPropertyModel())->saveAll($params);

        return writeJson(201, '商品属性新增成功');
    }

    /**
     * 编辑商品属性
     * @auth('编辑商品','商品管理')
     * @validate('ProductPropertyForm.edit')
     */
    public function updateProductProperty()
    {
        $params = Request::put('property');
        (new ProductPropertyModel())->saveAll($params);

        return writeJson(201, '商品属性编辑成功');
    }

    /**
     * 删除商品属性
     * @auth('编辑商品','商品管理')
     * @param('ids','待删除的商品属性id列表','require|array|min:1')
     */
    public function delProductProperty()
    {
        $ids = Request::delete('ids');
        ProductPropertyModel::destroy($ids);
        return writeJson(201, '商品属性删除成功');
    }

    /**
     * 添加套餐
     * @auth('编辑商品','商品管理')
     * @validate('SkuForm')
     */
    public function addSku()
    {
        $params = Request::post('sku');
        (new SkuModel())->saveAll($params);

        return writeJson(201, '套餐新增成功');
    }

    /**
     * 编辑商品套餐
     * @auth('编辑商品','商品管理')
     * @validate('SkuForm.edit')
     */
    public function updateSku()
    {
        $params = Request::put('sku');
        (new SkuModel())->saveAll($params);

        return writeJson(201, '套餐编辑成功');
    }

    /**
     * 删除套餐
     * @auth('编辑商品','商品管理')
     * @param('ids','待删除的套餐id列表','require|array|min:1')
     */
    public function delSku()
    {
        $ids = Request::delete('ids');
        SkuModel::destroy($ids);
        return writeJson(201, '商品属性删除成功');
    }
}
