<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-01-01 11:26:49
 */

namespace JoseChan\App\Admin\Controllers;

use JoseChan\App\DataSet\Models\App;
use JoseChan\Base\Admin\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

/**
 * 应用后台管理
 * Class AppController
 * @package JoseChan\App\Controllers
 */
class AppController extends Controller
{

    use HasResourceActions;

    /**
     * 列表页
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('应用管理');
            //小标题
            $content->description('应用列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '应用管理', 'url' => '/apps']
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

            $content->header('应用管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '应用管理', 'url' => '/apps'],
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

            $content->header('应用管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '应用管理', 'url' => '/apps'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    /**
     * 获取列表
     * @return Grid
     */
    public function grid()
    {
        return Admin::grid(App::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("name","名称");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近修改时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","ID");
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    /**
     * 获取表单
     * @return Form
     */
    protected function form()
    {
        return Admin::form(App::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('name',"名称")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近修改时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);



        });
    }
}