<?php

class MetricController extends Controller
{

    public function actionIndex()
    {


        if(Yii::app()->request->isPostRequest)
        {
            $geo=GeoOptions::getParm('metric_core');
            $geo->parameter=Yii::app()->request->getParam('cores', 0);
            $geo->update();
            GeoUnique::model()->updateAll(array('main'=>0));
            GeoUnique::model()->updateAll(array('main'=>1),'user_id="'.Yii::app()->request->getParam('mainPoint', 0).'"');

        }
        //$this->layout = false;
        $criteria = new CDbCriteria;
        $criteria->group = 'user_id';
        $criteria->order = 'time DESC';

        $geolocal = GeoUnique::model()->findAll($criteria);
        $geoPoints=GeoPoints::model()->findAll();
        $this->render('index', array('model' => $geolocal, 'geoPoints'=>$geoPoints));

    }

    public function actionGetData()
    {

        //set data if geoCore EXISTS
        {
            if(Yii::app()->request->getParam('mid') && Yii::app()->request->getParam('latitude') && Yii::app()->request->getParam('longitude')){
                $geoLog = new GeoLog();
                $geoLog->longitude =Yii::app()->request->getParam('longitude');
                $geoLog->latitude = Yii::app()->request->getParam('latitude');
                $geoLog->user_id =Yii::app()->request->getParam('mid');
                $geoLog->time = time();
                $geoLog->insert();

                $geoUser = GeoUnique::model()->findAll('user_id="' . Yii::app()->request->getParam('mid') . '"');
                if (isset($geoUser)) {
                    foreach ($geoUser as $userData) {
                        $userData->delete();
                    }
                }
                $geoUser = new GeoUnique();
                $geoUser->longitude = Yii::app()->request->getParam('longitude');
                $geoUser->latitude = Yii::app()->request->getParam('latitude');
                $geoUser->user_id = Yii::app()->request->getParam('mid');
                $geoUser->time = time();
                $geoUser->insert();
            }
        }



        $data = array();


            $geo=GeoOptions::getParm('metric_core');
            if($geo){
                $data['request'] = $geo->parameter;
            }

        //get endpoint core
        {
            $data['endPointCoreLat'] = 0;
            $data['endPointCoreLng'] = 0;

            $geo=GeoOptions::getParm('endPointCoreLat');
            if($geo){
                $data['endPointCoreLat'] = $geo->parameter;
            }

            $geo=GeoOptions::getParm('endPointCoreLng');
            if($geo){
                $data['endPointCoreLng'] = $geo->parameter;
            }
        }

        //prepare markers
       $currentMid = Yii::app()->request->getParam('mid');
        if($currentMid){
            $data['point'] = GeoUnique::getTeamPoints($currentMid);

        }

        $result[]=$data;
        echo json_encode($result);

    }


    public function actionAjaxbackend()
    {
        if (Yii::app()->request->isAjaxRequest) {


            $h_ua = str_replace('windows ce', '', strtolower($_SERVER['HTTP_USER_AGENT']));
            if (
                !$h_ua ||
                strpos($h_ua, 'windows') !== false
            ) {
                // it's computer - not show counter
            } else {


                $geoLog = new GeoLog();
                $geoLog->longitude = $_POST['longitude'];
                $geoLog->latitude = $_POST['latitude'];
                $geoLog->user_id = $_POST['user_id'];
                $geoLog->time = time();
                $geoLog->insert();

                $geoUser = GeoUnique::model()->findAll('user_id="' . $_POST['user_id'] . '"');
                if (isset($geoUser)) {
                    foreach ($geoUser as $userData) {
                        $userData->delete();
                    }
                }
                $geoUser = new GeoUnique();
                $geoUser->longitude = $_POST['longitude'];
                $geoUser->latitude = $_POST['latitude'];
                $geoUser->user_id = $_POST['user_id'];
                $geoUser->time = time();
                $geoUser->insert();
            }
        }

    }




    public function actionAjaxb()
    {



            $h_ua = str_replace('windows ce', '', strtolower($_SERVER['HTTP_USER_AGENT']));
            if (
                !$h_ua ||
                strpos($h_ua, 'windows') !== false
            ) {
                // it's computer - not show counter
            } else {


                $geoLog = new GeoLog();
                $geoLog->longitude = $_POST['lon'];
                $geoLog->latitude = $_POST['lat'];
                $geoLog->user_id = $_POST['imei'];
                $geoLog->time = time();
                $geoLog->insert();

                $geoUser = GeoUnique::model()->findAll('user_id="' . $_POST['imei'] . '"');
                if (isset($geoUser)) {
                    foreach ($geoUser as $userData) {
                        $userData->delete();
                    }
                }
                $geoUser = new GeoUnique();
                $geoUser->longitude = $_POST['lon'];
                $geoUser->latitude = $_POST['lat'];
                $geoUser->user_id = $_POST['trackid'];
                $geoUser->time = time();
                $geoUser->insert();
            }


    }



    public function actionGetCores()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $geolocal = GeoUnique::model()->find('main = 1');
            if(!$geolocal){
                $criteria = new CDbCriteria;
                $criteria->group = 'user_id';
                $criteria->order = 'time DESC';
                $geolocal = GeoUnique::model()->find($criteria);
            }

            $geos['start']=array($geolocal->latitude,$geolocal->longitude );
            $geos['end']='';
            $geos['end']=(preg_match('/[а-я]/', GeoOptions::getParm('metric_core')->parameter))?'Харьков '. GeoOptions::getParm('metric_core')->parameter:GeoOptions::getParm('metric_core')->parameter;

            $geos['icocolor']=$this->getColor($geolocal->time);
            $geos['corecolor']=$this->getCoreColor($geolocal->time);

            $geos['updated']='Updated '.(time()-$geolocal->time).' secs ago!';


            echo json_encode($geos, 1);
//            echo $geolocal->latitude.', '.$geolocal->longitude ;
//            if ($geolocal) {
//                foreach ($geolocal as $geo) {
//                    $geosp[$geo->user_id]['latitude'] = $geo->latitude;
//                    $geosp[$geo->user_id]['longitude'] = $geo->longitude;
//                }
//            }

        }
//        echo json_encode($geosp, 1);
    }

    public function actionGetAnotherPoints(){
        if (Yii::app()->request->isAjaxRequest) {
            $geos = array();
            $geolocals = GeoUnique::model()->findAll('main = 0');
            foreach ($geolocals as $geoLocal) {
                $geos[$geoLocal->user_id]['updated'] = 'Updated ' . (time() - $geoLocal->time) . ' secs ago!';
                $geos[$geoLocal->user_id]['cores'] = array($geoLocal->latitude, $geoLocal->longitude);
                $geos[$geoLocal->user_id]['title'] = $geoLocal->user_id;
                $geos[$geoLocal->user_id]['icocolor']=$this->getColor($geoLocal->time);
                $geos[$geoLocal->user_id]['corecolor']=$this->getCoreColor($geoLocal->time);
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

            $geo=GeoOptions::getParm('endPointCoreLat');
            if(!$geo){
                $geo = new GeoOptions;
                $geo->data="endPointCoreLat";
                $geo->parameter=$endPointCoreLat;
                $geo->insert(false);
            }
            $geo->parameter=$endPointCoreLat;
            $geo->save(false);

            $geo=GeoOptions::getParm('endPointCoreLng');
            if(!$geo){
                $geo = new GeoOptions;
                $geo->data="endPointCoreLng";
                $geo->parameter=$endPointCoreLng;
                $geo->insert(false);
            }
            $geo->parameter=$endPointCoreLng;
            $geo->save(false);

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