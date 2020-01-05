<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-05 18:01:02
 */

namespace App\Admin\Controllers;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
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

            $grid->column("id","id")->sortable();
            $grid->column("name","商店名称");
            $grid->column("status","status")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Shop::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('admin_id',"所属商户")->rules("required|integer");
            $form->text('name',"商店名称")->rules("required|string");
            $form->text('desc',"简介")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->image('headimg_url',"店铺头图")->move("shops/images");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);

            $form->latlong('latitude', 'longitude', '位置');
//            $form->text('latitude',"纬度")->rules("required|string");
//            $form->text('longitude',"经度")->rules("required|string");

        });
    }
}