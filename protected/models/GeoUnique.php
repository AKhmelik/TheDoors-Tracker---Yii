<?php

/**
 * This is the model class for table "geo_unique".
 *
 * The followings are the available columns in table 'geo_unique':
 * @property integer $id
 * @property string $longitude
 * @property string $latitude
 * @property string $user_id
 * @property integer $time
 */
class GeoUnique extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geo_unique';
	}

    public static function getSelectPoint($isAll=false){
        $html = '';
        $team = Team::getTeam();
        if($isAll){
            if($team && $team->is_private==Team::TYPE_PRIVATE){
                $userArray = [$team->owner_id];
            }
            else{
                $userArray = ($team)?$team->getAllUsers():[];
            }
        }
        else{
            if($team && $team->is_private==Team::TYPE_PRIVATE){
                $userArray = [$team->owner_id];
            }
            else{
                $userArray = ($team)?$team->getUsersInMap():[];
            }
        }
        if($userArray){
            foreach ($userArray as $userId) {
                $selected= '';
                if($userId == $team->user_host_id){
                    $selected='selected="selected"';
                }

                $html.='<option '.$selected.'value="'.$userId.'">'.User::getUserIdentityById($userId).'</option>';
            }
        }

        return $html;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('longitude, latitude, user_id, time', 'required'),
			array('time', 'numerical', 'integerOnly'=>true),
			array('longitude, latitude', 'length', 'max'=>32),
			array('user_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, longitude, latitude, user_id, time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'user_id' => 'User',
			'time' => 'Time',
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
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeoUnique the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getTeamPoints($userIdArray, $excludeId =0){


        $arrayPoint = array();


        if($userIdArray){
            $criteria = new CDbCriteria;
            $criteria->condition = "user_api_id !=:user_api_id and time > ".strtotime('-60 minutes', time());
            $criteria->params = array(':user_api_id' => $excludeId);
            $criteria->addInCondition('user_api_id', $userIdArray);
            $points = self::model()->findAll($criteria);

            foreach ($points as $key => $point) {
                /**
                 * @var $point GeoUnique
                 */
                $arrayPoint[$key]['latitude']= $point->latitude;
                $arrayPoint[$key]['longitude']= $point->longitude;
                $arrayPoint[$key]['id']=  User::getUserIdentityById($point->user_api_id);
            }
        }
        $user = User::model()->findByPk($excludeId);
        if($user){
            $team =$user->getTeam();
            $arrayPoint[]=['latitude'=>(string)$team->end_point_lat, 'longitude'=>(string)$team->end_point_lng, 'id'=>(string)$team->end_point_name];

        }
        return $arrayPoint;
    }

    public static function getByUserId($userId){
        return self::model()->findByAttributes(array('user_api_id'=>$userId));
    }
}
