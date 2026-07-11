<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>

<section class="not-found">
    <div class="container">
        <div class="not-found__code" aria-hidden="true">404</div>
        <h1>Такой страницы нет</h1>
        <p class="not-found__lead">Возможно, проект переехал в другой раздел или адрес набран с опечаткой.</p>
        <div class="not-found__actions">
            <a href="/catalog/" class="btn">Смотреть каталог</a>
            <a href="/" class="btn btn--ghost">На главную</a>
        </div>
    </div>
</section>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
