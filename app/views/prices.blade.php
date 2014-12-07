<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
</head>
<body>

<div id='map'></div>
<script>
    L.mapbox.accessToken = 'pk.eyJ1Ijoib2x1bWJ5IiwiYSI6IjFDTWM3V2sifQ.KXc-98r7KCMVsboqLE9nFA';
    var map = L.mapbox.map('map', 'olumby.ke3hc51l').setView([40, -74.50], 9);
</script>


</body>
</html>