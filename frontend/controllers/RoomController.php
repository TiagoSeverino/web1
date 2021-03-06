<?php

namespace frontend\controllers;

use Yii;
use common\models\Room;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
{
    /**
     * {@inheritdoc}
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
        ];
    }

    public function beforeAction ( $action ){
        if (Yii::$app->user->isGuest){
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.')); 
        }

        if (!isset( $_GET['id'] )){
            return true;
        }

        $request = Yii::$app->request;
        $OrganizationID  = $this->findModel($request->get('id'))->OrganizationID;

        $user = User::findIdentity(Yii::$app->user->identity->id);
        if ($user->isMember($OrganizationID)){
            return true;
        }
        else
        {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.')); 
        }
    }

    /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = User::findIdentity(Yii::$app->user->identity->id);
        $dataProvider = new ActiveDataProvider([
            'query' => $user->getRooms(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Room model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $room  = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $room->getBookings(),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Room();

        if ($model->load(Yii::$app->request->post())) {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            if ($user->isMember($model->OrganizationID)){
                if ($model->save()){
                    return $this->redirect(['view', 'id' => $model->ID]);
                }
            }
            else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.')); 
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $user = User::findIdentity(Yii::$app->user->identity->id);
            if ($user->isMember($model->OrganizationID)){
                if ($model->save()){
                    return $this->redirect(['view', 'id' => $model->ID]);
                }
            }
            else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.')); 
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Room model.
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
     * Finds the Room model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Room::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
