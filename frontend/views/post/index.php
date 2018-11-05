<?php

use yii\helpers\Html;
//use yii\widgets\ListView;
use yii\data\activeDataProvider;


/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
        <div class="row">

            <div class="col-md-9">

                <?=\yii\widgets\ListView::widget([
                        'id' => 'postList',
                        'dataProvider' =>  $dataProvider,
                        'itemView' => '_list', //定义子视图文件
                        'layout' => '{items}{pager}',
                        'pager' => [
                                'maxButtonCount' => 10,
                                'nextPageLabel' => Yii::t('app','下一页'),
                                'prevPageLabel' => Yii::t('app','上一页'),
                                 'firstPageLabel' => Yii::t('app','首页'),
                                 'lastPageLabel' => Yii::t('app','尾页'),
                        ],

                ])?>

            </div>

            <div class="col-md-3">
                <div class="searchbox">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 查找文章
                        </li>
                        <li class="list-group-item">
                            <form class="form-inline" action="index.php?r=post/index" id="w0" method="get">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="PostSearch[title]" id="w0input" placeholder="按标题">
                                </div>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </form>
                        </li>
                    </ul>
                </div>

                <div class="tagclodbox">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 标签云
                        </li>
                        <li class="list-group-item">
                            <?= \frontend\components\TagCloudWidget::widget(['tags'=>$tags]);?>
                        </li>
                    </ul>
                </div>

                <div class="commentbox">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 最新回复
                        <li class="list-group-item">
                            <?= \frontend\components\RctReplyWidget::widget(['recentComments'=>$recentComments])?>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
</div>
