<?php

/**
 * This is the model class for table "geo_track".
 *
 * The followings are the available columns in table 'geo_track':
 * @property integer $id
 * @property integer $owner_id
 * @property string $hash
 * @property string $time
 * @property integer $enabled
 *
 * The followings are the available model relations:
 * @property GeoLog[] $geoLogs
 * @property Users $owner
 */
class GeoTrack extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geo_track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time', 'required'),
			array('owner_id, enabled', 'numerical', 'integerOnly'=>true),
			array('hash', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner_id, hash, time, enabled', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'geoLogs' => array(self::HAS_MANY, 'GeoLog', 'track_id'),
			'owner' => array(self::BELONGS_TO, 'Users', 'owner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Owner',
			'hash' => 'Hash',
			'time' => 'Time',
			'enabled' => 'Enabled',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('enabled',$this->enabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeoTrack the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    function getDistance($lat1, $lon1, $lat2, $lon2, $unit="K") {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

	public function prepareStats(){
        $maxSpeed = 0;
        $speedAVG = 0;
        $routeTime = 0;
        $routeDistance = 0;
        $i = 0;

        $stastTime = 0;
        $lat=0;
        $lng=0;
        $speeds = [];
        $condition = ['track_id'=>$this->id];
        $logs =  GeoLog::model()->findAllByAttributes($condition, array('order'=>'id DESC'));
        if($logs && !(empty($logs))){
            foreach ($logs as $log) {
                if ($i == 0) {
                    $stastTime = $log->time;
                    $time =$log->time;
                } else {
                    $dist = $this->getDistance($log->latitude, $log->longitude, $lat, $lng);
                    $deltaTime =  $log->time - $time ;
                    if($deltaTime!=0 && !is_nan($dist)){
                        $routeDistance += $dist;
                    }
                }
                if ($log->speed != "0.0") {
                    $speeds[] = $log->speed;
                }

                $lng = $log->longitude;
                $lat = $log->latitude;
                $time =$log->time;
                $i++;
            }

            $routeTime = $stastTime- $log->time ;
            $routeTime = gmdate("H:i:s", $routeTime);
        }

        if(count($speeds)>0){
            $speedAVG=  array_sum($speeds) / count($speeds)*3.6;
            $maxSpeed =max($speeds)*3.6;
        }

        return ['maxSpeed'=>round($maxSpeed, 2),
            'speedAVG'=>round($speedAVG, 2),
            'routeTime'=>$routeTime,
        'distance'=>round($routeDistance, 2)];
    }

	public static function prepareGeoLogStat($userId){
	    $geoLog = [];
        $condition = ['owner_id'=>$userId, 'enabled'=>1];
        $tracks =  self::model()->findAllByAttributes($condition, array('order'=>'id DESC'));
        if($tracks && !(empty($tracks))){
            foreach ($tracks as $track){
                /**
                 * @var $track GeoTrack
                 */
                $data['time']= $track->time;
                $data['hash']= $track->hash;
                $geoLog[]=$data;
            }
        }

        return $geoLog;
    }
}
