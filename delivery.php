<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Антикварный салон - Крым, г. Симферополь, ул. Толстого 9 - антиквариат Крым, антикварный магазин Симферополь</title>
<meta name="description" content="Официальный веб сайт Антикварного Салона, Крым, г. Симферополь, ул. Толстого 9"> 
<meta name="keywords" content="антиквариат крым, антикварный магазин симферополь, антикварный, салон, магазин, лавка, симферополь, крым, толстого, антиквар, антикварная">
<meta name="robots" content="all"> 
<meta name="revisit-after" content="1 week"> 
<meta name="author" content="Vyacheslav Subbotin"> 

<link rel="stylesheet" href="antiqua.css">
<link rel="icon" type="image/png" href="./images/favicon3.png" />
<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="imgmgr.js"></script>
</head>
<body>
<?php
require_once 'auth.php';
require_once 'imgmgr.php';
require_once 'analytics.php';

$edit = isset($_SESSION[InputVar::admin]);

DrawHeader($edit);

//echo phpinfo();

echo '<div style="background-color: #1F0B0A; text-align: center; position: relative;">';

echo '<div style="display: inline-block; position: absolute; left: 2px; bottom: 36px; z-index: 1">';
echo $analyticsCodeLiveInternet;
echo '</div>';

echo '<div style="display: inline-block; position: absolute; left: 2px; bottom: 2px; z-index: 1">';
echo $analyticsCodeYandex;
echo '</div>';

echo '<div style="display: inline-block; position: absolute; left: 86px; bottom: 2px; z-index: 1">';
echo $analyticsCodeGoogle;
echo '</div>';

$conn = db_connect();
$rootID = Item::get_root_id();
$root = Item::CreateItem($rootID);
db_close();

echo '<input type="hidden" id="' . Html::hidID . '" name="' . Html::hidID . '" value="'. $rootID . '" />';
echo '<input type="hidden" id="' . Html::hidImagesID . '" name="' . Html::hidImagesID . '" value="'. $root->get_images() . '" />';
echo '<input type="hidden" id="' . Html::rootImagesWidthID . '" name="' . Html::rootImagesWidthID . '" value="'. SystemVars::rootImagesWidth . '" />';
echo '<table style="vertical-align: top; width: 100%" class="'. Css::tableCommon . '"><tr><td style="vertical-align: top;">';
echo '<div id="' . Html::divThumbsID . '" style="overflow: hidden; max-width: 400px; max-height: 1200px"></div>';
echo '</td><td style="vertical-align: top; width: 100%">';

echo '<div style="position: relative; background-color: #5F1904; height: 21px; width: 100%">';
echo '<div style="height: 2px;"></div>';
echo '<div style="color: #FFCD70; font-size: 16px; font-weight: bold; margin-left: 4px;\">Оплата и доставка</div>';
echo '</div>';

echo "<div style=\"margin: 8px;\">

<p align=\"justify\" style=\"color: #E4D49B; font-size: 18px;\">
Мы принимаем как наличные, так и безналичные рубли.
</p>

<p align=\"justify\" style=\"font-size: 18px;\">
Для оплаты по безналу - вышлем счёт на указанный Вами адрес.
</p>

<hr style=\"border: 1px solid #E4D49B;\">

<p align=\"justify\" style=\"color: #E4D49B; font-size: 18px;\">
Реквизиты для оплаты (безналичные перечисления в рублях РФ):
</p>

<p align=\"justify\" style=\"font-size: 18px;\">
<span style=\"color: #E4D49B;\">Получатель: </span>КРЫЛОВ АЛЕКСЕЙ ВИКТОРОВИЧ<br>
<span style=\"color: #E4D49B;\">Банк получателя: </span>РНКБ (ОАО) г. Москва<br>
<span style=\"color: #E4D49B;\">Лицевой счет: </span>40817810041670010816<br>
<span style=\"color: #E4D49B;\">ИНН: </span>7701105460<br>
<span style=\"color: #E4D49B;\">БИК: </span>044525607<br>
<span style=\"color: #E4D49B;\">к/с: </span>30101810400000000607
</p>

<hr style=\"border: 1px solid #E4D49B;\">

<p align=\"justify\" style=\"color: #E4D49B; font-size: 18px;\">
Купленные и оплаченные предметы Вы можете забрать в нашем салоне в рабочее время, или по договорённости.
</p>

<hr style=\"border: 1px solid #E4D49B;\">

<p align=\"justify\" style=\"font-size: 18px;\">
<span style=\"color: #E4D49B;\">Также, мы осуществляем бесплатную доставку по Крыму:</span>
<br>
Алушта, Ялта, Феодосия, Евпатория, Бахчисарай, Севастополь, Партенит, Гурзуф, Алупка, Судак и др.
<br>
<br>
<span style=\"color: #E4D49B;\">при покупке минимум на 40 000 руб.</span>
<br>
<br>
Керчь:
<br>
<span style=\"color: #E4D49B;\">При покупке минимум на 50 000 руб.</span>
<br>
<br>
КРОМЕ <span style=\"color: #E4D49B;\">крупногабаритной мебели.</span>
</p>

<hr style=\"border: 1px solid #E4D49B;\">

<p align=\"justify\" style=\"color: #E4D49B; font-size: 18px;\">
Доставку по городам России осуществляют курьерские службы:&nbsp;
<a href=\"http://www.edostavka.ru\" target=\"_blank\" style=\"font-size: 18px;\">СДЭК</a>,
<a href=\"http://www.cityexpress.ru\" target=\"_blank\" style=\"font-size: 18px;\">City Express</a>
и <a href=\"https://www.google.ru/?gws_rd=ssl#newwindow=1&q=%D1%81%D0%BB%D1%83%D0%B6%D0%B1%D0%B0+%D0%B4%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B8+%D1%80%D0%BE%D1%81%D1%81%D0%B8%D1%8F\" target=\"_blank\" style=\"font-size: 18px;\">другие</a>.
</p>

<p align=\"justify\" style=\"color: #E4D49B; font-size: 18px;\">
Стоимость посылки можно легко рассчитать по тарифным сеткам курьерских служб.
</p>

<hr style=\"border: 1px solid #E4D49B;\">

<p align=\"justify\" style=\"font-size: 18px;\">
Во избежание недоразумений все посылки будут 100% застрахованы.
</p>

</div>";

echo '<div style="position: absolute; bottom: 0; left: 0; z-index: -1;">' . GetKeyPhrases() . '</div></div>';
echo '</td></tr></table>';

echo '</div>';

DrawFooter();
?>

<script>
$(document).ready(function(){
	
im_load();
im_animate_root(1, false, true);
	
});
</script>

</body>
</html>