$(document).ready(function(){
    $("#keywords").hide();
$('select[name="Criteria"]').change(function(){
    if($('select[name="Criteria"] option:selected').val()=='Note'){
        $("#keywords").val("");
        $("#note").show();
        $("#keywords").hide();

    }
    else if($('select[name="Criteria"] option:selected').val()=='Keywords'){
        $("#note").val("");
        $("#keywords").show();
        $("#note").hide();
    }
});
});

