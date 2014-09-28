<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>API расписания КПИ</title>
    <link rel="stylesheet" href="<?=HOME?>/assets/css/style.css"/>
    <link rel="stylesheet" href="<?=HOME?>/assets/css/simplegrid.css"/>
    <link href='http://fonts.googleapis.com/css?family=Roboto&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="<?=HOME?>/assets/images/favicon.png">
</head>
<body>
<div id="wrapper">
    <div id="header">
        <div class="container">
            <a href="" class="header-logo">Api.rozklad.org.ua</a>
			<div class="header-menu">
                <ul class="items-horizontal">
                    <li><a href="http://rozklad.org.ua">rozklad.org.ua</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">

        <div class="grid grid-pad">
            <div class="col-1-1">
                <div class="content">
                    <h3>Группы</h3>
                    Формат ответа для всех запросов: <strong>json</strong><br>
					Название группы можно вводить английскими(по правилам транслитерации),украинскими буквами в любом регистре
                    <div class="table-responsive">
                        <table class="table-primary">
                            <thead>
                            <tr>
                                <th>Url</th>
                                <th>Тип запроса</th>
                                <th>Тип параметра</th>
                                <th>Описание</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">&mdash;</td>
                                <td data-title="Описание">Получить список всех групп (по умолчанию выводится 100 групп)</td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/?offset=100&limit=100</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">int<br>int</td>
                                <td data-title="Описание">
                                    Получить список всех групп с указанием дополнительных параметров:<br>
                                    <i>offset</i> - смещение<br>
                                    <i>limit</i> - лимит записей (от 1 до 100)
                                    Группы помещаются в массив <i>data</i>, дополнительная информация - в массив <i>meta</i>
                                </td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/{group_name|group_id}</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">mixed {string|int}</td>
                                <td data-title="Описание">Получить группу по имени или по идентификатору</td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/?q={search+word}</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">string</td>
                                <td data-title="Описание">
                                    Поиск группы по имени
                                </td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/{group_name|group_id}/lessons</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">mixed {string|int}</td>
                                <td data-title="Описание">
                                    Получить список всех предметов конкретной группы
                                </td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/{group_name|group_id}/lessons/?week=1</td>
                                <td data-title="Тп запроса">GET</td>
                                <td data-title="Тип параметра">mixed {string|int}<br>int</td>
                                <td data-title="Описание">
                                    Получить список всех предметов 1ой или 2ой недели конкретной группы
                                </td>
                            </tr>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/groups/{group_name|group_id}/lessons/?week=1&day=1</td>
                                <td data-title="Тп запроса">GET</td>
                                <td data-title="Тип параметра">mixed {string|int}<br>int<br>int</td>
                                <td data-title="Описание">
                                    Получить список всех предметов 1ой недели понедельника
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>Недели</h3>
                    <div class="table-responsive">
                        <table class="table-primary">
                            <thead>
                            <tr>
                                <th>Url</th>
                                <th>Тип запроса</th>
                                <th>Тип параметра</th>
                                <th>Описание</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td data-title="Url">http://api.rozklad.org.ua/v1/weeks</td>
                                <td data-title="Тип запроса">GET</td>
                                <td data-title="Тип параметра">&mdash;</td>
                                <td data-title="Описание">Получить номер текущей недели (1ая или 2ая)</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>Описание полей сущностей</h3>
                    <div class="table-responsive">
                        <table class="table-primary">
                            <thead>
                            <tr>
                                <th>Имя сущности</th>
                                <th>Описание полей</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td data-title="Имя обьекта">Группа (group)</td>
                                <td data-title="Описание полей">
                                    <i>int</i> <strong>group_id</strong> &mdash; идентификатор группы<br>
                                    <i>string</i> <strong>group_full_name</strong> &mdash; полное название группы<br>
                                    <i>string</i> <strong>group_prefix</strong> &mdash; префикс группы<br>
                                    <i>string</i> <strong>group_okr</strong> &mdash; ОКР группы (возможные значения: bachelor,magister,specialist)<br>
                                    <i>string</i> <strong>group_type</strong> &mdash; форма обучения группы(возможные значения: daily,extramural)<br>
                                    <i>string</i> <strong>group_url</strong> &mdash; url группы на rozklad.kpi.ua
                                </td>
                            </tr>
                            <tr>
                                <td data-title="Имя обьекта">Предмет (lesson)</td>
                                <td data-title="Описание полей">
                                    <i>int</i> <strong>lesson_id</strong> &mdash; идентификатор предмета<br>
                                    <i>int</i> <strong>group_id</strong> &mdash; идентификатор группы<br>
                                    <i>int</i> <strong>day_number</strong> &mdash; номер дня недели (от 1-пн до 7-вс)<br>
                                    <i>string</i> <strong>day_name</strong> &mdash; название дня недели<br>
                                    <i>int</i> <strong>lesson_number</strong> &mdash; номер пары по счету(от 1 до 5)<br>
                                    <i>string</i> <strong>lesson_name</strong> &mdash; название предемта<br>
                                    <i>string</i> <strong>lesson_room</strong> &mdash; аудитория/аудитории <br>
                                    <i>string</i> <strong>lesson_type</strong> &mdash; тип предмета(Возможные значения: Лек,Прак,Лаб)<br>
                                    <i>string</i> <strong>teacher_name</strong> &mdash; имя препадавателя/преподавателей<br>
                                    <i>int</i> <strong>lesson_week</strong> &mdash; номер недели (Возможные значения: от 1 до 2)<br>
                                    <i>string</i> <strong>time_start</strong> &mdash; время начала пары<br>
                                    <i>string</i> <strong>time_end</strong> &mdash; время конца пары<br>
                                    <i>float</i> <strong>rate</strong> &mdash; ставка предмета(Возможные значения: 1, 1.5, 0.5)<br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer">
    <div class="container">
        <ul class="items-inline small left">
            <li>&copy; 2014 api.rozklad.org.ua</li>
        </ul>
        <ul class="items-inline small">
        </ul>
    </div>
</div>
</body>
</html>