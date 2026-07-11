<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */
/** @var array $arParams */
?>
<form name="<?= $arResult['FILTER_NAME'] ?>_form" action="<?= $arResult['FORM_ACTION'] ?>" method="get" class="filter">

    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
        <?php
        if (empty($arItem['VALUES']) || !empty($arItem['PRICE'])) {
            continue;
        }
        $isRange = isset($arItem['VALUES']['MIN']) && isset($arItem['VALUES']['MAX']);
        ?>
        <fieldset class="filter__group">
            <legend class="filter__title"><?= $arItem['NAME'] ?></legend>

            <?php if ($isRange): ?>
                <?php $min = $arItem['VALUES']['MIN']; $max = $arItem['VALUES']['MAX']; ?>
                <div class="filter__range">
                    <input type="number" inputmode="numeric"
                           name="<?= $min['CONTROL_NAME'] ?>"
                           value="<?= $min['HTML_VALUE'] ?>"
                           placeholder="от <?= number_format((float)$min['VALUE'], 0, '', ' ') ?>"
                           aria-label="<?= $arItem['NAME'] ?>, от">
                    <span class="filter__range-sep" aria-hidden="true">–</span>
                    <input type="number" inputmode="numeric"
                           name="<?= $max['CONTROL_NAME'] ?>"
                           value="<?= $max['HTML_VALUE'] ?>"
                           placeholder="до <?= number_format((float)$max['VALUE'], 0, '', ' ') ?>"
                           aria-label="<?= $arItem['NAME'] ?>, до">
                </div>
            <?php else: ?>
                <ul class="filter__list">
                    <?php foreach ($arItem['VALUES'] as $ar): ?>
                        <li>
                            <label class="filter__check<?= $ar['DISABLED'] ? ' is-disabled' : '' ?>">
                                <input type="checkbox"
                                       name="<?= $ar['CONTROL_NAME'] ?>"
                                       value="<?= $ar['HTML_VALUE'] ?>"
                                       <?= $ar['CHECKED'] ? 'checked' : '' ?>
                                       <?= $ar['DISABLED'] ? 'disabled' : '' ?>>
                                <span><?= $ar['VALUE'] ?></span>
                                <?php if (isset($ar['ELEMENT_COUNT'])): ?>
                                    <em><?= $ar['ELEMENT_COUNT'] ?></em>
                                <?php endif; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </fieldset>
    <?php endforeach; ?>

    <div class="filter__actions">
        <button type="submit" name="set_filter" value="Y" class="btn">Показать</button>
        <button type="submit" name="del_filter" value="Y" class="btn btn--ghost">Сбросить</button>
    </div>
</form>
