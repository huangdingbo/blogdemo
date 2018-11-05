<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\Tag;
use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public $added=0; //0代表还没有新回复
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' =>
                [
                    'class' => AccessControl::className(),
                    'rules' =>
                        [
                            [
                                'actions' => ['index'],
                                'allow' => true,
                                'roles' => ['?'],
                            ],
                            [
                                'actions' => ['detail','index'],
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        ],
                ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $tags = Tag::findTagWeights();

        $recentComments=Comment::findRecentComments();

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        var_dump($dataProvider);exit;

//        var_dump($dataProvider);exit;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags,
            'recentComments'=>$recentComments,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetail($id)
    {
        //step1. 准备数据模型
        $model = $this->findModel($id);
        $tags=Tag::findTagWeights();
        $recentComments=Comment::findRecentComments();

        $userMe = User::findOne(Yii::$app->user->id);
        $commentModel = new Comment();
        $commentModel->email = $userMe->email;
        $commentModel->userid = $userMe->id;

        //step2. 当评论提交时，处理评论
        if($commentModel->load(Yii::$app->request->post()))
        {
            $commentModel->status = 1; //新评论默认状态为 pending
            $commentModel->post_id = $id;
            $commentModel->create_time = time();
            if($commentModel->save())
            {
                $this->added=1;
            }
        }

        //step3.传数据给视图渲染

        return $this->render('detail',[
            'model'=>$model,
            'tags'=>$tags,
            'recentComments'=>$recentComments,
            'commentModel'=>$commentModel,
            'added'=>$this->added,
        ]);

    }
}
