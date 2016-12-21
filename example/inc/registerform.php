<div class="form-group">
	<input type="text" name="fname" id="fname" tabindex="1" class="form-control" placeholder="First name" value="">
</div>
<div class="form-group">
	<input type="text" name="lname" id="lname" tabindex="1" class="form-control" placeholder="Last name" value="">
</div>
<div class="form-group">
	<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
</div>
<div class="form-group">
	<input type="password" name="password" id="password2" tabindex="2" class="form-control" placeholder="Password">
</div>
<div class="form-group">
	<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<input type="button" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#register-submit").click(function(){
			if($("#fname").val() != "" && $("#lname").val() != "" && $("#email").val() != "" && $("#password2").val() != "" && validateEmail($("#email").val())){
				if($("#password2").val() === $("#confirm-password").val()){
					$.ajax({
					  method: "POST",
					  url: "<?=registerfile?>",
					  data: { fname: $("#fname").val(), lname: $("#lname").val(), email: $("#email").val(), password: $("#password2").val() }
					}).done(function( msg ) {
					   	alert(msg);
					});
				}else{
					alert("Passwords do not match!");
				}
				
			}else{
				alert("Please fill all fields with valid data!");
			}
		});
	});
</script>
