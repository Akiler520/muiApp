<?php
/**
 * Created by PhpStorm.
 * User: yumin
 * Date: 2018/5/3
 * Time: 10:36 AM
 */

namespace App\Models;

use DB;

class Article extends Base
{
    protected $table = "post_article";


    /**
     * get list of env
     *
     * @return mixed
     */
    public function getList($params = []){
        $page       = $params['page'] ?? 1;
        $pageSize   = $params['page_size'] ?? 10;

        $query      = self::select("*");

        isset($params['user_id']) && $query = $query->where("user_id", $params['user_id']);

        $list       = $query->orderBy("id", "DESC")->paginate($pageSize, null, null, $page);

        // get images of each article
        if ($list) {
            $list = $list->toArray();

            $articleIDs = array_column($list['data'], "id");
            $userIDs = array_unique(array_column($list['data'], "user_id"));

            $imageObj = new ArticleImage();
            $imageList = $imageObj->getList($articleIDs);

            $userObj = new User();
            $userList = $userObj->getListByID($userIDs);

            foreach ($list['data'] as &$article) {
                $article['username'] = "";
                $article['image'] = [];

                foreach ($userList as $user) {
                    if ($user['id'] == $article['user_id']) {
                        $article['username'] = $user['username'];
                        break;
                    }
                }

                foreach ($imageList as $image) {
                    if ($image['article_id'] == $article['id']) {
                        $image['url'] = env("APP_URL") . $image['url'];
                        $article['image'][] = $image;
                    }
                }
            }
        }


        return $list;
    }

    /**
     * create new env
     *
     * @param $data
     * @return bool
     */
    public function createOne($data){
        if (!isset($data["content"])) {
            return false;
        }

        $userInfo = $_SERVER["userInfo"];
        $data['user_id'] = $userInfo->id;

        $insert = new self();

        foreach ($data as $key => $value) {
            $insert->$key = $value;
        }

        $save_ret = $insert->save();

        return ($save_ret) ? DB::getPdo()->lastInsertId() : false;
    }

    /**
     * delete one record
     *
     * @param $envID
     * @return mixed
     */
    public function deleteOne($envID){
        $element = self::find($envID);

        if (!$element) {
            throw new \Exception("文章不存在，请检测!");
        }

        return $element->delete();
    }

    /**
     * 更新
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateOne($id, $data){
        $element = self::find($id);

        if (!$element) {
            throw new \Exception("文章不存在，请检测!");
        }

        foreach ($data as $key => $value) {
            $element->$key = $value;
        }

        return $element->save();
    }

}