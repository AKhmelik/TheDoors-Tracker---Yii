$("#submit-order").click(function (e) {
    e.preventDefault();
    $("#order-form").submit();
});

var inputStatus = [0];
var conInpNum = 0;
var next = 1;
var valueClear = 0;
var buttonColors = ["\u0020", "btn-danger", "btn-warning", "btn-success", "btn-info", "btn-primary"];
/*    var labelColors = ["\u0020", "label-success", "label-warning", "label-important", "label-info"];*/
var labelColors = buttonColors;
/*действия при добавлении строк*/
$(".add-more").click(function (i) {
    i.preventDefault();
    var addto = "#field";
    var addRemove = "#field" + (next);
    inputStatus [next] = 0;
    next = next + 1;

    var newIn = ' <div id="field-wrap' + next + '" style="padding: 10px" ><input autocomplete="off" placeholder="Фрагмент кода" class="input form-control" id="field' + next + '" name="code' + next +'" type="text"><button id="remove' + (next) + '" class="btn remove-me" >-</button><button id="field-fix' + (next) + '" class="fixfield btn btn-medium" href="#"><i class="icon-lock"></i> fix</button></div></div><div id="field">';
    var newInput = $(newIn);
    $(addto).append(newInput);
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
        $(fieldFix).remove();
        $(fieldDiv).remove();
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
        if ((conInpNum < 5) && (valueClear == 0)) {
            valueClear = 0;
            $("#clickDummy").trigger('click');
            $(".prevpart").addClass('shine');
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
        var addLabel = '<div id = "particCode' + (elementIndex) + '"class="preview-text prevpart ' + addLabelClass + ' btn label">' + addSymbol + '</div>';
        $("#preview-place").append(addLabel);
        $("#preview").removeClass('shine');
    });
    var variNum = "1";
    var n = 0;
    inputStatus.forEach(function (val) {
        if (val == 0) {
            n++;
            variNum = variNum * n;
        }
    });
    $(".fixedinfo").val(inputStatus);
    $(".uneditable-input").text(variNum + ' вариант/ов');
});
$("#clickDummy").trigger('click');
