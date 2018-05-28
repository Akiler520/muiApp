<?php

namespace App\Http\Controllers;

use App\Lib\MTResponse;
use App\Libraries\Ak\AkUploader;
use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getList(Request $request){
        $deployEnvObj = new  Article();
        $list = $deployEnvObj->getList();

        MTResponse::jsonResponse("ok", RESPONSE_SUCCESS, $list);
    }

    public function create(Request $request){
        $title      = $request->input("title");
        $content    = $request->input("content");

        $insertData = [
            "title"         => $title,
            "content"       => $content,
        ];

        $deployEnvObj   = new  Article();
        $ret_insert     = $deployEnvObj->createOne($insertData);
        if (!$ret_insert) {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }

        if (isset($_FILES["articleImage"]) && !empty($_FILES["articleImage"])) {
            // upload and save images of article
            $uploader = new AkUploader($_FILES["articleImage"]);

            $rootPath = $_SERVER['DOCUMENT_ROOT'];
            $savePathProject = "/upload/" . date("Ymd") . "/";
            $savePath = $rootPath . $savePathProject;
            $uploader->setSavePath($savePath);

            $uploader->uploadAll();

            $errorInfo = $uploader->getError();

            if ($errorInfo) {
                MTResponse::jsonResponse("部分图片上传失败，请检查！", RESPONSE_ERROR);
            }

            // save the images to the article
            $imageInfo = $uploader->getResult();
            $articleImageData = [
                "article_id"    => $ret_insert,
                "url"           => ""
            ];

            $imageObj = new ArticleImage();

            foreach ($imageInfo['save_name'] as $image) {
                $articleImageData['url'] = $savePathProject . $image;

                $imageObj->createOne($articleImageData);
            }
        }

        MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
    }

    public function update(Request $request, $id){
        $title = $request->input("title");
        $content = $request->input("content");

        $insertData = [
            "title"        => $title,
            "content"         => $content,
        ];

        $deployEnvObj = new  Article();
        $ret_insert = $deployEnvObj->updateOne($id, $insertData);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function delete(Request $request, $env_id){

        $deployEnvObj = new  Article();
        $ret_insert = $deployEnvObj->deleteOne($env_id);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

}
