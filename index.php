<?php
session_start();

if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {

    include_once 'config/core.php';
    include_once 'config/database.php';
    include_once 'objects/Barometer.php';
    include_once 'objects/Functions.php';

    // instantiate database and charts object
    $database = new Database();
    $db = $database->getConnection();

    $barometer = new Barometer($db);
    $createJson = new functions();
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - Baromètre</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/bootstrap-table.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">
            <link href="css/monitoring-app.css" rel="stylesheet">
            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>

            <style>
                th, td {
                    text-align: center;
                }
                th .th-inner{
                    font-size: 12px;
                }
                #store input {
                    visibility:hidden;
                }
            </style>
        </head>

        <body>
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php"><span>STORM</span>onitoring</a>
                        <ul class="user-menu">
                            <li class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> <?php echo $_SESSION['name']; ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="logout.php"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Déconnexion</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.container-fluid -->
            </nav>

            <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar" style="width: 50px;">
                <ul class="nav menu">
                    <li class="active" title="Baromètre"><a href="index.php"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></a></li>
                    <li title="Vue Détaillée"><a href="charts.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></a></li>
                    <li title="Alerting"><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>

                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                        <li title="Gestion des utilisateurs"><a href="read_user.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                        <li title="Gestion des mots clés"><a href="read_keyword.php"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif; ?>

                    <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>

                </ul>
            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li class="active"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></li>
                    </ol>
                </div><!--/.row-->
                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form action="index.php" method="POST">
                                    <div class="radio" id="store">
                                        <label id="allStore"><a href="index.php" style="outline: 0;"><img src="images/store.png"></a><span id="checkGooglePlay">Tous Stores</span></label>
                                        <label id="GooglePay">
                                            <input type="radio" onchange="this.form.submit()" name="optionsRadios" id="optionsRadios1" value="googleplay" <?php
                                            if (isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == 'googleplay') {
                                                echo ' checked="checked"';
                                            }
                                            ?> ><img src="images/android.png"><span id="checkGooglePlay">Google Play</span>
                                        </label>
                                        <label id="AppStore">
                                            <input type="radio" onchange="this.form.submit()" name="optionsRadios" id="optionsRadios2" value="appstore" <?php
                                            if (isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == 'appstore') {
                                                echo ' checked="checked"';
                                            }
                                            ?>><img src="images/appstore.PNG"><span id="checkAppStore">App Store</span>
                                        </label>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == "googleplay") {
                                    $data = $barometer->getAndroidBarometer();
                                    $barometerData = $data->fetchAll(PDO::FETCH_OBJ);
                                } elseif (isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == "appstore") {
                                    $data = $barometer->getIosBarometer();
                                    $barometerData = $data->fetchAll(PDO::FETCH_OBJ);
                                } elseif (isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == "beta") {
                                    $data = $barometer->getBetaBarometer();
                                    $barometerData = $data->fetchAll(PDO::FETCH_OBJ);
                                } else {
                                    $data = $barometer->getBarometer();
                                    $barometerData = $data->fetchAll(PDO::FETCH_OBJ);
                                }

                                $createJson->createJsonFileBrometer($barometerData);
                                ?>

                                <table class="table table-striped"
                                       data-toggle="table"
                                       data-url="tables/barometerData.json"
                                       data-show-export="true"
                                       data-show-refresh="true"
                                       data-show-toggle="false"
                                       data-show-columns="true"
                                       data-search="true"
                                       data-select-item-name="toolbar1"
                                       data-pagination="true"
                                       data-sort-name="name"
                                       data-sort-order="desc"
                                       data-show-pagination-switch="true"
                                       data-page-size = "20">
                                    <thead>
                                        <tr>
                                            <th class="col-md-5" data-field="appName" data-sortable="true" data-formatter="LinkFormatter">Application</th>
                                            <th data-field="icon" data-formatter="LinkFormatter">Icone</th>
                                            <th data-field="storeicon" data-formatter="LinkStore">Store</th>
                                            <th class="col-md-1" data-field="appCurrentStars"  data-sortable="true">Note</th>
                                            <th class="col-md-1" data-field="Unites_total" data-sortable="true" data-formatter="FormatThousand">Téléchargements M-1</th>
                                            <th class="col-md-1" data-field="Unites_cumul" data-sortable="true" data-formatter="FormatThousand">Téléchargements cumulés</th>
                                            <th class="col-md-1" data-field="appVersion" data-sortable="false">Version appli</th>
                                            <th class="col-md-1" data-field="Unites" data-sortable="true" data-formatter="FormatThousand">Visiteurs uniques</th>
                                            <th class="col-md-1" data-field="currentVersionReleaseDate" data-sortable="false">Mise à jour appli</th>
                                            <th class="col-md-1" data-field="DateMeasure" data-sortable="false">Extraction de données</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="js/jquery-1.11.1.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="js/bootstrap-datepicker.js"></script>
                <script src="js/bootstrap-table.js"></script>
                <script src="js/plugin/tableExport.js"></script>
                <script src="js/modules/export-png/html2canvas.js"></script>
                <script src="js/modules/export-png/jquery.base64.js"></script>
                <script src="js/modules/export-png/bootstrap-table-export.js"></script>
                <!--<script src="js/modules/export-png/tableExport.js"></script>-->
                <script>

                                                !function ($) {
                                                    $(document).on("click", "ul.nav li.parent > a > span.icon", function () {
                                                        $(this).find('em:first').toggleClass("glyphicon-minus");
                                                    });
                                                    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
                                                }(window.jQuery);

                                                $(window).on('resize', function () {
                                                    if ($(window).width() > 768)
                                                        $('#sidebar-collapse').collapse('show')
                                                })
                                                $(window).on('resize', function () {
                                                    if ($(window).width() <= 767)
                                                        $('#sidebar-collapse').collapse('hide')
                                                });

                                                function LinkFormatter(value, row) {
                                                    var id = row.appIdAllStore;
                                                    var strHTML = "<a href='/charts.php?id_app=" + id + "'>" + value + "</a>";

                                                    var valReturn = strHTML;
                                                    return valReturn;
                                                }

                                                function LinkStore(value, row) {
                                                    var url = row.appURL;
                                                    var strHTML = "<a href='" + url + "'target='_blank'>" + value + "</a>";

                                                    var valReturn = strHTML;
                                                    return valReturn;
                                                }

                                                function FormatThousand(value, row) {
                                                    if (value !== null) {
                                                        var value = value.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
                                                    }
                                                    return value;
                                                }
                </script>
                <?php
            } elseif ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 1) { // Le mot de passe n'est pas bon.
                header('Location: notfound.php');
            } else {
                header('Location: login.php');
            }// Fin du else.