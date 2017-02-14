<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Произошла ошибка либо на сайте в данный момент проводятся технические работы.</title>
    <meta name="robots" content="DISALLOW">
    <meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <style type="text/css">
        body, div, h1 {
            font-family: Verdana, Arial, Helvetica;
            margin: 0
        }

        div {
            color: #000;
            padding: 20px;
            text-align: left;
            font-size: 0.72em;
            border: 1px solid #999;
            position: absolute;
            left: 20%;
            top: 30%;
            width: 60%
        }

        h1 {
            color: #999;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5em
        }
    </style>
</head>
<body>
<div>
    <h1>Ошибка / плановые технические работы</h1>
    Произошла ошибка либо на сайте в данный момент проводятся технические работы. Приносим свои извинения за временные
    неудобства. Пожалуйста, повторите попытку позднее
</div>
<div>
    <?php
    global $global;
    echo $global['db_link']->connect_errno . ':' . $global['db_link']->connect_error;
    ?>
</div>
</body>
</html>