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
                    <a class="navbar-brand"><span>STORM</span>onitoring</a>
                </div>
            </div><!-- /.container-fluid -->
        </nav>

        <div class="panel panel-default" style="height: 550px;">
            <div class="panel-body">
                <h3 class='page-header' style="margin-top: 10px;">
                    <span style='text-transform: uppercase; color: rgb(23, 144, 199); font: 0.7em/1.6em "Open Sans",Verdana,Geneva,sans-serif,sans-serif;'>votre mot de passe a été réinitilisé</span>
                </h3>
                <br>
                <p style="width: 50%; margin: auto; font-size: 16px;"><span>votre mot de passe a été enregistrer. Vous pouvez vous <a href="login.php">connecter</a> maintenant</span></p>
                <br>
            </div>
        </div>

        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
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
        </script>
