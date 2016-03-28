<?php

class FormController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'create';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'results', 'updater', 'updatecell'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {


        $model = new GeoForm;


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (count($_POST) > 0) {
            $model = new GeoForm;
            $model->type = 'number';
            $model->save();
            $cellsArray = array();
            if (isset($_POST['type']) && $_POST['type'] == 'number') {
                $cellsArray = $model->generateCodeNumber($_POST);
            } elseif (isset($_POST['type']) && $_POST['type'] == 'sequence') {
                $cellsArray = $model->generateCodeSequence($_POST);
            } elseif (isset($_POST['type']) && $_POST['type'] == 'correct') {
                $cellsArray = $model->generateCodeCorrect($_POST);
            } elseif (isset($_POST['type']) && $_POST['type'] == 'block') {
                $cellsArray = $model->generateCodeBlock($_POST);
            } else {
                $this->redirect(array('generator'));
            }
            foreach ($cellsArray as $cell) {
                $modelCell = new GeoFormCell();
                $modelCell->form_id = $model->id;
                $modelCell->cell_value = $cell;
                $modelCell->save(false);

            }
            $this->redirect(array('form/results', 'id' => $model->id));
        }

        $this->render('generator', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GeoForm'])) {
            $model->attributes = $_POST['GeoForm'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('GeoForm');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new GeoForm('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['GeoForm']))
            $model->attributes = $_GET['GeoForm'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GeoForm the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = GeoForm::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GeoForm $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'geo-form-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionResults($id)
    {
        $geoModel = GeoForm::model()->find('id=:id', array(':id' => $id));
        $this->render('results', array(
            'geoModel'=>$geoModel,
            'model' => $geoModel->geoFormCells,
        ));
    }

    public function actionUpdater()
    {

        if(Yii::app()->request->isAjaxRequest ){
            $geoModel = GeoForm::model()->find('id=:id', array(':id' => Yii::app()->request->getPost('id')));
            $data=array();
            foreach ($geoModel->geoFormCells as $cell){
                $data['cell_id'.$cell->id]=($cell->checked==1)?'btn-inverse':'btn-success';
            }
            echo json_encode($data);
        }

    }

    public function actionUpdatecell(){
        if(Yii::app()->request->isAjaxRequest ){

            $id=Yii::app()->request->getPost('id');

            $id=(int)str_replace('cell_id', '',$id);
             $model= GeoFormCell::model()->find('id=:id', array(':id' => $id));
            $model->checked=1;
            $model->update();
        }
    }


}


