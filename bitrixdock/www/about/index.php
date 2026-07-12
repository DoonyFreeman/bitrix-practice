<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("О компании БрусВуд");
$APPLICATION->SetPageProperty("description", "БрусВуд строит дома из профилированного и клеёного бруса с 2012 года: собственное производство, фиксированная смета, 5 лет гарантии на конструктив.");
?>

<div class="page-head">
    <div class="container">
        <h1>Компания, которая строит из дерева уже 14 лет</h1>
        <p class="page-head__lead">Мы начинали с одного домокомплекта в год, а сейчас закрываем 15–20 проектов ежегодно — но принцип не изменился: считать смету один раз и не менять её на площадке.</p>
    </div>
</div>

<section class="section about-history">
    <div class="container about-history__grid">
        <div class="about-history__text" data-reveal>
            <h2>С чего всё началось</h2>
            <p>В 2012 году три плотника из-под Приозерска собрали первый домокомплект для себя — без подрядчиков и посредников, от чертежа до кровли. Соседи попросили построить так же им, потом друзьям соседей — так вырос небольшой цех, а из цеха выросла компания.</p>
            <p>Сегодня у БрусВуда собственное производство под Санкт-Петербургом: там же, где режут и профилируют брус, его нумеруют, упаковывают и грузят на площадку — без промежуточных складов и накруток за логистику.</p>
        </div>
        <figure class="about-history__media" data-reveal>
            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/about/process-1.jpg" alt="Стропильная система дома из бруса на этапе сборки" loading="lazy">
        </figure>
    </div>
</section>

<section class="section section--soft about-tech">
    <div class="container">
        <header class="section-head" data-reveal>
            <h2>Профилированный или клеёный: в чём разница</h2>
        </header>
        <div class="tech-grid">
            <article class="tech-card" data-reveal>
                <h3>Профилированный брус</h3>
                <p>Цельный массив камерной сушки с фрезерованным профилем «шип-паз» — венцы плотно садятся друг на друга, без щелей на продувание. Даёт усадку 3–5% в первый год: отделку и окна ставим после того, как сруб отстоится.</p>
                <p class="tech-card__meta">Дешевле на 20–30% · срок от 30 дней · усадка есть</p>
            </article>
            <article class="tech-card" data-reveal>
                <h3>Клеёный брус</h3>
                <p>Несколько ламелей одной породы склеены со сдвигом волокон — конструкция стабильнее массива и почти не «играет» от влажности. Усадки нет, поэтому чистовую отделку и остекление можно начинать сразу после сборки коробки.</p>
                <p class="tech-card__meta">Не даёт усадки · остекление сразу · выше геометрическая точность</p>
            </article>
        </div>
    </div>
</section>

<section class="section about-process">
    <div class="container about-process__grid">
        <div class="about-process__text" data-reveal>
            <h2>Как устроена стройка</h2>
            <p>Любой проект — от «Ладоги» на 54 м² до «Вуоксы» на два этажа — проходит одни и те же пять шагов: проект и смета, фундамент, сборка сруба на площадке, кровля с окнами, отделка под ключ. Смету фиксируем в договоре на первом шаге — дальше она не меняется, даже если что-то на площадке пошло не по плану.</p>
            <a class="link-arrow" href="/#steps">Смотреть этапы подробно<span aria-hidden="true"> →</span></a>
        </div>
        <div class="about-process__photos" data-reveal>
            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/about/process-2.jpg" alt="Сборка каркаса дома" loading="lazy">
            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/about/process-3.jpg" alt="Брусья на солнце крупным планом" loading="lazy">
        </div>
    </div>
</section>

<section class="section section--soft about-values">
    <div class="container">
        <header class="section-head" data-reveal>
            <h2>На чём строим репутацию</h2>
        </header>
        <div class="values-grid">
            <article class="value-card" data-reveal>
                <div class="value-card__num">5 лет</div>
                <p>гарантии на конструктив сруба — прописано в договоре, а не на словах</p>
            </article>
            <article class="value-card" data-reveal>
                <div class="value-card__num">1 смета</div>
                <p>фиксируется на старте и не растёт по ходу стройки, даже при задержках</p>
            </article>
            <article class="value-card" data-reveal>
                <div class="value-card__num">61%</div>
                <p>клиентов приходят по рекомендации — большинство сделок без рекламы</p>
            </article>
            <article class="value-card" data-reveal>
                <div class="value-card__num">своё</div>
                <p>производство под Петербургом: без посредников между цехом и площадкой</p>
            </article>
        </div>
    </div>
</section>

<section class="cta">
    <img class="cta__bg" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/cta.jpg" alt="" loading="lazy">
    <div class="cta__scrim" aria-hidden="true"></div>
    <div class="container cta__in" data-reveal>
        <h2>Готовы обсудить свой проект</h2>
        <p class="cta__lead">Покажем каталог, посчитаем смету под ваш участок и материал.</p>
        <a class="btn btn--light" href="/catalog/">Смотреть проекты</a>
    </div>
</section>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
