<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-17 14:33:41
 */

namespace App\Admin\Controllers;

use App\Models\Config;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ConfigController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('系统配置');
            //小标题
            $content->description('系统配置');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '系统配置', 'url' => '/config']
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

            $content->header('系统配置');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '系统配置', 'url' => '/config'],
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

            $content->header('系统配置');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '系统配置', 'url' => '/config'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Config::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("desc","描述");
            $grid->column("value","值");
            $grid->column("key","变量名");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('key', 'like', "{$this->input}%");
                }, '变量名');
                $filter->where(function ($query) {
                    $query->where('desc', 'like', "{$this->input}%");
                }, '描述');


            });


        });
    }

    protected function form()
    {
        return Admin::form(Config::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('desc',"描述")->rules("required|string");
            $form->text('value',"值");

            if($form->isEditing()){
                $form->text('key',"变量名")->rules("required|string")->readonly();
                $form->text('callback',"处理函数")->rules("required|string")->readonly();
            }else{
                $form->text('key',"变量名")->rules("required|string");
                $form->text('callback',"处理函数")->rules("required|string");
            }

        });
    }
}