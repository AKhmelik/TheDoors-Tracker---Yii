<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * HOMEPAGE action
	 */
	public function actionIndex()
	{
        if (!Yii::app()->user->isGuest){
            $this->redirect('metric/index');
        }
		if($this->isDesktop()){
			Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/home.css');
			Yii::app()->clientScript->registerScriptFile('/js/home.js',CClientScript::POS_END);

		}
		else{
			Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/homeMobile.css');
			Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/swiper.min.css');
			Yii::app()->clientScript->registerScriptFile('/js/home.js',CClientScript::POS_END);

		}
        $this->layout = 'home';
        $this->pageTitle = Yii::t('app', 'Simple GPS Tracker'); // It could be something from DB or...whatever
        $description = Yii::t('app','simple and handy service for GPS-signals tracking from mobile devices. You can follow GPS-beacon movement on online maps. Team up in order to coordinate movement, track all routs or just selected ones, create access links to share your location. Do you play in the city quests? Do you want to be sure of your child safety? Are you walking around the city all day long? Then EQBEAT will be indispensable assistant for you!');
        $keywords="GPS трекер, encounter, quest, квест, трекер местоположения, tracker, GPS маячек, трекер ребенка";

        Yii::app()->clientScript->registerMetaTag($keywords, 'keywords');
        Yii::app()->clientScript->registerMetaTag($description, 'description');
		// using the default layout 'protected/views/layouts/main.php'
		$this->render($this->isDesktop()?'home':'home_mobile');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form

		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
    public function actionLoginByHash(){
        if(Yii::app()->request->getParam('hash')){
            $team = Team::model()->findByAttributes(['access_hash'=>Yii::app()->request->getParam('hash')]);
            if($team){
                Yii::app()->session['teamId'] = $team->id;
                Yii::app()->session['teamHash'] = Yii::app()->request->getParam('hash');
                $this->redirect(array('metric/index'));
            }
        }
        return false;
    }
}