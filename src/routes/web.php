<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/Laravel/route'], function () {
    Route::group(['prefix' => '/test'], function () {
        Route::get('/http', function () {
            \Illuminate\Support\Facades\Log::info('test laravel tars log');
           // app()->call(testController::class,[],'index');
            return app('service.demo')->ping() . ':接入Laravel Router成功啦,配置:' . json_encode(config('foo')) . ',入参:' .
                json_encode(app('request')->all());
        });
        /*公共的服务start*/
        //图片上传
        Route::any('/upload', function() {
            return app('common')->getToken();
        });
        /*公共的服务end*/
        //添加试用版店铺信息
        Route::any('/addTrialShop', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->addTrialShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //正式订购-创建店铺流程
        Route::any('/addFormalShop', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->addFormalShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //商家添加子店铺
        Route::any('/addSubshop', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->addSubshop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //子店铺完善资料 查询多店铺的行业分类
        Route::any('/shopSubList', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->shopSubList();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });

        //查询用户存在几个店铺
        Route::any('/userShop', function() {
            return (new \App\Tars\impl\ShopHttp())->userShop();
        });
        //查询多店版下面有几个店铺
        Route::any('/userShopMany', function() {
            return (new \App\Tars\impl\ShopHttp())->userShopMany();
        });
        //查询某个店铺信息
        Route::any('/selectShop', function() {
            return (new \App\Tars\impl\ShopHttp())->selectShop();
        });
        //商家后台设置店铺信息
        Route::any('/shopInfo', function() {
            return (new \App\Tars\impl\ShopHttp())->shopInfo();
        });
        //商家后台设置店铺信息
        Route::any('/shopInfoUpdate', function() {
            return (new \App\Tars\impl\ShopHttp())->shopInfoUpdate();
        });
        //商家后台试用版店铺转正为正式版店铺
        Route::any('/shopTurnJust', function() {
            return (new \App\Tars\impl\ShopHttp())->shopTurnJust();
        });
        //完善店铺信息
        Route::any('/perfectShop', function() {
            try {
                $impl =(new  \App\Tars\impl\ShopHttp());
                return $impl->perfectShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //绑定支付商户号
        Route::any('/merchant', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->merchant();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //绑定微信公众号回调
        Route::any('/callback', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->callback();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //更换域名
        Route::any('/replace', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->replace();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //商家的公众号店铺配置
        Route::any('/appConfig', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->app_config();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //店铺设置手续费
        Route::any('/shopRate', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->shopRate();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });

        //查询平台设置的分类
        Route::any('/platformType', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->platformType();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //查询平台客户列表
        Route::any('/platSelectShop', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->platSelectShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //查询某家店铺得详细信息
        Route::any('/platSelectShopOne', function() {
            try {
                $impl =(new  \App\Tars\impl\ShopHttp());
                return $impl->platSelectShopOne();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //平台确定审核，客户经理对接
        Route::any('/platDockingShop', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->platDockingShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //平台的店铺管理列表
        Route::any('/platShoplist', function() {
            try {
                $impl = (new  \App\Tars\impl\ShopHttp());
                return $impl->platShoplist();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //平台店铺状态总数
        Route::any('shopStatus',function(){
            return (new \App\Tars\impl\ShopHttp())->shopStatus();
        });
        //平台的客户列表店铺列表
        Route::any('platUserShopList',function(){
            return (new \App\Tars\impl\ShopHttp())->platUserShopList();
        });
        //平台查询审核店铺状态
        Route::any('platAuditStatus',function(){
            return (new \App\Tars\impl\ShopHttp())->platAuditStatus();
        });
        //平台进行审核店铺状态修改
        Route::any('platChangeStatus',function(){
            return (new \App\Tars\impl\ShopHttp())->platChangeStatus();
        });

        //平台设置店铺开店的有效期
        Route::any('platSettingTime',function(){
            return (new \App\Tars\impl\ShopHttp())->platSettingTime();
        });

        //邦邦店铺菜单账号权限详情添加删除查询
        Route::any('platMenuList',function(){
            return (new \App\Tars\impl\ShopHttp())->platMenuList();
        });

        //切换店铺 (该店是单店还是多店铺)
        Route::any('/switchShop', function() {
            try {
                $impl =(new  \App\Tars\impl\ShopHttp());
                return $impl->switchShop();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        });
        //查询用户存在几个店铺
        Route::any('/generate', function() {
            return (new \App\Tars\impl\ShopHttp())->generate();
        });
        //查询搜索商家订单列表
        Route::any('/orderList', function() {
            return (new \App\Tars\impl\ShopHttp())->orderList();
        });
        //查询搜索商家订单列表
        Route::any('/OrderInfo', function() {
            return (new \App\Tars\impl\ShopHttp())->OrderInfo();
        });
        // 分页查询
        Route::any('/shopList', function() {
            return (new \App\Tars\impl\ShopHttp())->shopList();
        });
        // 分页查询
        Route::any('/forward', function() {
            return (new \App\Tars\impl\ShopHttp())->forward();
        });
        //查询银行地址
        Route::any('banksVague',function(){
            return (new \App\Tars\impl\ShopHttp())->banksVague();
        });

        //添加客服
        Route::any('addcustomer',function(){
            return (new \App\Tars\impl\ShopHttp())->addCustomerService();
        });
        //查询客服
        Route::any('selectcustomer',function(){
            return (new \App\Tars\impl\ShopHttp())->selectCustomerService();
        });

        //手机端获取店铺信息数据start
        //手机端获取
        Route::any('phoneSelectShop',function(){
            return (new \App\Tars\impl\ShopPhoneHttp())->phoneSelectShop();
        });
        //手机端获取店铺信息数据end
        //公共接口start
        //打烊店铺
        Route::any('closeShop',function(){
            return (new \App\Tars\impl\ShopHttp())->commonCloseShop();
        });
        Route::any('platUpload',function(){
            return (new \App\Tars\impl\ShopHttp())->platUpload();
        });
        //公共接口end
    });
});
