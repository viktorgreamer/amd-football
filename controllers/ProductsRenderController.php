<?php

namespace app\controllers;

use app\models\Processing;
use app\utils\D;
use Yii;
use app\models\ProductsRender;
use app\models\ProductsRenderSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Products;
use yii\helpers\Html;

/**
 * ProductsRenderController implements the CRUD actions for ProductsRender model.
 */
class ProductsRenderController extends MainController
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
        ];
    }

    /**
     * Lists all ProductsRender models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsRenderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductsRender model.
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
     * Creates a new ProductsRender model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductsRender();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductsRender model.
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
     * Deletes an existing ProductsRender model.
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

    public function actionDeleteAll()
    {
        ProductsRender::deleteAll();

        return $this->redirect(['index']);
    }

    public function actionReset()
    {
        Yii::$app->db->createCommand('UPDATE `products` SET `render_status`=0 WHERE 1;')
            ->execute();
        $message = " СБРОСИЛИ ЭКСПОРТ";

        return $this->render('info', compact('message'));
    }

    /**
     * @param bool $autorefresh
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionCreateRender($autorefresh = false)
    {
       Processing::Render();

        return $this->render('info', compact('message'));

    }

    public function actionManualRender($id = 0, $id_category = 0)
    {

        $message = Processing::Render(['id_category' => $id_category, 'id' => $id]);

        return $this->render('render-category', compact(['id_category','id', 'message']));

    }

    public function actionSaveCsv2()
    {
        $product_render = new ProductsRender();

        $Products = ProductsRender::find()->where(['<>', 'id', 0])->asArray()
            //  ->limit(5000)
            ->all();
        $groped_products = array_chunk($Products, 2500);

        foreach ($groped_products as $key => $groped_product) {
            $fp = fopen("export/" . date("Y-m-d H-i-s") . " file " . ($key + 1) . ".csv", 'w');
            fputcsv($fp, $product_render->attributeLabels(), ",");

            foreach ($groped_product as $fields) {
                // вычитаем id
                array_shift($fields);
                fputcsv($fp, $fields, ",");
            }
            //  D::echor("<br> РАЗМЕР ФАЙЛА = ".filesize($fp));
            fclose($fp);
        }
        $files = scandir("export/");
        array_shift($files);
        array_shift($files);

        //D::dump($files);


        return $this->render('export', compact('files'));

    }

    public function actionSaveCsv()
    {
        $product_render = new ProductsRender();
        $Categories = ProductsRender::find()->select('category')->distinct()->column();
        foreach ($Categories as $category) {
            // D::echor("CATEGORY = " . $category);


            $Products = ProductsRender::find()->where(['<>', 'id', 0])->andWhere(['category' => $category])->asArray()->all();

            $fp = fopen("export/" . date("Y-m-d") . " -  " . preg_replace("/\//", "-", str2url($category)) . ".csv", 'w');
            fputcsv($fp, $product_render->attributeLabels(), ",");

            foreach ($Products as $fields) {
                // вычитаем id
                array_shift($fields);
                fputcsv($fp, $fields, ",");
            }
            //  D::echor("<br> РАЗМЕР ФАЙЛА = ".filesize($fp));
            fclose($fp);
        }
        $files = scandir("export/");
        array_shift($files);
        array_shift($files);

        //D::dump($files);


        return $this->render('export', compact('files'));

    }

    public function actionDeleteFile($filename)
    {
        $files = scandir("export/");
        if ($files) {
            array_shift($files);
            array_shift($files);

        }

        if ($filename == 'all') {
            if ($files) {
                foreach ($files as $filename) {
                    unlink("export/" . $filename);
                }
            }
        } else {
            if (in_array($filename, $files)) {
                unlink("export/" . $filename);

            }
        }

        $files = scandir("export/");
        array_shift($files);
        array_shift($files);

        //   D::dump($files);


        return $this->render('export', compact('files'));


    }

    public function actionViewFiles()
    {
        $files = scandir("export/");
        if ($files) {
            array_shift($files);
            array_shift($files);

        }

        //   D::dump($files);


        return $this->render('export', compact('files'));


    }




    /**
     * Finds the ProductsRender model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductsRender the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductsRender::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
