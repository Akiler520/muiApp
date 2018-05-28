<?php
/**
 * Created by PhpStorm.
 * User: yumin
 * Date: 2018/5/3
 * Time: 10:36 AM
 */

namespace App\Models;


class ArticleImage extends Base
{
    protected $table = "post_article_image";


    /**
     * get list of env
     *
     * @return mixed
     */
    public function getList($articleIDs = []){
        $list = self::whereIn("article_id", $articleIDs)->get();

        return $list;
    }

    /**
     * create new env
     *
     * @param $data
     * @return bool
     */
    public function createOne($data){
        if (!isset($data["url"]) || !isset($data['article_id'])) {
            return false;
        }

        $insert = new self();

        foreach ($data as $key => $value) {
            $insert->$key = $value;
        }

        return $insert->save();
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
            throw new \Exception("图片不存在，请检测!");
        }

        return $element->delete();
    }
}