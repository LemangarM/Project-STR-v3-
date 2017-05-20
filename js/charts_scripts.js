
//scroll 
function goScrollPos()
{
    var uri = document.location.href;
    /scroll=([\d]+)/.exec(uri);
    console.debug(RegExp.$1);
    $(window).scrollTop(RegExp.$1);
}


/*
 * 
 * @param {type} index
 * @param {type} row
 * @returns {String}
 */
//function detailFormatter(index, row) {
//    var html = [];
//    if (row.reponse === null) {
//        html.push('<p style="color: #000;"><b>Pas de réponse</b></p>');
//    } else {
//        html.push('<p style="color: #000;"><b>' + row.reponse + '</b></p>');
//    }
//    return html.join('');
//}

/*
 * tag réponse faites ou pas
 */
//function tagReply(value, row) {
//    var id = row.id;
//    var reponse = row.reponse;
//    if (reponse === null) {
//        var strHTML = '<img src="images/notreply.png">';
//    } else {
//        strHTML = '<img src="images/reply.png">';
//    }
//    var valReturn = strHTML;
//    return valReturn;
//}

/*
 * Attribut l'url de redirection vers le store pour la collone store du tableau reviews
 */
function urlReview(value, row) {
    var id = row.appID;
    var url = row.url;
    if (id.charAt(0) === '0' && url !== null) {

        var strHTML = "<a href='" + url + "'target='_blank'>" + value + "</a>";

    } else {
        strHTML = "<span>" + value + "</span>";
    }
    var valReturn = strHTML;
    return valReturn;
}

/*
 * title & comment formatter
 */
function titleComment(value, row) {
    var id = row.appID;
    var title = row.title;
    var review = row.review;

    if (title !== null && title !== '') {
        var strHTML = "<div class='review'><b>" + title + "</b><br>" + review + "</div>";
    } else {
        var strHTML = "<div class='review'>" + review + "</div>";
    }

    return strHTML;
}

/*
 * transforme le format français du daterange au fromat SQL
 */
function dateFormatFrToSql(date) {
    var jour = date.slice(0, 2);
    var mois = date.slice(3, 5);
    var annee = date.slice(6, 10);

    var new_date = annee + '-' + mois + '-' + jour;
    return new_date;
}

/*
 * transforme le format SQL des parametres de date au format français
 */
function dateFormatSqlToFr(date) {
    if (date !== null) {
        var annee = date.slice(0, 4);
        var mois = date.slice(5, 7);
        var jour = date.slice(8, 10);

        var new_date = jour + '/' + mois + '/' + annee;
        return new_date;
    } else {
        return null;
    }
}

/*
 * Récupère les paramètres passer en url avec js
 * @param name, url 
 */
function getParameterByName(name, url) {
    if (!url)
        url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(document).ready(function () {

    //checkbox scenario review
    $(document).on('click', '#mycheckbox', function () {
        var x = OptionsId(this);
        var y = OptionsSelected(this);
        $.ajax({
            url: "process_check.php?id_comment=" + x + "&reponse=" + y,
            type: 'GET',
            success: function (s) {
                if (s === 'status') {
                }
                if (s === 'error') {
                    alert('Error Processing your Request!');
                }
            },
            error: function (e) {
                alert('Error Processing your Request!!');
            }
        });
    });

    //submit textarea comment graph
    $('#submitTextGraph').on('click', function () {
        $('#formTextGraph').submit(); // js fiddle won't allow this
    });

    //submit dynamically a form and waiting 10 milliseconds 
    $("#select_app_id").change(function () {
        setTimeout(function () {
            $("#form_app_id").submit();
        }, 10);
    });

    //edit this cell comment of table
    $.fn.editable.defaults.mode = 'popup';
    $('.editable-open').editable();
    $(document).on('click', '.editable-submit', function () {
        var x = $(this).closest('td').children('a').attr('data-pk');
        var y = $('.input-large').val();
        var z = $(this).closest('td').children('a');
        if (y.length !== 0) {
            $(this).closest('td').children('a').text(y);
        }
        $.ajax({
            url: "process.php?id=" + x + "&data=" + y,
            type: 'GET',
            success: function (s) {
                if (s === 'status') {
                    alert($(z).html(y));
                }
                if (s === 'error') {
                    alert('Error Processing your Request!');
                }
            },
            error: function (e) {
                alert('Error Processing your Request!!');
            }
        });
    });

    //delete this cell comment of table
    $(document).on('click', '.editable-cancel', function () {
        var x = $(this).closest('td').children('a').attr('data-pk');
        $.ajax({
            url: "process.php?id=" + x,
            type: 'GET',
            success: function (s) {
                if (s === 'status') {
                    alert('success');
                }
                if (s === 'error') {
                    alert('Error Processing your Request!');
                }
            },
            error: function (e) {
                alert('Error Processing your Request!!');
            }
        });
        setTimeout(function () {
            $('.columns button[name="refresh"]').trigger('click');
        }, 100);
    });

    //if click cancel write "add comment"
    $(document).on('click', '.editable-cancel', function () {
        $(this).closest('td').children('a').text("ajouter un commentaire");
    });

    // Choix de période commentaires 
    moment.lang('fr');

    var pickerLocale = {
        applyLabel: 'OK',
        format: 'DD/MM/YYYY',
        cancelLabel: 'Annuler',
        fromLabel: 'Entre',
        toLabel: 'et',
        customRangeLabel: 'Période personnalisée',
        daysOfWeek: moment().lang()._weekdaysMin,
        monthNames: moment().lang()._months,
        firstDay: 0
    };

    //suggestion daterange
    var pickerRanges = {
        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 derniers jours': [moment().subtract(6, 'days'), moment()],
        '30 derniers jours': [moment().subtract(29, 'days'), moment()]
    };

    // =================================== Date range review =====================================//

    //dates passées en paramètres dans l'url
    var date_s = getParameterByName('start');
    var date_e = getParameterByName('end');

    //format français
    var date_start = dateFormatSqlToFr(date_s);
    var date_end = dateFormatSqlToFr(date_e);

    function cb(start, end) {
        $('#reportrange').html(start.format('DD/MM/YYYY') + ' à ' + end.format('DD/MM/YYYY'));
    }

    //si dates récupérées en paramètre different de null on attribut au "span" ces dates sinon on attribut les 3 dernier mois 
    if (date_s !== null && date_e !== null) {
        $('#reportrange').html(date_start + ' à ' + date_end);
    } else {
        cb(moment().subtract(3, 'months'), moment());
    }

    $('#reportrange').daterangepicker(
            {
                showDropdowns: true,
                ranges: pickerRanges,
                locale: pickerLocale,
                applyClass: 'btn-success reportrange',
                opens: "left"
            }
    , cb);

    // on passe les parametre du daterange à l'url
    $(document).on('click', '.reportrange', function () {
        var date = $('#reportrange').text();
        var enddate = date.slice(13, 23);
        var startdate = date.slice(0, 10);
        var new_startdate = dateFormatFrToSql(startdate);
        var new_enddate = dateFormatFrToSql(enddate);
        var e = document.getElementById("select_app_id");
        var id_globale = e.options[e.selectedIndex].value;
        window.location.href = "/charts.php?id_app=" + id_globale + "&start=" + new_startdate + "&end=" + new_enddate + "&scroll=900";
    });

    // =================================== Date range note =====================================//

    //dates passées en paramètres dans l'url
    var date_notes_s = getParameterByName('start_notes');
    var date_notes_e = getParameterByName('end_notes');

    //format français
    var date_notes_start = dateFormatSqlToFr(date_notes_s);
    var date_notes_end = dateFormatSqlToFr(date_notes_e);

    function cbn(start, end) {
        $('#rangenotes span').html(start.format('DD/MM/YYYY') + ' à ' + end.format('DD/MM/YYYY'));
    }

    //si dates récupérées en paramètre different de null on attribut au "span" ces dates sinon on attribut les 3 dernier mois 
    if (date_notes_s !== null && date_notes_e !== null) {
        $('#rangenotes span').html(date_notes_start + ' à ' + date_notes_end);
    } else {
        cbn(moment(date_notes_start_default), moment(date_notes_end_default));
    }

    $('#rangenotes').daterangepicker(
            {
                showDropdowns: true,
                ranges: pickerRanges,
                locale: pickerLocale,
                applyClass: 'btn-success rangenotes'
            }
    , cbn);

    // on passe les parametre du daterange à l'url
    $(document).on('click', '.rangenotes', function () {
        var date_notes = $('#rangenotes span').text();
        var enddate = date_notes.slice(13, 23);
        var startdate = date_notes.slice(0, 10);
        var new_startdate = dateFormatFrToSql(startdate);
        var new_enddate = dateFormatFrToSql(enddate);
        var e = document.getElementById("select_app_id");
        var id_globale = e.options[e.selectedIndex].value;
        window.location.href = "/charts.php?id_app=" + id_globale + "&start_notes=" + new_startdate + "&end_notes=" + new_enddate + "&scroll=250";
    });

    // =================================== Date range sales =====================================//

    //dates passées en paramètres dans l'url
    var date_sales_s = getParameterByName('start_sales');
    var date_sales_e = getParameterByName('end_sales');

    //format français
    var date_sales_start = dateFormatSqlToFr(date_sales_s);
    var date_sales_end = dateFormatSqlToFr(date_sales_e);

    function cbs(start, end) {
        $('#rangesales span').html(start.format('DD/MM/YYYY') + ' à ' + end.format('DD/MM/YYYY'));
    }

    //si dates récupérées en paramètre different de null on attribut au "span" ces dates sinon on attribut les 3 dernier mois 
    if (date_sales_s !== null && date_sales_e !== null) {
        $('#rangesales span').html(date_sales_start + ' à ' + date_sales_end);
    } else {
        cbs(moment(date_sales_start_default), moment(date_sales_end_default));
    }

    $('#rangesales').daterangepicker(
            {
                showDropdowns: true,
                ranges: pickerRanges,
                locale: pickerLocale,
                applyClass: 'btn-success rangesales'
            }
    , cbs);

    // on passe les parametre du daterange à l'url
    $(document).on('click', '.rangesales', function () {
        var date_sales = $('#rangesales span').text();
        var enddate = date_sales.slice(13, 23);
        var startdate = date_sales.slice(0, 10);
        var new_startdate = dateFormatFrToSql(startdate);
        var new_enddate = dateFormatFrToSql(enddate);
        var e = document.getElementById("select_app_id");
        var id_globale = e.options[e.selectedIndex].value;
        window.location.href = "/charts.php?id_app=" + id_globale + "&start_sales=" + new_startdate + "&end_sales=" + new_enddate + "&scroll=570";
    });

    goScrollPos();
}
);