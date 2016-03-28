<?php

/**
 * This is the model class for table "geo_form".
 *
 * The followings are the available columns in table 'geo_form':
 * @property integer $id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property GeoFormCell[] $geoFormCells
 * @property GeoFormInputs[] $geoFormInputs
 */
class GeoForm extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'geo_form';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type', 'required'),
            array('type', 'length', 'max' => 32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type', 'safe', 'on' => 'search'),
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
            'geoFormCells' => array(self::HAS_MANY, 'GeoFormCell', 'form_id'),
//			'geoFormInputs' => array(self::HAS_MANY, 'GeoFormInputs', 'form_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => 'Type',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GeoForm the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * Функция генерация составного кода для бла бла...
     *
     * @param $inputs
     * @return array
     */

    public function getCellFromBlock($inputs)
    {

//        ...

        return array('vav asf', ' asf vav');
    }


    /**
     * Функция генерации "код +числовой индекс"
     *
     * @param $inputs массив исходных данных с формы
     * @return array массив всех возможный комбинаций
     */
    public function generateCodeNumber($inputs)
    {
        settype($inputs["minVal"], "integer");
        settype($inputs["maxVal"], "integer");
        $output = array();
        if ($inputs["minVal"] > $inputs["maxVal"]) {
            $inputs["maxVal"] = $inputs["minVal"];
        };
        for ($i = $inputs["minVal"]; $i <= $inputs["maxVal"]; $i++) {
            array_push($output, $inputs["code"] . $inputs["divide"] . $i);
        };
//        var_dump($output);
        return $output;
    }

    /**
     * Функция генерации составного кода, где неизвестен порядок кодов
     *
     * @param $inputs массив исходных данных с формы
     * @return array массив всех возможный комбинаций
     */
    public function generateCodeSequence($inputs)
    {
        /*Подготовка исходных данных для отпрапавки в генератор*/
        $frozen = array();
        $codes = array();
        $divide = '';
        foreach ($_POST as $key => $value) {
            if ($key == 'divide') {
                $divide = substr($_POST['divide'], 0);
            } elseif ($key == 'fixedinfo') {
                $frozen = explode(',', $_POST['fixedinfo']);
            }
            if (is_integer(strripos($key, 'code'))) {
                $codes[$key] = $value;
            }
        }
        array_unshift($frozen, '0');
        /*Формирование массива со значениями замороженх инпутов*/
        foreach ($frozen as $key => $value) {
            if ($value > 0) {
                if (isset($codes['code' . $value])) {
                    $frozen[$key] = $codes['code' . $value];
                    unset($codes['code' . $value]);
                }
            } else {
                unset($frozen[$key]);
            }
        }
        $output = array();
        $level = 0;


       /* функция генератора*/
        function recursion($inarray, $readystring, $outputarray, $frozen, $divide, $level)
        {
            $level++;
            /*проверка, заморожен ли этот уровень*/
            if (isset($frozen[$level])) {
                ($level > 1 ? $divideFirst = $divide : $divideFirst = '');
                $readystring = ($readystring . $divideFirst . $frozen[$level]);
                unset($frozen[$level]);
                $outputarray = recursion($inarray, $readystring, $outputarray, $frozen, $divide, $level);
                return $outputarray;
            } else {
                /*проверка последний ли это элемент*/
                if ((count($inarray) > 1)) {
                    $tempstring = $readystring;
                    foreach ($inarray as $key => $value) {
                        unset($inarray[$key]);
                        ($level > 1 ? $divideFirst = $divide : $divideFirst = '');
                        $readystring = ($tempstring . $divideFirst . $value);
                        $outputarray = recursion($inarray, $readystring, $outputarray, $frozen, $divide, $level);
                        $inarray[$key] = $value;
                    }
                    return $outputarray;
                } else {
                    (count($frozen) > 0 ? $addstr = $divide . implode($divide, $frozen) : $addstr = '');
                    $readystring = ($readystring . $divide . implode('', $inarray) . $addstr);
                    array_push($outputarray, $readystring);
                    return $outputarray;
                }
            }
        }


        return (recursion($codes, '', $output, $frozen, $divide, $level));
    }


    public function generateCodeCorrect($inputs)
    {
//        var_dump($_POST);
        $frozen = array();
        $codes = array();
        $divide = '';
        $alphabet = 1;
        $minVal = 1;
        $maxVal = 0;
        foreach ($_POST as $key => $value) {
            if ($key == 'minVal') {
                $minVal = ($_POST['minVal']);
            }
            if ($key == 'maxVal') {
                $maxVal = ($_POST['maxVal']);
            }
            if ($key == 'alphabet') {
                $alphabet = (substr($_POST['alphabet'], 0))-1;
            }
            if ($key == 'divide') {
                $divide = substr($_POST['divide'], 0);
            } elseif ($key == 'fixedinfo') {
                $frozen = explode(',', $_POST['fixedinfo']);
            }
            if (is_integer(strripos($key, 'code'))) {
                $codes[$key] = $value;
            }
        }
        foreach ($frozen as $key => $value) {
            if ($value > 0) {
                if (isset($codes['code' . $key])) {
                    $frozen[$key] = $codes['code' . $key];
                }
            } else {
                unset($frozen[$key]);
            }
        }
        asort($codes);


        /* функция генератора*/
        function recursion($inarray, $readystring, $outputarray, $frozen, $divide, $level,$codeNum,$alphabet)
        {
            $level++;
            $tempFrozen=$frozen;
            if ($level == $codeNum) {
                $tempstring = $readystring;

                foreach ($inarray as $key => $value) {
                    ($level > 1 ? $divideFirst = $divide : $divideFirst = '');
                    $frozen=$tempFrozen;
                    if (in_array($value,$frozen))
                    {
                        unset($frozen[array_search($value,$frozen)]);
                    }
                    $readystring = ($tempstring . $divideFirst . $value);
                    if(empty($frozen)){
                        array_push($outputarray, $readystring);}
                }
                return $outputarray;
            } else {
                if ((count($inarray) > 1)) {
                    $tempstring = $readystring;
                        foreach ($inarray as $key => $value) {
                        $frozen=$tempFrozen;
                        unset($inarray[$key]);

                        if (in_array($value,$frozen))
                        {
                            unset($frozen[array_search($value,$frozen)]);
                        }
                        ($level > 1 ? $divideFirst = $divide : $divideFirst = '');
                        $readystring = ($tempstring . $divideFirst . $value);
                        if (!empty($inarray)){
                        $outputarray = recursion($inarray, $readystring, $outputarray, $frozen, $divide, $level,$codeNum,$alphabet);
                        }
                            if ($alphabet == 1) ($inarray[$key] = $value);
                    }
                    return $outputarray;
                } else {
                    return $outputarray;
                }  }}
        $level = 0;
        $codeNum = 1 ;
        $outputRender = array();
        $output = array();
        ;
        if (($minVal) < 1 ) {$minVal = 1;};
        if ($minVal > count($codes)) {$minVal = count($codes);};
        if ( $maxVal==0 || ($maxVal > count($codes))){
            $maxVal = count($codes);
        }
        for($i=$minVal;$i<=$maxVal;$i++)
        {
            $codeNum = $i;
            $output = array_merge($output,(recursion($codes, '', array(), $frozen, $divide, $level,$codeNum,$alphabet)));
        }

        return($output);
       }

    /**
     * функция генерации значений блокового инпута
     * @param $inputs данные с формы
     * @return array возможных комбинаций
     */

    public function generateCodeBlock($inputs)
    {
        $frozen = array();
        $codes = array();
        $divide = '';
        $alphabet = 1;
        $minVal = 1;
        $maxVal = 0;
        foreach ($_POST as $key => $value) {
            if ($key == 'alphabet') {
                $alphabet = (substr($_POST['divide'], 0))-1;
            }
            if ($key == 'divide') {
                $divide = substr($_POST['divide'], 0);
            } elseif ($key == 'fixedinfo') {
                $frozen = explode(',', $_POST['fixedinfo']);
            }
            if (is_integer(strripos($key, 'inp'))) {
                $codes[substr($key,-5,1)][substr($key,-3,1)][substr($key,-1)]=$value;
            }
        }
            function recursion ($codes,$readyArray,$outputArray,$level){
            $level ++;
            $tempArray = $readyArray;

            foreach ($codes[$level] as $key => $value)
            {

                $readyArray= $tempArray;
                $readyArray = array_merge($readyArray,$value);
                if (count($codes) == $level)
                {
                    array_push($outputArray,$readyArray);
                }
                else{
                $outputArray = array_merge($outputArray,recursion($codes,$readyArray,$outputArray,$level));
                }
            }
            return ($outputArray);
        };
        $result = ((recursion($codes,array(),array(),0)));
        $result = array_map("unserialize", array_unique( array_map("serialize", $result) ));
        $output = array();
        foreach ($result as $key=>$value)
        {
            if ($alphabet == 0){sort($value);};
            array_push($output,implode($divide,$value));
        }
        return $output;
    }



}
