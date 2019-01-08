<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="3">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <title>Vue SPA Demo</title>
</head>

<body>

<div id="app">
    <component-admin :urldata="{{ json_encode($jsonArray) }}"></component-admin>
    <component-player :urldata="{{ json_encode($jsonArray) }}"></component-player>
</div>

<script src="{{ mix('js/app.js') }}"></script>

</body>
</html>

<style>
    *{
        font-family: 'Roboto Condensed', sans-serif;
    }
    body{
        background: #edeef0;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }

    .adminBlock{
        width: 100%;
        padding: 20px;
        font-size: 35px;
        text-align: center;
        border: 2px solid pink;
        color: pink;
        font-weight: bold;
        word-spacing: 10px;
    }

    .playerBlock{
        display:flex;
        width: 100%;
        font-size: 25px;
        color: seagreen;
        padding: 10px;
        border: 1px solid seagreen;
        text-align: center;
    }

    .dataPlayer{
        width: 32%;
        flex-wrap: wrap;
        padding: 10px;
    }

    .gameResult{
        width: 100%;
        font-size: 30px;
        color: blue;
        border: 1px solid blue;
        padding: 20px;
    }

</style>