<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 10:25
 */

namespace App\Api\Controllers;


use App\Models\Teacher;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;

class TeacherController extends Controller
{

    public function get(Request $request)
    {
        $this->validate($request->all(), [
            "teacher_id" => "required|Integer"
        ]);

        $teacher_id = $request->get("teacher_id");

        /** @var Teacher $teacher */
        $teacher = Teacher::find($teacher_id);

        if(!$teacher){
            $this->response([], 900001, "老师不存在")->send();
        }

        return $this->response($teacher);
    }

}
