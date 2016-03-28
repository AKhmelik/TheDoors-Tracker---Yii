<input type="hidden" name="type" value="block">
<div class="inp3"><div id="clickDummy" style="float: left"></div>
    <div id="col1" class="outer-box">
        <div id="field1_1" class="inner-box">
            <div>
                <input id="inp1_1_1" name="inp1_1_1" type="text" class="input-mini input-box"></div>
            <div>
                <button style="margin-top: 10px; margin-bottom: 10px" id="add1_1" type="button"
                        class="add-inp btn btn-primary button-width">+
                </button>
                <button style="margin-top: 10px; margin-bottom: 10px" id="rem1_1" type="button"
                        class="rem-inp btn btn-danger button-width">-
                </button>
            </div>
        </div>

        <div>
            <button id="addfie_1" type="button" class="btn btn-primary button-width add-fie"
                    style="margin-top: 10px; margin-bottom: 10px">+
            </button>
            <button id="remfie_1" type="button" class="btn btn-danger button-width rem-fie"
                    style="margin-top: 10px; margin-bottom: 10px">-
            </button>
        </div>
    </div>
    <div id="coladd" class="outer-box">
        <div class=" inner-box">
            <div>
                <button id="addcol" type="button" class="btn btn-primary button-width add-col">+ колонка</button>
            </div>
            <div>
                <button id="remcol" type="button" class="btn btn-danger button-width rem-col">- колонка</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var colVal = 1;
    var inputStatus = [
        [1]
    ];

    /*    Добавление интпутов*/
    $('body').on('click','.add-inp', function (e) {
        e.preventDefault();
            var fieldNum = this.id.charAt(this.id.length - 1);
            var colNum = this.id.charAt(this.id.length - 3);
        inputStatus[colNum - 1][fieldNum - 1]++;
        if ((inputStatus[colNum - 1][fieldNum - 1])<11) {
        var newInp = '<div><input id="inp' + colNum + '_' + fieldNum + '_' + inputStatus[(colNum - 1)][fieldNum - 1] + '" name="inp' + colNum + '_' + fieldNum + '_' + inputStatus[(colVal - 1)][fieldNum - 1] + '" type="text" class="input-mini input-box"></div>';
        $(this).before(newInp);
        }
        else
        {
            inputStatus[colNum - 1][fieldNum - 1]--;
        }
    });
    /*    Удаление интпутов*/
    $('body').on('click','.rem-inp', function (e) {
        e.preventDefault();
        var fieldNum = this.id.charAt(this.id.length - 1);
        var colNum = this.id.charAt(this.id.length - 3);
        var delInp = '#inp' + colNum + '_' + fieldNum + '_' + inputStatus[(colNum - 1)][fieldNum - 1];
        if (inputStatus[colNum - 1][fieldNum - 1] > 1) {
            inputStatus[colNum - 1][fieldNum - 1]--;
            $(delInp).remove();
        }
    });
    /*    Добавление полей*/
    $('body').on('click','.add-fie', function (e) {
        e.preventDefault();
        var colNum = this.id.charAt(this.id.length - 1);
        var fieldNum = (inputStatus[colNum-1].length + 1);
        inputStatus[(colNum - 1)][((inputStatus[(colNum - 1)].length))] = 1;
        var newFie = '<div id="field' + colNum + '_' + fieldNum + '" class="inner-box"><div><input id="inp'+ colNum + '_' + fieldNum + '_1" name="inp'+ colNum + '_' + fieldNum + '_1" type="text" class="input-mini input-box"></div><div><button style="margin-top: 10px; margin-bottom: 10px" id="add' + colNum +'_' +fieldNum +'" type="button" class="add-inp btn btn-primary button-width">+</button> <button style="margin-top: 10px; margin-bottom: 10px" id="rem' + colNum +'_' +fieldNum +'" type="button" class="rem-inp btn btn-danger button-width">-</button></div></div>';
        $(this).before(newFie);
        $("#clickDummy").trigger('click');
    });
    /*    Удаление полей*/
    $('body').on('click','.rem-fie', function (e) {
        e.preventDefault();
        var colNum = this.id.charAt(this.id.length - 1);
        var fieldNum = (inputStatus[colNum-1].length);
        if (fieldNum > 1) {
        inputStatus[colNum - 1].splice((inputStatus[(colNum - 1)].length - 1),1);
/*        delete inputStatus[(colNum - 1)][((inputStatus[(colNum - 1)].length) - 1)];*/
        var delFie = '#field' + colNum +'_' + fieldNum;
        $(delFie).remove();
            $("#clickDummy").trigger('click');
        }
    });
    /*    Добавление колонок*/
    $(".add-col").click(function (e) {
        e.preventDefault();
        colVal++;
        inputStatus[colVal - 1] = [1];
        var newDiv = '<div id="col'+ colVal + '" class="outer-box"><div id="field'+ colVal + '_1" class="inner-box"><div><input id="inp'+ colVal + '_1_1" name="inp'+ colVal + '_1_1" type="text" class="input-mini input-box"></div><div><button style="margin-top: 10px; margin-bottom: 10px" id="add'+ colVal + '_1" type="button" class="add-inp btn btn-primary button-width">+</button><button style="margin-top: 10px; margin-bottom: 10px" id="rem'+ colVal + '_1" type="button" class="rem-inp btn btn-danger button-width">-</button></div></div><div><button id="addfie_'+ colVal + '" type="button" class="btn btn-primary button-width add-fie" style="margin-top: 10px; margin-bottom: 10px">+</button><button id="remfie_'+ colVal + '" type="button" class="btn btn-danger button-width rem-fie" style="margin-top: 10px; margin-bottom: 10px">-</button></div></div>';
            $("#coladd").before(newDiv);
        $("#clickDummy").trigger('click');
    });
    /*    Удаление колонок*/
    $(".rem-col").click(function (e) {
        e.preventDefault();
        if (colVal > 1) {
        colVal--;
        inputStatus.splice(colVal, 1);
        var delFie = '#col' + (colVal + 1);
        $(delFie).remove();
        }
        $("#clickDummy").trigger('click');
    });
    /*Определение числа вариантов*/
    $("#clickDummy").click(function () {
        var variNum = 0;
        var n = 1;
        while (variNum < (colVal)) {
                n = n * inputStatus[variNum].length;
            variNum++;
        }
        $(".uneditable-input").text(n + ' вариант/ов');
    });
    $("#clickDummy").trigger('click');
</script>