<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("БрусВуд — загородные дома из бруса под ключ");
$APPLICATION->SetPageProperty("description", "Проектируем и строим тёплые загородные дома из профилированного и клеёного бруса под ключ. Фиксированная смета, сроки от 30 дней.");
?>

<section class="hero" id="hero">
    <div class="hero__media">
        <picture>
            <source media="(max-width: 767px)" srcset="<?= SITE_TEMPLATE_PATH ?>/assets/img/hero-portrait.jpg">
            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/hero.jpg" alt="Дом из бруса с тёплой подсветкой в вечернем лесу" fetchpriority="high">
        </picture>
        <div class="hero__scrim" aria-hidden="true"></div>
    </div>
    <div class="container hero__content">
        <h1 class="hero__title">Дома из бруса,<br>в которые хочется возвращаться</h1>
        <p class="hero__lead">Проектируем и строим загородные дома под ключ. Фиксированная смета, сроки от 30 дней.</p>
        <div class="hero__cta">
            <a class="btn btn--light" href="/catalog/">Смотреть проекты</a>
        </div>
    </div>
</section>

<?php /* Числа компании — демонстрационные данные учебного проекта */ ?>
<section class="section stats">
    <div class="container">
        <div class="stats__grid">
            <div class="stat" data-reveal>
                <div class="stat__num"><span data-count="14">14</span></div>
                <div class="stat__label">лет строим дома из бруса</div>
            </div>
            <div class="stat" data-reveal>
                <div class="stat__num"><span data-count="183">183</span></div>
                <div class="stat__label">дома сдано под ключ</div>
            </div>
            <div class="stat" data-reveal>
                <div class="stat__num"><span data-count="61">61</span><span class="stat__unit">%</span></div>
                <div class="stat__label">клиентов приходят по рекомендации</div>
            </div>
            <div class="stat" data-reveal>
                <div class="stat__num"><span data-count="5">5</span></div>
                <div class="stat__label">лет гарантии на конструктив</div>
            </div>
        </div>
    </div>
</section>

<section class="section section--soft featured">
    <div class="container">
        <header class="section-head" data-reveal>
            <h2>Проекты, с которых начинают</h2>
            <a href="/catalog/" class="link-arrow">Весь каталог<span aria-hidden="true"> →</span></a>
        </header>
        <?php
        // ponytail: подборка по кодам; при росте каталога — свойство «показывать на главной»
        $GLOBALS['featuredFilter'] = ['CODE' => ['onega', 'moroshka', 'vuoksa']];
        $APPLICATION->IncludeComponent('bitrix:catalog.section', 'featured', [
            'IBLOCK_TYPE' => 'catalog',
            'IBLOCK_ID' => '1',
            'SECTION_ID' => '',
            'SECTION_CODE' => '',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'FILTER_NAME' => 'featuredFilter',
            'ELEMENT_SORT_FIELD' => 'sort',
            'ELEMENT_SORT_ORDER' => 'asc',
            'PAGE_ELEMENT_COUNT' => '3',
            // современный catalog.section ждёт код свойств с суффиксом ID инфоблока
            'PROPERTY_CODE_1' => ['PRICE', 'AREA', 'BEDROOMS', 'FLOORS', 'SIZE'],
            'SET_TITLE' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'ADD_SECTIONS_CHAIN' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '3600',
            'CACHE_GROUPS' => 'N',
        ]); ?>
    </div>
</section>

<section class="steps" id="steps">
    <div class="container steps__layout">
        <div class="steps__text">
            <h2 class="steps__heading">Как мы строим</h2>
            <div class="steps__items">
                <article class="steps__item is-active">
                    <span class="steps__num">01</span>
                    <h3>Проект и смета</h3>
                    <p>Выбираете проект или адаптируем под вас. Смета фиксируется в договоре и не растёт по ходу стройки.</p>
                </article>
                <article class="steps__item">
                    <span class="steps__num">02</span>
                    <h3>Фундамент</h3>
                    <p>Свайно-винтовой или мелкозаглублённая лента — по грунту участка. Готов за 7–14 дней.</p>
                </article>
                <article class="steps__item">
                    <span class="steps__num">03</span>
                    <h3>Сборка сруба</h3>
                    <p>Домокомплект приезжает с нашего производства пронумерованным — коробка собирается за 2–4 недели.</p>
                </article>
                <article class="steps__item">
                    <span class="steps__num">04</span>
                    <h3>Кровля и окна</h3>
                    <p>Закрываем тёплый контур: кровля с утеплением, окна, входная дверь. Дом больше не зависит от погоды.</p>
                </article>
                <article class="steps__item">
                    <span class="steps__num">05</span>
                    <h3>Отделка и ключи</h3>
                    <p>Инженерия, полы, лестница, терраса. Принимаете дом по чек-листу — и заезжаете.</p>
                </article>
            </div>
            <div class="steps__progress" aria-hidden="true"><span class="steps__bar"></span></div>
        </div>
        <div class="steps__media" aria-hidden="true">
            <img class="steps__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/steps/step-1.jpg" alt="">
            <img class="steps__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/steps/step-2.jpg" alt="" loading="lazy">
            <img class="steps__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/steps/step-3.jpg" alt="" loading="lazy">
            <img class="steps__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/steps/step-4.jpg" alt="" loading="lazy">
            <img class="steps__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/steps/step-5.jpg" alt="" loading="lazy">
        </div>
    </div>
</section>

<section class="cta">
    <img class="cta__bg" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/cta.jpg" alt="" loading="lazy">
    <div class="cta__scrim" aria-hidden="true"></div>
    <div class="container cta__in" data-reveal>
        <h2>Обсудим ваш будущий дом?</h2>
        <p class="cta__lead">Расскажем про проекты, посчитаем смету под ваш участок.</p>
        <a href="tel:+79215550188" class="btn btn--light">+7 921 555-01-88</a>
    </div>
</section>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
