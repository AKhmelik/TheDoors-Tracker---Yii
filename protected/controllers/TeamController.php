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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'admin', 'userDisplay', 'userHide', 'userDelete'),
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
        $team = Team::model()->getTeam();
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
        $team = Team::model()->getTeam();
        if($team->is_private == Team::TYPE_PUBLIC){
            if(isset($_POST['user_id'])){
                $teamUsers = new TeamUsers();
                $teamUsers->team_id = $team->id;
                $teamUsers->user_id = $_POST['user_id'];
                $teamUsers->status = TeamUsers::STATUS_INVITED;
                if($teamUsers->save()){
                    $user = Yii::app()->getComponent('user');
                    $user->setFlash(
                        'success',
                        "<strong>Well done!</strong> User was added to our team!"
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

            $this->render('index',array(
                'dataProvider'=>$dataProvider,  'team' =>$team, 'users'=>$users
            ));
        }
        else{
            $teamUsers= new TeamUsers();
            if(isset($_POST['TeamUsers'])){
                if(isset($_POST['TeamUsers']['team_id'])){
                   $team = TeamUsers::model()->findByAttributes(array('user_id'=>$userId, 'team_id'=>$_POST['TeamUsers']['team_id']));
                   if($team){
                       $team->status = TeamUsers::STATUS_IN_TEAM;
                       $team->save(false);
                       $this->refresh();
                   }
                }
                $teamUsers->addError('team_error','Please select the team');

            }


            $teamInvitedArray = TeamUsers::model()->findAllByAttributes(array('user_id'=>$userId, 'status'=>TeamUsers::STATUS_INVITED));
            $this->render('teamInvite',array(
                'teamInvitedArray'=>$teamInvitedArray,  'teamUsers' =>$teamUsers
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
        $team = Team::model()->getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $teamUsers->show_in_map =self::MAP_DISPLAY_FALSE;
            $teamUsers->save(false);
        }
    }

    public function actionUserDisplay(){

        $team = Team::model()->getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $teamUsers->show_in_map =self::MAP_DISPLAY_TRUE;
            $teamUsers->save(false);
        }
    }


    public function actionUserDelete(){

        $team = Team::model()->getTeam();
        $teamUsers = TeamUsers::model()->findByAttributes(array('team_id'=>$team->id, 'user_id'=>Yii::app()->request->getParam('id')));
        if($teamUsers){
            $userId = $teamUsers->user_id;
            if($teamUsers->delete()){

                if($team->owner_id == $userId || $team->user_host_id == $userId){
                    $users = $team->getUserIdArray();
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

}
