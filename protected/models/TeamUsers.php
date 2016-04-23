<?php

/**
 * This is the model class for table "{{team_users}}".
 *
 * The followings are the available columns in table '{{team_users}}':
 * @property integer $team_id
 * @property integer $user_id
 * @property string $status
 */
class TeamUsers extends CActiveRecord
{
    const STATUS_INVITED = 0;
    const STATUS_IN_TEAM = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{team_users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('team_id, user_id, status', 'required'),
			array('team_id, user_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('team_id, user_id, status', 'safe', 'on'=>'search'),
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
            'User'    => array(self::BELONGS_TO, 'User',  'user_id'),
            'Team'    => array(self::BELONGS_TO, 'Team',   'team_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'team_id' => 'Team',
			'user_id' => 'User',
			'status' => 'Status',
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

		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TeamUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function addUserToTeam($userId){
        $teamUser = new TeamUsers();
        $teamUser->team_id =1;
        $teamUser->user_id =$userId;
        $teamUser->status = self::STATUS_IN_TEAM;
        $teamUser->save(false);
    }
}
