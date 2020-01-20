<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-05 17:38:57
 */

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CategoryController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('课程分类管理');
            //小标题
            $content->description('课程分类管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程分类管理', 'url' => '/category']
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

            $content->header('课程分类管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程分类管理', 'url' => '/category'],
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

            $content->header('课程分类管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程分类管理', 'url' => '/category'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Category::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("name","分类名称");
            $grid->column("img_url","img_url");
            $grid->column("created_at","created_at");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");


            });


        });
    }

    protected function form()
    {
        return Admin::form(Category::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('name',"分类名称")->rules("required|string");
            $form->image("img_url", "img_url");
            $form->select('parent_id',"上级分类")->options($this->getCategories())->default(0);
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);

        });
    }

    public function getCategories()
    {
        $categories = [0=>"无"];
        $category = Category::where("parent_id", "=", 0)->get();
        if($category){
            foreach ($category->toArray() as $item){
                $categories[$item['id']] = $item['name'];
            }
        }

        return $categories;
    }
}