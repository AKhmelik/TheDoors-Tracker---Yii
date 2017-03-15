<?php

class MetricController extends Controller
{
    public  $endPointName ='';

    public function filters()
    {
        return array( 'accessControl' ); // perform access control for CRUD operations
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                'actions'=>array('generatenewlink'),
                'users'=>array('@'),
            ),
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index', 'setendpoint','calculateHistory', 'getcores',  'addmarker', 'getanotherpoints'),
                'expression'=>'WebUser::isSupport()',
            ),
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('loginbyhash', 'getdata'),
                'users'=>array('*'),
            ),
            array('deny'),
        );
    }

    public function actionIndex()
    {

       $team = Team::getTeam();

        if(Yii::app()->request->isPostRequest)
        {
            $codes = Yii::app()->request->getParam('cores', 0);
            $mainUserId = Yii::app()->request->getParam('mainPoint', 0);

            if($team->is_private == Team::TYPE_PRIVATE){
                $team->end_point_name =$codes;
                $team->save(false);

            }
            else{
                $team->end_point_name =$codes;
                if($team->getUsersInMap()){
                    if(in_array($mainUserId, $team->getUsersInMap())){
                        $team->user_host_id =$mainUserId;
                    }
                }
                $team->save(false);
            }

}

        $geoPoints=GeoPoints::model()->findAllByAttributes(array('team_id'=>$team->id));

        $team = Team::getTeam();
        if($team){
            $this->endPointName = $team->end_point_name;
        }
        $this->render('index', array('model' => array(), 'geoPoints'=>$geoPoints, 'team'=>$team));

    }

    /**
     * updated geo coordinates of mobile API
     *
     * returned end point coordinate data and team points
     *
     * @throws CDbException
     * @throws CException
     *
     */
    public function actionGetData()
    {
        {
            if(Yii::app()->request->getParam('mid') && Yii::app()->request->getParam('latitude') && Yii::app()->request->getParam('longitude')){
               $hash = null;
                if(Yii::app()->request->getParam('hash')){
                    Yii::import('application.modules.user.models.User');
                    $user = User::getUserByHash(Yii::app()->request->getParam('hash'));
                    if($user){

                        if(Yii::app()->request->getParam('longitude')!=0 &&  Yii::app()->request->getParam('latitude')!=0){
                            date_default_timezone_set('UTC');
                            $geoLog = new GeoLog();
                            $geoLog->longitude =Yii::app()->request->getParam('longitude');
                            $geoLog->latitude = Yii::app()->request->getParam('latitude');
                            $geoLog->user_id =Yii::app()->request->getParam('mid');
                            $geoLog->speed =Yii::app()->request->getParam('speed');
                            $geoLog->user_api_id =$user->id;
                            $geoLog->time = time();
                            $geoLog->datetime_col = date('Y-m-d H:i:s', time());
                            $geoLog->insert();

                            GeoUnique::model()->deleteAllByAttributes(array('user_api_id'=>$user->id));

                            $geoUser = new GeoUnique();
                            $geoUser->longitude = Yii::app()->request->getParam('longitude');
                            $geoUser->latitude = Yii::app()->request->getParam('latitude');
                            $geoUser->user_id = Yii::app()->request->getParam('mid');
                            $geoUser->user_api_id = $user->id;
                            $geoUser->time = time();
                            $geoUser->insert();
                        }

                        $data = $user->getPointsData();
                        echo json_encode($data);
                       exit;
                    }
                }
            }
        }


          echo json_encode(array());

    }



    /**
     * Displays Lead Point
     *
     */

    public function actionGetCores()
    {
        if (Yii::app()->request->isAjaxRequest) {

            $team = Team::getTeam();

            $geolocal =GeoUnique::getByUserId($team->user_host_id);

            $geos['start']=array($geolocal->latitude,$geolocal->longitude );
            $geos['end']=$team->end_point_name;

            $geos['icocolor']=$this->getColor($geolocal->time);
            $geos['corecolor']=$this->getCoreColor($geolocal->time);

            $geos['updated']='Updated '.(time()-$geolocal->time).' secs ago!';


            echo json_encode($geos, 1);

        }
    }

    /**
     * returns poins from team (not include lead)
     */
    public function actionGetAnotherPoints(){
        if (Yii::app()->request->isAjaxRequest) {

            $geos = array();
            $team = Team::getTeam();
            $usersIds = $team->getUsersInMap();
            if($usersIds){
                foreach ($usersIds as $userId) {
                    if($team->user_host_id !=$userId){
                        $geoLocal = GeoUnique::getByUserId($userId);
                        $geos[$userId]['updated'] = 'Updated ' . (time() - $geoLocal->time) . ' secs ago!';
                        $geos[$userId]['cores'] = array($geoLocal->latitude, $geoLocal->longitude);
                        $geos[$userId]['title'] = User::getUserIdentityById($userId);
                        $geos[$userId]['icocolor']=$this->getColor($geoLocal->time);
                        $geos[$userId]['corecolor']=$this->getCoreColor($geoLocal->time);
                    }
                }
            }
            echo json_encode($geos, 1);
        }
    }

    public function getColor($time){
        if((time()-$time)<10){
            return 'islands#greenCircleDotIconWithCaption';
        }
        elseif((time()-$time)<60){
            return 'islands#yellowCircleDotIconWithCaption';
        }
        elseif((time()-$time)<120){
            return 'islands#redCircleDotIconWithCaption';
        }
        else{
            return  'islands#blackCircleDotIconWithCaption';
        }
    }

    public function getCoreColor($time){
        if((time()-$time)<10){
            return 'twirl#greenStretchyIcon';
        }
        elseif((time()-$time)<60){
            return 'twirl#yellowStretchyIcon';
        }
        elseif((time()-$time)<120){
            return 'twirl#redStretchyIcon';
        }
        else{
            return  'twirl#blackStretchyIcon';
        }
    }


    public function actionSetendpoint(){
        if (Yii::app()->request->isAjaxRequest) {
            $endPointCoreLat = Yii::app()->request->getParam('endPointCoreLat');
            $endPointCoreLng = Yii::app()->request->getParam('endPointCoreLng');

            $team = Team::getTeam();
            $team->end_point_lat = $endPointCoreLat;
            $team->end_point_lng = $endPointCoreLng;
            $team->save(false);
        }
    }


    public function actionImportpoins(){
        if($_FILES){

            GeoPoints::model()->deleteAll();

            $file = fopen($_FILES['filename']['tmp_name'],"r");
            while(! feof($file))
            {
                $string=fgetcsv($file);
                $geoPoints = new GeoPoints;
                $geoPoints->cores= $string[3];
                $geoPoints->street=$string[1];
                $geoPoints->house=$string[2];
                $geoPoints->comments=$string[5];
                $geoPoints->save();
            }
            fclose($file);
        }
        $this->render('import', array());
    }

    public function actionAddMarker()
    {
        $team = Team::getTeam();

        $isNew = false;
        if (Yii::app()->request->getParam('markerLat') && Yii::app()->request->getParam('markerLng')) {
            $isDeleted = Yii::app()->request->getParam('isDeleted');
            $geoPoint = GeoPoints::model()->findByPk(Yii::app()->request->getParam('placeId'));
            if (!$geoPoint) {
                $geoPoint = new  GeoPoints();
                $isNew = true;
            }
            if($isNew){
                $geoPoint->cores = Yii::app()->request->getParam('markerLat') . ", " . Yii::app()->request->getParam('markerLng');
            }
            $geoPoint->display = (Yii::app()->request->getParam('markerType') && Yii::app()->request->getParam('markerType') == 1) ? 1 : 0;
            $geoPoint->house = Yii::app()->request->getParam('markerNumb');
            $geoPoint->comments = Yii::app()->request->getParam('markerName');
            $geoPoint->color = Yii::app()->request->getParam('markerColor');
            $geoPoint->team_id = $team->id;
            if($isDeleted == "true"){
                $geoPoint->delete();
            }
            elseif ($isNew) {
                $geoPoint->insert();
            } else {
                $geoPoint->save();
            }
            echo $geoPoint->id;
        }
    }

    public function actionCalculateHistory()
    {
        $previosValue = 0;
        $result = [];
        if ((Yii::app()->request->getParam('startDate')
            && Yii::app()->request->getParam('endDate')
            && Yii::app()->request->getParam('user'))
        || Yii::app()->request->getParam('dailyHistory')
        ) {
            $startDate = (Yii::app()->request->getParam('dailyHistory'))?date('Y-m-d h:i:s', time()-86400):Yii::app()->request->getParam('startDate');
            $endDate = (Yii::app()->request->getParam('dailyHistory'))?date('Y-m-d h:i:s', time()+86400):Yii::app()->request->getParam('endDate');
            $team = Team::getTeam();
            $user = (Yii::app()->request->getParam('dailyHistory'))?$team->user_host_id:Yii::app()->request->getParam('user');

            $usersArray = ($team->is_private==Team::TYPE_PRIVATE)?[$team->owner_id]:$team->getAllUsers();
            if (in_array($user, $usersArray)) {
                $criteria = new CDbCriteria;
                $criteria->condition = "user_api_id =:user_api_id and datetime_col > :start AND datetime_col < :end";
                $criteria->params = array(':user_api_id' => $user, ':start' => $startDate, ':end' => $endDate);
                $data = GeoLog::model()->findAll($criteria);
                $i = 0;

                foreach ($data as $row) {
                    if ($previosValue + 10 > $row->time ) {
                        continue;
                    }
                    if ($previosValue + 30 < $row->time) {
                        $i++;
                    }
                    $result[$i][] = [ 'latitude' => $row->latitude, 'longitude' => $row->longitude,
                        'speed'=>$row->speed, 'time'=>$row->time];
                    $previosValue = $row->time;
                }
            }
        }
        echo json_encode($result);
    }
    public function actionLoginByHash(){
        Yii::app()->session->clear();
        if(Yii::app()->request->getParam('hash')){
            $team = Team::model()->findByAttributes(['access_hash'=>Yii::app()->request->getParam('hash')]);
            if($team){
                Yii::app()->session['teamId'] = $team->id;
                Yii::app()->session['teamHash'] = Yii::app()->request->getParam('hash');
                if(isset($_GET['search']) && !empty($_GET['search'])){

                    $response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?format=json&geocode='.$_GET['search']);
                    $jsonData = json_decode($response, true);
                    if(isset($jsonData['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])){
                        $data = explode(' ',$jsonData['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'] ) ;
                        $team->end_point_lng =$data[0];
                        $team->end_point_lat =$data[1];
                    }



                    $team->end_point_name =$_GET['search'];
                    $team->save(false);
                }
            }
        }
        $this->redirect(array('metric/index'));
    }
    public function actionGenerateNewLink(){
        if (Yii::app()->request->isAjaxRequest) {
            $team = Team::getTeam();
            /**
             * @var $team Team
             */
            $team->generateSharingHash();
            echo $team->getSharingLink();
        }
    }

}