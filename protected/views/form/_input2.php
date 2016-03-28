<input type="hidden" name="type" value="<?php if ($_GET["type"] == 'sequence') {print 'sequence';} else {print 'correct';} ?>"/>

<div id="clickDummy"></div>
<div class="control-group" id="fields" style="text-align: center; ">


    <div class="controls input-append" id="profs" style="text-align: center; ">

        <div id="field">
            <div id="field-wrap1" style="padding: 10px">

                <input autocomplete="off" class="input" id="field1" name="code1" type="text"
                       placeholder="Фрагмент кода" data-items="8"/>
                <button id="b1" class="btn add-more btn-primary" type="button">+</button>
                <button id="field-fix1" class="fixfield btn btn-medium" type="button" href="#"><i
                        class="icon-lock"></i> fix
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="fixedinfo" name="fixedinfo" value="0"/>

