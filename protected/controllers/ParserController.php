<?php

class ParserController extends Controller
{

    public function actionSendposition()
    {



        echo 'OK';
                $geoLog = new GeoLog();
                $geoLog->longitude =$_POST['lon'];
                $geoLog->latitude = $_POST['lat'];
                $geoLog->user_id =$_POST['imei'];
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
                $geoUser->user_id = $_POST['imei'];
                $geoUser->time = time();
                $geoUser->insert();


        echo 'OK';

    }




}