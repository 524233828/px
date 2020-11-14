<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-11-10
 * Time: 11:12
 */

namespace App\Admin\Extensions\Form\Field;


use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Form;

class WeekTimeSelector extends Field
{
    private $weekField = "week";
    private $timeField = "time";
    private $relationKey;
    /** @var array $css */
    protected static $css = [
        '/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/moment/min/moment-with-locales.min.js',
        '/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    /** @var string $view 视图 */
    protected $view = "form.field.weekTimeSelector";

    public function __construct($column = '', array $arguments = [])
    {
        $this->relationKey = $column;

        parent::__construct($column, $arguments);
    }


    public function relatedField($weekField, $timeField)
    {
        $this->weekField = $weekField;
        $this->timeField = $timeField;

        return $this;
    }

    private function script()
    {
        $options = [];
        $options['format'] = 'HH:mm:ss';
        $options['locale'] = config('app.locale');
        $options['allowInputToggle'] = true;
        return "$('.timePicker').datetimepicker(" . json_encode($options) . ');';
    }

    /**
     * 渲染
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->addVariables(["week" => $this->weekField, "time" => $this->timeField, "column" => $this->relationKey]);
//        $this->variables = array_merge($this->variables, ["id_name" => $this->getIdName()]);
        Admin::script($this->script());
        return parent::render(); // TODO: Change the autogenerated stub
    }

    /**
     * 填充回显数据
     * @param array $data
     */
    public function fill($data)
    {
        if ($this->form && $this->form->shouldSnakeAttributes()) {
            $key = Str::snake($this->column);
        } else {
            $key = $this->column;
        }

        if (Str::contains($key, '.')) {
            list($relation_key) = explode('.', $key);
            $relations = Arr::get($data, $relation_key);
        } else {
            $relations = Arr::get($data, $key);
        }

        if (is_string($relations)) {
            $this->value = explode(',', $relations);
        }

        if (!is_array($relations)) {
            return;
        }


        $first = current($relations);

        if (is_null($first)) {
            $this->value = null;

            return;

        } else {
            foreach ($relations as $relation) {
                $this->value["week"][] = $relation[$this->weekField];
                if ($relation[$this->weekField] == 7) {
                    $time_index = 0;
                } else {
                    $time_index = $relation[$this->weekField];
                }
                $this->value["time"][$time_index] = $relation[$this->timeField];
            }
        }
    }

    /**
     * 返回入库数据结构
     * @param $value
     * @return array|mixed
     */
    public function prepare($value)
    {
        if (method_exists($this->form->model(), $this->relationKey)
        ) {
            $data = [];
            if ($this->form->model()->{$this->relationKey}() instanceof HasMany) {
                if (isset($value[$this->weekField]) && isset($value[$this->timeField])) {
                    foreach ($value[$this->weekField] as $week) {
                        if ($week == 7) {
                            $time_index = 0;
                        } else {
                            $time_index = $week;
                        }

                        if (!empty($value[$this->timeField][$time_index])) {
                            $data[] = [
                                $this->weekField => $week,
                                $this->timeField => $value[$this->timeField][$time_index]
                            ];
                        }
                    }
                }
                //获取已有的关系
                $collection = $this->form->model()->{$this->relationKey};
                /** @var \Iterator $related */
                $related = $collection->getIterator();
                $value = [];
                // 自动复用记录
                foreach ($data as $item) {
                    /** @var Model $current */
                    if ($current = $related->current()) {
                        //更新
                        $value[] = [
                            $current->getKeyName() => $current->getKey(),
                            $this->weekField => $item[$this->weekField],
                            $this->timeField => $item[$this->timeField],
                            Form::REMOVE_FLAG_NAME => 0,
                        ];
                        $related->next();
                    } else {
                        //新增
                        $value[] = [
                            $this->weekField => $item[$this->weekField],
                            $this->timeField => $item[$this->timeField],
                            Form::REMOVE_FLAG_NAME => 0,
                        ];
                    }
                }
                // 清理多余的记录
                while ($current = $related->current()) {
                    //删除
                    $value[] = [
                        $current->getKeyName() => $current->getKey(),
                        Form::REMOVE_FLAG_NAME => 1,
                    ];
                    $related->next();
                }
            }
        }
        return $value;
    }
}
