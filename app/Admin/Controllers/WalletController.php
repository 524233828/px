<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-20 10:42:28
 */

namespace App\Admin\Controllers;

use App\Models\Wallet;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WalletController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('用户钱包');
            //小标题
            $content->description('用户钱包');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户钱包', 'url' => '/wallet']
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

            $content->header('用户钱包');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户钱包', 'url' => '/wallet'],
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

            $content->header('用户钱包');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户钱包', 'url' => '/wallet'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Wallet::class, function (Grid $grid) {

            $grid->disableCreateButton();
//            $grid->disableActions();
            $grid->column("id","id");
            $grid->column("uid","用户ID");
            $grid->column("amount","余额");
            $grid->column("freeze_amount","冻结金额");
            $grid->column("updated_at","更新时间")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("uid","用户ID");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Wallet::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('uid',"用户ID")->rules("required|integer");
            $form->text('amount',"余额")->rules("required");
            $form->text('freeze_amount',"冻结金额")->rules("required");
//            $form->datetime('created_at',"创建时间");
//            $form->datetime('updated_at',"更新时间");


        });
    }
}