<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-04 15:00:04
 */

namespace App\Admin\Controllers;

use App\Models\Classes;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ClassController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('课程管理');
            //小标题
            $content->description('课程管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes']
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

            $content->header('课程管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes'],
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

            $content->header('课程管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Classes::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("name","课程名字");
            $grid->column("start_time","上课时间");
            $grid->column("created_at","created_at");


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
        return Admin::form(Classes::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('shop_id',"关联店铺id")->rules("required|integer");
            $form->text('name',"课程名字")->rules("required|string");
            $form->text('info',"课程信息")->rules("required|string");
            $form->text('desc', '课程简介')->rules("required|string");
            $form->text('pic',"课程图片")->rules("required|string");
            $form->datetime('start_time',"上课时间");
            $form->datetime('end_time',"下课时间");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");


        });
    }
}