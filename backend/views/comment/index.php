<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
        [
                'attribute' => 'id',
                'contentOptions' => ['width' => '10px','text-align' => 'center'],
        ],
//            'content:ntext',
        [
            'attribute' => 'content',
            'value' => 'dealContent'  //通过getter的方式设置
        ],
//        [
//                'attribute' => 'content',
//                'value' => function($model){  //匿名函数设置value值
//                    $contenZhi = strip_tags( $model->content);
//
//                    $len = strlen($contenZhi);
//
//                    return mb_substr($contenZhi,0,30,'utf-8').(($len > 20) ? '......' : '');
//                }
//        ],
//            'status',
        [
                'attribute' => 'status',
                'value' => 'status0.name',
                'filter' => \common\models\Commentstatus::find()
                                            ->select('name,id')
                                            ->orderBy('position')
                                            ->indexBy('id')
                                            ->column(),
             'contentOptions' => function($model){
                                return $model->status == 1 ? ['class' => 'bg-danger'] : [];
                                },

        ],
//            'create_time:datetime',
        [
                'attribute' => 'create_time',
                'format' => ['date','php:Y-m-d H:i:s'],
        ],
//            'userid',
        [
                'attribute' => 'userName',
                'label' => '用户名',
                'value' => 'user.username'
        ],
            //'email:email',
            //'url:url',
            'post.title',

//            ['class' => 'yii\grid\ActionColumn'],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}{approve}',
            'buttons' => [
                    'approve' => function($url,$model,$key){
                        $options = [
                              'title' => Yii::t('yii','审核'),
                             'aria-label' => Yii::t('yii','审核'),
                            'data-confirm' => Yii::t('yii','你确定要通过这条评论吗？'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
//                            'controller'='', //指定控制器
                        ];
                        return Html::a('<span class = "glyphicon glyphicon-check"></span>',$url,$options);
                    }
            ],
        ],
        ],
    ]); ?>
</div>
