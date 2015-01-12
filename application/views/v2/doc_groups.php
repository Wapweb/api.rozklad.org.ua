<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>API расписания КПИ - группы</title>
    <link rel="stylesheet" href="<?=HOME?>/assets/css/style.css"/>
    <link rel="stylesheet" href="<?=HOME?>/assets/css/simplegrid.css"/>
    <link href='http://fonts.googleapis.com/css?family=Roboto&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="<?=HOME?>/assets/images/favicon.png">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="<?=HOME."/assets/js/jquery.collapse.js"?>"></script>
</head>
<body>
<div id="wrapper">
<div id="header">
    <div class="container">
        <a href="<?=HOME?>" class="header-logo"><span class="logo-border"><img src="<?=HOME?>/assets/images/logo.svg" height="17" width="17">API <span class="logo-blue">Расписания</span> КПИ</span></a>
        <div class="header-menu">
            <ul class="items-horizontal">
                <li><a href="https://github.com/Wapweb/api.rozklad.org.ua" target="_blank">Github</a></li>
                <li><a href="http://rozklad.org.ua">rozklad.org.ua</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="header-menu">
    <div class="scroll">
        <ul class="items-inline">
            <li style="width: 25%;" ><a href="<?=HOME?>">Описание</a></li>
            <li style="width: 25%;" class="items-inline-active"><a href="<?=HOME?>/doc_groups" >Группы</a></li>
            <li style="width: 25%;" ><a href="<?=HOME?>/v2/doc_teachers">Преподаватели</a></li>
            <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_other">Другое</a></li>
        </ul>
    </div>

</div>

<div class="container">
<div class="grid grid-pad">
<div class="col-1-1">
<div class="content">
<h3 class="logo-border">Группы</h3>
Название группы можно вводить английскими(по правилам транслитерации),украинскими буквами в любом регистре<br>
Запрос можно делать по двум протоколам: http или https
<h3></h3>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th style="width: 50%">Url</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups</td>
            <td data-title="Описание">Получить список всех групп (по умолчанию выводится первые 100 групп)</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/?filter={"limit":10,"offset":5}</td>
            <td data-title="Описание">Получить список всех групп с указанием дополнительных параметров:<br>
                int <i>offset</i> - смещение<br>
                int <i>limit</i> - лимит записей (от 1 до 100)<br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Пример запроса</h4>
GET JSON: <a href="<?=HOME?>/v2/groups/?filter={'limit':2,'offset':5}" target="_blank"><?=HOME?>/v2/groups/?filter={'limit':2,'offset':5}</a>
<br>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/group_1_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
</div>

<h3></h3>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th style="width: 50%">Url</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/{group_name|group_id}</td>
            <td data-title="Описание">Получить группу по имени или по идентификатору</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/?search={'query':'ia'}</td>
            <td data-title="Описание">Поиск группы по имени  <br>
                string <i>query</i> - значение поискового запроса<br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Пример запроса</h4>
GET JSON: <a href="<?=HOME?>/v2/groups/ia-23" target="_blank"><?=HOME?>/v2/groups/ia-23</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/group_2_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
</div>

<h3></h3>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th style="width: 50%">Url</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/{group_name|group_id}/lessons</td>
            <td data-title="Описание">Получить список всех предметов конкретной группы</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/{group_name|group_id}/teachers</td>
            <td data-title="Описание">Получить список всех преподавателей конкретной группы</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/groups/{group_name|group_id}/timetable</td>
            <td data-title="Описание">Получить расписание конкретной группы в иерархическом виде</td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Примеры запросов</h4>
GET JSON: <a href="<?=HOME?>/v2/groups/ia-23/lessons" target="_blank"><?=HOME?>/v2/groups/ia-23/lessons</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/group_3_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
</div>
GET JSON: <a href="<?=HOME?>/v2/groups/ia-23/teachers" target="_blank"><?=HOME?>/v2/groups/ia-23/teachers</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/group_4_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
</div>
GET JSON: <a href="<?=HOME?>/v2/groups/ia-23/timetable" target="_blank"><?=HOME?>/v2/groups/ia-23/timetable</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/group_5_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
</div>

<h3 class="logo-border">Фильтры для запроса v2/groups/{group_name|group_id}/lessons</h3>
<p>Для данного запроса можно комбинировать несколько фильтров</p>
<h3></h3>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th>Фильтр</th>
            <th>Тип данных</th>
            <th>Пример</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Фильтр">day_number</td>
            <td data-title="тип">int</td>
            <td data-title="Пример">?filter={'day_number':1}</td>
            <td data-title="Описание">Показать предметы конкретного дня (возможные значения: от 1 до 6)</td>
        </tr>
        <tr>
            <td data-title="Фильтр">day_name</td>
            <td data-title="тип">string</td>
            <td data-title="Пример">?filter={'day_name': 'Понеділок'}</td>
            <td data-title="Описание">Показать предметы конкретного дня  (возможные значения: Понеділок ... Субота)</td>
        </tr>
        <tr>
            <td data-title="Фильтр">lesson_number</td>
            <td data-title="тип">int</td>
            <td data-title="Пример">?filter={'lesson_number':5}</td>
            <td data-title="Описание">Показать предметы, n-ые по расписанию (возможные значения: от 1 до 5)</td>
        </tr>
        <tr>
            <td data-title="Фильтр">lesson_week</td>
            <td data-title="тип">int</td>
            <td data-title="Пример">?filter={'lesson_week':2}</td>
            <td data-title="Описание">Показать предметы 1ой или 2ой недели</td>
        </tr>
        <tr>
            <td data-title="Фильтр">lesson_type</td>
            <td data-title="тип">string</td>
            <td data-title="Пример">?filter={'lesson_type': 'Лек'}</td>
            <td data-title="Описание">Показать предметы конкретного типа (возможные значения: Лек,Лаб,Прак)</td>
        </tr>
        <tr>
            <td data-title="Фильтр">rate</td>
            <td data-title="тип">double</td>
            <td data-title="Пример">?filter={'rate':0.5}</td>
            <td data-title="Описание">Показать предметы конкретной ставки (возможеы значения: 0.5, 1, 1.5)</td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Примеры комбинаций фильтров</h4>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th style="width: 50%">Пример</th>
            <th>Тип комбинации</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Пример"><a href="<?=HOME?>/v2/groups/ia-23/lessons?filter={'day_number':3,'lesson_week':1}" target="_blank"><?=HOME?>/v2/groups/ia-23/lessons?filter={'day_number':3,'lesson_week':1}</a></td>
            <td data-title="Тип комбинации">AND</td>
            <td data-title="Описание">Показать все предметы 1ой недели 3го дня(среды) </td>
        </tr>
        <tr>
            <td data-title="Пример"><a href="<?=HOME?>/v2/groups/ia-23/lessons?filter=[{'day_number':3},{'day_number':2}]" target="_blank"><?=HOME?>/v2/groups/ia-23/lessons?filter=[{'day_number':3},{'day_number':2}]</a> </td>
            <td data-title="Тип комбинации">OR</td>
            <td data-title="Описание">Показать все предметы 3го или 2го дня</td>
        </tr>
        <tr>
            <td data-title="Пример"><a href="<?=HOME?>/v2/groups/ia-23/lessons?filter=[{'day_number':3,'lesson_week':1},{'day_number':2,'lesson_week':1}]" target="_blank"><?=HOME?>/v2/groups/ia-23/lessons?filter=[{'day_number':3,'lesson_week':1},{'day_number':2,'lesson_week':1}]</a> </td>
            <td data-title="Тип комбинации">OR/AND</td>
            <td data-title="Описание">Показать все предметы (3го дня и 1ой недели) или (2го дня и 1ой недели)</td>
        </tr>
        </tbody>
    </table>
</div>

<h3 class="logo-border">Фильтры для запроса v2/groups/{group_name|group_id}/teachers</h3>
<h3></h3>
<div class="table-responsive">
    <table class="table-primary">
        <thead>
        <tr>
            <th>Фильтр</th>
            <th>Тип данных</th>
            <th>Пример</th>
            <th>Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-title="Фильтр">duplicateTeachersFilter</td>
            <td data-title="тип">int</td>
            <td data-title="Пример">?filter={'duplicateTeachersFilter':1}</td>
            <td data-title="Описание">Убирает повторяющиеся записи преподавателей(косяки rozklad.kpi.ua)<br> По умолчанию фильтр отключен<br>Возможные значения : 1(включен) или 0(по умолчанию - отключен)</td>
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
        <ul class="items-horizontal small left" style="margin-top: 20px">
            <li>&copy; 2014 api.rozklad.org.ua</li>
        </ul>
        <ul class="items-inline small">
        </ul>
    </div>
</div>
</body>
</html>