$(function() {

    $('#login-form-link').click(function(e) {
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

	$('#register-form-link').click(function(e) {
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

	$('#login-submit').click(function(){
		if($('#username').val() != '' && $('#password1').val() != '' && validateEmail($('#username').val())){
			$.ajax({
			  method: "POST",
			  url: "login.php",
			  data: { username: $('#username').val(), password: $('#password1').val() }
			}).done(function( msg ) {
			    if(msg !== ''){
			    	alert(msg);
			    }else{
			    	window.location = 'user.php';
			    }
			});
		}else{
			alert('Please fill all fields with valid data!');
		}
	});

	$('#activate-submit').click(function(){
		if($('#useractivation').val() != '' && $('#activationcode').val() != '' && validateEmail($('#useractivation').val())){
			$.ajax({
			  method: "POST",
			  url: "activate.php",
			  data: { email: $('#useractivation').val(), code: $('#activationcode').val() }
			}).done(function( msg ) {
			    if(msg !== ''){
			    	alert(msg);
			    }else{
			    	window.location = 'user.php';
			    }
			});
		}else{
			alert('Please fill all fields with valid data!');
		}
	});


	$('#register-submit').click(function(){
		if($('#fname').val() != '' && $('#lname').val() != '' && $('#email').val() != '' && $('#password2').val() != '' && validateEmail($('#email').val())){
			if($('#password2').val() === $('#confirm-password').val()){
				$.ajax({
				  method: "POST",
				  url: "register.php",
				  data: { fname: $('#fname').val(), lname: $('#lname').val(), email: $('#email').val(), password: $('#password2').val() }
				}).done(function( msg ) {
				   	alert(msg);
				});
			}else{
				alert('Passwords do not match!');
			}
			
		}else{
			alert('Please fill all fields with valid data!');
		}
	});

});

function validateEmail($email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  	return emailReg.test( $email );
}