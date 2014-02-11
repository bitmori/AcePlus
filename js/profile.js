/**
 * Created by yimingjiang on 12/8/13.
 */

$(document).ready(function() {

    function submit () {
        var last_name_string = $('input#last_name_input').val();
        var first_name_string = $('input#first_name_input').val();
        var signature_string = $('textarea#signature_input').val();
        if ($("#male_input").is(":checked")) {
            var gender_string = "Male";
        }
        if ($("#female_input").is(":checked")) {
            var gender_string = "Female";
        }
        var year_string = $("#year_input option:selected").text();
        var month_string = $("#month_input option:selected").text();
        var day_string = $("#day_input option:selected").text();
        var birthday_string = month_string + day_string + year_string;
        //$('#feedback').html(last_name_string + first_name_string + signature_string + gender_string + year_string + month_string + day_string);
        $.ajax({
            type: "POST",
            url: "update_profile.php",
            data: {
                last_name: last_name_string,
                first_name: first_name_string,
                signature: signature_string,
                gender: gender_string,
                birthday: birthday_string
            },
            cache:false,
            success: function(html){
                $("div#feedback").html(html);
            }
        });
    }

    $("#submitButton").click(function() {
        submit();
    });

});