<?php

namespace backend\controllers;

use common\models\blog\BlogCategory;
use common\models\catalog\CatalogCategory;
use Yii;
use backend\components\CurrentUser;
use common\components\helpers\PermissionHelper;
use common\models\blog\Blog;
use common\models\blog\search\BlogSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\core\Store;
use yii\web\UploadedFile;


/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
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
//                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionCreate(){
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to create blog posts.");

        $categories = ArrayHelper::map(BlogCategory::find()->all(), 'cat_id', 'title');

        $model                  = new Blog();
        $model->comments        = 1;
        $model->created_time    = date('Y-m-d H:i:s');

        /**
         * Save the model
         */
        if( $model->load(Yii::$app->request->post()) ){
            $model->setIdentifier();

            switch($_POST['action']){
                case 'Preview':
                    $model->status = 0;
                    break;
                case 'Publish':
                    $model->status = 1;
                    break;
                default:
                    $model->status = 0;
                    break;
            }

            if( $model->validate() ){

                $blog_category = null;
                if( $model->new_category ){ //set new blog category
                    $blog_category_count = BlogCategory::find()->count();
                    $blog_category = new BlogCategory();
                    $blog_category->title               = $model->new_category_name;
                    $blog_category->meta_description    = '_';
                    $blog_category->meta_keywords       = '_';
                    $blog_category->sort_order          = (intval($blog_category_count)+1);
                    $blog_category->save();
                }else{
                    if( $model->category_id ){  //set existing blog category
                        $blog_category = BlogCategory::findOne($model->category_id);
                    }
                }

                if( $model->save() ){ //save blog post
                    if( $blog_category ){   //link blog category
                        $model->unlinkAll('category', true);
                        $model->link('category', $blog_category);
                    }

                    switch($_POST['action']){
                        case 'Preview':
                            $post_id = $model->getPrimaryKey();
                            return $this->redirect("/blog/preview/$post_id", 302);
                            break;
                        case 'Publish':
                            Yii::$app->session->setFlash('success', 'Blog Post was successfully Published');
                            return $this->redirect(['index']);
                            break;
                        default:
                            return $this->redirect(['index']);
                            break;
                    }
                }else{
                    Yii::$app->session->setFlash('error', 'Something went wrong while saving your Blog Post');
                }

            }
        }

        return $this->render('create', [
            'model'         => $model,
            'categories'    => $categories
        ]);
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to update blog posts.");

        $categories = ArrayHelper::map(BlogCategory::find()->all(), 'cat_id', 'title');

        $model = Blog::findOne($id);
        $model->update_time     = date('Y-m-d H:i:s');


        if( $model->category ){
            $model->category_id = $model->category->cat_id;
        }

        /**
         * Save the model
         */
        $old_category = $model->category_id;
        if( $model->load(Yii::$app->request->post()) ){
            switch($_POST['action']){
                case 'Preview':
                    $model->status = 0;
                    break;
                case 'Publish':
                    $model->status = 1;
                    break;
                case 'Update':
                    $model->status = 1;
                    break;
            }
            if( $model->validate() ){
                //featured image
                $model->featured_image = UploadedFile::getInstance($model, "featured_image");
                if( $model->remove_featured_image ){
                    $model->featured_image_path = null;
                }else{
                    if( $model->featured_image ){
                        $upload_dir = '/uploads/blog/'.$model->post_id.'.'.$model->featured_image->extension;
                        $path = Yii::getAlias("@frontend") . '/web' . $upload_dir;
                        $model->featured_image->saveAs($path);
                        $model->featured_image_path = 'uploads/blog/'.$model->post_id.'.'.$model->featured_image->extension;
                        $model->featured_image = null;  //need to do this to trick the model into saving, otherwise it will try to save an object
                    }
                }

                //category
                $blog_category = null;
                if( $model->new_category ){ //set new blog category
                    $blog_category_count = BlogCategory::find()->count();
                    $blog_category = new BlogCategory();
                    $blog_category->title               = $model->new_category_name;
                    $blog_category->meta_description    = '_';
                    $blog_category->meta_keywords       = '_';
                    $blog_category->sort_order          = (intval($blog_category_count)+1);
                    $blog_category->save();
                }else{
                    if( $model->category_id
                        && $model->category_id != $old_category ){  //set existing blog category
                        $blog_category = BlogCategory::findOne($model->category_id);
                    }
                }

                $model->save();

                if( $blog_category ){   //link blog category
                    $model->unlinkAll('category', true);
                    $model->link('category', $blog_category);
                }

                switch($_POST['action']){
                    case 'Preview':
                        return $this->redirect("/blog/preview/$model->post_id", 302);
                        break;
                    case 'Publish':
                        Yii::$app->session->setFlash('success', 'Blog Post was successfully Published');
                        return $this->redirect(['index']);
                        break;
                    case 'Update':
                        Yii::$app->session->setFlash('success', 'Blog Post was successfully Updated');
                        return $this->redirect(['index']);
                        break;
                    default:
                        return $this->redirect(['index']);
                        break;
                }
            }
        }

        return $this->render('update',[
            'model'         => $model,
            'categories'    => $categories
        ]);
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to remove blog posts.");

        if( $this->findModel($id)->delete() )
            Yii::$app->session->setFlash('success', "Blog Post #$id successfully deleted.");
        else
            Yii::$app->session->setFlash('error', "There was an error when attempting to delete Blog Post #$id");

        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            Log::record(Log::DOCUMENTS, Log::WARNING, 'The requested page does not exist', 404, true);
        }
    }

//    /**
//     * Finds the Blog model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param integer $id
//     * @return Blog the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findModel($id)
//    {
//        if (($model = Blog::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
}
