<?php

namespace App\Http\Controllers;

use App\Lib\MTResponse;
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
        $params = $request->all();
        $deployEnvObj = new  User();
        $list = $deployEnvObj->getList($params);

        MTResponse::jsonResponse("ok", RESPONSE_SUCCESS, $list);
    }

    public function create(Request $request){
        $username = $request->input("username");
        $password = $request->input("password");

        $insertData = [
            "username"        => $username,
            "password"         => md5($password),
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
        $password = $request->input("password");

        $insertData = [
            "password"         => md5($password),
        ];
        $deployEnvObj = new  User();
        $ret_insert = $deployEnvObj->updateOne($id, $insertData);

        if ($ret_insert) {
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

    public function delete(Request $request, $env_id){

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
            MTResponse::jsonResponse("ok", RESPONSE_SUCCESS, ["token" => $loginToken]);
        } else {
            MTResponse::jsonResponse("error", RESPONSE_ERROR);
        }
    }

}
