<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-07 00:53:38
 */

namespace App\Admin\Controllers;

use App\Models\PxUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class UserController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('用户管理');
            //小标题
            $content->description('用户管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户管理', 'url' => '/user']
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

            $content->header('用户管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户管理', 'url' => '/user'],
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

            $content->header('用户管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '用户管理', 'url' => '/user'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(PxUser::class, function (Grid $grid) {
            $grid->disableCreateButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableView();
                $actions->disableDelete();
            });
            $grid->column("id","id")->sortable();
            $grid->column("open_id","微信open_id");
            $grid->column("pid","上级用户id");
            $grid->column("nickname","昵称");
            $grid->column("headimg_url","头像")->image('',100, 100);
            $grid->column("phone_number","手机号");
            $grid->column("code","邀请码");
            $grid->column("created_at","created_at")->sortable();
            $grid->column("updated_at","updated_at")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('nickname', 'like', "{$this->input}%");
                }, '昵称');


            });


        });
    }

    protected function form()
    {
        return Admin::form(PxUser::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('open_id',"微信open_id")->rules("required|integer");
            $form->text('pid',"上级用户id")->rules("required|integer");
            $form->text('nickname',"昵称")->rules("required|string");
            $form->text('headimg_url',"头像")->rules("required|string");
            $form->text('phone_number',"手机号")->rules("required|string");
            $form->text('code',"邀请码")->rules("required|string");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");


        });
    }
}
