<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-14 20:07:02
 */

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('订单管理');
            //小标题
            $content->description('订单管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '订单管理', 'url' => '/orders']
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

            $content->header('订单管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '订单管理', 'url' => '/orders'],
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

            $content->header('订单管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '订单管理', 'url' => '/orders'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {
            $grid->disableCreateButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableView();
                $actions->disableDelete();
            });
            $grid->column("id","id")->sortable();
            $grid->column("order_sn","订单号");
            $grid->column("uid","用户id");
            $grid->column("type","订单类型");
            $grid->column("money","订单金额");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间")->sortable();
            $grid->column("pay_sn","支付号");
            $grid->column("status","订单状态 0-未付款 1-已付款 2-已退款");

            $uid = Admin::user()->id;
            $role = DB::table("admin_role_users")->where(["user_id" => $uid])->first(["role_id"]);
            $role = (array)$role;

            if ($role['role_id'] != 1) {
                $grid->model()->where(["type", "=", 1], ["admin_id", "=", $uid]);
            }

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('order_sn', 'like', "{$this->input}%");
                }, '订单号');
                $filter->equal("uid","用户id");
                $filter->equal("type","订单类型");
                $filter->between("created_at","创建时间")->datetime();
                $filter->equal("status","订单状态 0-未付款 1-已付款 2-已退款")->select(0,1,2);



            });


        });
    }

    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('order_sn',"订单号")->rules("required|string");
            $form->text('uid',"用户id")->rules("required|integer");
            $form->text('type',"订单类型")->rules("required|integer");
            $form->text('money',"订单金额")->rules("required");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->text('pay_sn',"支付号")->rules("required|string");
            $form->select("status","订单状态 0-未付款 1-已付款 2-已退款")->options(0,1,2);



        });
    }
}