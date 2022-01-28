<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-05 14:47:46
 */

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class GoodsController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('商品管理');
            //小标题
            $content->description('商品管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '商品管理', 'url' => 'goods']
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

            $content->header('商品管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '商品管理', 'url' => 'goods'],
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

            $content->header('商品管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '商品管理', 'url' => 'goods'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Goods::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("name","商品名称");
            $grid->column("img_url","商品图片")->image();
            $grid->column("created_at","created_at")->sortable();
            $grid->column("updated_at","updated_at")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('name', 'like', "{$this->input}%");
                }, '商品名称');


            });


        });
    }

    protected function form()
    {
        return Admin::form(Goods::class, function (Form $form) {

            $form->text('id',"id")->rules("required|integer");
            $form->text('name',"商品名称")->rules("required|string");
            $form->text('price',"商品价格")->rules("required");
            $form->image('img_url',"上传图片");
            $form->text("intro", "商品简介");
            $form->editor("desc", "商品详情");
//            $form->datetime('created_at',"created_at");
//            $form->datetime('updated_at',"updated_at");


        });
    }
}
