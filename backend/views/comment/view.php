<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = $model->post->title;
$this->params['breadcrumbs'][] = ['label' => '评论管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改评论', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除评论', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'content:ntext',
//            'status',
        [
                'label' => '状态',
                'value' => $model->status0->name,
        ],
//            'create_time:datetime',
        [
                'attribute' => 'create_time',
                'value' => date('Y-m-d H:i:s',$model->create_time),
        ],
//            'userid',
        [
                'label' => '用户名',
                'value' => $model->user->username,
        ],
            'email:email',
            'url:url',
//            'post_id',
        ],
    ]) ?>

</div>
