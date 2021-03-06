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
            <title>Stormonitoring - Ajouter un mot clé</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/datepicker3.css" rel="stylesheet">
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
                    <li title="Alerting"><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>
                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                    <li title="Gestion des utilisateurs"><a href="read_user.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                    <li class="active" title="Gestion des mots clés"><a href="read_keyword.php"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif;?>

                                        <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>

                </ul>

            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li><a href="read_user.php"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                        <li class="active">ajouter un mot clé</li> 
                    </ol>
                </div><!--/.row-->

                <div class='row'>
                    <div class='col-lg-12'>
                        <?php
                        $page_title = "Ajouter un mot clé";
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
                                include_once 'objects/keyw.php';
                                include_once 'objects/Functions.php';

                                // get database connection
                                $database = new Database();
                                $db = $database->getConnection();

                                // instantiate alerte object
                                $keyw = new keyw($db);
                                $date_validator = new functions();

                                // set page headers
                                //include_once "layout_header.php";
                                // read alertes button
                                echo "<div class='margin-bottom-1em overflow-hidden'>";
                                echo "<a href='read_keyword.php' class='btn btn-primary pull-right'>";
                                echo "<span class='glyphicon glyphicon-list'></span> Liste des mots clés";
                                echo "</a>";
                                echo "</div>";

                                // if the form was submitted
                                if ($_POST) {

                                    try {
                                        // data validation
                                        if (empty($_POST['label'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ mot clé est vide.</div>";
                                       // } else if (empty($_POST['StartDate'])) {
                                         //   echo "<div class='alert alert-danger'>Oups!!! le champ Date Début est vide.</div>";
                                       // } else if ($date_validator->checkDateTime($_POST['StartDate']) == false) {
                                       //     echo "<div class='alert alert-danger'>Oups!!! La date de début n'est pas valide. (format attendu aaaa-mm-jj)</div>";
                                        //} else if ($date_validator->checkDateTime($_POST['EndDate']) == false) {
                                         //   echo "<div class='alert alert-danger'>Oups!!! La date de fin n'est pas valide. (format attendu aaaa-mm-jj)</div>";
                                        //} else if (empty($_POST['EndDate'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Date Fin est vide.</div>";
                                        //} //else if (empty($_POST['appName'])) {
                                        //echo "<div class='alert alert-danger'>Oups!!! le champ Application est vide.</div>";
                                        }
                                        else if (empty($_POST['keywords'])) {
                                            echo "<div class='alert alert-danger'>Oups!!! le champ keywords est vide.</div>";
                                        } 
										//else if (empty($_POST['first_name'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Prénom est vide.</div>";
                                        //} else if (empty($_POST['password'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Mot de passe est vide.</div>";
                                      // } 
									//echo "<th class='text-align-center'><input type='checkbox' id='checker' /></th>";
										//else if (empty($_POST['NbOccurs'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Occurence souhaité est vide.</div>";
                                        //} else if (empty($_POST['Status'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Statut est vide.</div>";
                                        //} else if (empty($_POST['appID'])) {
                                          //  echo "<div class='alert alert-danger'>Oups!!! le champ Date Fin est vide.</div>";
                                        //} else {
                                           // $idAndroid = array("0001APPEL", "0003BBDUO", "0004BTVSM", "0007BVNUE", "0008BBECM", "0009BBMVB", "0010BBVVM", "0011BANDY");


                                            // set alert property values
                                            $keyw->id = filter_input(INPUT_POST, 'id');
                                            $keyw->label = filter_input(INPUT_POST, 'label');
                                            $keyw->keywords = filter_input(INPUT_POST, 'keywords');
                                            //$useradmin->first_name = filter_input(INPUT_POST, 'first_name');
                                          //$useradmin->password = filter_input(INPUT_POST, 'password');
											//$useradmin->indicateur_first_login = filter_input(INPUT_POST, 'indicateur_first_login');
                                           // $alert->mailing_list = filter_input(INPUT_POST, 'MailingList');
                                            //$alert->nombre_occurence = filter_input(INPUT_POST, 'NbOccurs');
                                            //$alert->status = filter_input(INPUT_POST, 'Status');
                                            //if (!empty(filter_input(INPUT_POST, 'Valuenote'))) {
                                              //  $alert->value = filter_input(INPUT_POST, 'Valuenote');
                                            //} else if (!empty(filter_input(INPUT_POST, 'Valuelabels'))) {
                                              //  $alert->value = filter_input(INPUT_POST, 'Valuelabels');
                                           // }
                                           // if (in_array(filter_input(INPUT_POST, 'appID'), $idAndroid)) {
                                            //    $alert->store = "Google Play";
                                            //} else {
                                              //  $alert->store = "App Store";
                                            // }
                                            // get appname
                                           // $name_app = $alert->getappNameByIb();
                                            // convert appName array to string
                                            //$alert->app_name = implode("','", $name_app);


                                            // create the alert
                                            if ($keyw->create()) {
                                                echo "<div class=\"alert alert-info alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Mot clé ajouté.";
                                                echo "</div>";

                                                // empty post array
                                                $_POST = array();
                                            }

                                            // if unable to create the alert, tell the user
                                            else {
                                                echo "<div class=\"alert alert-danger alert-dismissable\">";
                                                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                                                echo "Impossible d'ajouter ce mot clé).";
                                                echo "</div>";
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
                                <form action='create_keyword.php' method='post' id="keywordFrm">
                                    <table class='table table-hover table-responsive table-bordered'>
                                        <tr>
                                            <td>Libellé</td>
                                            <td><input type='text' name='label' class='form-control' value="<?php echo isset($_POST['label']) ? htmlspecialchars($_POST['label'], ENT_QUOTES) : ""; ?>"></td>
                                        </tr>
										
										<tr>
                                            <td>Mots clés</td>
                                            <td><input type='text' name='keywords' class='form-control' value="<?php echo isset($_POST['keywords']) ? htmlspecialchars($_POST['keywords'], ENT_QUOTES) : ""; ?>"></td>
                                        </tr>
										
										
										
									
										
                                
                                      <!--  <tr>
                                            
                                            <td><P align="right"><b>
                                                
												Changer le Mot de passe à la première connexion ?</b></P>
                                                   
                                             </td>
											 <td> <input  type='checkbox' name='item[]' class='checkboxes' value='{$indicateur_first_login}'>    </td>
                                        </tr> -->
                                      
                                     
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
                                include_once "layout_footer_keyword.php";
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
