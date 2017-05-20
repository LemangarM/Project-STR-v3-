<?php
session_start(); // Démarage de la session
// $startTime = microtime(true);
// register_shutdown_function('measureTime');
//
// function measureTime(){
// global $startTime;
//     $execTime = microtime(true)-$startTime;
//     echo "Script execution time: $execTime seconds.";
// }
if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {

    include_once 'config/core.php';
    include_once 'config/database.php';
    include_once 'objects/ChartsBtv.php';
    include_once 'objects/Charts.php';
    include_once 'objects/Top_10.php';


    // instantiate database and charts object
    $database = new Database();
    $db = $database->getConnection();

    $chartsBtv = new ChartsBtv($db);
    $charts = new Charts($db);
    $top_10 = new top_10($db);

    // Palmarès Btv android array
    $date = array();
    $note_orange_a = array();
    $note_sfr_a = array();
    $note_btv_a = array();
    $notes_mytf1_a = array();
    $notes_mycanal_a = array();
    $notes_6play_a = array();

    // Palmarès Btv iOS array
    $note_orange_i = array();
    $note_sfr_i = array();
    $note_btv_i = array();
    $notes_mytf1_i = array();
    $notes_mycanal_i = array();
    $notes_6play_i = array();

    /*
     * Rating concurrence chart
     */
    $max_date_rating = $charts->getMaxDateRating('0004BTVSM');
    $max_date_r = $max_date_rating->fetch(PDO::FETCH_ASSOC);

    $date_end_default = $max_date_r['date'];
    $date_start_default = strtotime("-15 days", strtotime($date_end_default));
    $date_start_default = date("Y-m-d", $date_start_default);

    $notes_date = $charts->getDatesBetweenTwoDates($date_start_default, $date_end_default);
    $notes_date_query = $notes_date->fetchAll(PDO::FETCH_OBJ);

    $notes_orange_a = $chartsBtv->getPalmares($date_start_default, $date_end_default, '0050ORGTV');
    $notes_orange_i = $chartsBtv->getPalmares($date_start_default, $date_end_default, '308816822');

    $notes_sfr_a = $chartsBtv->getPalmares($date_start_default, $date_end_default, '0050SFRTV');
    $notes_sfr_i = $chartsBtv->getPalmares($date_start_default, $date_end_default, '309187846');
    
    $notes_mytf1_a = $chartsBtv->getPalmares($date_start_default, $date_end_default, '0060MYTF1');
    $notes_mytf1_i = $chartsBtv->getPalmares($date_start_default, $date_end_default, '407248490');
    
    $notes_mycanal_a = $chartsBtv->getPalmares($date_start_default, $date_end_default, '0062CANAL');
    $notes_mycanal_i = $chartsBtv->getPalmares($date_start_default, $date_end_default, '694580816');
    
    $notes_6play_a = $chartsBtv->getPalmares($date_start_default, $date_end_default, '00616PLAY');
    $notes_6play_i = $chartsBtv->getPalmares($date_start_default, $date_end_default, '369692259');

    $notes_btv_a = $chartsBtv->getPalmaresBtv($date_start_default, $date_end_default, '0004BTVSM');
    $notes_btv_i = $chartsBtv->getPalmaresBtv($date_start_default, $date_end_default, '739824309');

    $notes_orange_android = $notes_orange_a->fetchAll(PDO::FETCH_OBJ);
    $notes_orange_ios = $notes_orange_i->fetchAll(PDO::FETCH_OBJ);

    $notes_sfr_android = $notes_sfr_a->fetchAll(PDO::FETCH_OBJ);
    $notes_sfr_ios = $notes_sfr_i->fetchAll(PDO::FETCH_OBJ);

    $notes_btv_android = $notes_btv_a->fetchAll(PDO::FETCH_OBJ);
    $notes_btv_ios = $notes_btv_i->fetchAll(PDO::FETCH_OBJ);
    
    $notes_mytf1_android = $notes_mytf1_a->fetchAll(PDO::FETCH_OBJ);
    $notes_mytf1_ios = $notes_mytf1_i->fetchAll(PDO::FETCH_OBJ);
    
    $notes_mycanal_android = $notes_mycanal_a->fetchAll(PDO::FETCH_OBJ);
    $notes_mycanal_ios = $notes_mycanal_i->fetchAll(PDO::FETCH_OBJ);
    
    $notes_6play_android = $notes_6play_a->fetchAll(PDO::FETCH_OBJ);
    $notes_6play_ios = $notes_6play_i->fetchAll(PDO::FETCH_OBJ);

    foreach ($notes_orange_android as $data) {
        $note_orange_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_orange_ios as $data) {
        $note_orange_i[] = $data->Total_Average_Rating;
    }
    foreach ($notes_sfr_android as $data) {
        $note_sfr_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_sfr_ios as $data) {
        $note_sfr_i[] =$data->Total_Average_Rating;
    }
    foreach ($notes_mytf1_android as $data) {
        $note_mytf1_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_mytf1_ios as $data) {
        $note_mytf1_i[] =$data->Total_Average_Rating;
    }
    foreach ($notes_mycanal_android as $data) {
        $note_mycanal_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_mycanal_ios as $data) {
        $note_mycanal_i[] =$data->Total_Average_Rating;
    }
    foreach ($notes_6play_android as $data) {
        $note_6play_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_6play_ios as $data) {
        $note_6play_i[] =$data->Total_Average_Rating;
    }
    foreach ($notes_btv_android as $data) {
        $note_btv_a[] = $data->Total_Average_Rating;
    }
    foreach ($notes_btv_ios as $data) {
        $note_btv_i[] = $data->Total_Average_Rating;
    }
    foreach ($notes_date_query as $data) {
        $date[] = $data->date;
    }

    //Get total VOD
    $total_VOD_a = $chartsBtv->getTotalVOD('0004BTVSM');
    $total_VOD_i = $chartsBtv->getTotalVOD('739824309');
    $VOD_a = $chartsBtv->getVOD('0004BTVSM');
    $VOD_i = $chartsBtv->getVOD('739824309');

    $total_VOD_android = $total_VOD_a->fetch();
    $total_VOD_ios = $total_VOD_i->fetch();
    $date_vod = $total_VOD_android['DateMeasure'];

    $VOD_android = $VOD_a->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
    $VOD_ios = $VOD_i->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

    //Get total RPVR
    $total_RPVR_a = $chartsBtv->getTotalRPVR('0004BTVSM');
    $total_RPVR_i = $chartsBtv->getTotalRPVR('739824309');
    $RPVR_a = $chartsBtv->getRPVR('0004BTVSM');
    $RPVR_i = $chartsBtv->getRPVR('739824309');

    $total_RPVR_android = $total_RPVR_a->fetch();
    $total_RPVR_ios = $total_RPVR_i->fetch();
    $date_rpvr = $total_RPVR_android['DateMeasure'];
    $RPVR_android = $RPVR_a->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
    $RPVR_ios = $RPVR_i->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

    //Get total Craches et ANRs
    $total_craches = $chartsBtv->getTotalCraches();
    $total_ANRs = $chartsBtv->getTotalANRs();
    $repartition_craches = $chartsBtv->getRepartitionCraches();
    $repartition_ANRs = $chartsBtv->getRepartitionANRs();

    $total_crache = $total_craches->fetch();
    $total_ANR = $total_ANRs->fetch();
    $repartition_crache = $repartition_craches->fetchAll(PDO::FETCH_ASSOC);
    $repartition_ANR = $repartition_ANRs->fetchAll(PDO::FETCH_ASSOC);

    //Get total notes vs comments
    $total_NAC = $chartsBtv->getTotalCommentNotNull();
    $total_NSC = $chartsBtv->getTotalCommentNull();
    $repartition_NAC = $chartsBtv->getRepartitionCommentNotNull();
    $repartition_NSC = $chartsBtv->getRepartitionCommentNull();

    $NAC_total = $total_NAC->fetch();
    $NSC_total = $total_NSC->fetch();
    $NAC_repartition = $repartition_NAC->fetchAll(PDO::FETCH_ASSOC);
    $NSC_repartition = $repartition_NSC->fetchAll(PDO::FETCH_ASSOC);

    // Get total unites OS
    $total_OS_a = $chartsBtv->getTotalUnitesOS('0004BTVSM');
    $total_OS_i = $chartsBtv->getTotalUnitesOS('739824309');
    $OS_a = $chartsBtv->getUnitesOS('0004BTVSM');
    $OS_i = $chartsBtv->getUnitesOS('739824309');

    $total_OS_android = $total_OS_a->fetch();
    $total_OS_ios = $total_OS_i->fetch();
    $date_os = $total_OS_android['DateMeasure'];
    $OS_android = $OS_a->fetchAll(PDO::FETCH_OBJ);
    $OS_ios = $OS_i->fetchAll(PDO::FETCH_OBJ);

    $name_os_android = [];
    $name_os_ios = [];
    $value_os_android = [];
    $value_os_ios = [];
    foreach ($OS_ios as $data) {
        $name_os_ios[] = $data->OSVersion;
    }
    foreach ($OS_android as $data) {
        $name_os_android[] = $data->OSVersion;
    }
    foreach ($OS_ios as $data) {
        $value_os_ios[] = (int) $data->Unites;
    }
    foreach ($OS_android as $data) {
        $value_os_android[] = (int) $data->Unites;
    }

    //Get TOtal unites version
    $total_version_a = $chartsBtv->getTotalUnitesVersion('0004BTVSM');
    $total_version_i = $chartsBtv->getTotalUnitesVersion('739824309');
    $VERSION_a = $chartsBtv->getUnitesVersion('0004BTVSM');
    $VERSION_i = $chartsBtv->getUnitesVersion('739824309');

    $total_version_android = $total_version_a->fetch();
    $total_version_ios = $total_version_i->fetch();
    $date_version = $total_version_android['DateMeasure'];
    $VERSION_android = $VERSION_a->fetchAll(PDO::FETCH_OBJ);
    $VERSION_ios = $VERSION_i->fetchAll(PDO::FETCH_OBJ);

    $name_version_android = [];
    $name_version_ios = [];
    $value_version_android = [];
    $value_version_ios = [];
    foreach ($VERSION_ios as $data) {
        $name_version_ios[] = $data->OSVersion;
    }
    foreach ($VERSION_android as $data) {
        $name_version_android[] = $data->OSVersion;
    }
    foreach ($VERSION_ios as $data) {
        $value_version_ios[] = (int) $data->Unites;
    }
    foreach ($OS_android as $data) {
        $value_version_android[] = (int) $data->Unites;
    }

    //find top 10 Android
    $datatop10_a = $top_10->findTopTenAndroid();
    $top10_Android = $datatop10_a->fetchAll(PDO::FETCH_OBJ);
    $date_top_android = $top10_Android[0]->DateMeasure;

    //find top 10 iOS
    $datatop10_i = $top_10->findTopTenIos();
    $top10_iOS = $datatop10_i->fetchAll(PDO::FETCH_OBJ);
    $date_top_ios = $top10_iOS[0]->DateMeasure;
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - Vue</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/bootstrap-table.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">

            <style>
                th, td {
                    text-align: center;
                }
                .th-inner{
                    font-size: 11px;
                }
            </style>

            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>
            <script type="text/javascript" src="js/modules/jquery.min.js"></script>

            <script>

                // Palmarès DATA
                var date_de_mesure = <?= json_encode($date); ?>;
                var notes_orange_android = <?= json_encode($note_orange_a); ?>;
                var notes_orange_ios = <?= json_encode($note_orange_i); ?>;
                var notes_sfr_android = <?= json_encode($note_sfr_a); ?>;
                var notes_sfr_ios = <?= json_encode($note_sfr_i); ?>;
                var notes_btv_android = <?= json_encode($note_btv_a); ?>;
                var notes_btv_ios = <?= json_encode($note_btv_i); ?>;
                var notes_mytf1_android = <?= json_encode($note_mytf1_a); ?>;
                var notes_mytf1_ios = <?= json_encode($note_mytf1_i); ?>;
                var notes_mycanal_android = <?= json_encode($note_mycanal_a); ?>;
                var notes_mycanal_ios = <?= json_encode($note_mycanal_i); ?>;
                var notes_6play_android = <?= json_encode($note_6play_a); ?>;
                var notes_6play_ios = <?= json_encode($note_6play_i); ?>;

                //Pie DATA
                var total_vod_android = <?= json_encode($total_VOD_android['Total']); ?>;
                var total_vod_ios = <?= json_encode($total_VOD_ios['Total']); ?>;
                var total_rpvr_android = <?= json_encode($total_RPVR_android['Total']); ?>;
                var total_craches = <?= json_encode($total_crache['Total']); ?>;
                var total_ANRs = <?= json_encode($total_ANR['Total']); ?>;
                var total_rpvr_ios = <?= json_encode($total_RPVR_ios['Total']); ?>;
                var total_os_android = <?= json_encode($total_OS_android['Total']); ?>;
                var total_os_ios = <?= json_encode($total_OS_ios['Total']); ?>;
                var total_version_android = <?= json_encode($total_version_android['Total']); ?>;
                var total_version_ios = <?= json_encode($total_version_ios['Total']); ?>;
                var total_NAC = <?= json_encode($NAC_total['total']); ?>;
                var total_NSC = <?= json_encode($NSC_total['total']); ?>;

                //VOD & RPVR & Notes vs comments & craches/ANRs
                var vod_ios = <?= json_encode(array_values($VOD_ios)[0]); ?>;
                var vod_android = <?= json_encode(array_values($VOD_android)[0]); ?>;
                var rpvr_ios = <?= json_encode(array_values($RPVR_ios)[0]); ?>;
                var rpvr_android = <?= json_encode(array_values($RPVR_android)[0]); ?>;
                var r_NAC = <?= json_encode($NAC_repartition); ?>;
                var r_NSC = <?= json_encode($NSC_repartition); ?>;
                var r_craches = <?= json_encode($repartition_crache); ?>;
                var r_ANRs = <?= json_encode($repartition_ANR); ?>;

                var data_vod_i = JSON.parse(JSON.stringify(vod_ios));
                var data_vod_a = JSON.parse(JSON.stringify(vod_android));
                var data_rpvr_i = JSON.parse(JSON.stringify(rpvr_ios));
                var data_rpvr_a = JSON.parse(JSON.stringify(rpvr_android));
                var repart_NAC = JSON.parse(JSON.stringify(r_NAC));
                var repart_NSC = JSON.parse(JSON.stringify(r_NSC));
                var repart_craches = JSON.parse(JSON.stringify(r_craches));
                var repart_ANRs = JSON.parse(JSON.stringify(r_ANRs));

                data_vod_ios = prepareArrayVR(data_vod_i);
                data_vod_android = prepareArrayVR(data_vod_a);
                data_rpvr_ios = prepareArrayVR(data_rpvr_i);
                data_rpvr_android = prepareArrayVR(data_rpvr_a);
                repartition_NAC = prepareArrayNVsC(repart_NAC);
                repartition_NSC = prepareArrayNVsC(repart_NSC);
                repartition_craches = prepareArrayCraches(repart_craches);
                repartition_ANRs = prepareArrayCraches(repart_ANRs);

                //OS & Version
                var name_os_ios = JSON.parse(JSON.stringify(<?= json_encode($name_os_ios); ?>));
                var name_os_android = JSON.parse(JSON.stringify(<?= json_encode($name_os_android); ?>));
                var value_os_ios = JSON.parse(JSON.stringify(<?= json_encode($value_os_ios); ?>));
                var value_os_android = JSON.parse(JSON.stringify(<?= json_encode($value_os_android); ?>));
                var name_version_ios = JSON.parse(JSON.stringify(<?= json_encode($name_version_ios); ?>));
                var name_version_android = JSON.parse(JSON.stringify(<?= json_encode($name_version_android); ?>));
                var value_version_ios = JSON.parse(JSON.stringify(<?= json_encode($value_version_ios); ?>));
                var value_version_android = JSON.parse(JSON.stringify(<?= json_encode($value_version_android); ?>));

                var version_ios = <?= json_encode(array_values($VERSION_ios)[0]); ?>;
                var version_android = <?= json_encode(array_values($VERSION_android)[0]); ?>;

                // Date title graphe
                var date_vod = <?= json_encode($date_vod); ?>;
                var date_rpvr = <?= json_encode($date_rpvr); ?>;
                var date_os = <?= json_encode($date_os); ?>;
                var date_version = <?= json_encode($date_version); ?>;

                // function o prepare array for pie chart VOD & RPVR
                function prepareArrayVR(array_to_work) {
                    var data = [];
                    var data2 = [];
                    for (var name in array_to_work) {
                        data = [];
                        data.push(array_to_work[name].Page);
                        data.push(parseInt(array_to_work[name].Unites));
                        data2.push(data);
                    }

                    return data2;
                }
                // function o prepare array for pie chart OS & Version
                function prepareArrayNVsC(array_to_work) {
                    var data = [];
                    var data2 = [];
                    for (var name in array_to_work) {
                        data = [];
                        data.push(array_to_work[name].stars);
                        data.push(parseInt(array_to_work[name].repartition));
                        data2.push(data);
                    }

                    return data2;
                }
                // function o prepare array for pie chart craches & ANRs
                function prepareArrayCraches(array_to_work) {
                    var data = [];
                    var data2 = [];
                    for (var name in array_to_work) {
                        data = [];
                        data.push(array_to_work[name].version);
                        data.push(parseInt(array_to_work[name].repartition));
                        data2.push(data);
                    }

                    return data2;
                }
            </script>
            <script src="js/modules/highcharts.js"></script>
            <script src="js/drilldown.js"></script>
            <script type="text/javascript" src="js/btv-charts.js"></script>
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

            <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar" style="width:50px">
                <ul class="nav menu">
                    <li title="Baromètre"><a href="index.php" style="outline: 0;"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></a></li>
                    <li title="Vue Détaillée"><a href="charts.php" style="outline: 0;"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></a></li>
                    <li title="Alerting"><a href="read_alertes.php" style="outline: 0;"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>
                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                        <li title="Gestion des utilisateurs"><a href="read_user.php" style="outline: 0;"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                        <li title="Gestion des mots clés"><a href="read_keyword.php" style="outline: 0;"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif; ?>

                    <li role="presentation" class="divider"></li>
                    <li class="active" title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>
                </ul>
            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">

                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li class="active"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></li>
                    </ol>
                </div><!--/.row-->
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div id="container-btv-googleplay" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container-btv-appstore" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-md-6">
                        <div id="container-uOS" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container-uVersion" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-md-6">
                        <div id="container-craches" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container-Note-Comment" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-md-6">
                        <div id="container-RPVR" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container-VOD" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
                </br>

                <div class="row">
                    <div class="col-md-6">
                        <table data-toggle="table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="th-inner "><div><img src="images/androidtab.png" style="width: 23px;"></div> <div><?php echo $date_top_android; ?></div></div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="text-align: right; ">
                                        <div class="th-inner ">Top 10 des pages visitées</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Visiteurs Uniques</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Clics</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Pourcentage</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($top10_Android as $data):
                                    ?>
                                    <tr data-index="0">
                                        <td style=""><?php echo $i++ ?></td>
                                        <td style="text-align: right; "><?php echo $data->Chaine; ?></td>
                                        <td style=""><?php echo $data->Unites; ?></td>
                                        <td style=""><?php echo $data->Clics; ?></td>
                                        <td style=""><?php echo $data->Percentage; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table data-toggle="table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="th-inner "><div><img src="images/appstoretab.png" style="width: 23px;"></div> <div style="font-size: 10px;"><?php echo $date_top_ios; ?></div></div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="text-align: right; ">
                                        <div class="th-inner ">Top 10 des pages visitées</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Visiteurs Uniques</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Clics</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="">
                                        <div class="th-inner ">Pourcentage</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($top10_iOS as $data):
                                    ?>
                                    <tr data-index="0">
                                        <td style=""><?php echo $i++ ?></td>
                                        <td style="text-align: right; "><?php echo $data->Chaine; ?></td>
                                        <td style=""><?php echo $data->Unites; ?></td>
                                        <td style=""><?php echo $data->Clics; ?></td>
                                        <td style=""><?php echo $data->Percentage; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/.row-->
                <br>

                <script src="js/jquery-1.11.1.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="js/modules/exporting.js"></script>
                <script src="js/modules/offline-exporting.js"></script>
                <script src="js/bootstrap-table.js"></script>
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

