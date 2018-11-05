<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property int $status
 * @property int $create_time
 * @property int $update_time
 * @property int $author_id
 *
 * @property Comment[] $comments
 * @property Adminuser $author
 * @property Poststatus $status0
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $old_tags;
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminuser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Poststatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()  //属性标签
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '最后修改时间',
            'author_id' => '作者',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Adminuser::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()  //这里的操作就是关联数据表
    {
        return $this->hasOne(Poststatus::className(), ['id' => 'status']);
    }
    //重写beforeSave  在保存之前
    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->update_time = time();
                $this->create_time = time();
            }else{
                $this->update_time = time();
            }
            return true;
        }else{
            return false;
        }
    }

    public static function test(&$model){
        $list = Adminuser::find()->where('id = 1')
            ->select('nickname')
            ->all();
        $model->test = $list;
    }

    public function afterFind(){
        parent::afterFind();
        $this->old_tags = $this->tags;
    }

    //新增和修改
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert,$changedAttributes);
        Tag::updateFrequency($this->old_tags,$this->tags);
    }

    //删除
    public function afterDelete(){
        parent::afterDelete();
        Tag::updateFrequency($this->tags,'');
    }

    public function getUrl(){
        return Yii::$app->urlManager->createUrl(
            [
                'post/detail',
                'id'=>$this->id,
                'title' => $this->title
            ]);

    }

    public function getBeginning($len = 288){
        $tmpStr = strip_tags($this->content);
        $tmpLen = mb_strlen($tmpStr);

        $tmpStr = mb_substr($tmpStr,0,$len,'utf-8');
        return $tmpStr.($tmpLen > $len ? '......' : '');
    }

    public function getTagLinks(){
        $links = array();

        foreach (Tag::string2array($this->tags) as $tag){
            $links[] = Html::a(HTML::encode($tag),array('post/index','PostSearch[tags]'));
        }
        return $links;
    }
    public function getCommentCount(){
        return Comment::find()->where(['post_id'=>$this->id,'status'=>2])->count();
    }
    public function getActiveComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])
            ->where('status=:status',[':status'=>2])->orderBy('id DESC');
    }




}
