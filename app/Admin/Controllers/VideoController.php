<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-20 11:56:02
 */

namespace App\Admin\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('视频管理');
            //小标题
            $content->description('视频管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '视频管理', 'url' => '/video']
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

            $content->header('视频管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '视频管理', 'url' => '/video'],
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

            $content->header('视频管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '视频管理', 'url' => '/video'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Video::class, function (Grid $grid) {


            $grid->column("id","id");
            $grid->column("type","业务类型")->using(Video::$business_type);
            $grid->column("path","地址");
            $grid->column("business_id","业务ID");
            $grid->column("created_at","创建时间");
            $grid->column("updated_at","更新时间");



            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->equal("type","业务类型");
                $filter->equal("business_id","业务ID");


            });


        });
    }

    protected function form()
    {
        $form = Admin::form(Video::class, function (Form $form) {

            $form->display('id',"id");
            $form->select('type',"业务类型")
                ->options(Video::$business_type)
                ->load('business_id', "/admin/business");
            $form->text('path',"地址");
            $form->select('business_id',"业务对象")->rules("required|integer");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");

        });

        $script = <<<SCRIPT
        var type = $(".type")
        var target = $(type).closest('.fields-group').find(".business_id");
        $.get("/admin/business",{q : $(".type").val()}, function (data) {
            target.find("option").remove();
            var selected_value = $(target).data("value");
            $(target).select2({
                placeholder: {"id":"","text":"\u9009\u62e9"},
                allowClear: true,
                data: $.map(data, function (d) {
                    d.id = d.id;
                    d.text = d.text;
                    if(d.id == selected_value){
                        d.selected = true;
                    }
                    return d;
                })
            }).trigger('change');
            
        });
SCRIPT;

        Admin::script($script);
        return  $form;
    }

    public function getBusiness(Request $request)
    {
        $type = $request->get("q", 1);

        return Video::getBusiness($type);
    }
}