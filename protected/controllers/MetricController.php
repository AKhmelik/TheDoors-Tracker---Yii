<?php

class MetricController extends Controller
{

    public function filters()
    {
        return array( 'accessControl' ); // perform access control for CRUD operations
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
            array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('getdata'),
                'users'=>array('*'),
            ),
            array('deny'),
        );
    }

    public function actionIndex()
    {

        $userId = Yii::app()->user->getId();
        $user = User::model()->findByPk($userId);
        $team = $user->getTeam();

        if(Yii::app()->request->isPostRequest)
        {
            $codes = Yii::app()->request->getParam('cores', 0);
            $mainUserId = Yii::app()->request->getParam('mainPoint', 0);

            if($team->is_private == TblTeam::TYPE_PRIVATE){
                $team->end_point_name =$codes;
                $team->save(false);

            }
            else{
                $team->end_point_name =$codes;
                if($team->getUserIdArray()){
                    if(in_array($mainUserId, $team->getUserIdArray())){
                        $team->user_host_id =$mainUserId;
                    }
                }
                $team->save(false);
            }

}

        $geoPoints=GeoPoints::model()->findAllByAttributes(array('team_id'=>$team->id));
        $this->render('index', array('model' => array(), 'geoPoints'=>$geoPoints));

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

                        $geoLog = new GeoLog();
                        $geoLog->longitude =Yii::app()->request->getParam('longitude');
                        $geoLog->latitude = Yii::app()->request->getParam('latitude');
                        $geoLog->user_id =Yii::app()->request->getParam('mid');
                        $geoLog->user_api_id =$user->id;
                        $geoLog->time = time();
                        $geoLog->insert();

                        GeoUnique::model()->deleteAllByAttributes(array('user_api_id'=>$user->id));

                        $geoUser = new GeoUnique();
                        $geoUser->longitude = Yii::app()->request->getParam('longitude');
                        $geoUser->latitude = Yii::app()->request->getParam('latitude');
                        $geoUser->user_id = Yii::app()->request->getParam('mid');
                        $geoUser->user_api_id = $user->id;
                        $geoUser->time = time();
                        $geoUser->insert();

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

            $userId = Yii::app()->user->getId();
            $user = User::model()->findByPk($userId);
            $team = $user->getTeam();

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
            $userId = Yii::app()->user->getId();
            $user = User::model()->findByPk($userId);
            $team = $user->getTeam();
            $usersIds = $team->getUserIdArray();

            foreach ($usersIds as $userId) {
                if($team->user_host_id !=$userId){
                    $geoLocal = GeoUnique::getByUserId($userId);
                    $geos[$geoLocal->user_id]['updated'] = 'Updated ' . (time() - $geoLocal->time) . ' secs ago!';
                    $geos[$geoLocal->user_id]['cores'] = array($geoLocal->latitude, $geoLocal->longitude);
                    $geos[$geoLocal->user_id]['title'] = User::getUserIdentityById($userId);
                    $geos[$geoLocal->user_id]['icocolor']=$this->getColor($geoLocal->time);
                    $geos[$geoLocal->user_id]['corecolor']=$this->getCoreColor($geoLocal->time);
                }
            }
            echo json_encode($geos, 1);
        }
    }

    public function getColor($time){
        if((time()-$time)<10){
            return 'twirl#greenIcon';
        }
        elseif((time()-$time)<60){
            return 'twirl#yellowIcon';
        }
        elseif((time()-$time)<120){
            return 'twirl#redIcon';
        }
        else{
            return  'twirl#blackIcon';
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

            $userId = Yii::app()->user->getId();
            $user = User::model()->findByPk($userId);
            $team = $user->getTeam();
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
        $userId = Yii::app()->user->getId();
        $user = User::model()->findByPk($userId);
        $team = $user->getTeam();

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


}