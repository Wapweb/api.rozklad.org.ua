<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>API расписания КПИ - другие методы</title>
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
                <li style="width: 25%;"><a href="<?=HOME?>/v2/doc_teachers">Преподаватели</a></li>
                <li style="width: 25%;"  class="items-inline-active"><a href="<?=HOME?>/v2/doc_other">Другое</a></li>
            </ul>
        </div>

    </div>

    <div class="container">
        <div class="grid grid-pad">
            <div class="col-1-1">
                <div class="content">
                    <h3 class="logo-border">Другие запросы</h3>
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
                                <td data-title="Url"><a href="<?=HOME?>/v2/weeks" target="_blank"><?=HOME?>/v2/weeks</a> </td>
                                <td data-title="Описание">Получить номер текущей недели (1ая или 2ая)</td>
                            </tr>
                            <tr>
                                <td data-title="Url"><a href="<?=HOME?>/version" target="_blank"><?=HOME?>/version</a></td>
                                <td data-title="Описание">Получить актуальную версию api
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
        <ul class="items-horizontal small left" style="margin-top: 20px">
            <li>&copy; 2014 api.rozklad.org.ua</li>
        </ul>
        <ul class="items-inline small">
        </ul>
    </div>
</div>
</body>
</html>