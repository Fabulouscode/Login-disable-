<?php
	session_start();

	if(isset($_POST['login'])){
		//connection
		$conn = new mysqli('localhost', 'root', '', 'votesystem');

		//set login attempt if not set
		if(!isset($_SESSION['attempt'])){
			$_SESSION['attempt'] = 0;
		}

		//get the user with the email
		$sql = "SELECT * FROM voters WHERE voters_id = '".$_POST['username']."'";
		$query = $conn->query($sql);
		if($query->num_rows > 0){
			$row = $query->fetch_assoc();
			//verify password
			if($_POST['password']==$row['password']){
				//action after a successful login
				//for now just message a successful login
				header('location: home.php');
				//unset our attempt
				unset($_SESSION['attempt']);
			}
			else{
				$_SESSION['error'] = 'Password incorrect';
				//this is where we put our 3 attempt limit
				$_SESSION['attempt'] += 1;
				//set the time to allow login if third attempt is reach
				if($_SESSION['attempt'] == 3){
					$_SESSION['attempt_again'] = time() + (5*60);
					//note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
				}
			}
		}
		else{
			$_SESSION['error'] = 'No account with that username';
		    $_SESSION['attempt'] += 1;
				//set the time to allow login if third attempt is reach
				if($_SESSION['attempt'] == 3){
					$_SESSION['attempt_again'] = time() + (5*60);
		}

		}

	}
	else{
		$_SESSION['error'] = 'Fill up login form first';
	}

	header('location: index.php');

?>