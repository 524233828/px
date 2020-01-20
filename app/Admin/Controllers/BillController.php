<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-20 10:32:10
 */

namespace App\Admin\Controllers;

use App\Models\Bill;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BillController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('用户账单流水');
            //小标题
            $content->description('用户账单流水');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户账单流水', 'url' => '/bills']
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

            $content->header('用户账单流水');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户账单流水', 'url' => '/bills'],
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

            $content->header('用户账单流水');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户账单流水', 'url' => '/bills'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Bill::class, function (Grid $grid) {

            $grid->column("id","id");
            $grid->column("user.nickname","用户");
            $grid->column("user.open_id","微信ID");
            $grid->column("bill_no","流水号");
            $grid->column("out_trade_type","关联外部业务类型")->using([1=>"分销",2=>"提现"]);
            $grid->column("out_trade_no","外部单号");
            $grid->column("type","类型")->using([0=>"冻结",1=>"支出",2=>"收入"]);
            $grid->column("money","金额")->sortable();
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间");

            $grid->disableActions();
            $grid->disableCreateButton();

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('bill_no', 'like', "{$this->input}%");
                }, '流水号');
                $filter->equal("out_trade_type","关联外部业务类型")->select([1=>"分销",2=>"提现"]);

                $filter->where(function ($query) {
                    $query->where('out_trade_no', 'like', "{$this->input}%");
                }, '外部单号');
                $filter->equal("type","类型")->select([0=>"冻结",1=>"支出",2=>"收入"]);

                $filter->equal("uid","用户ID");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Bill::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('bill_no',"流水号")->rules("required|string");
            $form->select("out_trade_type","关联外部业务类型")->options([1=>"分销",2=>"提现"]);

            $form->text('out_trade_no',"外部单号")->rules("required|string");
            $form->select("type","类型")->options([0=>"冻结",1=>"支出",2=>"收入"]);

            $form->text('money',"金额")->rules("required");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->editor('remark', '备注信息')->rules("required|string");
            $form->text('uid',"用户ID")->rules("required|integer");


        });
    }
}