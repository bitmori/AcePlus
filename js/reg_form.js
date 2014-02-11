/*$(function(){
	var mail_tip = $('input#email').qtip({
		content:{
			text: 'Please input E-mail'
		},
		style:{
			classes: 'qtip-bootstrap'
		},
		position:{
			my: 'center left',
			at: 'center right'
		},
		show:false
	}).qtip('api');

	var user_tip = $('input#user').qtip({
		content:{
			text: 'Please input username'
		},
		style:{
			classes: 'qtip-bootstrap'
		},
		position:{
			my: 'center left',
			at: 'center right'
		},
		show:false
	}).qtip('api');

	var pass_tip = $('input#pass').qtip({
		content:{
			text: 'Please input password'
		},
		style:{
			classes: 'qtip-bootstrap'
		},
		position:{
			my: 'center left',
			at: 'center right'
		},
		show:false
	}).qtip('api');

	$("#email").blur(function() {
		var mailaddress = $("#email").val();
		if (mailaddress.length==0) {
			$("#mail-div").addClass('error');
			mail_tip.show();
		}else if (!isValidEmail(mailaddress)) { //put validation condition here
			mail_tip.set('content.text', 'Invalid E-mail address.');
			$("#mail-div").addClass('error');
			mail_tip.show();
		}else{
			$("#mail-div").removeClass('error');
			mail_tip.hide();
		}
	});

	$("#user").blur(function() {
		var username = $("#user").val();
		if (username.length==0) {
			$("#user-div").addClass('error');
			user_tip.show();
		}else if (username.length<6) { //put validation condition here
			user_tip.set('content.text', 'Username should be longer than 6');
			$("#user-div").addClass('error');
			user_tip.show();
		}else{
			$("#user-div").removeClass('error');
			user_tip.hide();
		}
	});

	$("#pass").blur(function() {
		var password = $("#pass").val();
		if (!password.length) { //put validation condition here
			//pass_tip.set('content.text', 'Password cannot be empty.');
			$("#pass-div").addClass('error');
			pass_tip.show();
		} else if (!isValidPassword(password)) {
			pass_tip.set('content.text', 'Password should be longer than 6 and contain at least one digit of number and one letter');
			$("#pass-div").addClass('error');
			pass_tip.show();
		} else {
			$("#pass-div").removeClass('error');
			pass_tip.hide();
		}
	});

	var form = $("#form");
	form.submit(function(e){
		$.post(form.attr("action"),
			{
				act: "reg",
				ap_mail: $("#email").val(),
				ap_user: $("#user").val(),
				ap_pass: $.md5($("#pass").val())
			},
			function (result, status) {
				switch (result.code){
					case 200:
						window.location.assign("/jumper.php");
					break;
					case 401:
						if (!result.mailOK) {
							mail_tip.set('content.text', 'E-mail address has been used.');
							$("#mail-div").addClass('error');
							mail_tip.show();
						};
						if (!result.userOK) {
							user_tip.set('content.text', 'Username already existed');
							$("#user-div").addClass('error');
							user_tip.show();
						};
					break;
				}
			},
		"json");
		return false;
	});

	$("#submitbtn").click(function () {
		var res = isValidEmail($("#email").val()) && $("#user").val().length>=6 && isValidPassword($("#pass").val());
		if (res) {
			form.submit();
		} else {
			$("#email").blur();
			$("#user").blur();
			$("#pass").blur();
		}
	});
});

function isValidEmail(e){
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(e);
}

function isValidPassword(p){
	var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i;
	return regex.test(p);
}*/

$(function(){
	var validator = $("form").validate({
		// errorClass: "errormessage",
		onkeyup: false,
		// errorClass: 'error',
		// validClass: 'success',
		rules: {
			email: {
            	required: true,
            	email:true,
            	remote: {
					url: "thor.php",
					type: "post",
					data: {
						act: "uniqueemail",
						ap_mail: function() { return $( "#email" ).val();}
					}
				}
            },
            username: {
            	required: true,
            	minlength: 6,
				remote: {
					url: "thor.php",
					type: "post",
					data: {
						act: "uniqueusername",
						ap_user: function() { return $( "#user" ).val();}
					}
				}
            },
            password: {    
                required: true,    
                minlength: 6,
                acepassword: true
            },
            rememberme: {
            	required: true
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
			} else if (id=='email') {
				var pDiv = $("#mail-div");
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
			} else if (elem.attr('id')=='email') {
				var pDiv = $("#mail-div");
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
				act: "reg",
				ap_mail: $("#email").val(),
				ap_user: $("#user").val(),
				ap_pass: $.md5($("#pass").val())
			},
			function (result, status) {
				if (result.code==200) {
					window.location.assign("/jumper.php");
				}else{
					alert('ERROR!');
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
	});
	$("input#email").blur(function (ev) {
		validator.element("input#email");
	});
});

$.validator.addMethod("acepassword", function(value, element) {
  return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value);
}, "Password should be longer than 6 and contain at least one digit of number and one letter");
