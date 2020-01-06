<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-07 00:49:51
 */

namespace App\Admin\Controllers;

use App\Models\Withdraw;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WithdrawController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('提现管理');
            //小标题
            $content->description('提现管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '提现管理', 'url' => '/withdraw']
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

            $content->header('提现管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '提现管理', 'url' => '/withdraw'],
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

            $content->header('提现管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '提现管理', 'url' => '/withdraw'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Withdraw::class, function (Grid $grid) {
            $grid->disableCreateButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableView();
                $actions->disableDelete();
            });
            $grid->column("id","id")->sortable();
            $grid->column("uid","uid");
            $grid->column("money","提现金额");
            $grid->column("status","提现状态");
            $grid->column("created_at","created_at")->sortable();
            $grid->column("updated_at","updated_at")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("uid","uid");
                $filter->equal("status","提现状态");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Withdraw::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('uid',"uid")->rules("required|integer");
            $form->text('money',"提现金额")->rules("required");
            $form->text('status',"提现状态")->rules("required|integer");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");


        });
    }
}