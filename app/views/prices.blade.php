<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
    <link href='/css/style.css' rel='stylesheet' />
</head>
<body>

    <section id="introduction" class="bg-navy white">
        <div class="container">
            <h1 class="text-light">Gas</h1>
        </div>
    </section>

    <section id="grid" class="bg-lightgrey">
        <div class="container">
            <div class="row clearfix">
                <div class="column-9 padded" style="float:right;">
                    <div id='map' style="min-height:500px;"></div>
                </div>
                <div class="column-7 padded">
                    <div class="row text-center" id="fuelButtons">
                        <button data-fuel="GPR" class="button bg-white black shadow selected" name="GPR">Gasolina 95</button>
                        <button data-fuel="G98" class="button bg-white black shadow deselected" name="G98">Gasolina 98</button>
                        <button data-fuel="GOA" class="button bg-white black shadow deselected" name="GOA">Diésel</button>
                        <button data-fuel="NGO" class="button bg-white black shadow deselected" name="NGO">Diésel+</button>
                    </div>
                    <form class="row">
                        <fieldset>
                            <input type="text" name="searchQuery" placeholder="Location.." style="width:100%;" />
                        </fieldset>
                        <fieldset>
                            <input type="submit" name="search" placeholder="Search" />
                        </fieldset>
                    </form>
                    <table class="row">
                        <thead>
                            <th>Name</th>
                            <th>Price</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BP</td>
                                <td>0,944</td>
                                <td>Directions</td>
                            </tr>
                            <tr>
                                <td>Repsol</td>
                                <td>0,944</td>
                                <td>Directions</td>
                            </tr>
                            <tr>
                                <td>BP</td>
                                <td>0,944</td>
                                <td>Directions</td>
                            </tr>
                            <tr>
                                <td>Repsol</td>
                                <td>0,944</td>
                                <td>Directions</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
<script src='/js/main.js'></script>

</body>
</html>