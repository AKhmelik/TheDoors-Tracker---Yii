<?php

/**
 * This is the model class for table "{{team}}".
 *
 * The followings are the available columns in table '{{team}}':
 * @property integer $id
 * @property string $is_private
 * @property integer $owner_id
 * @property string $name
 * @property double $end_point_lat
 * @property double $end_point_lng
 * @property string $end_point_name
 *
 * The followings are the available model relations:
 * @property Users $owner
 * @property Users[] $tblUsers
 */
class Team extends CActiveRecord
{

    const TYPE_PRIVATE = 1;
    const TYPE_PUBLIC = 0;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_team';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('name','required'),
			array('owner_id', 'numerical', 'integerOnly'=>true),
			array('end_point_lat, end_point_lng', 'numerical'),
			array('is_private', 'length', 'max'=>1),
			array('name, end_point_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, is_private, owner_id, name, end_point_lat, end_point_lng, end_point_name', 'safe', 'on'=>'search'),
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
			'owner' => array(self::BELONGS_TO, 'Users', 'owner_id'),
			'tblUsers' => array(self::MANY_MANY, 'Users', '{{team_users}}(team_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'is_private' => 'Is Private',
			'owner_id' => 'Owner',
			'name' => Yii::t('app','Group Name'),
			'end_point_lat' => 'End Point Lat',
			'end_point_lng' => 'End Point Lng',
			'end_point_name' => 'End Point Name',
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
		$criteria->compare('is_private',$this->is_private,true);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('end_point_lat',$this->end_point_lat);
		$criteria->compare('end_point_lng',$this->end_point_lng);
		$criteria->compare('end_point_name',$this->end_point_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Team the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getAllUsers($showInMap = false ){
    $condition = array('team_id'=>$this->id, 'status'=>TeamUsers::STATUS_IN_TEAM);
    if($showInMap){
        $condition['show_in_map'] = $showInMap;
    }

    $users = TeamUsers::model()->findAllByAttributes($condition);
    $userIds = null;
    if($users && !empty($users)){
        foreach($users as $teamUser){
            $userIds[] = $teamUser->user_id;
        }
    }
    return $userIds;
}


    public function getUsersInMap(){

        $users = TeamUsers::model()->findAllByAttributes(array(
            'team_id'=>$this->id,
            'status'=>TeamUsers::STATUS_IN_TEAM,
            'show_in_map'=> TeamUsers::DISPLAY_MAP));
        $userIds = null;
        if($users && !empty($users)){
            foreach($users as $teamUser){
                $userIds[] = $teamUser->user_id;
            }
        }
        return $userIds;
    }

    public static function getTeam(){
        if (Yii::app()->user->isGuest) {
            return (isset(Yii::app()->session['teamId'])) ? Team::model()->findByPk(Yii::app()->session['teamId']) : null;
        }
        else{
            $userId = Yii::app()->user->getId();
            if(!$userId){return false;}
            $user = User::model()->findByPk($userId);
            return $user->getTeam();
        }
    }

    /**
     * returns custom team markers
     * @param int $display
     * @return array
     * @author a.khmelik 2016-06-16
     */
    public function getTeamMarkers($display=GeoPoints::DISPLAY_BOTH){
        $arrayPoint = array();
        $criteria = new CDbCriteria;
        $criteria->condition = "team_id =:team_id and display =:display";
        $criteria->params = array(':team_id' => $this->id, ':display'=> $display);
        $markers = GeoPoints::model()->findAll($criteria);
        foreach ($markers as $key => $marker) {

            /**
             * @var $marker GeoPoints
             */
            $coresExploded =  explode(', ', $marker->cores);
            $arrayPoint[$key]['latitude']= $coresExploded[0];
            $arrayPoint[$key]['longitude']= $coresExploded[1];
            $arrayPoint[$key]['id']=  $marker->id;
            $arrayPoint[$key]['house']=  $marker->house;
            $arrayPoint[$key]['comment']=  $marker->comments;
        }

        return $arrayPoint;
    }

    /**
     * Returns sharing link
     * @return mixed|null
     * @author a.khmelik 2016-09-02
     */
    public function getSharingLink(){
        return (!$this->access_hash)?Yii::app()->getBaseUrl(true)."/metric/loginByHash?hash=".$this->generateSharingHash():Yii::app()->getBaseUrl(true)."/metric/loginByHash?hash=".$this->access_hash;
    }

    /**
     * Generates sharing hash
     * @return string
     * @author a.khmelik 2016-09-02
     */
    public function generateSharingHash(){
        $newHash = md5($this->id.time());
        $this->access_hash = $newHash;
        $this->save(false);
       return $newHash;
    }

}
