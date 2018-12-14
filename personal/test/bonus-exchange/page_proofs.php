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

                        <div class="bonus-bill">Текущий счет: <span>2 999 ТМ-бонусов</span></div>
                        <div class="bonus-description-link"><a href="#">Как накопить и на что потратить?</a> </div>

                    </div>
                    <nav class="bonus-list-tabs">
                        <ul>
                            <li><a href="#" class="btn btn-default active">ВСЕ СРАЗУ</a></li>
                            <li><a href="#" class="btn btn-default">СЕМИНАРЫ</a></li>
                            <li><a href="#" class="btn btn-default">КНИГИ</a></li>
                            <li><a href="#" class="btn btn-default">СКИДКИ</a></li>
                            <li><a href="#" class="btn btn-default">УСЛУГИ</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="info-block">
                <div class="list-info">Найдено: <span>25 предложений</span></div>
                <div class="filter-checkbox">
                    <label>
                        <input type="checkbox" name="show-availble" value="Y" />
                        <span></span>
                        Показать только доступные мне
                    </label>
                </div>
                <div class="list-count">
                    <ul>
                        <li>Выводить по:</li>
                        <li><a href="#" class="active">15</a></li>
                        <li><a href="#">60</a></li>
                        <li><a href="#">90</a></li>
                        <li><a href="#">Показывать все</a></li>
                    </ul>
                </div>
                <div class="list-sort">
                    Сортировка:
                    <div class="select-block">
                        <span>По стоимости</span>
                        <span class="active">По стоимости</span>
                        <span>По стоимости</span>
                        <span>По стоимости</span>
                        <span>По стоимости</span>
                    </div>
                </div>
            </div>

            <div class="personal-list">
                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary disabled">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="personal-list-item content-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="personal-list-itemTitle"><a href="#">Семинар</a></div>
                            <div class="personal-list-itemAnons"><p>Как продвинуть свой сайт самостоятельно в Яндекс и Google:  курс для начинающих бизнесменов</p></div>
                            <div class="personal-list-itemDescription">
                                <ul>
                                    <li>Принципы разработки структуры интернет-магазина/сайта компании</li>
                                    <li>Обзор основных проблем в дизайне/юзабилити</li>
                                    <li>Семантическое ядро: принципы сбора ключевых запросов, обзор сервисов.</li>
                                </ul>
                            </div>
                            <div class="personal-list-itemButton"><a href="#" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a></div>

                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="bonusPrice">
                                <div class="table-wrap">
                                    <div class="cell">
                                        <div class="personal-list-itemDescriptionPrice">
                                            Стоимость:<br />
                                            <span>5 500 ТМ-бонусов</span>
                                        </div><br />
                                        <a href="#" class="btn btn-primary">АКТИВИРОВАТЬ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagination">
                <ul>
                    <li><a href="#">1</a></li>
                    <li class="active"><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a>...</a></li>
                    <li><a href="#">8</a></li>
                </ul>
                <a href="#" class="btn">Показать все</a>
            </div>


            <div class="content-block faq-block">
                <div class="title">Вопросы и ответы</div>
                <div class="faq-body">
                    <div class="faq-item">
                        <div class="question">Что такое ТМ-бонусы?</div>
                        <div class="answer">
                            <p>Условия начисления экстрабонусов определяются условиями соответствующих маркетинговых акций . Экстрабонусы могут начисляться как за покупки, соответствующие условиям акции (например, возможны дополнительные условия по времени покупки, сумме покупки, приобретаемым товарам, магазине, где совершаются покупки) так и за совершение определенных действий в рамках Клубной программы (заполнение анкеты, подписка на новости и т.д.) Кроме этого. экстрабонусы могут быть начислены участнику Клубной программы по решению компании в качестве дополнительной преференции.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="question">Как я могу потратить накопленные бонусные баллы?</div>
                        <div class="answer">
                            <p>Условия начисления экстрабонусов определяются условиями соответствующих маркетинговых акций . Экстрабонусы могут начисляться как за покупки, соответствующие условиям акции (например, возможны дополнительные условия по времени покупки, сумме покупки, приобретаемым товарам, магазине, где совершаются покупки) так и за совершение определенных действий в рамках Клубной программы (заполнение анкеты, подписка на новости и т.д.) Кроме этого. экстрабонусы могут быть начислены участнику Клубной программы по решению компании в качестве дополнительной преференции.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="question">Как я могу получить бонусные баллы?</div>
                        <div class="answer">
                            <p>Условия начисления экстрабонусов определяются условиями соответствующих маркетинговых акций . Экстрабонусы могут начисляться как за покупки, соответствующие условиям акции (например, возможны дополнительные условия по времени покупки, сумме покупки, приобретаемым товарам, магазине, где совершаются покупки) так и за совершение определенных действий в рамках Клубной программы (заполнение анкеты, подписка на новости и т.д.) Кроме этого. экстрабонусы могут быть начислены участнику Клубной программы по решению компании в качестве дополнительной преференции.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="question">Чему равен 1 бонусный балл?</div>
                        <div class="answer">
                            <p>Условия начисления экстрабонусов определяются условиями соответствующих маркетинговых акций . Экстрабонусы могут начисляться как за покупки, соответствующие условиям акции (например, возможны дополнительные условия по времени покупки, сумме покупки, приобретаемым товарам, магазине, где совершаются покупки) так и за совершение определенных действий в рамках Клубной программы (заполнение анкеты, подписка на новости и т.д.) Кроме этого. экстрабонусы могут быть начислены участнику Клубной программы по решению компании в качестве дополнительной преференции.</p>
                        </div>
                    </div>
                    <div class="faq-item active">
                        <div class="question">Что такое экстрабаллы и как их получить?</div>
                        <div class="answer">
                            <p>Условия начисления экстрабонусов определяются условиями соответствующих маркетинговых акций . Экстрабонусы могут начисляться как за покупки, соответствующие условиям акции (например, возможны дополнительные условия по времени покупки, сумме покупки, приобретаемым товарам, магазине, где совершаются покупки) так и за совершение определенных действий в рамках Клубной программы (заполнение анкеты, подписка на новости и т.д.) Кроме этого. экстрабонусы могут быть начислены участнику Клубной программы по решению компании в качестве дополнительной преференции.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>



    </div>
</div>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>