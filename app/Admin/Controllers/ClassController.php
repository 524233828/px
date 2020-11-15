<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2020-01-04 15:00:04
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Form\Field\MultiDateTimeSelect;
use App\Models\Category;
use App\Models\Classes;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use function foo\func;
use Illuminate\Database\Query\Builder;

class ClassController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('课程管理');
            //小标题
            $content->description('课程管理');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes']
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

            $content->header('课程管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes'],
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

            $content->header('课程管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '课程管理', 'url' => '/classes'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Classes::class, function (Grid $grid) {

            $shop_option = Shop::getSelector();
            if (Admin::user()->isRole('business')) {
                $admin_id = Admin::user()->id;
                $shop = Shop::query()->where("admin_id", "=", $admin_id)->get(["id"]);
                if ($shop && $shop->isNotEmpty()) {
                    $shop_id = array_column($shop->toArray(), "id");
                    $shop_option = [];
                    $grid->model()->whereIn("shop_id", $shop_id);
                    foreach ($shop->toArray() as $value) {
                        $shop_option[$value['id']] = $value['name'];
                    }
                }
            }
//            $grid->model()->getModel()->with(['shop' => function($query) use ($admin_id){
//                /** @var Builder $query */
//                $query->where("admin_id", "=", $admin_id);
//            }]);
            $grid->column("id", "id")->sortable();
            $grid->column("name", "课程名字");
            $grid->column("shop.name", "店铺名称");
//            $grid->column("start_time","上课时间");
//            $grid->column("created_at","created_at");

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter) use($shop_option) {
                $filter->equal("id", "id");
                $filter->equal("shop_id", "店铺")->select($shop_option);
                $filter->like("name", "课程名称");
            });
        });
    }

    protected function form()
    {
        return Admin::form(Classes::class, function (Form $form) {
            $form->display('id', "id");
            $form->select('shop_id', "店铺")->options(Shop::getSelector())->rules("notIn:0");
            $form->select("category_id", "分类")->options(Category::getSelector())->rules("notIn:0");
            $form->text('name', "课程名字")->rules("required|string");
            $form->text('info', "课程信息")->rules("required|string");
            $form->editor('desc', '课程简介');
            $form->image('pic', "课程图片")->move("classes/images");
            $form->weekTimeSelect('weekTime', "上课时间")->relatedField("week", "time");
//            $form->checkbox("week", "上课时间")->options([
//                "7" => "星期日",
//                "1" => "星期一",
//                "2" => "星期二",
//                "3" => "星期三",
//                "4" => "星期四",
//                "5" => "星期五",
//                "6" => "星期六",
//            ]);
//            $form->multiDatetime("schoolTime", "上课时间")->relateField("start_time");
//            $form->editor('school_time', "上课时间");
            $form->radio('is_buy', "是否购买")->options(['1' => '是', '0' => '否'])->default('0');
            $form->text('price', "价格")->default(0);
            $form->text('start_age', "最小适龄(0为不限)")->rules("Integer")->default(0);
            $form->text('end_age', "最大适龄（0为不限）")->rules("Integer")->default(0);
            $form->select('type', "类型")->options([1 => "线下课", 2 => "线上课"])->default(1);
            $form->text('like', "点赞数");

        });
    }
}