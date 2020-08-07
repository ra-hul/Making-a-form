<?php

session_start();

//initialising variables

$name = "";
$email = "";
$user = "";
$results="";
$password = "";

$errors = array();


//connect to db

$db = mysqli_connect('localhost','root','','bus_system') or die("could not connect to database");

//Register users

if (isset($_POST['name']) || isset($_POST['email']) || isset($_POST['password'])){

$name = mysqli_real_escape_string($db, $_POST['name']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$password = mysqli_real_escape_string($db, $_POST['password']);

}




//form validation

if(empty($name)) {array_push($errors, "");}
if(empty($email)){array_push($errors,"");}
if(empty($password)){array_push($errors,"");}



//check db for existing user with same username

$user_check_query ="SELECT * FROM registration WHERE name='$name' or email='$email' LIMIT 1";

$results = mysqli_query($db,$user_check_query);
$user = mysqli_fetch_assoc($results);

if($user) {
	if($user['name']=== $name){array_push($errors,"This name already exists ");}
		if($user['email']=== $email){array_push($errors,"This email id alreeady has a registered name");}
}

//Register the user if no error

if(count($errors) == 0){
	$password = md5($password); // this will encrypt the password
	$query = "INSERT INTO registration(name,email,password) VALUES ('paul','vai@gmail.com','123')";

    mysqli_query($db,$query);
    $_SESSION['name'] =$name;
    $_SESSION['success'] = "You are now logged in";

    header('location: index.php');

}

//login user
if(isset($_POST['submit'])){
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if(empty($name)){
		array_push($errors, "Name is required");
	}
	if(empty($password)){
		array_push($errors, "Password is required");
	}

	if(count($errors) == 0){
		$password = md5($password);

		$query = "SELECT * FROM registration WHERE name ='$name' AND password='$password' ";
		$results = mysqli_query($db, $query);

		if(mysqli_num_rows($results)){
			$_SESSION['name'] = $name;
			$_SESSION['success'] = "Logged in successfully";
			header('location : index.php');
		}else{
			array_push($errors, "Wrong name/password combination.Please try again");
		}
	}
}

?>
