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
class TblTeam extends CActiveRecord
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
			'name' => 'Name',
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
	 * @return TblTeam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getUserIdArray(){

        $users = TblTeamUsers::model()->findAllByAttributes(array('team_id'=>$this->id, 'status'=>TblTeamUsers::STATUS_IN_TEAM));
        $userIds = null;
        if($users && !empty($users)){
            foreach($users as $teamUser){
                $userIds[] = $teamUser->user_id;
            }
        }
        return $userIds;
    }
}
