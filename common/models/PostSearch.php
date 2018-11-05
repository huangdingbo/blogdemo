<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(),['authorName']);
    }

    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title', 'content', 'tags','authorName'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()  //场景
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 8], //设置分页条数
            'sort' => [
                'defaultOrder' => ['id' => 'SORT_DESC'], //排序
//                'attributes' => ['id','title'], //设置那些字段可以排序
            ],

        ]);

        $this->load($params); //将表单提交的数据赋值给属性

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'post.id' => $this->id,
            'post.status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);
        //构建查询
        $query -> join('inner join','adminuser','post.author_id = adminuser.id');
        $query -> andFilterWhere(['like','adminuser.nickname',$this->authorName]);

        $dataProvider->sort->attributes['authorName'] =
        [
            'asc' => ['adminuser.nickname' => SORT_ASC],
            'desc' => ['adminuser.nickname' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
