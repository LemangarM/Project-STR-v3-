<?php
session_start(); // À placer obligatoirement avant tout code HTML.

if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {
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
                    <?php endif; ?>
                    <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>

                </ul>

            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"></use></svg></a></li>
                        <li class="active">update alerts</li>
                    </ol>
                </div><!--/.row-->

                <div class='row'>
                    <div class='col-lg-12'>
                        <?php
                        $page_title = "Modifier Alerte";
                        echo "<h2 class='page-header'>{$page_title}</h1>";
                        ?>
                    </div>
                </div><!--/.row-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php
// include database and object files
                                include_once 'config/core.php';
                                include_once 'config/database.php';
                                include_once 'objects/Alerts.php';

// get database connection
                                $database = new Database();
                                $db = $database->getConnection();

// prepare alert object
                                $alert = new Alertes($db);

// read alertes button
                                echo "<div class='margin-bottom-1em overflow-hidden'>";
                                echo "<a href='read_alertes.php' class='btn btn-primary pull-right'>";
                                echo "<span class='glyphicon glyphicon-list'></span> Liste des alertes";
                                echo "</a>";
                                echo "</div>";

// get ID of the alert to be edited
                                $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// set ID property of alert to be edited
                                $alert->id = $id;

// if the form was submitted
                                if ($_POST) {

                                    try {
                                        // server-side data validation
                                        if (empty($_POST['Name'])) {
                                            echo "<div class='alert alert-danger'>l'intitulé de l'alerte ne peut pas étre vide.</div>";
                                        } else if (empty($_POST['StartDate'])) {
                                            echo "<div class='alert alert-danger'>La date de débute ne peut étre vide.</div>";
                                        } else if (empty($_POST['EndDate'])) {
                                            echo "<div class='alert alert-danger'>La date de fin ne peut étre vide.</div>";
                                        } else if (empty($_POST['appID'])) {
                                            echo "<div class='alert alert-danger'>Le Nom de l'application ne peut étre vide.</div>";
                                        } else {
                                            $idAndroid = array("0001APPEL", "0003BBDUO", "0004BTVSM", "0007BVNUE", "0008BBECM", "0009BBMVB", "0010BBVVM", "0011BANDY");

                                            // set alert property values
                                            $alert->name = $_POST['Name'];
                                            $alert->start_date = $_POST['StartDate'];
                                            $alert->end_date = $_POST['EndDate'];
                                            $alert->value = $_POST['Value'];
                                            $alert->mailing_list = $_POST['MailingList'];
                                            $alert->nombre_occurence = $_POST['NbOccurs'];
                                            $alert->status = $_POST['Status'];
                                            $alert->app_id = $_POST['appID'];
                                            if (in_array(filter_input(INPUT_POST, 'appID'), $idAndroid)) {
                                                $alert->store = "Google Play";
                                            } else {
                                                $alert->store = "App Store";
                                            }

                                            // get appname
                                            $name_app = $alert->getappNameByIb();
                                            // convert appName array to string
                                            $alert->app_name = implode("','", $name_app);

                                            // update the alert
                                            if ($alert->update()) {
                                                echo "<div class=\"alert alert-info alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Mise à jour effectuée.";
                                                echo "</div>";
                                            }

                                            // if unable to update the alert, tell the user
                                            else {
                                                echo "<div class=\"alert alert-danger alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Unable to update alert.";
                                                echo "</div>";
                                            }
                                        }

                                        // values to fill up our form
                                        $Name = $_POST['Name'];
                                        $StartDate = $_POST['StartDate'];
                                        $EndDate = $_POST['EndDate'];
                                        //  $Store = $_POST['Store'];
                                        //  $appName = $_POST['appName'];
                                        $Value = $_POST['Value'];
                                        $MailingList = $_POST['MailingList'];
                                        $NbOccurs = $_POST['NbOccurs'];
                                        $Status = $_POST['Status'];
                                        $appID = $_POST['appID'];
                                    }

                                    // show errors, if any
                                    catch (PDOException $exception) {
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                } else {
                                    // read the details of alert to be edited
                                    $alert->readOne();
                                }
                                ?>

                                <!-- HTML form for updating a alert -->
                                </br>
                                </br>
                                <form action='update_alert.php?id=<?php echo $id; ?>' method='post' id="emailFrm">

                                    <table class='table table-hover table-responsive table-bordered'>

                                        <tr>
                                            <td>Alerte</td>
                                            <td><input type='text' name='Name' value="<?php echo $alert->name; ?>" class='form-control' required></td>
                                        </tr>

                                        <tr>
                                            <td>Date début</td>
                                            <td>
                                                <input type='text' name='StartDate' id='date-from' value="<?php echo $alert->start_date; ?>" class='form-control' required />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date fin</td>
                                            <td>
                                                <input type='text'  name='EndDate' id='date-to' value="<?php echo $alert->end_date; ?>" class='form-control' required />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Application</td>
                                            <td>
                                                <?php
                                                $app_name_list = $alert->getAppNameList()->fetchAll(PDO::FETCH_OBJ);
                                                $selecteur = $alert->getappNameByIdAlert()->fetchAll(PDO::FETCH_OBJ);
                                                ?>

                                                <select name="appID" class="form-control">
                                                    <?php
                                                    foreach ($app_name_list as $data) {
                                                        if (substr($data->appID, 0, 2) == '00') {
                                                            echo '<option value="' . $data->appID . '"';
                                                            foreach ($selecteur as $name) {
                                                                if ($name->appID === $data->appID) {
                                                                    echo 'selected="selected"';
                                                                }
                                                            }
                                                            echo '>' . $data->appName. ' (Google Play)</option>';
                                                        } else {
                                                            echo '<option value="' . $data->appID . '"';
                                                            foreach ($selecteur as $name) {
                                                                if ($name->appID === $data->appID) {
                                                                    echo 'selected="selected"';
                                                                }
                                                            }
                                                            echo '>' . $data->appName . ' (App Store)</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Critères</td>
                                            <td>
                                                <?php
                                                $stmt = $alert->readCriteria()->fetch(PDO::FETCH_ASSOC);
                                                ?>
                                                <input type='text' name='Criteria' value="<?php echo $stmt['Criteria']; ?>" class='form-control' required disabled readonly/>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Valeur</td>
                                            <td>
                                                <input type='text' name='Value' value="<?php echo $alert->value; ?>" class='form-control' required />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Occurence</td>
                                            <td>

                                                <input type='number' name='NbOccurs' value="<?php echo $alert->nombre_occurence; ?>" class='form-control' required />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Liste de diffusion</td>
                                            <td>
                                                <!-- step="0.01" was used so that it can accept number with two decimal places -->
                                                <input type='text' id="emails" name='MailingList' value="<?php echo $alert->mailing_list; ?>" class='form-control' required />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Statut</td>
                                            <td>
                                                <?php
                                                $status_list = $alert->getStatusList()->fetchAll(PDO::FETCH_OBJ);
                                                $selecteur_s = $alert->getStatusByNameAlert()->fetchAll(PDO::FETCH_OBJ);
                                                ?>

                                                <select name="Status" class="form-control">
                                                    <?php
                                                    foreach ($status_list as $data) {
                                                        echo '<option value="' . $data->Status . '"';
                                                        foreach ($selecteur_s as $name) {
                                                            if ($name->Status === $data->Status) {
                                                                echo 'selected="selected"';
                                                            }
                                                        }
                                                        echo '>' . $data->Status . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit" class="btn btn-primary">
                                                    <span class='glyphicon glyphicon-edit'></span> Valider
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
