<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
</main>

<footer class="footer">
    <div class="container footer__in">
        <div>
            <a href="/" class="logo">Брус<span>Вуд</span></a>
            <p class="footer__blurb">Проектируем и строим загородные дома из профилированного и клеёного бруса. Под ключ, с фиксированной сметой.</p>
        </div>
        <div>
            <div class="footer__title">Разделы</div>
            <ul class="footer__links">
                <li><a href="/catalog/">Каталог домов</a></li>
                <li><a href="/about/">О компании</a></li>
                <li><a href="/contacts/">Контакты</a></li>
            </ul>
        </div>
        <div>
            <div class="footer__title">Контакты</div>
            <ul class="footer__links">
                <li><?php $APPLICATION->IncludeComponent('bitrix:main.include', '', [
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_TEMPLATE_PATH . '/include/phone.php',
                    'EDIT_TEMPLATE' => '',
                ]); ?></li>
                <li><a href="mailto:info@bruswood.local">info@bruswood.local</a></li>
            </ul>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">© <?= date('Y') ?> БрусВуд · Строительство домов из бруса</div>
    </div>
</footer>

<script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/js/vendor/gsap.min.js"></script>
<script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/js/vendor/ScrollTrigger.min.js"></script>
<script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/js/vendor/SplitText.min.js"></script>
<script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/js/vendor/lenis.min.js"></script>
<script defer src="<?= SITE_TEMPLATE_PATH ?>/assets/js/app.js"></script>
</body>
</html>
