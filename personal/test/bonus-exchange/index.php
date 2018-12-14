<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добро пожаловать в личный кабинет");
?>

    <div class="row">
        <? include ($_SERVER["DOCUMENT_ROOT"].'/include/personal_menu.php'); ?>
        <div class="col-xs-12 col-sm-9">

            <div class="personal-body">

                <div class="content-block bonus-head">
                    <div class="block-title">Обмен бонусных баллов</div>
                    <div class="block-description">
                        <div class="bonus-info">

                            <div class="bonus-bill">Текущий счет: <span>0 ТМ-бонусов</span></div>
                            <div class="bonus-description-link"><a href="/personal/bonus/kak-nakopit-i-na-chto-potratit/">Как накопить и на что потратить?</a> </div>

                        </div>
                    </div>
                </div>

                <p>Бонусы не найдены</p>

                <div class="content-block faq-block">
                    <div class="title">Вопросы и ответы</div>
                </div>

            </div>



        </div>
    </div>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>