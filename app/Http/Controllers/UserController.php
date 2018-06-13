<?php

namespace App\Http\Controllers;

use App\Lib\MTResponse;
use App\Libraries\Ak\AkUploader;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        if ($this->_loginInfo->is_super != 1) {
            MTResponse::jsonResponse("您没有操作权限，请联系管理员", RESPONSE_ERROR);
        }

        $params = $request->all();
        $deployEnvObj = new  User();
        $list = $deployEnvObj->getList($params);

        MTResponse::jsonResponse("ok", RESPONSE_SUCCESS, $list);
    }

    public function create(Request $request){
        if ($this->_loginInfo->is_super != 1) {
            MTResponse::jsonResponse("您没有操作权限，请联系管理员", RESPONSE_ERROR);
        }

        $username = $request->input("username");
        $password = $request->input("password");

        $insertData = [
            "username"        => $username,
            "nickname"        => $username,
            "password"        => md5($password),
        ];

        $deployEnvObj = new  User();

        $ret_insert = $deployEnvObj->createOne($insertData);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function update(Request $request, $id){
        if ($this->_loginInfo->id != $id && $this->_loginInfo->is_super != 1) {
            MTResponse::jsonResponse("您没有操作权限，请联系管理员", RESPONSE_ERROR);
        }

        $password = $request->input("password", null);
        $nickname = $request->input("nickname", null);

        $insertData = [
            "password"  => $password ? md5($password) : null,
            "nickname"  => $nickname
        ];

        if (!empty($_FILES)) {
            foreach ($_FILES as $fileData) {
                // upload and save images of article
                $uploader = new AkUploader($fileData);

                $rootPath = $_SERVER['DOCUMENT_ROOT'];
                $savePathProject = "/upload/" . date("Ymd") . "/";
                $savePath = $rootPath . $savePathProject;
                $uploader->setSavePath($savePath);

                $uploader->uploadAll();

                $errorInfo = $uploader->getError();

                if ($errorInfo) {
                    MTResponse::jsonResponse("部分图片上传失败，请检查！", RESPONSE_ERROR);
                }

                $imageInfo = $uploader->getResult();
                $insertData['header_img'] = $savePathProject . $imageInfo['save_name'][0];

            }
        }

        $deployEnvObj = new  User();

        $ret_insert = $deployEnvObj->updateOne($id, $insertData);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function delete(Request $request, $env_id){
        if ($this->_loginInfo->is_super != 1) {
            MTResponse::jsonResponse("您没有操作权限，请联系管理员", RESPONSE_ERROR);
        }

        $deployEnvObj = new  User();
        $ret_insert = $deployEnvObj->deleteOne($env_id);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function login(Request $request){
        $username = $request->input("username");
        $password = $request->input("password");

        $deployEnvObj = new  User();
        $loginToken = $deployEnvObj->login($username, $password);

        if ($loginToken) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS, $loginToken);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function message(Request $request){
        MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
    }

}
