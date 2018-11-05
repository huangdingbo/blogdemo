<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \common\models\Post;
/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>
    <?php
        $statusList = \common\models\Poststatus::find()->all();
        $statusListMap = ArrayHelper::map($statusList,'id','name');

    ?>

    <?=$form->field($model,'status')
        ->dropDownList($statusListMap,['prompt'=>'请选择状态']);
    ?>

    <?php
        $allAuthor = (new \yii\db\Query())->select('nickname,id')
            ->from('adminuser')
            ->indexBy('id')
            ->column();
    ?>

    <?=$form->field($model,'author_id')
        ->dropDownList($allAuthor,['prompt'=>'请选择作者'])
    ;?>

    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
