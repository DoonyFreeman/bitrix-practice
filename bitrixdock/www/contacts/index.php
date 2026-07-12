<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты — БрусВуд");
$APPLICATION->SetPageProperty("description", "Офис БрусВуд в Санкт-Петербурге: адрес, телефон, e-mail и часы работы. Приезжайте посмотреть образцы бруса и обсудить проект.");
?>

<div class="page-head">
    <div class="container">
        <h1>Контакты</h1>
        <p class="page-head__lead">Приезжайте в офис посмотреть образцы бруса и планировки — или начните с звонка.</p>
    </div>
</div>

<section class="section contacts">
    <div class="container contacts__grid">
        <div class="contacts__card" data-reveal>
            <dl class="contacts__list">
                <div class="contacts__row">
                    <dt>Адрес</dt>
                    <dd><?php $APPLICATION->IncludeComponent('bitrix:main.include', '', [
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_TEMPLATE_PATH . '/include/address.php',
                        'EDIT_TEMPLATE' => '',
                    ]); ?></dd>
                </div>
                <div class="contacts__row">
                    <dt>Телефон</dt>
                    <dd><?php $APPLICATION->IncludeComponent('bitrix:main.include', '', [
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_TEMPLATE_PATH . '/include/phone.php',
                        'EDIT_TEMPLATE' => '',
                    ]); ?></dd>
                </div>
                <div class="contacts__row">
                    <dt>E-mail</dt>
                    <dd><a href="mailto:info@bruswood.local">info@bruswood.local</a></dd>
                </div>
                <div class="contacts__row">
                    <dt>Часы работы</dt>
                    <dd>Пн–Сб, 9:00–19:00</dd>
                </div>
            </dl>
            <a class="btn" href="tel:+79215550188">Позвонить</a>
        </div>

        <div class="contacts__map" data-reveal aria-label="Схема проезда: Пулковское шоссе, 25">
            <svg class="contacts__map-svg" viewBox="0 0 480 360" role="img" aria-hidden="true">
                <rect width="480" height="360" fill="var(--paper-soft)"/>
                <path d="M0 120 H480 M0 220 H480" stroke="var(--line)" stroke-width="2"/>
                <path d="M120 0 V360 M340 0 V360" stroke="var(--line)" stroke-width="2"/>
                <path d="M0 170 Q 240 140 480 190" stroke="var(--forest)" stroke-width="4" fill="none" opacity="0.35"/>
                <circle cx="240" cy="175" r="9" fill="var(--amber)"/>
                <path d="M240 155 C 224 155 212 167 212 183 C 212 205 240 232 240 232 C 240 232 268 205 268 183 C 268 167 256 155 240 155 Z" fill="var(--forest)"/>
                <circle cx="240" cy="182" r="7" fill="var(--paper)"/>
            </svg>
            <span class="contacts__map-label">Пулковское шоссе, 25</span>
        </div>
    </div>
</section>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
