<?php
/**
 * Created by PhpStorm.
 * User: 黄定波
 * Date: 2018/11/1
 * Time: 0:55
 */
namespace backend\controllers\server;
use common\models\Adminuser;
use yii\base\Model;
use yii\db\Query;

class test extends Model
{
    public static function asd($model){
        $add = (new Query())->select('nickname as value')
            ->from('adminuser')
            ->where('id = 1')
            ->one();
       foreach ($model as &$item){
            $item['add'] = $add['value'];
       }
        return $model;
    }
}