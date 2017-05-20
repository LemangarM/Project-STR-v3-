<?php
session_start();

if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {
    ?>
    <?php

    function __autoload($class_name)
    {
        include $class_name . '.php';
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - Alerting</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/bootstrap-table.css" rel="stylesheet">
            <link href="css/bootstrap-datepicker.css" rel="stylesheet">
            <link href="css/monitoring-app.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">

            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>
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

            <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar" style="width: 50px">
                <ul class="nav menu">
                    <li title="Baromètre"><a href="index.php"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></a></li>
                    <li title="Vue Détaillée"><a href="charts.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></a></li>
                    <li class="active" title="Alerting"><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>
                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                    <li title="Gestion des utilisateurs"><a href="read_user.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                    <li title="Gestion des mots clés"><a href="read_keyword.php"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif;?>


                                        <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>

                </ul>

            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"></use></svg></a></li>
                        <li class="active">creat alerts</li> 
                    </ol>
                </div><!--/.row-->

                <div class='row'>
                    <div class='col-lg-12'>
                        <?php
                        $page_title = "Créer Alerte";
                        echo "<h2 class='page-header'>{$page_title}</h1>";
                        ?>
                    </div>
                </div><!--/.row-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php
                                // get database connection
                                include_once 'config/core.php';
                                include_once 'config/database.php';
                                include_once 'objects/Alerts.php';
                                include_once 'objects/Functions.php';

                                // get database connection
                                $database = new Database();
                                $db = $database->getConnection();

                                // instantiate alerte object
                                $alert = new Alertes($db);
                                $date_validator = new functions();

                                // set page headers
                                //include_once "layout_header.php";
                                // read alertes button
                                echo "<div class='margin-bottom-1em overflow-hidden'>";
                                echo "<a href='read_alertes.php' class='btn btn-primary pull-right'>";
                                echo "<span class='glyphicon glyphicon-list'></span> Liste des alertes";
                                echo "</a>";
                                echo "</div>";

                                // if the form was submitted
                                if ($_POST) {

                                    try {
                                        // data validation
                                        if (empty($_POST['Name'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Alerte est vide.</div>";
                                        } else if (empty($_POST['StartDate'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Date Début est vide.</div>";
                                        } else if ($date_validator->checkDateTime($_POST['StartDate']) == false) {
                                            echo "<div class='alert alert-danger'>Oups!!! La date de début n'est pas valide. (format attendu aaaa-mm-jj)</div>";
                                        } else if ($date_validator->checkDateTime($_POST['EndDate']) == false) {
                                            echo "<div class='alert alert-danger'>Oups!!! La date de fin n'est pas valide. (format attendu aaaa-mm-jj)</div>";
                                        } else if (empty($_POST['EndDate'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Date Fin est vide.</div>";
                                        } //else if (empty($_POST['appName'])) {
                                        //echo "<div class='alert alert-danger'>Oups!!! le champ Application est vide.</div>";
                                        //}
                                        else if (empty($_POST['Criteria'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Critères est vide.</div>";
                                        } else if (empty($_POST['Criteria'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Critères est vide.</div>";
                                        } else if (empty($_POST['MailingList'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Diffusion est vide.</div>";
                                        } else if (empty($_POST['NbOccurs'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Occurence souhaité est vide.</div>";
                                        } else if (empty($_POST['Status'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Statut est vide.</div>";
                                        } else if (empty($_POST['appID'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ Date Fin est vide.</div>";
                                        } else {
                                            $idAndroid = array("0001APPEL", "0003BBDUO", "0004BTVSM", "0007BVNUE", "0008BBECM", "0009BBMVB", "0010BBVVM", "0011BANDY");


                                            // set alert property values
                                            $alert->app_id = filter_input(INPUT_POST, 'appID');
                                            $alert->name = filter_input(INPUT_POST, 'Name');
                                            $alert->start_date = filter_input(INPUT_POST, 'StartDate');
                                            $alert->end_date = filter_input(INPUT_POST, 'EndDate');
                                            $alert->criteria = filter_input(INPUT_POST, 'Criteria');
                                            $alert->mailing_list = filter_input(INPUT_POST, 'MailingList');
                                            $alert->nombre_occurence = filter_input(INPUT_POST, 'NbOccurs');
                                            $alert->status = filter_input(INPUT_POST, 'Status');
                                            if (!empty(filter_input(INPUT_POST, 'Valuenote'))) {
                                                $alert->value = filter_input(INPUT_POST, 'Valuenote');
                                            } else if (!empty(filter_input(INPUT_POST, 'Valuekeywords'))) {
                                                $alert->value = filter_input(INPUT_POST, 'Valuekeywords');
                                            }
                                            if (in_array(filter_input(INPUT_POST, 'appID'), $idAndroid)) {
                                                $alert->store = "Google Play";
                                            } else {
                                                $alert->store = "App Store";
                                            }
                                            // get appname
                                            $name_app = $alert->getappNameByIb();
                                            // convert appName array to string
                                            $alert->app_name = implode("','", $name_app);


                                            // create the alert
                                            if ($alert->create()) {
                                                echo "<div class=\"alert alert-info alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Alerte Créer.";
                                                echo "</div>";

                                                // empty post array
                                                $_POST = array();
                                            }

                                            // if unable to create the alert, tell the user
                                            else {
                                                echo "<div class=\"alert alert-danger alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Unable to create alert.";
                                                echo "</div>";
                                            }
                                        }
                                    }

                                    // show error if any
                                    catch (PDOException $exception) {
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                }
                                ?>

                                <!-- HTML form for creating a alert -->
                                </br>
                                </br>
                                <form action='create_alert.php' method='post' id="emailFrm">
                                    <table class='table table-hover table-responsive table-bordered'>
                                        <tr>
                                            <td>Alerte</td>
                                            <td><input type='text' name='Name' class='form-control' value="<?php echo isset($_POST['Name']) ? htmlspecialchars($_POST['Name'], ENT_QUOTES) : ""; ?>"></td>
                                        </tr>

                                        <tr>
                                            <td>Date Début</td>
                                            <td>
                                                <!-- step="0.01" was used so that it can accept number with two decimal places -->
                                                <input type='text' name='StartDate' class='form-control' id='date-from' value="<?php echo isset($_POST['StartDate']) ? htmlspecialchars($_POST['StartDate'], ENT_QUOTES) : ""; ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Date Fin</td>
                                            <td>
                                                <input type='text' name='EndDate' class='form-control' id='date-to' value="<?php echo isset($_POST['EndDate']) ? htmlspecialchars($_POST['EndDate'], ENT_QUOTES) : ""; ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Application</td>
                                            <td>
                                                <select class='form-control' name='appID'>
                                                    <optgroup label="Google Play">
                                                        <option value='0001APPEL'>Appels & YOU (Google Play)</option>
                                                        <option value='0003BBDUO'>B.duo (Google Play)</option>
                                                        <option value='0004BTVSM'>B.tv (Google Play)</option>
                                                        <option value='0007BVNUE'>bienvenue (Google Play)</option>
                                                        <option value='0008BBECM'>Espace Client Mobile (Google Play)</option>
                                                        <option value='0009BBMVB'>Messagerie Vocale Bbox (Google Play)</option>
                                                        <option value='0010BBVVM'>Messagerie vocale visuelle (Google Play)</option>
                                                        <option value='0011BANDY'>World & YOU (Google Play)</option>
                                                        <option value='0012TBBXM'>Télécommande Bbox Miami (Google Play)</option>
                                                    </optgroup>
                                                    <optgroup label="App Store">
                                                        <option value='367615029'>Messagerie Vocale Bbox (App Store)</option>
                                                        <option value='422590767'>Espace Client Mobile Bouygues Telecom (App Store)</option>
                                                        <option value='657368068'>World & YOU (App Store)</option>
                                                        <option value='739824309'>B.tv mobile (App Store)</option>
                                                        <option value='908345121'>B.duo (App Store)</option>
                                                        <option value='1111496092'>Télécommande Bbox Miami (App Store)</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Critères</td>
                                            <td>
                                                <select class='form-control' name='Criteria'>
                                                    <option value='Note'>Note</option>
                                                    <option value='Keywords'>Keywords</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Valeur</td>
                                            <td>
                                                <input id='note' type='number' name='Valuenote' class='form-control' value="<?php echo isset($_POST['Valuenote']) ? htmlspecialchars($_POST['Valuenote'], ENT_QUOTES) : ""; ?>" />
                                                <input id='keywords' type='text' name='Valuekeywords' class='form-control' value="<?php echo isset($_POST['Valuekeywords']) ? htmlspecialchars($_POST['Valuekeywords'], ENT_QUOTES) : ""; ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Occurence</td>
                                            <td>
                                                <input type='number' name='NbOccurs' class='form-control' value="<?php echo isset($_POST['NbOccurs']) ? htmlspecialchars($_POST['NbOccurs'], ENT_QUOTES) : ""; ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Liste de diffusion</td>
                                            <td>
                                                <input type='text' id="emails" name='MailingList' class='form-control' value="<?php echo isset($_POST['MailingList']) ? htmlspecialchars($_POST['MailingList'], ENT_QUOTES) : ""; ?>" />
                                            </td>
                                        </tr>

                                        <input readonly="readonly" type='hidden' name='Status' class='form-control' required value="Active" />

                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit" class="btn btn-primary">
                                                    <span class="glyphicon glyphicon-plus"></span> Créer
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                                <?php
                                include_once "layout_footer.php";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } elseif ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 1) { // Le mot de passe n'est pas bon.
                header('Location: notfound.php');
            } else {
                header('Location: login');
            }// Fin du else.
