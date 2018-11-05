<?php

namespace backend\controllers;

use backend\controllers\server\test;
use backend\controllers\test\t;
use common\models\Adminuser;
use common\models\Comment;
//use common\models\commonModelsTestServer;
use common\models\Poststatus;
use common\models\User;
use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
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
                                'actions' => ['view','index'],
                                'allow' => true,
                                'roles' => ['?'],
                            ],
                            [
                                'actions' => ['create','index','update','view'],
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
//        var_dump(__DIR__);
//        exit;
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)  //文章查看
    {
//        $post = Yii::$app->db->createCommand('select * from post where id = :id')->bindValues([':id'=>$id])->queryAll();
//        var_dump($post);exit;
//        $model = Post::find()->where(['status' => '2'])->one();
//        var_dump($model->title);
//        exit();
//        $thePost = Post::findOne(32);
//        var_dump($thePost->author->email);
//        exit;
//        $list = Post::findOne($id);

//        $time = date('Y-m-d H:i:s',$list->create_time);


        return $this->render('view', [
            'model' => $this->findModel($id),
//            'time' =>   $time
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('createPost')){
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限');
        }
        $model = new Post();
//        $testModel = (new Query())->from('post')->select('*')->where('id=32')->all();
////        var_dump($testModel);exit;
//       $list = test::asd($testModel);
//        var_dump($list);
//        exit;
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
        if(!Yii::$app->user->can('updatePost')){
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限');
        }
        $model = new Post();
        //我的方法
        $model = $this->findModel($id);
//        $model->status = $model->status0->name;
//        $model->author_id = $model->author->nickname;
////        $model->create_time = date('Y-m-d H:i:s',$model->create_time);


//         var_dump(date('Y-m-d H:i:s',time() ));
//         exit;
        //看是否有数据提交上来//save（）验证
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
        if(!Yii::$app->user->can('deletePost')){
            throw new ForbiddenHttpException('对不起，你没有进行该操作的权限');
        }

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
//            $model->create_time =date('Y-m-d H:i:s',$model->create_time);
            return $model;
        }

        throw new NotFoundHttpException('请求的页面不存在。');
    }



}
