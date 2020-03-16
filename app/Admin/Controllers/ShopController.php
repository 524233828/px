<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-05 18:01:02
 */

namespace App\Admin\Controllers;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Latlong\Latlong;
use Encore\Admin\Layout\Content;

class ShopController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('店铺管理');
            //小标题
            $content->description('店铺管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '店铺管理', 'url' => '/shops']
            );

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('店铺管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '店铺管理', 'url' => '/shops'],
                ['text' => '编辑']
            );

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('店铺管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '店铺管理', 'url' => '/shops'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Shop::class, function (Grid $grid) {

            $grid->column("id", "id")->sortable();
            $grid->column("name", "商店名称");
            $grid->column("business.name", "所属商户");
            $grid->column("status", "status")->using([0 => "冻结", 1 => "启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter) {

                $filter->equal("id", "id");


            });


        });
    }

    protected function form()
    {

//        Admin::script(
//            '
//        $("#search-latitudelongitude").on("keyup", null, function (e) {
//            let $this = $(this);
//            let $val = $this.val();
//            $("input[name=\'position\']").val($val)
//        });
//
//        $("#search-latitudelongitude").on("change", null, function (e) {
//            let $this = $(this);
//            let $val = $this.val();
//            $("input[name=\'position\']").val($val)
//        });
//
//        $("select[name=\'province_id\']").on("change", null, function(e) {
//
//            let province_id = "";
//            let city_id = "";
//            let district_id = "";
//            if($("select[name=\'province_id\']").find("option:selected").val() != ""){
//                province_id = $("select[name=\'province_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'city_id\']").find("option:selected").val() != ""){
//                city_id = $("select[name=\'city_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'district_id\']").find("option:selected").val() != ""){
//                district_id = $("select[name=\'district_id\']").find("option:selected").text()
//            }
////            qq.maps.SearchService().search(province_id + city_id + district_id);
//
//            $("#search-latitudelongitude").val(province_id + city_id + district_id);
////            $(".btn.btn-info.btn-flat").trigger("click");
//        });
//
//        $("select[name=\'city_id\']").on("change", null, function(e) {
//
//            let province_id = "";
//            let city_id = "";
//            let district_id = "";
//            if($("select[name=\'province_id\']").find("option:selected").val() != ""){
//                province_id = $("select[name=\'province_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'city_id\']").find("option:selected").val() != ""){
//                city_id = $("select[name=\'city_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'district_id\']").find("option:selected").val() != ""){
//                district_id = $("select[name=\'district_id\']").find("option:selected").text()
//            }
//
//            $("#search-latitudelongitude").val(province_id + city_id + district_id);
////            $(".btn.btn-info.btn-flat").trigger("click");
//        });
//
//        $("select[name=\'district_id\']").on("change", null, function(e) {
//            let province_id = "";
//            let city_id = "";
//            let district_id = "";
//            if($("select[name=\'province_id\']").find("option:selected").val() != ""){
//                province_id = $("select[name=\'province_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'city_id\']").find("option:selected").val() != ""){
//                city_id = $("select[name=\'city_id\']").find("option:selected").text()
//            }
//
//            if($("select[name=\'district_id\']").find("option:selected").val() != ""){
//                district_id = $("select[name=\'district_id\']").find("option:selected").text()
//            }
//
//            $("#search-latitudelongitude").val(province_id + city_id + district_id);
////            $(".btn.btn-info.btn-flat").trigger("click");
//        });
//
//'
//        );
        return Admin::form(Shop::class, function (Form $form) {


            $form->display('id', "id");
            $form->select('admin_id', "所属商户")->options($this->fetchBusiness())->rules("notIn:0");
            $form->text('name', "商店名称")->rules("required|string");
            $form->text('intro', "简介");
            $form->text('tel', "联系电话");
            $form->editor('desc', "详情");
            $form->image('headimg_url', "店铺头图")->move("shops/images");
            $form->multipleImage( 'banner', "店铺轮播图")->move("shops/images");
            $form->select("status", "状态")->options([0 => "冻结", 1 => "启用"])->default(1);
            $form->text("sort", "排序，越大排越前");

            $form->distpicker([
                'province_id' => '省份',
                'city_id' => '市',
                'district_id' => '区'
            ], '地域选择')->default([
                'province' => 440000,
                'city' => 440200,
                'district' => 440203,
            ]);

            $form->text('position', '详细地址');
            /** @var Latlong $map */
            $form->latlong('latitude', 'longitude', '位置')->default(['lat' => 24.8109, 'lng' => 113.5974]);

//            $form->text('latitude',"纬度")->rules("required|string");
//            $form->text('longitude',"经度")->rules("required|string");

        });
    }

    public function fetchBusiness()
    {
        $businesses = Role::query()->find(2)->administrators->toArray();

        $business_list = [0=>"请选择"];
        foreach ($businesses as $business) {
            $business_list[$business['id']] = $business['name'];
        }

        return $business_list;
    }
}