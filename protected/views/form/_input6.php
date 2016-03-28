<div class="data-setting">
    <select name="alphabet" id="alphabet" style="width: 90%">
        <option value="1">В алфавитном порядке</option>
        <option value="2">
     <?php if ($_GET["type"] == "block") {
    echo('В поряке расположения блоков');
} else {
    echo('Порядок неизвестен');
};?>
        </option>
    </select>
</div>