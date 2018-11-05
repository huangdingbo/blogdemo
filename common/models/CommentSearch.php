<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;

/**
 * CommentSearch represents the model behind the search form of `common\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(),['userName']);
    }

    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'userid', 'post_id'], 'integer'],
            [['content', 'email', 'url','userName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
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
        $query = Comment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 8], //设置分页条数
            'sort' => [
                'defaultOrder' => ['id' => 'SORT_DESC'], //排序
//                'attributes' => ['id','title'], //设置那些字段可以排序
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'comment.id' => $this->id,
//            'status' => $this->status,
            'create_time' => $this->create_time,
            'userid' => $this->userid,
            'post_id' => $this->post_id,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'url', $this->url]);

        $query->join('inner join','user','comment.userid = user.id');
        $query->andFilterWhere(['=', 'comment.status', $this->status]);
        $query->andFilterWhere(['like','user.username',$this->userName]);

        $dataProvider->sort->attributes['userName'] =
            [
                'asc' => ['user.username' => SORT_ASC],
                'desc' => ['user.username' => SORT_DESC],
            ];
        //设置状态栏的默认排序
        $dataProvider->sort->defaultOrder = [
            'status'=> SORT_ASC,
            'id' => SORT_DESC,
        ];

        return $dataProvider;
    }
}
