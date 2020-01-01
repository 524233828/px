<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-01 16:28:15
 */

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BannerController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('首页banner管理');
            //小标题
            $content->description('首页banner管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '首页banner管理', 'url' => '/banners']
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

            $content->header('首页banner管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '首页banner管理', 'url' => '/banners'],
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

            $content->header('首页banner管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '首页banner管理', 'url' => '/banners'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Banner::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("name","name");
            $grid->column("img_url","img_url");
            $grid->column("created_at","created_at")->sortable();
            $grid->column("updated_at","updated_at");
            $grid->column("status","status")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('name', 'like', "{$this->input}%");
                }, 'name');


            });


        });
    }

    protected function form()
    {
        return Admin::form(Banner::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('name',"name")->rules("required|string");
            $form->image("img_url", "img_url");
//            $form->text('img_url',"img_url")->rules("required|string");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");
            $form->text('link',"link")->rules("required|string");
            $form->select("status","status")->options([0=>"冻结",1=>"启用"]);



        });
    }
}