<?php

class HelperController extends Controller
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */

    public function beforeRender( $view ) {
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
//        $cs->registerScriptFile($baseUrl . '/js/geo.js' );
        $cs->registerScriptFile( $baseUrl . '/js/geo-min.js' );
//        $cs->registerScriptFile( $baseUrl . '/js/geo_position_js_simulator.js' );
        return true;
    }

    public function init()
    {
      $this->layout =false;
    }

	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

       // @todo php ....
        $this->layout = false;
		$this->render('index');
	}
    public function actionBla(){
        echo 2;
    }
}