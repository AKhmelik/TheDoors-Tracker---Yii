
$("#submit-order").click(function (e) {
    e.preventDefault();
    $("#order-form").submit();
});
function factorial(n) {
return n ? n*factorial(n-1) : 1;
}
var inputStatus = [0];
var next = 1;
var inputNum = 1;
var fixedNum = 0;
var buttonColors = ["\u0020", "btn-danger", "btn-warning", "btn-success", "btn-info", "btn-primary"];
/*    var labelColors = ["\u0020", "label-success", "label-warning", "label-important", "label-info"];*/
var labelColors = buttonColors;
/*действия при добавлении строк*/
$('body').on('click','.add-more', function () {
    var addto = "#field";
    var addRemove = "#field" + (next);
    inputStatus [next] = 0;
    next = next + 1;
    inputNum ++;
    var newIn = ' <div id="field-wrap' + next + '" style="padding: 10px" ><input autocomplete="off" placeholder="Фрагмент кода" class="input form-control" id="field' + next + '" name="code' + next +'" type="text"><button id="remove' + (next) + '" class="btn remove-me" >-</button><button id="field-fix' + (next) + '" class="fixfield btn btn-medium" href="#"><i class="icon-lock"></i> fix</button></div></div><div id="field">';
    var newInput = $(newIn);
    $(addto).append(newInput);
    $("#field" + next).attr('data-source', $(addto).attr('data-source'));
    $("#clickDummy").trigger('click');
});
$('body').on('click','.remove-me', function () {
        var fieldNum = this.id.charAt(this.id.length - 1);
        var fieldID = "#field" + fieldNum;
        var fieldFix = "#field-fix" + fieldNum;
        var fieldDiv = "#field-wrap" + fieldNum;
        $(this).remove();
        $(fieldID).remove();
        $(fieldFix).remove();
          inputNum --;
        $(fieldDiv).remove();
        inputStatus.forEach(function (val, ind) {
            if (val == fieldNum) {
                inputStatus[ind] = 0;
            }
        });
        inputStatus [(fieldNum - 1)] = 0;
        if (fieldNum == 0) {
            inputStatus [0] = 0;
        }
        $("#clickDummy").trigger('click');

});
$('body').on('click','.fixfield', function (e) {
    e.preventDefault();
    var fieldNum = this.id.charAt(this.id.length - 1);
    if ((inputStatus[fieldNum] != 1)) {
        inputStatus[fieldNum] = 1;
    }
    else
    {
        inputStatus[fieldNum] = 0;
    }
    $("#clickDummy").trigger('click');
});
$("#clickDummy").click(function (o) {
    o.preventDefault();
    fixedNum = 0;
    var variNum = "0";
        $(".fixfield").removeClass(labelColors.join("\u0020"));
    inputStatus.forEach(function (elementValue, elementIndex) {
        if (elementValue > 0) {
            fixedNum++;
            var fiC = '#field-fix' + elementIndex;
            $(fiC).addClass(buttonColors[1]);
        }
    });
    var minVal;
    if ($('#minVal').val() >= 1)
    {
        if ($('#minVal').val() < inputNum)
        {
            minVal = $('#minVal').val();
        }
        else
        {
            minVal = inputNum;
        }
    }
    else
    {
        minVal = 1;
    }
    var maxVal;
    if ((inputNum > $('#maxVal').val()) && ($('#maxVal').val() >= 1))
    {
        if ($('#maxVal').val() > minVal)
        {
            maxVal = $('#maxVal').val();
        }
        else
        {
            maxVal = minVal;
        }
    }
    else
    {
        maxVal = inputNum;
    }
    var fixNum = 0;

    inputStatus.forEach(function (val) {
        fixNum = fixNum + val;
    });
    if (minVal>1) {
        minVal = minVal-fixNum;
    }
    maxVal = maxVal-fixNum;
    inputNum = inputNum-fixNum;
    var variants = 0;
    var coefAlph;
    for (i=minVal;i<=maxVal;i++)
    {
        if ($('#alphabet').val() == 2) {coefAlph = i} else {coefAlph = 1 }
        variants = variants + (coefAlph * factorial(inputNum))/(factorial(i) * factorial((inputNum - i)))
    }
    inputNum = inputNum+fixNum;
    $(".fixedinfo").val(inputStatus);
    $(".uneditable-input").text(variants + ' вариант/ов');
});

$(".input-mini").change(function(){
    $("#clickDummy").trigger('click');
});
$("#alphabet").change(function(){
    $("#clickDummy").trigger('click');
});
$("#clickDummy").trigger('click');
