# User class

A Secure PHP7 class for basic user login and registration. 

Very easy to use as REST API, with AJAX and Bootstrap

PHP Tested: 5.6.19, 7.0.11


## CONTENTS

	1. VARIABLE DEFINITIONS
	2. ALL METHODS
		2.1. User::dbConnect()
		2.2. User::getUser()
		2.3. User::login()
		2.4. User::registration()
		2.5. User::sendConfirmationEmail()
		2.6. User::emailActivation()
		2.7. User::passwordChange()
		2.8. User::assignRole()
		2.9. User::userUpdate()
		2.10. User::checkEmail()
		2.11. User::registerWrongLoginAttemp()
		2.12. User::hashPass()
		2.13. User::printMsg()
		2.14. User::logout()

* * *


## 1. VARIABLE DEFINITIONS

Variable definitions provided in the beginning of the class:

	/** @var object $pdo Copy of PDO connection */
	private $pdo;

	/** @var object of the logged in user */
	private $user;

	/** @var string error msg */
	private $msg;

	/** @var int number of permitted wrong login attemps */
	private $permitedAttemps = 5;
	
* * *
	
	
## 2. ALL METHODS

### 2.1. Public User::dbConnect($conString, $user, $pass)

Connection init function.

$conString		DB connection string.
$user			DB user.
$pass			DB password.

### 2.2. Public User::getUser()

Return the logged in user.

### 2.3. Public User::login($email,$password)

Login function. 

$email			User email.
$password		User password.

### 2.4. Public User::registration($email,$fname,$lname,$pass)

Register a new user account function

$email			User email.
$fname			User first name.
$lname			User last name.
$pass 			User password.

### 2.5. Private User::sendConfirmationEmail($email)

Email the confirmation code function.

$email			User email.

### 2.6. Public User::emailActivation($email,$confCode)

Activate a login by a confirmation code function.

$email			User email.
$confCode		Confirmation code.

### 2.7. Public User::passwordChange($id,$pass)

Password change function.

$id			User id.
$pass			New password.

### 2.8. Public User::assignRole($id,$role)

Assign a role function.

$id			User id.
$role			User role.

### 2.9. Public User::userUpdate($id,$fname,$lname)

User information change function.

$id			User id.
$fname			User first name.
$lname			User last name.

### 2.10. Private User::checkEmail($email)

Check if email is already used function.

$email			User email.

### 2.11. Private User::registerWrongLoginAttemp($email)

Register a wrong login attemp function.

$email			User email.

### 2.12. Private User::hashPass($pass)

Password hash function.

$password		User password.

### 2.13. Public User::printMsg()

Print error msg function.

### 2.14. Public User::logout()

Logout the user and remove it from the session.
