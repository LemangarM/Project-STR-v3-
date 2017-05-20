<?php
session_start(); // À placer obligatoirement avant tout code HTML.

if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 0) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - Utilisateurs</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/bootstrap-table.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">
            <link href="css/bootstrap-datepicker.css" rel="stylesheet">
            <link href="css/monitoring-app.css" rel="stylesheet">


            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>

            <!--[if lt IE 9]>
            <script src="js/html5shiv.js"></script>
            <script src="js/respond.min.js"></script>
            <![endif]-->
            <style>
                th, td {
                    text-align: center;
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

            <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar" style="width: 50px">
                <ul class="nav menu">
                    <li title="Baromètre"><a href="index.php"><svg class="glyph stroked table"><use xlink:href="#stroked-table"/></svg></a></li>
                    <li title="Vue Détaillée"><a href="charts.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></a></li>
                    <li title="Alerting"><a href="read_alertes.php"><svg class="glyph stroked sound on"><use xlink:href="#stroked-sound-on"/></svg></a></li>
                    <?php if ($_SESSION['admin'] == 'admin') : ?>
                        <li title="Gestion des utilisateurs"><a href="read_user.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg></a></li>
                        <li class="active" title="Gestion des mots clés"><a href="read_keyword.php"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></a></li>
                    <?php endif; ?>

                    <li role="presentation" class="divider"></li>
                    <li title="B.tv concurrence"><a href="btv.php" style="outline: 0;"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"/></svg></a></li>

                </ul>

            </div><!--/.sidebar-->

            <div class="col-sm-9 col-sm-offset-3 col-lg-11 col-lg-offset-1 main" style="margin-left: 3.7%; width: 96.3%;">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                        <li class="active"><svg class="glyph stroked key "><use xlink:href="#stroked-key"/></svg></li>
                    </ol>
                </div><!--/.row-->

                <div class='row'>
                    <div class='col-lg-12'>
                        <?php echo "<h2 class='page-header'>{$page_title}</h1>"; ?>
                    </div>
                </div><!--/.row-->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="bootstrap-table">
                                    <div class="fixed-table-toolbar">


                                        <!--                                        <form role="search" action='search_user.php'>
                                                                                    <div class="input-group col-md-3 pull-left margin-right-1em">
                                                                                        <input type="text" class="form-control" placeholder="recherche..." name="s" id="srch-term" required <?php echo isset($search_term) ? "value='$search_term'" : ""; ?> />
                                                                                        <div class="input-group-btn">
                                                                                            <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>-->

                                        <!--                                        <form role="search" action='search_alert_by_date_range.php'>
                                                                                    <div class="input-group col-md-3 pull-left">
                                                                                        <input type="text" class="form-control" placeholder="Date début..." name="date_from" id="date-debut" required
                                        <?php //echo isset($date_from) ? "value='$date_from'" : ""; ?> />
                                                                                        <span class="input-group-btn" style="width:0px;"></span>
                                        
                                                                                        <input type="text" class="form-control" placeholder="Date fin..." name="date_to" id="date-fin"
                                                                                               required <?php //echo isset($date_to) ? "value='$date_to'" : "";      ?> />
                                                                                        <div class="input-group-btn">
                                                                                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>-->


                                        <div class="columns btn-group pull-right" style="margin-top: -0.5px;">

                                            <!-- create user button -->
                                            <a href='create_keyword.php' class="btn btn-primary">
                                                <span class="glyphicon glyphicon-plus"></span> Ajouter un mot clé
                                            </a>

                                            <!-- delete alert records -->
                                            <button class="btn btn-primary" id="delete-selected-keyword">
                                                <span class="glyphicon glyphicon-remove-circle"></span> Supprimer les mots clés selectionnés
                                            </button>
                                        </div>
                                    </div>
                                    <div class="fixed-table-container" style="border-width: 0px; padding-bottom: 0px;">


                                        <?php
// display the products if there are any
                                        if ($num > 0) {

                                            // order opposite of the current order
                                            $reverse_order = isset($order) && $order == "asc" ? "desc" : "asc";

                                            // field name
                                            $field = isset($field) ? $field : "";

                                            // field sorting arrow
                                            $field_sort_html = "";

                                            if (isset($field_sort) && $field_sort == true) {
                                                $field_sort_html .= "<span class='badge'>";
                                                $field_sort_html .= $order == "asc" ? "<span class='glyphicon glyphicon-arrow-up'></span>" : "<span class='glyphicon glyphicon-arrow-down'></span>";
                                                $field_sort_html .= "</span>";
                                            }
                                            echo "<table class='table table-hover table-responsive table-bordered'>";
                                            echo "<tr>";

                                            echo "<th class='text-align-center'><input type='checkbox' id='checker' /></th>";
                                            echo "<th style='width:20%;'>";
                                            //echo "<a href='read_user_sorted_by_fields.php?field=login&order={$reverse_order}'>";
                                            echo "Libellé";
                                            echo $field == "label" ? $field_sort_html : "";
                                            echo "</a>";
                                            echo "</th>";
                                            echo "<th>";
                                            //echo "<a href='read_user_sorted_by_fields.php?field=profil&order={$reverse_order}'>";
                                            echo "Mots clés";
                                            echo $field == "keywords" ? $field_sort_html : "";
                                            echo "</a>";
                                            echo "</th>";


                                            // echo "<th style='width:15%;'>";
                                            //echo "<a href='read_user_sorted_by_fields.php?field=date_last_login&order={$reverse_order}'>";
                                            //echo "Dernière connexion";
                                            //echo $field == "date_last_login" ? $field_sort_html : "";
                                            // echo "</a>";
                                            // echo "</th>";
                                            echo "<th>";
                                            //echo "<a href='read_user_sorted_by_fields.php?field=date_last_maj&order={$reverse_order}'>";
                                            echo "Dernière mise à jour";
                                            echo $field == "datemaj" ? $field_sort_html : "";
                                            echo "</a>";
                                            echo "</th>";
                                            //   echo "<th>";
                                            //   echo "<a href='read_user_sorted_by_fields.php?field=Value&order={$reverse_order}'>";
                                            // echo "Valeur";
                                            //   echo $field == "Value" ? $field_sort_html : "";
                                            // echo "</a>";
                                            //  echo "</th>";
                                            //  echo "<th>";
                                            //    echo "<a href='read_user_sorted_by_fields.php?field=NbOccurs&order={$reverse_order}'>";
                                            //   echo "Occurence";
                                            //    echo $field == "NbOccurs" ? $field_sort_html : "";
                                            //   echo "</a>";
                                            //   echo "</th>";
                                            //   echo "<th>";
                                            //   echo "<a href='read_user_sorted_by_fields.php?field=Status&order={$reverse_order}'>";
                                            //   echo "Statut";
                                            //   echo $field == "Status" ? $field_sort_html : "";
                                            //   echo "</a>";
                                            //  echo "</th>";
                                            echo "<th>Action</th>";
                                            echo "</tr>";

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                                extract($row);

                                                echo "<tr>";

                                                echo "<td class='text-align-center'><input type='checkbox' name='item[]' class='checkboxes' value='{$id}' /></td>";
                                                echo "<td>{$label}</td>";
                                                //echo "<td>&#36;" . number_format($price, 2) . "</td>";
                                                echo "<td>{$keywords}</td>";

                                                // echo "<td>{$last_name}</td>";
                                                //echo "<td>{$first_name}</td>";
                                                //echo "<td>{$date_last_login}</td>";
                                                echo "<td>{$datemaj}</td>";
                                                //echo "<td>{$Value}</td>";
                                                //   echo "<td>{$NbOccurs}</td>";
                                                // echo "<td>{$Status}</td>";
                                                echo "<td>";

                                                // edit product button
                                                echo "<a href='update_keyword.php?id={$id}' class='btn btn-primary'>";
                                                echo "<span class='glyphicon glyphicon-edit'></span>";
                                                echo "</a> ";

                                                // delete product button
//                                                echo "<a delete-id='{$id}' delete-file='delete_selected_user.php' class='btn btn-default'>";
//                                                echo "<span class='glyphicon glyphicon-remove'></span>";
//                                                echo "</a>";

                                                echo "</td>";

                                                echo "</tr>";
                                            }

                                            echo "</table>";

                                            // needed for paging
                                            $total_rows = 0;

                                            if ($page_url == "read_keyword.php?") {
                                                $total_rows = $user->countAll();
                                                //} else if (isset($app_name) && $page_url == "category.php?appName={$app_name}&") {
                                                //    $total_rows = $user->countAll_ByAppName();
                                            } else if (isset($search_term) && $page_url == "search_user.php?s={$search_term}&") {
                                                $total_rows = $user->countAll_BySearch($search_term);
                                            } else if (isset($field) && isset($order) && $page_url == "read_user_sorted_by_fields.php?field={$field}&order={$order}&") {
                                                $total_rows = $user->countAll();
                                            }

                                            // search by date range
                                            else if (isset($date_from) && isset($date_to) && $page_url == "search_user_by_date_range.php?date_from={$date_from}&date_to={$date_to}&") {
                                                $total_rows = $user->countSearchByDateRange($date_from, $date_to);
                                            }

                                            // paging buttons
                                            include_once 'paging.php';
                                        }

// tell the user there are no products
                                        else {
                                            echo "<div class=\"user user-danger user-dismissable\">";
                                            echo "<button type=\"button\" class=\"close\" data-dismiss=\"user\" aria-hidden=\"true\">&times;</button>";
                                            echo "No user found.";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
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
