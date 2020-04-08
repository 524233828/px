<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-19 09:31:31
 */

namespace App\Admin\Controllers;

use App\Models\Appoint;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AppointController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('预约管理');
            //小标题
            $content->description('预约管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '预约管理', 'url' => 'appoint']
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

            $content->header('预约管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '预约管理', 'url' => 'appoint'],
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

            $content->header('预约管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '预约管理', 'url' => 'appoint'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Appoint::class, function (Grid $grid) {

            /** @var Administrator $administrator */
            $administrator = Admin::user();
            /** @var Collection $roles */
            $roles = $administrator->roles;

            $role_ids = array_column($roles->toArray(), "id");
            if(in_array(2, $role_ids)){
                $grid->model()->where("admin_id", "=", $administrator->id);
                $grid->column("shop_id","商户id")->sortable();
            }

            $grid->disableCreateButton();
            $grid->disableActions(true);
            $grid->column("id","id")->sortable();
            $grid->column("user.nickname","用户昵称");
            $grid->column("user.phone_number","手机号");
            $grid->column("shop.name","店铺名称")->sortable();
            $grid->column("classes.name","课程名称")->sortable();
            $grid->column("status","预约状态")->using([0 => "待上课", 1 => "已完成"])->editable();
            $grid->column("created_at","预约时间")->sortable();
            $grid->column("start_time","上课时间")->sortable();
            $grid->column("end_time","下课时间")->sortable();
            $grid->column("card_id","卡券ID");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("shop_id","商户id");
                $filter->equal("uid","用户id");
                $filter->equal("class_id","课程id");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Appoint::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('shop_id',"商户id")->rules("required|integer");
            $form->text('uid',"用户id")->rules("required|integer");
            $form->text('class_id',"课程id")->rules("required|integer");
            $form->text('status',"预约状态 待上课 已完成")->rules("required|integer");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");
            $form->text('card_id',"卡券ID")->rules("required|integer");
            $form->text('admin_id',"商户ID")->rules("required|integer");


        });
    }
}