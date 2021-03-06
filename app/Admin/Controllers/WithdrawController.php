<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-20 10:21:33
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
            $content->header('用户提现管理');
            //小标题
            $content->description('用户提现管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户提现管理', 'url' => '/withdraw']
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

            $content->header('用户提现管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户提现管理', 'url' => '/withdraw'],
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

            $content->header('用户提现管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户提现管理', 'url' => '/withdraw'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Withdraw::class, function (Grid $grid) {

            $grid->disableCreateButton();

            $grid->column("id","id");
            $grid->column("user.nickname","用户");
            $grid->column("user.open_id","用户微信号");
            $grid->column("money","提现金额");
            $grid->column("status","提现状态")->using([0=>"等待提现",1=>"提现成功",2=>"提现失败",3=>"人工审核不通过"]);
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间");
            $grid->column("order_sn","提现单号");
            $grid->column("remark","备注");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("uid","用户ID");
                $filter->equal("status","提现状态")->select([0=>"等待提现",1=>"提现成功",2=>"提现失败",3=>"人工审核不通过"]);

                $filter->where(function ($query) {
                    $query->where('order_sn', 'like', "{$this->input}%");
                }, '提现单号');


            });


        });
    }

    protected function form()
    {
        return Admin::form(Withdraw::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('uid',"用户ID")->readonly();
            $form->text('money',"提现金额")->readonly();
            $form->select("status","提现状态")->options([0=>"等待提现",1=>"提现成功",2=>"提现失败",3=>"人工审核不通过"]);

            $form->text('order_sn',"提现单号")->readonly();
            $form->text('remark',"备注")->rules("required|string");


        });
    }
}