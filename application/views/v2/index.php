<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>API расписания КПИ</title>
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
            <li style="width: 25%;" class="items-inline-active"><a href="<?=HOME?>">Описание</a></li>
            <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_groups" >Группы</a></li>
            <li style="width: 25%;" ><a href="<?=HOME?>/v2/doc_teachers">Преподаватели</a></li>
            <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_other">Другое</a></li>
        </ul>
    </div>

</div>

<div class="container">
<div class="grid grid-pad">
<div class="col-1-1">
<div class="content">
<h3 class="logo-border">Описание api</h3>
Формат ответа для всех запросов: <strong>json</strong><br>
Запрос можно делать по двум протоколам: http или https(имеется подтвержденный ssl-сертификат)<br>
Актуальная версия api: <strong>v2</strong><br>
<p>
    <strong>Контакты:</strong> admin[собака]rozklad.org.ua
</p>
<p>
    Документация актуальной версии api:<br>
    <a href="<?=HOME?>/v2/doc_groups">Группы</a><br>
    <a href="<?=HOME?>/v2/doc_teachers">Преподаватели</a><br>
    <a href="<?=HOME?>/v2/doc_other">Другие запросы</a>
</p>
<p>
    Документация предыдущей версии api v1:<br>
    <a href="<?=HOME?>/v1/"><?=HOME?>/v1</a>
</p>
<h3>Пример запроса</h3>
GET JSON: <a href="<?=HOME?>/v2/groups/?filter={'limit':2,'offset':5}" target="_blank"><?=HOME?>/v2/groups/?filter={'limit':2,'offset':5}</a>
<br>
<h3>Ответ</h3>
<p><pre><?=file_get_contents(ROOT."/assets/json_files/group_1_example.json",FILE_USE_INCLUDE_PATH);?></pre></p>
<h3>Параметры ответа</h3>
    <div class="table-responsive">
        <table class="table-primary">
            <thead>
            <tr>
                <th>Названия поля</th>
                <th>Описание</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-title="Названия поля">statusCode</td>
                <td data-title="Описание">Код статуса запроса</td>
            </tr>
            <tr>
                <td data-title="Названия поля">timeStamp</td>
                <td data-title="Описание">Серверное время (timestamp)</td>
            </tr>
            <tr>
                <td data-title="Названия поля">message</td>
                <td data-title="Описание">Сообщения статуса запроса(может выводится дополнительная информация)</td>
            </tr>
            <tr>
                <td data-title="Названия поля">debugInfo</td>
                <td data-title="Описание">Может выводится служебная информация (по умолчанию null)</td>
            </tr>
            <tr>
                <td data-title="Названия поля">meta</td>
                <td data-title="Описание">Если в результате запроса много записей - выводится дополнительная информация в данное поле:<br>
                    int <i>total_count</i> - общее количество записей<br>
                    int <i>offset</i> - смещение<br>
                    int <i>limit</i> - количество выводимых записей<br>
                </td>
            </tr>
            <tr>
                <td data-title="Названия поля">data</td>
                <td data-title="Описание">Результат запроса</td>
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