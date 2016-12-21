<h1>Activate a new account</h1>
<div class="form-group">
	<input type="text" name="username" id="useractivation" tabindex="3" class="form-control" placeholder="Email" value="">
</div>
<div class="form-group">
	<input type="text" name="code" id="activationcode" tabindex="4" class="form-control" placeholder="Activation code">
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<input type="button" name="activate-submit" id="activate-submit" tabindex="4" class="form-control btn btn-login" value="Send">
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$("#activate-submit").click(function(){
			if($("#useractivation").val() != "" && $("#activationcode").val() != "" && validateEmail($("#useractivation").val())){
				$.ajax({
				  method: "POST",
				  url: "<?=activatefile?>",
				  data: { email: $("#useractivation").val(), code: $("#activationcode").val() }
				}).done(function( msg ) {
				    if(msg !== ""){
				    	alert(msg);
				    }else{
				    	window.location = "<?=userfile?>";
				    }
				});
			}else{
				alert("Please fill all fields with valid data!");
			}
		});
	});
</script>
