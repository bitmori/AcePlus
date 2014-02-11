$(function(){
	var validator = $("form").validate({
		// errorClass: "errormessage",
		// onkeyup: false,
		// errorClass: 'error',
		// validClass: 'success',
		rules: {  
            username: {
            	required: true,
            	minlength: 6,
            	remote: {
					url: "thor.php",
					type: "post",
					data: {
						act: "userexist",
						ap_user: function() { return $( "#user" ).val();}
					}
				}
            },
            password: {    
                required: true,    
                minlength: 6,
                acepassword: true
            }
        },
		errorPlacement: function(error, element)
		{
			var elem = $(element);
			var id = element.attr('id');
			if (id=='user') {
				var pDiv = $("#user-div");
				if (!pDiv.hasClass('error')) {
					pDiv.addClass('error');
				}
				if (pDiv.hasClass('success')) {
					pDiv.removeClass('success');
				}
			} else if (id=='pass') {
				var pDiv = $("#pass-div");
				if (!pDiv.hasClass('error')) {
					pDiv.addClass('error');
				}
				if (pDiv.hasClass('success')) {
					pDiv.removeClass('success');
				}
			}
			// Check we have a valid error message
			if(!error.is(':empty')) {
				// Apply the tooltip only if it isn't valid
				elem.first().qtip({
					overwrite: false,
					content: error,
					style:{
						classes: 'qtip-bootstrap'
					},
					position:{
						my: (id=='term')?'top center':'center left',
						at: (id=='term')?'bottom center':'center right'
					},
					show: {
						event: false,
						ready: true
					},
					hide: false
				})
				// If we have a tooltip on this element already, just update its content
				.qtip('option', 'content.text', error);
			}

			// If the error is empty, remove the qTip
			else { elem.qtip('destroy'); }
		},
		success: function(label, element){
			var elem = $(element);
			if (elem.attr('id')=='user') {
				var pDiv = $("#user-div");
				if (pDiv.hasClass('error')) {
					pDiv.removeClass('error');
				}
				if (!pDiv.hasClass('success')) {
					pDiv.addClass('success');
				}
			} else if (elem.attr('id')=='pass') {
				var pDiv = $("#pass-div");
				if (pDiv.hasClass('error')) {
					pDiv.removeClass('error');
				}
				if (!pDiv.hasClass('success')) {
					pDiv.addClass('success');
				}
			}
		}/*,
		submitHandler:  function(form){
			form.submit();
		}*/
	});

	var form = $("form");
	form.submit(function(e){
		//console.log(e);
		$.post(form.attr("action"),
			{
				act: "login",
				ap_user: $("#user").val(),
				ap_pass: $.md5($("#pass").val()),
				ap_redirect: $.urlParam("src")
			},
			function (result, status) {
				if (result.code==200) {
					window.location.assign(result.redirect);
				}else{
					var pDiv = $("#pass-div");
					if (!pDiv.hasClass('error')) {
						pDiv.addClass('error');
					}
					if (pDiv.hasClass('success')) {
						pDiv.removeClass('success');
					}
				}
			},
		"json");
		return false;
	});
	$("#submitbtn").click(function(){
		var res = $("form").valid();
		if (res) {
			form.submit();
		};
		return false;
	});
	$("input#user").blur(function (ev) {
		validator.element("input#user");
	});
	$("input#pass").blur(function (ev) {
		validator.element("input#pass");
	})
});

$.validator.addMethod("acepassword", function(value, element) {
  return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value);
}, "Please specify the correct password");

$.urlParam = function(name){
	var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results==null){
		return null;
	}else{
		return results[1] || 0;
	}
}