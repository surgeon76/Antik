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
echo '<div style="color: #FFCD70; font-size: 16px; font-weight: bold; margin-left: 4px;\">О салоне</div>';
echo '</div>';
	
echo '<img src="images/reglament.jpg" style="width: 260px; height: 200px; float: right; margin: 4px;" />';

echo "<p align=\"justify\" style=\"margin: 4px; color: #E4D49B; font-size: 15px;\">
Основными направлениями работы Антикварного салона являются: продажа антиквариата, покупка антиквариата, прием антиквариата на комиссию, консультация по антикварным предметам, рекомендации и подбор предметов антиквариата по запросам клиентов.
<br><br>
<span style=\"color: white;\">В нашем антикварном салоне представлен антиквариат во всем его ассортименте: бронзовая скульптура; фарфор - декоративные вазы, сервизы, фарфоровая скульптура (Мейсенский фарфор); антиквариат из серебра (графины, вазы, сухарницы, сервизы, столовое серебро); живопись; антикварные иконы; часы (карманные, каретные, настенные, напольные, каминные, каминные гарнитуры, мебель, люстры, предметы быта, интерьера и многое другое.</span>
<br><br>
Приглашаем к сотрудничеству представительские отделы компаний, а также дизайнеров!
<br><br>
<span style=\"color: white;\">Антиквариат - великолепный подарок к любому торжеству, художественная и материальная ценность которого со временем только увеличивается. Такой подарок будет оценен получателем по достоинству!</span>
<br><br>
Наш антикварный салон предлагает Вам предметы антиквариата и старинного интерьера на любой вкус и кошелек. Мы готовы оказать помощь в формировании коллекций любой направленности путем целевого поиска конкретных предметов старины и коллекционирования.
<br><br>
<span style=\"color: white;\">Наш салон может помочь заказать оригинальную упаковку. В этом случае потребуется некоторое время для изготовления (до 10 дней), так как все предметы существуют в единственном экземпляре, у каждого свои габариты и специфика. Часть предметов уже имеют подарочные коробки.</span>
<br><br>
В последнее десятилетие значительно вырос интерес к старине - и это во время технических достижений и супер скоростей.
<br><br>
<span style=\"color: white;\">И это не удивительно: глядя на однообразный ряд поточных, пусть даже самых роскошных изделий, выставленных на продажу в наших супермаркетах, поневоле начинаешь ценить штучные изделия старых мастеров, созданные не на день или два, а на века.</span>
<br><br>
На нашем сайте представлен далеко не весь ассортимент антиквариата, выставленного в нашем салоне. Если Вы не нашли в каталоге интересующий Вас предмет или у Вас есть индивидуальные пожелания - напишите нам на электронную почту <a href=\"mailto:antikvarkrym@mail.ru\">antikvarkrym@mail.ru<a></p>";

echo '<div style="position: relative;"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2823.4626900206063!2d34.09001031571545!3d44.95458977532268!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40eaddc2f5f76867%3A0x8b5e0872440a0352!2zOSDRg9C7LiDQotC-0LvRgdGC0L7Qs9C-LCDQodC40LzRhNC10YDQvtC_0L7Qu9GM!5e0!3m2!1sru!2sru!4v1444836445537" width="100%" height="600"; frameborder="0" style="border:0" allowfullscreen></iframe>' .
'<div style="position: absolute; bottom: 0; left: 0; z-index: -1;">' . GetKeyPhrases() . '</div></div>';
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