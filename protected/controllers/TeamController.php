<?php

class TeamController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    const STATUS_IN_TEAM = 1;
    const STATUS_INVITED = 0;

    const MAP_DISPLAY_TRUE = 1;
    const MAP_DISPLAY_FALSE = 0;

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
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

//	/**
//	 * Displays a particular model.
//	 * @param integer $id the ID of the model to be displayed
//	 */
//	public function actionView($id)
//	{
//		$this->render('view',array(
//			'model'=>$this->loadModel($id),
//		));
//	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $team = Team::getTeam();
        if($team->is_private == Team::TYPE_PUBLIC){
            $this->redirect(array('index'));
        }
		$model=new Team;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Team']))
		{
            $userId = Yii::app()->user->getId();
			$model->attributes=$_POST['Team'];
            $model->owner_id=$userId;
            $model->is_private=Team::TYPE_PUBLIC;
            $model->user_host_id=$userId;

			if($model->save()){
                $teamUsers = new TeamUsers();
                $teamUsers->user_id=$userId;
                $teamUsers->team_id=$model->id;
                $teamUsers->status=TeamUsers::STATUS_IN_TEAM;
                $teamUsers->show_in_map=1;
                $teamUsers->save();
                $this->redirect(array('index'));
            }

		}

		$this->render('create',array(
			'model'=>$model,
		));
	}




	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $userId = Yii::app()->user->getId();
        $team = Team::getTeam();
        if($team->is_private == Team::TYPE_PUBLIC){
            if(isset($_POST['user_id_new'])){
                $teamUsers = new TeamUsers();
                $teamUsers->team_id = $team->id;
                $teamUsers->user_id = $_POST['user_id_new'];
                $teamUsers->status = TeamUsers::STATUS_INVITED;
                if($teamUsers->save()){
                    $user = Yii::app()->getComponent('user');
                    $user->setFlash(
                        'success',
                        Yii::t('app',"Well done! User was invited to our team!")
                    );
                }
            }
            $usersRows = Yii::app()->db->createCommand()
                ->select('id, username, team_id')
                ->from('tbl_users u')
                ->leftJoin('tbl_team_users p', 'u.id=p.user_id and p.team_id='.$team->id)
                ->where("u.username !='rest' and p.user_id IS NULL ")
                ->queryAll();
            $users = array();

            foreach($usersRows as $userRow){
                $users[$userRow['id']] = $userRow['username'];
            }

            $dataProvider=new CActiveDataProvider('TeamUsers', array(
                'criteria'=>array(
                    'condition'=>'t.status =1 AND team_id='.$team->id,
                    'with'=>array('User'),
                ),
                'pagination'=>array(
                    'pageSize'=>20,
                ),
            ));

            $dataProviderInvites=new CActiveDataProvider('TeamUsers', array(
                'criteria'=>array(
                    'condition'=>'t.status =0 AND team_id='.$team->id,
                    'with'=>array('User'),
                ),
                'pagination'=>array(
                    'pageSize'=>20,
                ),
            ));


            $this->render('index',array(
                'dataProvider'=>$dataProvider,  'dataProviderInvites' => $dataProviderInvites, 'team' =>$team, 'users'=>$users
            ));
        }
        else{

            $dataProviderInvites=new CActiveDataProvider('TeamUsers', array(
                'criteria'=>array(
                    'condition'=>'t.status =0 AND user_id='.$userId,
                    'with'=>array('Team'),
                ),
                'pagination'=>array(
                    'pageSize'=>20,
                ),
            ));
             $this->render('teamInvite',array(
                'dataProviderInvites'=>$dataProviderInvites
            ));

        }


	}



	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Team the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Team::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Team $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbl-team-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionUserHide(){
        $team = Team::getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $teamUsers->show_in_map =self::MAP_DISPLAY_FALSE;
            $teamUsers->save(false);
        }
    }

    public function actionUserDisplay(){

        $team = Team::getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $teamUsers->show_in_map =self::MAP_DISPLAY_TRUE;
            $teamUsers->save(false);
        }
    }

    public function actionUserLeave(){
        $team = Team::getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        $teamUsers->status = TeamUsers::STATUS_INVITED;
        $teamUsers->save();
    }

    public function actionUserDelete(){

        $team = Team::getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $userId = $teamUsers->user_id;
            if($teamUsers->delete()){

                if($team->owner_id == $userId || $team->user_host_id == $userId){
                    $users = $team->getAllUsers();
                    if(!empty($users)){
                        $firstUserId = array_shift($users);
                        if($team->owner_id == $userId){
                            $team->owner_id=$firstUserId;
                            $team->save();
                        }
                        if($team->user_host_id == $userId){
                            $team->user_host_id=$firstUserId;
                            $team->save();
                        }
                    }
                    else{
                        $team->delete();
                    }
                }
            }
        }
    }

    public function actionLeaveTeam(){
        if (Yii::app()->request->isAjaxRequest) {
            $userId = Yii::app()->user->getId();
            if(!$userId){return false;}
            $user = User::model()->findByPk($userId);
            $team = $user->getPublicTeam();
            if($team){
                $teamUsers = TeamUsers::model()->findByAttributes(['team_id'=>$team->id,'user_id'=>$userId]);
                $teamUsers->status = TeamUsers::STATUS_INVITED;
                return $teamUsers->save();
            }
        }
    }

    public function actionTeamAccept(){
        if (Yii::app()->request->isAjaxRequest) {
            $userId = Yii::app()->user->getId();
            $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>Yii::app()->request->getParam('team_id'),
                'user_id'=>$userId));
            if($teamUsers){
                $teamUsers->status = TeamUsers::STATUS_IN_TEAM;
                $teamUsers->save();
            }
        }
        echo 1;
    }
    public function actionTeamDelete(){
        if (Yii::app()->request->isAjaxRequest) {
            $userId = Yii::app()->user->getId();
            $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>Yii::app()->request->getParam('team_id'),
                'user_id'=>$userId));
            if($teamUsers){
                $teamUsers->delete();
            }
        }
        echo 1;
    }

}
