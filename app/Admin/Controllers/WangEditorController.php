<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-11
 * Time: 23:27
 */

namespace App\Admin\Controllers;


use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;

class WangEditorController extends Controller
{

    use Image;

    public function save(Request $request)
    {

        try{
            /** @var array<UploadedFile> $files */
            $files = $request->file("wangeditor_image");

            $url_list = [];
            /** @var UploadedFile $file */
            foreach ($files as $file)
            {
                $path = $file->store("wangeditor", "admin");
                $url_list[] = $this->imageHandle($path);
            }

            return \response()->json(["errno" => 0, "data" => $url_list]);

        }catch (\Exception $exception){

            return \response()->json(["errno" => $exception->getCode(), "data" => $exception->getMessage()]);
        }


    }
}