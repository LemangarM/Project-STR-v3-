<?php
session_start();
if ($_SESSION['connect'] == 1 && $_SESSION['first_login'] == 1) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Stormonitoring - First login</title>

            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/styles.css" rel="stylesheet">
            <!--Icons-->
            <script src="js/lumino.glyphs.js"></script>
            <style>
                .error {
                    color: #ff0000;
                }
            </style>
        </head>

        <?php
        include_once 'config/core.php';
        include_once 'config/database.php';
        include_once 'objects/Users.php';

        // instantiate database and users object
        $database = new Database();
        $db = $database->getConnection();

        $user = new Users($db);

        if (isset($_POST['new_password1']) && isset($_POST['new_password2'])) {
            $mot_de_passe1 = $_POST['new_password1'];
            $mot_de_passe2 = $_POST['new_password2'];

            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            
            $data = $user->selectUserId($login, $password);
            $id = $data->fetch(PDO::FETCH_NUM);
            $id_user = (int)$id[0];

            $password_sha256 = hash('sha256',$mot_de_passe1);
            
            if ($mot_de_passe1 !== $mot_de_passe2) {
                echo '<div class="col-md-6" style="margin-left: 25%;"">';
                echo '<div class="alert alert-danger">';
                echo '<strong>Formulaire refusé !</strong> Les mots de passes ne sont pas identiques.</div></div>';
            } else if ($mot_de_passe1 == $mot_de_passe2) {
                $user->updatePassword($password_sha256, $id_user);
                session_destroy();
                header('Location: reset_password_complete.php');
            }
        }
        ?>

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
                        <a class="navbar-brand"><span>STORM</span>onitoring</a>
                    </div>
                </div><!-- /.container-fluid -->
            </nav>

            <div class="panel panel-default" style="height: 550px;">
                <div class="panel-body">
                    <h3 class='page-header' style="margin-top: 10px;">
                        <span style='text-transform: uppercase; color: rgb(23, 144, 199); font: 0.7em/1.6em "Open Sans",Verdana,Geneva,sans-serif,sans-serif;'>Réinitialiser votre mot de passe</span>
                    </h3>
                    <br>
                    <p style="width: 50%; margin: auto; font-size: 16px;"><span>Bienvenue <strong><?php echo $_SESSION['login'] ?></strong>.</span>
                        <span>l'accès à l'application nécessite un changement de mot de passe lors de la première connexion.</span>
                        <span>Saisissez deux fois votre nouveau mot de passe afin de vérifier qu'il est correctement saisi.</span></p>
                    <br>
                    <form action="reset_password.php" id="resetpass" method="post" style="width: 50%; margin: 0px auto;">
                        <fieldset>
                            <div class="form-group">
                                <label>Votre nouveau mot de passe *</label>
                                <input class="form-control" placeholder="******" name="new_password1" type="password" id="pass" required>
                            </div>
                            <div class="form-group">
                                <label>Votre nouveau mot de passe (encore) *</label>
                                <input class="form-control" placeholder="******" name="new_password2" type="password"  required>
                            </div>
                            <br>
                            <input name="submit" class="btn btn-primary" type="submit"  value="Changer mon mot de passe" style="width: 100%; font-size: 16px;">
                        </fieldset>
                    </form>
                </div>
            </div>

            <script src="js/jquery-1.11.1.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/jquery.validate.min.js"></script>

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
                $("#resetpass").validate({
                    rules: {
                        new_password1: {
                            required: true,
                            minlength: 6
                        },
                        new_password2: {
                            equalTo: "#pass",
                            minlength: 6
                        }
                    },
                    messages: {
                        new_password1: {
                            required: "Veillez renseigner ce champ",
                            minlength: "Le mot de passe doit comporter minimum 6 caractères"
                        },
                        new_password2: {
                            required: "Veillez renseigner ce champ",
                            minlength: "Le mot de passe doit comporter minimum 6 caractères",
                            equalTo: "les 2 mots de passe ne sont pas identiques"
                        }
                    }
                });
            </script>
            <?php
        } else {
            header('Location: notfound.php');
        }
