<ul class="nav nav-list">
    <li class="nav-header">Числовой индекс</li>
    <li class="<?php if ($formType == 'number') {print 'active';} ?>"><a href="form?type=number">Подбор числового индекса</a></li>
    <li class="nav-header">Составной код</li>
    <li class="<?php if ($formType == 'sequence') {print 'active';} ?>"><a href="form?type=sequence">Подбор правильного порядка</a></li>
    <li class="<?php if ($formType == 'correct') {print 'active';} ?>"><a href="form?type=correct">Подбор правильных составляющих</a></li>
    <li class="<?php if ($formType == 'block') {print 'active';} ?>"><a href="form?type=block">Подбор по блочной структуре кода</a></li>
</ul>
