<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, //创建搜索框
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],//自增长的行号

//            'id',
        [
                'attribute' => 'id',
                'contentOptions' => ['width' => '25px','text-align' => 'center'],
        ],
            'title',
//            'author_id',
        [
            'attribute'=>'authorName',
            'label' => '作者',
            'value' => 'author.nickname',
            'contentOptions' => ['width' => '100px'],

        ],
//            'content:html',
            'tags:ntext',
//            'status',

            [
                    'attribute' => 'status',
                    'value' => 'status0.name' ,
                    'filter' => \common\models\Poststatus::find()
                                ->select('name,id')
                                ->orderBy('position')
                                ->indexBy('id')
                                ->column(),
                     'contentOptions' => ['width' => '105px'],

            ],
//            'create_time:datetime',
        [
                'attribute' => 'create_time',
                'format' => ['date','php:Y-m-d H:i:s'],
        ],
//            'update_time:datetime',
            [
                'attribute' => 'update_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
            //'author_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
