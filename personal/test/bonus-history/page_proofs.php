<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписки");
?>


    <div class="row">
        <? include ($_SERVER["DOCUMENT_ROOT"].'/include/personal_menu.php'); ?>
        <div class="col-xs-12 col-sm-9">

            <div class="personal-body">

                <div class="content-block">
                    <div class="block-title">
                        История бонусных операций
                    </div>

                    <div class="block-description">
                        <div class="bonus-info">

                            <div class="bonus-bill">Текущий счет: <span>2 999 ТМ-бонусов</span></div>
                            <div class="bonus-description-link"><a href="#">Как накопить и на что потратить?</a> </div>

                        </div>


                        <div class="bonus-history-block">
                            <div class="bonus-history-block-head">
                                <div class="bonus-history-cell-name">
                                    Причина
                                    <a href="">
                                        <svg width="9px" height="10px" viewBox="500 572 9 10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <!-- Generator: Sketch 40.3 (33839) - http://www.bohemiancoding.com/sketch -->
                                            <desc>arrow down.</desc>
                                            <defs></defs>
                                            <g id="Group-17" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(500.000000, 572.000000)">
                                                <path d="M3.29289322,9.70710678 C3.68341751,10.0976311 4.31658249,10.0976311 4.70710678,9.70710678 L7.70710678,6.70710678 C8.09763107,6.31658249 8.09763107,5.68341751 7.70710678,5.29289322 C7.31658249,4.90236893 6.68341751,4.90236893 6.29289322,5.29289322 L3.29289322,8.29289322 L4.70710678,8.29289322 L1.70710678,5.29289322 C1.31658249,4.90236893 0.683417511,4.90236893 0.292893219,5.29289322 C-0.0976310729,5.68341751 -0.0976310729,6.31658249 0.292893219,6.70710678 L3.29289322,9.70710678 Z" id="Path-2" fill="#979797"></path>
                                                <path d="M5,1 C5,0.44771525 4.55228475,0 4,0 C3.44771525,0 3,0.44771525 3,1 L3,7 C3,7.55228475 3.44771525,8 4,8 C4.55228475,8 5,7.55228475 5,7 L5,1 Z" id="Path-3" fill="#979797"></path>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="bonus-history-cell-date">
                                    Дата операции
                                    <a href="">
                                        <svg width="9px" height="10px" viewBox="500 572 9 10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <!-- Generator: Sketch 40.3 (33839) - http://www.bohemiancoding.com/sketch -->
                                            <desc>arrow down.</desc>
                                            <defs></defs>
                                            <g id="Group-17" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(500.000000, 572.000000)">
                                                <path d="M3.29289322,9.70710678 C3.68341751,10.0976311 4.31658249,10.0976311 4.70710678,9.70710678 L7.70710678,6.70710678 C8.09763107,6.31658249 8.09763107,5.68341751 7.70710678,5.29289322 C7.31658249,4.90236893 6.68341751,4.90236893 6.29289322,5.29289322 L3.29289322,8.29289322 L4.70710678,8.29289322 L1.70710678,5.29289322 C1.31658249,4.90236893 0.683417511,4.90236893 0.292893219,5.29289322 C-0.0976310729,5.68341751 -0.0976310729,6.31658249 0.292893219,6.70710678 L3.29289322,9.70710678 Z" id="Path-2" fill="#979797"></path>
                                                <path d="M5,1 C5,0.44771525 4.55228475,0 4,0 C3.44771525,0 3,0.44771525 3,1 L3,7 C3,7.55228475 3.44771525,8 4,8 C4.55228475,8 5,7.55228475 5,7 L5,1 Z" id="Path-3" fill="#979797"></path>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="bonus-history-cell-bonusCount">
                                    Бонусы
                                    <a href="">

                                        <svg width="9px" height="10px" viewBox="1034 572 9 10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <!-- Generator: Sketch 40.3 (33839) - http://www.bohemiancoding.com/sketch -->
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Group-17"  stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(1038.000000, 577.000000) scale(1, -1) translate(-1038.000000, -577.000000) translate(1034.000000, 572.000000)">
                                                <path d="M3.29289322,9.70710678 C3.68341751,10.0976311 4.31658249,10.0976311 4.70710678,9.70710678 L7.70710678,6.70710678 C8.09763107,6.31658249 8.09763107,5.68341751 7.70710678,5.29289322 C7.31658249,4.90236893 6.68341751,4.90236893 6.29289322,5.29289322 L3.29289322,8.29289322 L4.70710678,8.29289322 L1.70710678,5.29289322 C1.31658249,4.90236893 0.683417511,4.90236893 0.292893219,5.29289322 C-0.0976310729,5.68341751 -0.0976310729,6.31658249 0.292893219,6.70710678 L3.29289322,9.70710678 Z" id="Path-2" fill="#979797"></path>
                                                <path d="M5,1 C5,0.44771525 4.55228475,0 4,0 C3.44771525,0 3,0.44771525 3,1 L3,7 C3,7.55228475 3.44771525,8 4,8 C4.55228475,8 5,7.55228475 5,7 L5,1 Z" id="Path-3" fill="#979797"></path>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="bonus-history-cell-balance">Остаток</div>
                            </div>
                            <div class="bonus-history-block-body">
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                                <div class="bonus-history-block-row buyBonus">
                                    <div class="bonus-history-cell-name">Благотворительность</div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">- 2 000</div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                    <div class="bonus-history-block-moreBlock">
                                        <div class="title">Семинар «Сервера и Системы Хранения Данных»</div>
                                        <div class="text">
                                            <p>Семинар проводится в рамках нового направления дистрибуции Систем Хранения Данных компании CompTek.</p>
                                            <p>Специалисты компании CompTek и EMC2 представят обзор решений EMC2, определят положительные аспекты консолидации информации, продемонстрируют связку систем хранения данных с жизнеобразующими аппликациями на телекоммуникационном рынке, поделятся опытом успешных внедрений.</p>
                                            <p>Семинар адресован представителям компаний операторов связи (директорам по развитию), системным интеграторам (дизайнерам и проектировщикам сетей), реселлерам (продакт-менеджерам, проектировщикам).</p>
                                        </div>
                                        <div class="info">
                                            <span> 22 июня 20016 г.</span>
                                            <span>Адрес: Cмоленская площадь, д.3, Смоленский Пассаж, 7-й этаж, зал «Рим».</span>
                                        </div>
                                        <div class="button-block">
                                            <a href="" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="bonus-history-block-row buyBonus active">
                                    <div class="bonus-history-cell-name">Благотворительность</div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">- 2 000</div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                    <div class="bonus-history-block-moreBlock">
                                        <div class="title">Семинар «Сервера и Системы Хранения Данных»</div>
                                        <div class="text">
                                            <p>Семинар проводится в рамках нового направления дистрибуции Систем Хранения Данных компании CompTek.</p>
                                            <p>Специалисты компании CompTek и EMC2 представят обзор решений EMC2, определят положительные аспекты консолидации информации, продемонстрируют связку систем хранения данных с жизнеобразующими аппликациями на телекоммуникационном рынке, поделятся опытом успешных внедрений.</p>
                                            <p>Семинар адресован представителям компаний операторов связи (директорам по развитию), системным интеграторам (дизайнерам и проектировщикам сетей), реселлерам (продакт-менеджерам, проектировщикам).</p>
                                        </div>
                                        <div class="info">
                                            <span> 22 июня 20016 г.</span>
                                            <span>Адрес: Cмоленская площадь, д.3, Смоленский Пассаж, 7-й этаж, зал «Рим».</span>
                                        </div>
                                        <div class="button-block">
                                            <a href="" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="bonus-history-block-row buyBonus">
                                    <div class="bonus-history-cell-name">Благотворительность</div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">- 2 000</div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                    <div class="bonus-history-block-moreBlock">
                                        <div class="title">Семинар «Сервера и Системы Хранения Данных»</div>
                                        <div class="text">
                                            <p>Семинар проводится в рамках нового направления дистрибуции Систем Хранения Данных компании CompTek.</p>
                                            <p>Специалисты компании CompTek и EMC2 представят обзор решений EMC2, определят положительные аспекты консолидации информации, продемонстрируют связку систем хранения данных с жизнеобразующими аппликациями на телекоммуникационном рынке, поделятся опытом успешных внедрений.</p>
                                            <p>Семинар адресован представителям компаний операторов связи (директорам по развитию), системным интеграторам (дизайнерам и проектировщикам сетей), реселлерам (продакт-менеджерам, проектировщикам).</p>
                                        </div>
                                        <div class="info">
                                            <span> 22 июня 20016 г.</span>
                                            <span>Адрес: Cмоленская площадь, д.3, Смоленский Пассаж, 7-й этаж, зал «Рим».</span>
                                        </div>
                                        <div class="button-block">
                                            <a href="" class="btn">ЧИТАТЬ ПОДРОБНЕЕ</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                                <div class="bonus-history-block-row addBonus">
                                    <div class="bonus-history-cell-name"><a href="#">Заказ 123 555 272</a></div>
                                    <div class="bonus-history-cell-date">25.04.2016</div>
                                    <div class="bonus-history-cell-bonusCount">+ 750 </div>
                                    <div class="bonus-history-cell-balance">2 999</div>
                                </div>
                            </div>
                            <div class="bonus-history-block-footer">
                                <a href="" class="moreButton">СМОТРЕТЬ ЕЩЕ</a>
                            </div>
                        </div>


                    </div>

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