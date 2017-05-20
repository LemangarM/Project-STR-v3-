</div>
<!-- /container -->

<!-- jQuery library -->
<script src="libs/js/jquery.js"></script>


<script src="libs/js/bootstrap/docs-assets/js/holder.js"></script>

<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- jquery ui -->
<script src="libs/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.fr.js" charset="UTF-8"></script>
<script src="js/bootstrap-table.js"></script>
<script src="js/modules/canvas-tools.js"></script>
<script src="js/alert.js"></script>


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
    });
    $(window).on('resize', function () {
        if ($(window).width() <= 767)
            $('#sidebar-collapse').collapse('hide')
    });
    $(document).ready(function () {
        // Date parameters
        $('#date-from').datepicker({
            format: 'yyyy-mm-dd',
            //endDate: new Date(),
            language: 'fr',
            todayBtn: true,
            autoclose: true
                    //startDate: '-1d'
        });

        $('#date-to').datepicker({
            format: 'yyyy-mm-dd',
            language: 'fr',
            todayBtn: true,
            autoclose: true
        });


//method to validate multi email
        jQuery.validator.addMethod("multikeywords", function (value, element) {
            if (this.optional(element)) {
                return true;
            }
            var keywords = value.split(';'),
                    valid = true;
            return valid;
        }, "Les mots clés doivent être séparés par ';' sans espace.");

//Jquery validator
        $("#keywordFrm").validate({
            errorElement: 'div',
            errorClass: 'jqInvalid',
            rules: {
                label: {
                    required: true,
                },
                keywords: {
                    required: true,
                    multikeywords: true
                }
            },
            
        });

        //check/uncheck script
        $(document).on('click', '#checker', function () {
            $('.checkboxes').prop('checked', $(this).is(':checked'));
        });


        // delete selected records
        $(document).on('click', '#delete-selected-keyword', function () {

            var at_least_one_was_checked = $('.checkboxes:checked').length > 0;

            if (at_least_one_was_checked) {

                var answer = confirm('Êtes-vous sûr de vouloir supprimer définitivement les objets séléctionnés ?');

                if (answer) {

                    // get converts it to an array
                    var del_checkboxes = $('.checkboxes:checked').map(function (i, n) {
                        return $(n).val();
                    }).get();

                    if (del_checkboxes.length == 0) {
                        del_checkboxes = "none";
                    }

                    $.post("delete_selected_keyword.php", {'del_checkboxes[]': del_checkboxes},
                            function (response) {

                                if (response == "") {
                                    // refresh
                                    location.reload();
                                } else {
                                    // tell the user there's a problem
                                    alert(response);
                                }

                            });

                }
            } else {
                alert('Please select at least one record to delete.');
            }
        });

        // delete record
        $(document).on('click', '.delete-object', function () {

            // php file used for deletion
            var delete_file = $(this).attr('delete-file');

            var id = $(this).attr('delete-id');
            var q = confirm("Are you sure?");

            if (q == true) {

                $.post(delete_file, {
                    object_id: id
                }, function (data) {
                    location.reload();
                }).fail(function () {
                    alert('Unable to delete.');
                });

            }
            return false;
        });
    });
</script>

</body>
</html>
