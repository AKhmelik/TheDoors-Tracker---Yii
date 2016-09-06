<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 8]>
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />-->
<!--    <![endif]-->

    	<link rel="stylesheet" type="text/css" href="
    <?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    	<link rel="stylesheet" type="text/css" href="
    <?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="main-wraper" id="page">
    <?php

    $this->widget('bootstrap.widgets.TbAlert', array(
        'fade' => true,
        'closeText' => '&times;', // false equals no close link
        'events' => array(),
        'htmlOptions' => array(),
        'userComponentId' => 'user',
        'alerts' => array( // configurations per alert type
            // success, info, warning, error or danger
            'success' => array('closeText' => '&times;'),
            'info', // you don't need to specify full config
            'warning' => array('closeText' => false),
            'error' => array('closeText' => '&times;')
        ),
    ));

    $customForm = (Yii::app()->controller->id == 'metric') ? '
                              <form class="navbar-search pull-left" method="post">
                                  <input data-placement="bottom" data-content="'.Yii::t('app','Type end point coordinates or street').'" type="text" name="cores" id="search-query-main" class="search-query" value="' . $this->endPointName . '" placeholder="Aдрес / Координаты">
                                  <select id="user-select" data-placement="bottom" data-content="'.Yii::t('app','Select user for routing').'" name="mainPoint">' . GeoUnique::getSelectPoint() . '</select>
                                   <input class="btn btn-success" type="button" id="submit-search"  name="sub" value="'.Yii::t('app', 'Search').'">
                                  <input class="btn btn-success" id="submit-form" type="submit" name="sub" value="'.Yii::t('app', 'Set Route').'">
                              </form>' : '';

    echo CHtml::openTag('div', array('class' => 'bs-navbar-top-example'));
    $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'type' => null, // null or 'inverse'
            'brand' => '',

            'brandUrl' => '#',
            'collapse' => true, // requires bootstrap-responsive.css
            //'fixed' => false,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array(
//                        // array('label' => 'Codes Generator', 'url' => '#'),
//
//                    ),
                        array('label' => Yii::t('app', 'Home') , 'url' => array('/metric/index')),
//                        array('label' => 'Generaror', 'url' => array('/form/create')),
                        array('label' => Yii::t('app', 'Group') , 'url' => array('/team/index'), 'visible'=>!Yii::app()->user->isGuest),

                        array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), 'visible' => Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"), 'visible' => Yii::app()->user->isGuest),
                        array('url' =>  array('/user/profile/edit'), 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),

                    ),
                ),
                $customForm

            ),
        )
    );
    echo CHtml::closeTag('div');

    //        echo CHtml::openTag('div', array('class' => 'bs-navbar-top-example'));
    //        $this->widget(
    //            'bootstrap.widgets.TbNavbar',
    //            array(
    //                'brand' => 'Title',
    //                'brandOptions' => array('style' => 'width:auto;margin-left: 0px;'),
    //                'fixed' => 'top',
    //                'htmlOptions' => array('style' => 'position:absolute'),
    //                'items' => array(
    //                    array(
    //                        'class' => 'bootstrap.widgets.TbMenu',
    //                        'items' => array(
    //                            array('label' => 'Home', 'url' => '#', 'active' => true),
    //                            array('label' => 'Link', 'url' => '#'),
    //                            array('label' => 'Link', 'url' => '#'),
    //                        )
    //                    )
    //                )
    //            )
    //        );
    //        echo CHtml::closeTag('div');

    ?>


    <!-- mainmenu -->
    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
        )); ?><!-- breadcrumbs -->
    <?php endif ?>

        <?php echo $content; ?>

    <div class="clear"></div>

    <div id="footer">
        <!--		Copyright &copy; --><?php //echo date('Y'); ?><!-- by My Company.<br/>-->
        <!--		All Rights Reserved.<br/>-->
        <!--		--><?php //echo Yii::powered(); ?>
    </div>

</div>
<!-- page -->


</body>
</html>
