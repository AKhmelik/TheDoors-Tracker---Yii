<?php
if (isset ($_GET['type'])) {
    if (($_GET['type'] == 'sequence')) {
        $formType = 'sequence';
    }
    if (($_GET['type'] == 'block')) {
        $formType = 'block';
    }
    if (($_GET['type'] == 'correct')) {
        $formType = 'correct';
    }
    if (($_GET['type'] == 'number')) {
        $formType = 'number';
    }
} else {
    $formType = 'number';
};
?>
<div class="container-fluid">
    <div class="row">
        <div class="span2 navigation-border">
            <ul class="nav nav-list">
                <li class="nav-header">Числовой индекс</li>
                <li class="<?php if ($formType == 'number') {
                    print 'active';
                } ?>">
                    <?php echo CHtml::link('Подбор числового индекса', array('form/create',
                        'type' => 'number')); ?></li>
                <li class="nav-header">Составной код</li>
                <li class="<?php if ($formType == 'sequence') {
                    print 'active';
                } ?>">
                    <?php echo CHtml::link('Подбор правильного порядка', array('form/create',
                        'type' => 'sequence')); ?></li>
                <li class="<?php if ($formType == 'correct') {
                    print 'active';
                } ?>">
                    <?php echo CHtml::link('Подбор правильных составляющих', array('form/create',
                        'type' => 'correct')); ?></li>
                <li class="<?php if ($formType == 'block') {
                    print 'active';
                } ?>">
                    <?php echo CHtml::link('Подбор по блочной структуре кода', array('form/create',
                        'type' => 'block')); ?>
                </li>
            </ul>
        </div>
        <!--        <form>-->
        <?php echo CHtml::beginForm(); ?>
        <div class="span7 data-form-border">
            <?php
            if ($formType == 'sequence') {
                $this->renderPartial('_input5');
                $this->renderPartial('_input2');
                $scriptPath = Yii::app()->AssetManager->publish(Yii::app()->baseUrl . 'js/form-sequence.js');
                $cs = Yii::app()->getClientScript();
                $cs->registerScriptFile($scriptPath, CClientScript::POS_END);
            } elseif ($formType == "correct") {
                $this->renderPartial('_input2');
            } elseif ($formType == "block") {
                $this->renderPartial('_input3');
            } else {
                $this->renderPartial('_input1');
            }
            ?>
        </div>
        <div class="span3  data-form-options">
            <div class="data-background">
                <?php
                if ($formType == "sequence") {
                    $this->renderPartial('_input4');
                    $this->renderPartial('_input7');
                } elseif ($formType == "correct") {
                    $this->renderPartial('_input4');
                    $this->renderPartial('_input8');
                    $this->renderPartial('_input6');
                    $this->renderPartial('_input7');
                    $scriptPath = Yii::app()->AssetManager->publish(Yii::app()->baseUrl . 'js/form-correct.js');
                    $cs = Yii::app()->getClientScript();
                    $cs->registerScriptFile($scriptPath, CClientScript::POS_END);
                } elseif ($formType == "block") {
                    $this->renderPartial('_input4');
                    $this->renderPartial('_input6');
                    $this->renderPartial('_input7');
                } else {
                    $this->renderPartial('_input7');
                };
                ?>
                <div style="text-align: center;padding: 10px">
                    <button type="submit" class="btn btn-large btn-danger">Сгенерировать</button>


                </div>
                <div class="alert">
                    <?php
                    if ($formType == "sequence") {
                        $this->renderPartial('_info_sequence');
                    } elseif ($formType == "correct") {
                        $this->renderPartial('_info_correct');
                    } elseif ($formType == "block") {
                        $this->renderPartial('_info_block');
                    } else {
                        $this->renderPartial('_info_number');
                    };
                    ?>
                </div>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
        <!--        </form>-->
    </div>
</div>