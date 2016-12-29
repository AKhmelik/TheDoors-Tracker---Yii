<?php
/**
 * Created by PhpStorm.
 * User: glx
 * Date: 29.12.2016
 * Time: 5:46
 */
class LanguageSelector extends CWidget
{
    public function run()
    {
        $currentLang = Yii::app()->language;
        $languages = Yii::app()->params->languages;
        $this->render('languageSelector', array('currentLang' => $currentLang, 'languages'=>$languages));
    }
}