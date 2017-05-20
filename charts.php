<?php
session_start(); // Démarage de la session
if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {

    include_once 'config/core.php';
    include_once 'config/database.php';
    include_once 'objects/Charts.php';
    include_once 'objects/Reviews.php';
    include_once 'objects/Functions.php';


    // instantiate database and charts object
    $database = new Database();
    $db = $database->getConnection();

    $charts = new Charts($db);
    $review = new Reviews($db);
    $createJson = new functions();

    // Install & uninstall & upgrade line chart array
    $date_sales = array();
    $install_android = array();
    $uninstall_android = array();
    $upgrade_android = array();
    $install_ios = array();
    $uninstall_ios = array();
    $upgrade_ios = array();
   

    // Visitors bar chart array
    $date_visitors = array();
    $visitors_android = array();
    $visitors_ios = array();

    // Notes line chart array
    $date_notes = array();
    $notes_android = array();
    $notes_android_total = array();
    $notes_ios = array();
    $nbr_de_notes_android = array();

    //get two appID from global ID
    if (filter_input(INPUT_GET, 'id_app')) {
        $id_global = filter_input(INPUT_GET, 'id_app');
        $id_app_i = $charts->getappIDIos($id_global);
        $id_app_a = $charts->getappIDAndroid($id_global);
        $id_app_ios = $id_app_i->fetch();
        $id_app_android = $id_app_a->fetch();
    } else {
        $id_app_i = $charts->getappIDIos();
        $id_app_a = $charts->getappIDAndroid();
        $id_app_ios = $id_app_i->fetch();
        $id_app_android = $id_app_a->fetch();
    }

    /*
     * Install & uninstall & upgrade line chart
     */
    if (filter_input(INPUT_GET, 'id_app')) {
        $max_date_sales = $charts->getMaxDateSales($id_global);
    } else {
        $max_date_sales = $charts->getMaxDateSales();
    }
    $max_date_s = $max_date_sales->fetch(PDO::FETCH_ASSOC);

    $date_sales_end_default = $max_date_s['date'];
    $date_sales_start_default = strtotime("-1 month", strtotime($date_sales_end_default));
    $date_sales_start_default = date("Y-m-d", $date_sales_start_default);

    if (filter_input(INPUT_GET, 'id_app') && filter_input(INPUT_GET, 'start_sales') && filter_input(INPUT_GET, 'end_sales')) {

        $date_sales_start = filter_input(INPUT_GET, 'start_sales');
        $date_sales_end = filter_input(INPUT_GET, 'end_sales');

        $chart_sales_a = $charts->getSalesAndroid($date_sales_start, $date_sales_end, $id_app_android['appID']);
        $chart_sales_i = $charts->getSalesIos($date_sales_start, $date_sales_end, $id_app_ios['appID']);
        $date_sales_q = $charts->getDatesBetweenTwoDates($date_sales_start, $date_sales_end);

        $chart_sales_android = $chart_sales_a->fetchAll(PDO::FETCH_OBJ);
        $chart_sales_ios = $chart_sales_i->fetchAll(PDO::FETCH_OBJ);
        $date_sales_query = $date_sales_q->fetchAll(PDO::FETCH_OBJ);
    } else if (filter_input(INPUT_GET, 'id_app')) {

        $id = filter_input(INPUT_GET, 'id_app');

        $chart_sales_a = $charts->getSalesAndroid($date_sales_start_default, $date_sales_end_default, $id_app_android['appID']);
        $chart_sales_i = $charts->getSalesIos($date_sales_start_default, $date_sales_end_default, $id_app_ios['appID']);
        $date_sales_q = $charts->getDatesBetweenTwoDates($date_sales_start_default, $date_sales_end_default);


        $chart_sales_android = $chart_sales_a->fetchAll(PDO::FETCH_OBJ);
        $chart_sales_ios = $chart_sales_i->fetchAll(PDO::FETCH_OBJ);
        $date_sales_query = $date_sales_q->fetchAll(PDO::FETCH_OBJ);
    } else {
        $chart_sales_a = $charts->getSalesAndroid($date_sales_start_default, $date_sales_end_default, $id_app_android['appID']);
        $chart_sales_i = $charts->getSalesIos($date_sales_start_default, $date_sales_end_default, $id_app_ios['appID']);
        $date_sales_q = $charts->getDatesBetweenTwoDates($date_sales_start_default, $date_sales_end_default);


        $chart_sales_android = $chart_sales_a->fetchAll(PDO::FETCH_OBJ);
        $chart_sales_ios = $chart_sales_i->fetchAll(PDO::FETCH_OBJ);
        $date_sales_query = $date_sales_q->fetchAll(PDO::FETCH_OBJ);
    }

    foreach ($chart_sales_android as $data) {
        array_push($install_android, $data->Unites);
        array_push($uninstall_android, $data->Daily_uninstall);
        array_push($upgrade_android, $data->Daily_upgrade);
    }

    foreach ($date_sales_query as $data) {

        array_push($date_sales, $data->date);
    }
    foreach ($chart_sales_ios as $data) {
        array_push($install_ios, $data->Unites);
        array_push($uninstall_ios, $data->Daily_uninstall);
        array_push($upgrade_ios, $data->Daily_upgrade);
    }

    /*
     * Visitors bar chart
     */
    if (filter_input(INPUT_GET, 'id_app')) {
        $chart_visitors_android = $charts->getVisitorsAndroid(filter_input(INPUT_GET, 'id_app'));
        $chart_visitors_ios = $charts->getVisitorsIos(filter_input(INPUT_GET, 'id_app'));
        $chart_visitors_android = $chart_visitors_android->fetchAll(PDO::FETCH_OBJ);
        $chart_visitors_ios = $chart_visitors_ios->fetchAll(PDO::FETCH_OBJ);
    } else {
        $chart_visitors_android = $charts->getVisitorsAndroid();
        $chart_visitors_ios = $charts->getVisitorsIos();
        $chart_visitors_android = $chart_visitors_android->fetchAll(PDO::FETCH_OBJ);
        $chart_visitors_ios = $chart_visitors_ios->fetchAll(PDO::FETCH_OBJ);
    }

    foreach ($chart_visitors_android as $data) {
        array_push($visitors_android, $data->Unites);
        array_push($date_visitors, $data->date);
    }

    foreach ($chart_visitors_ios as $data) {
        array_push($visitors_ios, $data->Unites);
    }

    $visitors_android = array_reverse($visitors_android);
    $visitors_ios = array_reverse($visitors_ios);
    $date_visitors = array_reverse($date_visitors);

    /*
     * Notes line chart
     */
    if (filter_input(INPUT_GET, 'id_app')) {

        $max_date_rating = $charts->getMaxDateRating($id_app_android['appID']);
    } else {
        $max_date_rating = $charts->getMaxDateRating();
    }
    $max_date_r = $max_date_rating->fetch(PDO::FETCH_ASSOC);

    $date_notes_end_default = $max_date_r['date'];
    $date_notes_start_default = strtotime("-15 days", strtotime($date_notes_end_default));
    $date_notes_start_default = date("Y-m-d", $date_notes_start_default);

    if (filter_input(INPUT_GET, 'id_app') && filter_input(INPUT_GET, 'start_notes') && filter_input(INPUT_GET, 'end_notes')) {

        $date_notes_start = filter_input(INPUT_GET, 'start_notes');
        $date_notes_end = filter_input(INPUT_GET, 'end_notes');
        $chart_notes_a = $charts->getNotesAndroid($date_notes_start, $date_notes_end, $id_app_android['appID']);
        $chart_notes_i = $charts->getNotesIos($date_notes_start, $date_notes_end, $id_app_ios['appID']);
        $date_notes_q = $charts->getDatesBetweenTwoDates($date_notes_start, $date_notes_end);
        $chart_nbrnotes = $charts->getNbrDeNotes($date_notes_start, $date_notes_end, $id_app_android['appID']);

        $chart_notes_android = $chart_notes_a->fetchAll(PDO::FETCH_OBJ);
        $chart_notes_ios = $chart_notes_i->fetchAll(PDO::FETCH_OBJ);
        $date_notes_query = $date_notes_q->fetchAll(PDO::FETCH_OBJ);
        $chart_nbr_notes = $chart_nbrnotes->fetchAll(PDO::FETCH_OBJ);
    } else if (filter_input(INPUT_GET, 'id_app')) {

        $chart_notes_a = $charts->getNotesAndroid($date_notes_start_default, $date_notes_end_default, $id_app_android['appID']);
        $chart_notes_i = $charts->getNotesIos($date_notes_start_default, $date_notes_end_default, $id_app_ios['appID']);
        $date_notes_q = $charts->getDatesBetweenTwoDates($date_notes_start_default, $date_notes_end_default);
        $chart_nbrnotes = $charts->getNbrDeNotes($date_notes_start_default, $date_notes_end_default, $id_app_android['appID']);


        $chart_notes_android = $chart_notes_a->fetchAll(PDO::FETCH_OBJ);
        $chart_notes_ios = $chart_notes_i->fetchAll(PDO::FETCH_OBJ);
        $date_notes_query = $date_notes_q->fetchAll(PDO::FETCH_OBJ);
        $chart_nbr_notes = $chart_nbrnotes->fetchAll(PDO::FETCH_OBJ);
    } else {
        $chart_notes_a = $charts->getNotesAndroid($date_notes_start_default, $date_notes_end_default, $id_app_android['appID']);
        $chart_notes_i = $charts->getNotesIos($date_notes_start_default, $date_notes_end_default, $id_app_ios['appID']);
        $date_notes_q = $charts->getDatesBetweenTwoDates($date_notes_start_default, $date_notes_end_default);
        $chart_nbrnotes = $charts->getNbrDeNotes($date_notes_start_default, $date_notes_end_default, $id_app_android['appID']);


        $chart_notes_android = $chart_notes_a->fetchAll(PDO::FETCH_OBJ);
        $chart_notes_ios = $chart_notes_i->fetchAll(PDO::FETCH_OBJ);
        $date_notes_query = $date_notes_q->fetchAll(PDO::FETCH_OBJ);
        $chart_nbr_notes = $chart_nbrnotes->fetchAll(PDO::FETCH_OBJ);
    }

    foreach ($chart_nbr_notes as $data) {
        array_push($nbr_de_notes_android, $data->nbrdenote);
    }

    foreach ($chart_notes_android as $data) {

        array_push($notes_android, $data->Daily_Average_Rating);
        array_push($notes_android_total, $data->Total_Average_Rating);
    }

    foreach ($date_notes_query as $data) {

        array_push($date_notes, $data->date);
    }

    foreach ($chart_notes_ios as $data) {

        array_push($notes_ios, $data->Total_Average_Rating);
    }

    // Gestion parametre URl
    if (filter_input(INPUT_GET, 'id_app')) {
        $appIdAllStore = filter_input(INPUT_GET, 'id_app');
    } else {
        $appIdAllStore = "0999BBDUO";
    }

    /*
     * @Array $sum_stores
     * Somme de deux array $visitors_android et $visitors_ios
     */
    $sum_stores = array_map(function () {
        return array_sum(func_get_args());
    }, $visitors_android, $visitors_ios);
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - Vue</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/bootstrap-datepicker.css" rel="stylesheet">
            <link href="css/bootstrap-table.css" rel="stylesheet">
            <link href="css/bootstrap-editable.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">
            <link href="css/monitoring-app.css" rel="stylesheet">
            <link href="css/daterangepicker-bs3.css" rel="stylesheet">
            <!--bootstrap switch css--> 
            <link href="css/bootstrap-switch.css" rel="stylesheet">

            <style>
                th, td {
                    text-align: center;
                }
                .th-inner {
                    font-size: 11px;
                }
            </style>

            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>
            <script type="text/javascript" src="js/modules/jquery.min.js"></script>
            <script>

                // Install & uninstall & upgrade line chart
                var date_sales_start_default = <?= json_encode($date_sales_start_default) ?>;
                var date_sales_end_default = <?= json_encode($date_sales_end_default) ?>;
                var date_de_mesure = <?= json_encode($date_sales) ?>;
                var telechargement_android = <?= json_encode($install_android) ?>;
                var telechargement_ios = <?= json_encode($install_ios) ?>;
                var desinstallation_android = <?= json_encode($uninstall_android) ?>;
                var mise_a_jour_android = <?= json_encode($upgrade_android) ?>;
                var desinstallation_ios = <?= json_encode($uninstall_ios) ?>;
                var mise_a_jour_ios = <?= json_encode($upgrade_ios) ?>;

                // Visitors bar chart
                var visiteurs_android = <?= json_encode($visitors_android) ?>;
                var visiteurs_ios = <?= json_encode($visitors_ios) ?>;
                var visiteurs_date = <?= json_encode($date_visitors) ?>;
                var sum_stores = <?= json_encode($sum_stores) ?>;

                // Notes line chart
                var date_notes_start_default = <?= json_encode($date_notes_start_default) ?>;
                var date_notes_end_default = <?= json_encode($date_notes_end_default) ?>;
                var notes_date = <?= json_encode($date_notes) ?>;
                var notes = <?= json_encode($notes_android) ?>;
                var notes_ios = <?= json_encode($notes_ios) ?>;
                var notes_android_total = <?= json_encode($notes_android_total) ?>;
                var nbr_notes_android = <?= json_encode($nbr_de_notes_android) ?>;

                //data nbre de notes android
                var notes_android = [];
                var notes_a = JSON.parse(JSON.stringify(notes));
                var nbr_notes_a = JSON.parse(JSON.stringify(nbr_notes_android));
                for (var i in notes_a) {
                    notes_android.push({
                        y: parseFloat(notes_a[i]),
                        mydata: parseInt(nbr_notes_a[i])
                    });
                }
            </script>
            <script type="text/javascript" src="js/charts.js"></script>
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
            <?php
            $getName = $charts->appName(filter_input(INPUT_GET, 'id_app'));
            $getNameDefault = $charts->appName();
            ?>
            <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar" style="width:50px">
                <ul class="nav menu">
                    <li title="Baromètre"><a href="index.php" style="outline: 0;"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></a></li>
                    <li class="active" title="Vue Détaillée"><a href="charts.php" style="outline: 0;"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></a></li>
                    <li title="Alerting"><a href="read_alertes.php" style="outline: 0;"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>
                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                        <li title="Gestion des utilisateurs"><a href="read_user.php" style="outline: 0;"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                        <li title="Gestion des mots clés"><a href="read_keyword.php" style="outline: 0;"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif; ?>

                    <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>
                </ul>
            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">

                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li class="active"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></li>
                    </ol>
                </div><!--/.row-->

                <!--======================================================= APPS DROPDOWNLIST AND CALENDAR  ==============================================================-->
                </br>
                <?php $app_name_list = $charts->getAppNameList()->fetchAll(PDO::FETCH_OBJ); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-3 col-md-offset-9" style="padding-right: 0px;">
                            <form method="get" role="form" id="form_app_id">

                                <div class="form-group">
                                    <select name="id_app" class="form-control" id="select_app_id">
                                        <?php
                                        if (filter_input(INPUT_GET, 'id_app')) {
                                            $selecteur = filter_input(INPUT_GET, 'id_app');
                                        } else {
                                            $selecteur = "0999BBDUO";
                                        }
                                        foreach ($app_name_list as $data) {
                                            echo '<option value="' . $data->appIdAllStore . '"';
                                            if ($selecteur === $data->appIdAllStore) {
                                                echo 'selected="selected"';
                                            }
                                            echo '>' . $data->appName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--   Fin Liste Déroulante -->
                <!--======================================================= FIN APPS DROPDOWNLIST AND CALENDAR  ==============================================================-->
                <div class="row">
                    <div class="col-lg-2">
                        <div class="panel panel-default">
                            <table class="table table-bordered">
                                <tr>
                                    <td><?php if (filter_input(INPUT_GET, 'id_app')) { ?>
                                            <?php foreach ($getName->fetchAll(PDO::FETCH_OBJ) as $data) : ?>
                                                <div class="form-group" id="iconleft">
                                                    <img src="images/<?php echo $data->appName ?>.png" id="img">
                                                </div>
                                                <div class="entity-name" itemprop="name"><?php echo $data->appName ?></div>
                                            <?php endforeach; ?>
                                        <?php } else { ?>
                                            <?php foreach ($getNameDefault->fetchAll(PDO::FETCH_OBJ) as $data) : ?>
                                                <div class="form-group" id="iconleft">
                                                    <img src="images/<?php echo $data->appName ?>.png" id="img">
                                                </div>
                                                <div class="entity-name" itemprop="name"><?php echo $data->appName ?></div>
                                            <?php endforeach; ?>
                                        <?php } ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="panel panel-default">
                            <table class="table table-striped" id="infos">
                                <thead>
                                    <tr>
                                        <th>Store</th>
                                        <th>Version appli</th>
                                        <th>Version OS min</th>
                                        <th>Note actuelle</th>
                                        <th>Téléchargements M-1</th>
                                        <th>Téléchargements cumulés</th>
                                        <th>Visiteurs uniques</th>
                                        <th>Mise à jour appli</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $visitor = array();

                                    if (filter_input(INPUT_GET, 'id_app')) {
                                        $datatable_android = $charts->InfosAndroid(filter_input(INPUT_GET, 'id_app'))->fetchAll(PDO::FETCH_OBJ);
                                        $datavisitor_android = $charts->visitorAndroid(filter_input(INPUT_GET, 'id_app'))->fetchAll(PDO::FETCH_OBJ);
                                        $datatable_ios = $charts->InfosIos(filter_input(INPUT_GET, 'id_app'))->fetchAll(PDO::FETCH_OBJ);
                                        $datavisitor_ios = $charts->visitorIos(filter_input(INPUT_GET, 'id_app'))->fetchAll(PDO::FETCH_OBJ);
                                    } else {
                                        $datatable_android = $charts->InfosAndroid()->fetchAll(PDO::FETCH_OBJ);
                                        $datavisitor_android = $charts->visitorAndroid()->fetchAll(PDO::FETCH_OBJ);
                                        $datatable_ios = $charts->InfosIos()->fetchAll(PDO::FETCH_OBJ);
                                        $datavisitor_ios = $charts->visitorIos()->fetchAll(PDO::FETCH_OBJ);
                                    }
                                    ?>
                                    <?php foreach ($datatable_android as $data): ?>
                                        <?php foreach ($datavisitor_android as $visitor): ?>
                                            <?php
                                            if ($visitor->count == 0) {
                                                $Unites = "NC";
                                            } else {
                                                $Unites = $visitor->Unites;
                                            }
                                            ?>

                                            <tr>
                                                <td><a href="<?php echo $data->appURL; ?>" target="_blank" style="outline: 0;"><img src="images/androidtab.png" style="width: 25px;"></a></td>
                                                <td><?php echo $data->appVersion; ?></td>
                                                <td><?php echo $data->appMinimumOsVersion; ?></td>
                                                <td><?php echo $data->appCurrentStars; ?></td>
                                                <td><?php echo $data->Unites_total; ?></td>
                                                <td><?php echo $data->Unites_cumul; ?></td>
                                                <td><?php echo $Unites; ?></td>
                                                <td><?php echo $data->currentVersionReleaseDate; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>

                                    <?php foreach ($datatable_ios as $data): ?>
                                        <?php foreach ($datavisitor_ios as $visitor): ?>
                                            <?php
                                            if ($visitor->count == 0) {
                                                $Unites = "NC";
                                            } else {
                                                $Unites = $visitor->Unites;
                                            }
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo $data->appURL; ?>" target="_blank" style="outline: 0;"><img src="images/appstoretab.PNG" style="width: 25px;"></a></td>
                                                <td><?php echo $data->appVersion; ?></td>
                                                <td><?php echo $data->appMinimumOsVersion; ?></td>
                                                <td><?php echo $data->appCurrentStars; ?></td>
                                                <td><?php echo $data->Unites_total; ?></td>
                                                <td><?php echo $data->Unites_cumul; ?></td>
                                                <td><?php echo $Unites; ?></td>
                                                <td><?php echo $data->currentVersionReleaseDate; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div id="container-notes" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container-visitors" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div><!--/.row-->
                </br>

                <?php
                // update comment graph
                if (isset($_POST['textGraph'])) {
                    if (filter_input(INPUT_POST, 'textGraph') == '') {
                        try {
                            $text = '';
                            $charts->updateTextGraph($text, $appIdAllStore);
                            $charts->text_graph = $text;
                        } catch (Exception $ex) {
                            die('ERROR: ' . $ex->getMessage());
                        }
                    } else {
                        try {
                            $text = filter_input(INPUT_POST, 'textGraph');
                            $charts->updateTextGraph($text, $appIdAllStore);
                            $charts->text_graph = $text;
                        } catch (Exception $ex) {
                            die('ERROR: ' . $ex->getMessage());
                        }
                    }
                } else {
                    $charts->getTextGraph($appIdAllStore);
                }
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="container-sales" style="min-width: 310px; height: 370px; margin: 0 auto"></div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-md-11" style="background:#fff;height: 145px;">
                            <form id="formTextGraph" action="" method="post" style="background-color: #fff; padding-bottom: 10px;">
                                <div><span class="badge badge-momo" style="font-size: 12px; margin-left: 50px;">Faits marquants :</span></div>
                                <div class="input-group" style="padding-left: 50px;">
                                    <textarea form="formTextGraph" name="textGraph" class="form-control custom-control" rows="4" style="resize:none; color: #000;background-color: #DFF2FF;"><?php echo $charts->text_graph; ?></textarea>     
                                    <span class="input-group-addon btn btn-info" id="submitTextGraph">Enregistrer</span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-1" style="background:#fff; height: 145px; padding-top: 60px; padding-left: 13px;">
                            <span style="cursor:pointer" id="export-pdf" title="exporter tout en pdf"><img src="images/pdf-download.png"></span>
                            <span style="cursor:pointer" id="export-png" title="exporter tout en png"><img src="images/png-download.png"></span>
                        </div>
                    </div>

                </div><!--/.row-->
                <br>
                <?php
                // Gestion parametre URl

                if (filter_input(INPUT_GET, 'keywords')) {
                    $keywords_url = filter_input(INPUT_GET, 'keywords');
                } else {
                    $keywords_url = "";
                }

                if (filter_input(INPUT_GET, 'start') && filter_input(INPUT_GET, 'end')) {
                    $startdate = filter_input(INPUT_GET, 'start');
                    $enddate = filter_input(INPUT_GET, 'end');
                } else {
                    // reviews of 3 last months
                    $startdate = date("Y-m-d", strtotime("-3 months"));
                    $enddate = date("Y-m-d");
                }

                $data = $review->readAll($appIdAllStore, $startdate, $enddate, $keywords_url);
                $reviewData = $data->fetchAll(PDO::FETCH_OBJ);

                $createJson->createJsonFileReview($reviewData);


                //nombre de reviews google play
                $data_android = $review->countReviewsAndroid($appIdAllStore, $startdate, $enddate, $keywords_url);
                $nb_reviews_android = $data_android->fetch(PDO::FETCH_NUM);

                //nombre de reviews app store
                $data_ios = $review->countReviewsIos($appIdAllStore, $startdate, $enddate, $keywords_url);
                $nb_reviews_ios = $data_ios->fetch(PDO::FETCH_NUM);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <span><img src="images/appstoretab.png" style="width: 25px;"><span class="badge badge-momo"><?php echo $nb_reviews_ios[0]; ?> commentaires</span></span>
                                    <span><img src="images/androidtab.png" style="width: 25px;"><span class="badge badge-momo"><?php echo $nb_reviews_android[0]; ?> commentaires</span></span>
                                </div>
                                <div class="col-md-8">
                                    <?php
                                    // mots clés 
                                    $data_keywords = $review->readAllKeywords();
                                    $keywords = $data_keywords->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($keywords as $data):

                                        //occurence 
                                        $list_keywords = str_replace(';', '|', $data->keywords);
                                        $data_occurs = $review->countOccurs($appIdAllStore, $startdate, $enddate, $list_keywords);
                                        $occurs = $data_occurs->fetch(PDO::FETCH_NUM);
                                        ?>
                                        <span id="libelle-reviews" title="<?php echo $data->keywords; ?>"><a href="charts.php?start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>&id_app=<?php echo $appIdAllStore; ?>&keywords=<?php echo $list_keywords; ?>&scroll=900" style="outline: 0; color: #454545;"><?php echo $data->label; ?> </a><span class="badge badge-momo"><?php echo $occurs[0]; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <?php endforeach; ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-momo"><a href="charts.php?start=<?php echo urlencode($startdate); ?>&end=<?php echo urlencode($enddate); ?>&id_app=<?php echo urlencode($appIdAllStore); ?>&scroll=900" style="outline: 0;"><i class="glyphicon glyphicon-refresh icon-refresh" style="color: #fff;"></i></a></span>
                                </div>
                                <!--data-detail-view="true"
                                    data-detail-formatter="detailFormatter"-->
                                <table class="table fixed-table-body"
                                       data-toggle="table" 
                                       data-url="tables/reviewData.json"
                                       data-show-export="true" 
                                       data-show-refresh="true" 
                                       data-show-toggle="false" 
                                       data-show-columns="true"
                                       data-search="true" 
                                       data-select-item-name="toolbar1" 
                                       data-pagination="true" 
                                       data-sort-name="name" 
                                       data-sort-order="desc"
                                       data-filter-control="true" 
                                       data-editable-emptytext="ajouter un commentaire"
                                       data-editable-type="textarea"
                                       data-id-field="id"
                                       data-show-pagination-switch="true"  
                                       data-show-date-range="true"
                                       style="font-size:12px;"
                                       >
                                    <thead>
                                        <tr>
                                            <!--<th class="col-md-0" data-formatter="tagReply"></th>-->
                                            <th class="col-md-2" data-field="reponse" data-filter-control="input">Réponse</th>
                                            <th class="col-md-4" data-formatter="titleComment" data-filter-control="input" data-align="left">Commentaire</th>
                                            <th class="col-md-0" data-field="stars" data-sortable="true" data-filter-control="select">Note</th>
                                            <th class="col-md-1" data-field="Store" data-formatter="urlReview" data-filter-control="select">Store</th>
                                            <th class="col-md-2" data-field="comment" data-editable="true" data-filter-control="input">Remarque</th>
                                            <th class="col-md-2" data-field="Device" data-filter-control="select">Device</th>
                                            <th class="col-md-0" data-field="Volume" data-filter-control="input">Volume</th>
                                            <th class="col-md-0" data-field="version" data-filter-control="input">Version Appli</th>
                                            <th class="col-md-2" data-field="date_epoch" data-filter-control="datepicker" data-filter-datepicker-options='{"autoclose":true, "clearBtn": true, "todayHighlight": true, "orientation": "top", "format": "yyyy-mm-dd","language": "fr"}'>Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--/.row-->
                </br>

                <script src="js/jquery-1.11.1.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="js/bootstrap-datepicker.js"></script>
                <script src="js/bootstrap-datepicker.fr.js" charset="UTF-8"></script>
                <script src="js/highcharts.js"></script>
                <script src="js/modules/canvas-tools.js"></script>
                <script src="js/modules/exporting.js"></script>
                <script src="js/modules/offline-exporting.js"></script>
                <script src="js/modules/export-csv/export-csv.js"></script>
                <script src="js/bootstrap-table.js"></script>
                <script src="js/export/bootstrap-table-export.js"></script>
                <script src="js/plugin/tableExport.js"></script>
                <script src="js/editable/bootstrap-table-editable.js"></script>
                <script src="js/editable/bootstrap-editable.js"></script>
                <script src="js/edit/jquery.tabledit.js"></script>
                <script src="js/edit/jquery.tabledit.min.js"></script>
                <!--date range js-->
                <script type="text/javascript" src="js/moment-with-langs.min.js"></script>
                <script src="js/filter/bootstrap-table-filter-control.js"></script>
                <!-- Include Date Range Picker -->
                <script type="text/javascript" src="js/daterangepicker.js"></script>
                <!--All scripts executed in this page-->
                <script type="text/javascript" src="js/charts_scripts.js"></script>

                <script>
                !function ($) {
                    $(document).on("click", "ul.nav li.parent > a > span.icon", function () {
                        $(this).find('em:first').toggleClass("glyphicon-minus");
                    });
                    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
                }(window.jQuery);

                $(window).on('resize', function () {
                    if ($(window).width() > 768)
                        $('#sidebar-collapse').collapse('show');
                });
                $(window).on('resize', function () {
                    if ($(window).width() <= 767)
                        $('#sidebar-collapse').collapse('hide');
                });

                </script>

            </div><!--/.main-->
            <?php
        } elseif ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 1) { // Le mot de passe n'est pas bon.
            header('Location: notfound.php');
        } else {
            header('Location: login');
        }// Fin du else.
