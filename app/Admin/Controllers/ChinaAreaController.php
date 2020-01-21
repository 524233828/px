<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-21 16:07:31
 */

namespace App\Admin\Controllers;

use App\Models\ChinaArea;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ChinaAreaController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('地区管理');
            //小标题
            $content->description('地区管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '地区管理', 'url' => '/area']
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

            $content->header('地区管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '地区管理', 'url' => '/area'],
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

            $content->header('地区管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '地区管理', 'url' => '/area'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(ChinaArea::class, function (Grid $grid) {

            $grid->column("id","ID");
            $grid->column("code","地区码");
            $grid->column("name","地区名称");
            $grid->column("created_at","created_at");
            $grid->column("updated_at","updated_at");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){



            });


        });
    }

    protected function form()
    {
        return Admin::form(ChinaArea::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('parent_id',"父类ID")->rules("required|integer");
            $form->text('code',"地区码")->rules("required|string");
            $form->text('name',"地区名称")->rules("required|string");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");


        });
    }
}