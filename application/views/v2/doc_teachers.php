<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>API расписания КПИ - преподаватели</title>
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
            <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_groups" >Группы</a></li>
            <li style="width: 25%;" class="items-inline-active"><a href="<?=HOME?>/v2/doc_teachers">Преподаватели</a></li>
            <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_other">Другое</a></li>
        </ul>
    </div>

</div>

<div class="container">
<div class="grid grid-pad">
<div class="col-1-1">
<div class="content">
<h3 class="logo-border">Преподаватели</h3>
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
            <td data-title="Url">http://api.rozklad.org.ua/v2/teachers</td>
            <td data-title="Описание">Получить список всех преподавателей (по умолчанию выводится первые 100 групп)</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/teachers/?filter={"limit":10,"offset":5}</td>
            <td data-title="Описание">Получить список всех преподавателей с указанием дополнительных параметров фильтра:<br>
                int <i>offset</i> - смещение<br>
                int <i>limit</i> - лимит записей (от 1 до 100)<br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Пример запроса</h4>
GET JSON: <a href="<?=HOME?>/v2/teachers/?filter={'limit':2,'offset':1000}" target="_blank"><?=HOME?>/v2/teachers/?filter={'limit':2,'offset':1000}</a>
<br>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/teacher_1_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
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
            <td data-title="Url">http://api.rozklad.org.ua/v2/teachers/{teacher_name|teacher_id}</td>
            <td data-title="Описание">Получить запись преподавателя по ФИО или по идентификатору</td>
        </tr>
        <tr>
            <td data-title="Url">http://api.rozklad.org.ua/v2/teachers/?search={'query': 'Тел'}</td>
            <td data-title="Описание">Поиск преподавателя по ФИО <br>
                string <i>query</i> - значение поискового запроса (не менее 3 символов)<br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Пример запроса</h4>
GET JSON: <a href="<?=HOME?>/v2/teachers/3232" target="_blank"><?=HOME?>/v2/teachers/3232</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/teacher_2_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
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
            <td data-title="Url">http://api.rozklad.org.ua/v2/teachers/{teacher_name|teacher_id}/lessons</td>
            <td data-title="Описание">Получить список всех предметов конкретного преподавателя</td>
        </tr>
        </tbody>
    </table>
</div>
<h4>Примеры запросов</h4>
GET JSON: <a href="<?=HOME?>/v2/teachers/Теленик+Сергій+Федорович/lessons" target="_blank"><?=HOME?>/v2/teachers/Теленик+Сергій+Федорович/lessons</a>
<div data-collapse>
    <h4 class="close">Пример ответа</h4>
    <div>
        <p><pre><?=file_get_contents(ROOT."/assets/json_files/teacher_3_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
    </div>
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