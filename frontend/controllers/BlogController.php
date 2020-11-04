<?php

namespace frontend\controllers;


use frontend\models\BlogSearchForm;
use frontend\models\NewsletterSignupForm;
use Yii;
use yii\web\Controller;
use common\models\blog\Blog;

class BlogController extends Controller
{

    public function actionIndex($stub = null){
        $model = new BlogSearchForm();
        var_dump(Yii::$app->request->post());
        $model->load(Yii::$app->request->post());
        $model->search();

        $this->view->title = "Blog";
        return $this->render('list', [
            'model' => $model
        ]);
    }

    public function actionDetail($stub = null){
        $this->view->title = "Blog";
        $post = Blog::findOne([
            'identifier' => $stub,
            'status'     => 1
        ]);

        $newsletter = new NewsletterSignupForm();

        if( $newsletter->load(Yii::$app->request->post()) ){
            $newsletter->save();
        }

        $this->view->title = $post->title;
        return $this->render('detail', [
            'post'          => $post,
            'newsletter'    => $newsletter
        ]);
    }

    public function actionPreview($id){
        $this->view->title = "Blog";
        $post = Blog::findOne($id);

        $newsletter = new NewsletterSignupForm();

        if( $newsletter->load(Yii::$app->request->post()) ){
            $newsletter->save();
        }

        $this->view->title = $post->title;
        return $this->render('preview', [
            'post'          => $post,
            'newsletter'    => $newsletter
        ]);
    }

}