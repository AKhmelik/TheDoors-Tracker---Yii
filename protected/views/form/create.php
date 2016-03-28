<?php
/* @var $this FormController */
/* @var $model GeoForm */

$this->breadcrumbs = array(
    'Geo Forms' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List GeoForm', 'url' => array('index')),
    array('label' => 'Manage GeoForm', 'url' => array('admin')),
);
?>

<h1>Create GeoForm</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>
<?php

?>



<div style="codegen-inputfield">
    <div>
        INF1
    </div>
    <button id="bplus" class="btn add-inp" type="button">+</button>
</div>
<div id="workField">
    <div class="span6 text-text">
        <input type="hidden" name="count" value="1"/>

        <div class="control-group" id="fields">
            <label class="control-label" for="field1">Фрагменты кода</label>

            <div class="controls" id="profs">
                <form class="input-append">
                    <div id="field">
                        <div id="field-wrap1" style="padding: 10px">

                            <input autocomplete="off" class="input" id="field1" name="Order[keywords][]" type="text"
                                   placeholder="Фрагмент кода" data-items="8"/>
                            <button id="b1" class="btn add-more btn-primary" type="button">+</button>
                            <button id="field-fix1" class="fixfield btn btn-medium" href="#"><i
                                    class="icon-lock"></i> fix
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="span4 text-text">
        <label class="control-label" style="padding: 10px" for="field2">Параметры генерации</label>
        <div id="clickDummy"></div>
        <div>
            <select id="divide">
                <option value="1">Без разделения фрагментов</option>
                <option value="2">Пробел</option>
                <option value="3">Точка</option>
                <option value="4">Запятая</option>
            </select>
<div class="preview-outer">
            <div id="preview" class="preview-inner" >
            </div>
</div>
            <div id="variables" class="preview-outer" style="padding-top: 10px; text-align: center;">
            <span class="input-small uneditable-input">888</span></div>
        </div>
    </div>
</div>



<script type="text/javascript">
    /*    var scriptWorking = false;*/
    $("#submit-order").click(function (e) {
        e.preventDefault();
        $("#order-form").submit();
    });
    var inputStatus = [0];
    var conInpNum = 0;
    var next = 1;
    var valueClear = 0;
    /*var divideTypes = ["","\u0020",".",","];*/
    var buttonColors = ["\u0020", "btn-success", "btn-warning", "btn-danger", "btn-info"];
/*    var labelColors = ["\u0020", "label-success", "label-warning", "label-important", "label-info"];*/
    var labelColors = buttonColors;
    /*    if (scriptWorking==false) {
     var scriptWorking = true;*/

    $(".add-more").click(function (i) {
        i.preventDefault();
        var addto = "#field";
        var addRemove = "#field" + (next);
        inputStatus [next] = 0;
        next = next + 1;

        var newIn = ' <div id="field-wrap' + next + '" style="padding: 10px" ><input autocomplete="off" placeholder="Фрагмент кода" class="input form-control" id="field' + next + '" name="Order[keywords][]" type="text"><button id="remove' + (next) + '" class="btn remove-me" >-</button><button id="field-fix' + (next) + '" class="fixfield btn btn-medium" href="#"><i class="icon-lock"></i> fix</button></div></div><div id="field">';
        var newInput = $(newIn);
        //  var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div></div><div id="field">';
        // var removeButton = $(removeBtn);
        $(addto).append(newInput);
        //$(addRemove).append(removeButton);
        $("#field" + next).attr('data-source', $(addto).attr('data-source'));
        $("#count").val(next);
        /*        var scriptWorking = false;*/
        $("#clickDummy").trigger('click');
        $('.remove-me').click(function (q) {
            q.preventDefault();
            var fieldNum = this.id.charAt(this.id.length - 1);
            var fieldID = "#field" + fieldNum;
            var fieldFix = "#field-fix" + fieldNum;
            var fieldDiv = "#field-wrap" + fieldNum;
            $(this).remove();
            $(fieldID).remove();
            $(fieldFix).remove();            $(fieldDiv).remove();
            inputStatus.forEach(function (val, ind) {
                if (val == fieldNum) {
                    inputStatus[ind] = 0;
                }
            });
            delete inputStatus [(fieldNum - 1)];
            if (fieldNum == 0) {
                inputStatus [0] = 0;
            }
            $("#clickDummy").trigger('click');
        });
        $(".fixfield").click(function (r) {
            r.preventDefault();
            valueClear = 0;
            var fieldNum = this.id.charAt(this.id.length - 1);
            inputStatus.forEach(function (val, ind) {
                if (val == fieldNum) {
                    inputStatus[ind] = 0;
                    valueClear = 1;

                }
            });
            console.log(conInpNum)
            if ((conInpNum < 4) && (valueClear==0)) {
                valueClear = 0;
                $("#clickDummy").trigger('click');
                $("#preview").addClass('shine');
                setInterval(function(){},1000);
                    $(".prevpart").click(function (u) {
                        u.preventDefault();
                        var spanNum = (this.id.match(/\d/g));
                        inputStatus[spanNum] = fieldNum;
                        $("#clickDummy").trigger('click');
                    });
            }
            else {
                $("#clickDummy").trigger('click');
            }
        });

    });
    /*    };*/
</script>

<script type="text/javascript">
    $("#clickDummy").click(function (o) {
        o.preventDefault();
            $("#particCode").remove();
            $("#preview-place").remove();
            conInpNum = 0;
            var removePlace = ".preview-place";
            $(removePlace).remove();
            var addDiv = '<div id="preview-place"></div>';
            $("#preview").append(addDiv);
            $(".fixfield").removeClass(buttonColors.join("\u0020"));
            $(".prevpart").removeClass(labelColors.join("\u0020"));
            inputStatus.forEach(function (elementValue, elementIndex) {
                var addLabelClass = "";
                var addSymbol = "*";
                if (elementValue > 0) {
                    conInpNum++;
                    addLabelClass = labelColors[conInpNum];
                    var fiC = '#field-fix' + elementValue;
                    $(fiC).addClass(buttonColors[conInpNum]);
                    addSymbol = '<i class="icon-lock lock-symbol"></i>';
                }
                var addLabel = '<span id = "particCode' + (elementIndex) + '"class="preview-text prevpart ' + addLabelClass + ' btn label">' + addSymbol + '</span>';
                $("#preview-place").append(addLabel);
                $("#preview").removeClass('shine');
            });
        var variNum = "1";
        var n = 0;
        inputStatus.forEach(function (val) {

            if (val == 0)

            {
                n ++;
                variNum = variNum*n;
            }
        });
        $(".uneditable-input").text(variNum + ' вариант');
    });
    $("#clickDummy").trigger('click');
</script>


