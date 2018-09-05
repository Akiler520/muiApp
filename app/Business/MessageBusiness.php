<?php
namespace App\Business;

use App\Models\App;
use Cncal\Getui\Getui;

class MessageBusiness
{
    public function __construct()
    {
    }

    /**
     * send message to all user
     * @param $message
     *
     * @return bool
     */
    public function notice($message)
    {
//        Getui::push
        return true;
    }

    public static function checkClient($clientInfo){
        if (!isset($clientInfo['appid'])) {
            return false;
        }

        $appID = $clientInfo['appid'];

        if (App::isExist($appID)){
            // update
            $ret = App::updateByAppID($appID, $clientInfo);
        } else {
            // create new
            $ret = App::createOne($clientInfo);
        }

        return $ret;
    }
}