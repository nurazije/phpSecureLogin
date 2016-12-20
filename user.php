<?php
/**
* Secure login/registration user class.
*/

class User{
	/** @var object $pdo Copy of PDO connection */
	private $pdo;
	/** @var object of the logged in user */
	private $user;
	/** @var string error msg */
	private $msg;
	/** @var int number of permitted wrong login attemps */
	private $permitedAttemps = 5;

	/**
	* Connection init function
	* @param string $conString		DB connection string.
	* @param string $user			DB user.
	* @param string $pass			DB password.
	*
	* @return bool Returns connection success.
	*/
	public function dbConnect($conString, $user, $pass){
		if(session_status() === PHP_SESSION_ACTIVE){
			try {
				$pdo = new PDO($conString, $user, $pass);
				$this->pdo = $pdo;
				return true;
			}catch(PDOException $e) {  
				$this->msg = 'Connection did not work out!';
				return false;
			}
		}else{
			$this->msg = 'Session did not start.';
			return false;
		}
	}

	/**
	* Return the logged in user.
	* @return user array data
	*/
	public function getUser(){
		return $this->user;
	}

	/**
	* Login function
	* @param string $email			User email.
	* @param string $password		User password.
	*
	* @return bool Returns login success.
	*/
	public function login($email,$password){
		if(is_null($this->pdo)){
			$this->msg = 'Connection did not work out!';
			return false;
		}else{
			$pdo = $this->pdo;
			$stmt = $pdo->prepare('SELECT id, fname, lname, email, wrong_logins, password, user_role FROM users WHERE email = ? and confirmed = 1 limit 1');
			$stmt->execute([$email]);
			$user = $stmt->fetch();

			if(password_verify($password,$user['password'])){
				if($user['wrong_logins'] <= $this->permitedAttemps){
					$this->user = $user;
					session_regenerate_id();
					$_SESSION['user']['id'] = $user['id'];
					$_SESSION['user']['fname'] = $user['fname'];
					$_SESSION['user']['lname'] = $user['lname'];
					$_SESSION['user']['email'] = $user['email'];
					$_SESSION['user']['user_role'] = $user['user_role'];
					return true;
				}else{
					$this->msg = 'This user account is blocked, please contact our support department.';
					return false;
				}
			}else{
				$this->registerWrongLoginAttemp($email);
				$this->msg = 'Invalid login information or the account is not activated.';
				return false;
			}		
		}
	}

	/**
	* Register a new user account function
	* @param string $email			User email.
	* @param string $fname			User first name.
	* @param string $lname			User last name.
	* @param string $pass 			User password.
	* @return boolean of success.
	*/
	public function registration($email,$fname,$lname,$pass){
		$pdo = $this->pdo;
		if($this->checkEmail($email)){
			$this->msg = 'This email is already taken.';
			return false;
		}
		if(!(isset($email) && isset($fname) && isset($lname) && isset($pass) && filter_var($email, FILTER_VALIDATE_EMAIL))){
			$this->msg = 'Inesrt all valid requered fields.';
			return false;
		}

		$pass = $this->hashPass($pass);
		$confCode = $this->hashPass(date('Y-m-d H:i:s').$email);
		$stmt = $pdo->prepare('INSERT INTO users (fname, lname, email, password, confirm_code) VALUES (?, ?, ?, ?, ?)');
		if($stmt->execute([$fname,$lname,$email,$pass,$confCode])){
			if($this->sendConfirmationEmail($email)){
				return true;
			}else{
				$this->msg = 'confirmation email sending has failed.';
				return false;	
			}
		}else{
			$this->msg = 'Inesrting a new user failed.';
			return false;
		}
	}

	/**
	* Email the confirmation code function
	* @param string $email			User email.
	* @return boolean of success.
	*/
	private function sendConfirmationEmail($email){
		$pdo = $this->pdo;
		$stmt = $pdo->prepare('SELECT confirm_code FROM users WHERE email = ? limit 1');
		$stmt->execute([$email]);
		$code = $stmt->fetch();

		$subject = 'Confirm your registration';
		$message = 'Please confirm you registration by pasting this code in the confirmation box: '.$code['confirm_code'];
		$headers = 'X-Mailer: PHP/' . phpversion();

		if(mail($email, $subject, $message, $headers)){
			return true;
		}else{
			return false;
		}
	}

	/**
	* Activate a login by a confirmation code and login function
	* @param string $email			User email.
	* @param string $confCode		Confirmation code.
	* @return boolean of success.
	*/
	public function emailActivation($email,$confCode){
		$pdo = $this->pdo;
		$stmt = $pdo->prepare('UPDATE users SET confirmed = 1 and confirm_code = NULL WHERE email = ? and confirm_code = ?');
		if($stmt->execute([$email,$confCode])){
			$stmt = $pdo->prepare('SELECT id, fname, lname, email, wrong_logins, user_role FROM users WHERE email = ? and confirmed = 1 limit 1');
			$stmt->execute([$email]);
			$user = $stmt->fetch();

			$this->user = $user;
			session_regenerate_id();
			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['fname'] = $user['fname'];
			$_SESSION['user']['lname'] = $user['lname'];
			$_SESSION['user']['email'] = $user['email'];
			$_SESSION['user']['user_role'] = $user['user_role'];
			return true;
		}else{
			$this->msg = 'Account activitation failed.';
			return false;
		}
	}

	/**
	* Password change function
	* @param int $id				User id.
	* @param string $pass			New password.
	* @return boolean of success.
	*/
	public function passwordChange($id,$pass){
		$pdo = $this->pdo;
		if(isset($id) && isset($pass)){
			$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
			if($stmt->execute([$id,$this->hashPass($pass)])){
				return true;
			}else{
				$this->msg = 'Password change failed.';
				return false;
			}
		}else{
			$this->msg = 'Provide an ID and a password.';
			return false;
		}
	}


	/**
	* Assign a role function
	* @param int $id				User id.
	* @param int $role				User role.
	* @return boolean of success.
	*/
	public function assignRole($id,$role){
		$pdo = $this->pdo;
		if(isset($id) && isset($role)){
			$stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
			if($stmt->execute([$id,$role])){
				return true;
			}else{
				$this->msg = 'Role assign failed.';
				return false;
			}
		}else{
			$this->msg = 'Provide a role for this user.';
			return false;
		}
	}



	/**
	* User information change function
	* @param int $id				User id.
	* @param string $fname			User first name.
	* @param string $lname			User last name.
	* @return boolean of success.
	*/
	public function userUpdate($id,$fname,$lname){
		$pdo = $this->pdo;
		if(isset($id) && isset($fname) && isset($lname)){
			$stmt = $pdo->prepare('UPDATE users SET fname = ?, lname = ? WHERE id = ?');
			if($stmt->execute([$id,$fname,$lname])){
				return true;
			}else{
				$this->msg = 'User information change failed.';
				return false;
			}
		}else{
			$this->msg = 'Provide a valid data.';
			return false;
		}
	}

	/**
	* Check if email is already used function
	* @param string $email			User email.
	* @return boolean of success.
	*/
	private function checkEmail($email){
		$pdo = $this->pdo;
		$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? limit 1');
		$stmt->execute([$email]);
		if($stmt->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}


	/**
	* Register a wrong login attemp function
	* @param string $email			User email.
	* @return void.
	*/
	private function registerWrongLoginAttemp($email){
		$pdo = $this->pdo;
		$stmt = $pdo->prepare('UPDATE users SET wrong_logins = wrong_logins + 1 WHERE email = ?');
		$stmt->execute([$email]);
	}

	/**
	* Password hash function
	* @param string $password			User password.
	* @return string $password 			Hashed password.
	*/
	private function hashPass($pass){
		return password_hash($pass, PASSWORD_DEFAULT);
	}

	/**
	* Print error msg function
	* @return void.
	*/
	public function printMsg(){
		print $this->msg;
	}

	/**
	* Logout the user and remove it from the session.
	*
	* @return true
	*/
	public function logout() {
		$_SESSION['user'] = null;
		session_regenerate_id();
		return true;
	}



	/**
	* List users function
	*
	* @return array Returns list of users.
	*/
	public function listUsers(){
		if(is_null($this->pdo)){
			$this->msg = 'Connection did not work out!';
			return [];
		}else{
			$pdo = $this->pdo;
			$stmt = $pdo->prepare('SELECT id, fname, lname, email FROM users WHERE confirmed = 1');
			$stmt->execute();
			$result = $stmt->fetchAll();			
			return $result;		
		}
	}


	/**
	* Login form generation function
	*
	* @return String Returns HTML.
	*/
	public function loginForm(){
		print '<div class="form-group">
					<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Email" value="">
				</div>
				<div class="form-group">
					<input type="password" name="password" id="password1" tabindex="2" class="form-control" placeholder="Password">
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-3">
							<input type="button" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
						</div>
					</div>
				</div>
				<script type="text/javascript>
					$(function() {
						$("#login-submit").click(function(){
							if($("#username").val() != "" && $("#password1").val() != "" && validateEmail($("#username").val())){
								$.ajax({
								  method: "POST",
								  url: "'.loginfile.'",
								  data: { username: $("#username").val(), password: $("#password1").val() }
								}).done(function( msg ) {
								    if(msg !== ""){
								    	alert(msg);
								    }else{
								    	window.location = "'.userfile.'";
								    }
								});
							}else{
								alert("Please fill all fields with valid data!");
							}
						});
					});
				</script>';
	}

	/**
	* Activation form generation function
	*
	* @return String Returns HTML.
	*/
	public function activationForm(){
		print '<h1>Activate a new account</h1>
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
				<script type="text/javascript>
					$(function() {
						$("#activate-submit").click(function(){
							if($("#useractivation").val() != "" && $("#activationcode").val() != "" && validateEmail($("#useractivation").val())){
								$.ajax({
								  method: "POST",
								  url: "'.activatefile.'",
								  data: { email: $("#useractivation").val(), code: $("#activationcode").val() }
								}).done(function( msg ) {
								    if(msg !== ""){
								    	alert(msg);
								    }else{
								    	window.location = "'.userfile.'";
								    }
								});
							}else{
								alert("Please fill all fields with valid data!");
							}
						});
					});
				</script>';
	}



	/**
	* Registration form generation function
	*
	* @return String Returns HTML.
	*/
	public function registerForm(){
		print '<div class="form-group">
					<input type="text" name="fname" id="fname" tabindex="1" class="form-control" placeholder="First name" value="">
				</div>
				<div class="form-group">
					<input type="text" name="lname" id="fname" tabindex="1" class="form-control" placeholder="Last name" value="">
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
				<script type="text/javascript>
					$(function() {
						$("#register-submit").click(function(){
							if($("#fname").val() != "" && $("#lname").val() != "" && $("#email").val() != "" && $("#password2").val() != "" && validateEmail($("#email").val())){
								if($("#password2").val() === $("#confirm-password").val()){
									$.ajax({
									  method: "POST",
									  url: "'.registerfile.'",
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
				</script>';
	}
}