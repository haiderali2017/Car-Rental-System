<?php
session_start();
if(isset( $_SESSION['user'] )  )
	;
else
	header("location:Client_login.php?msg=Login First");

include "dbConn.php";

class User extends dbConn
{
	private $name;
	private $email;
	private $gender;
	private $password;
	private $cell_phone;

	function __Construct()
	{
		$this->name="";
		$this->email="";
		$this->gender="";
		$this->password="";
		$this->cell_phone=0;
	}

	function new_register_error_handling()
	{
		$name_error = $email_error = $password_error = $cell_phone_err = "";

		if(!empty($_POST['full_name']))
			$this->name=$_POST['full_name'];
		else
			$name_error="**";

		if(!empty($_POST['email']))
			$this->email=$_POST['email'];
		else
			$email_error="**";

		if(!empty($_POST['password']))
			$this->password=$_POST['password'];
		else
			$password_error="**";

		if(!empty($_POST['cell']))
			$this->cell_phone=$_POST['cell'];
		else
			$cell_phone_err="**";

		$this->gender=$_POST['gender'];

		if(empty($this->name || $this->email || $this->password || $this->cell_phone))
		{
			header("Location: user_registration_page.php?name_err=$name_error&email_err=$email_error&pwd_err=$password_error&cell_err=$cell_phone_err&name_data=$this->name&email_data=$this->email&gender_data=$this->gender&pwd_data=$this->password&cell_phone=$this->cell_phone&msg1=Please fill the required fields.");
		}
		else
		{
			header("Location: user_registration_page.php?name_err=$name_error&email_err=$email_error&pwd_err=$password_error&cell_err=$cell_phone_err&name_data=$this->name&email_data=$this->email&gender_data=$this->gender&pwd_data=$this->password&cell_phone=$this->cell_phone&msg2=REGISTRATION SUCCESSFUL.");
		}
	}

	function login_error_handling()
	{
		$name_error = $email_error = "";

		if(!empty($_POST['full_name']))
			$this->name=$_POST['full_name'];
		else
			$name_error="**";

		if(!empty($_POST['password']))
			$this->password=$_POST['password'];
		else
			$password_error="**";

		if(empty($this->name || $this->password))
		{
			header("Location: Client_login.php?name_err=$name_error&pwd_err=$password_error&name_data=$this->name&msg1=Please fill the required fields.");
		}

	}

	function register_user($t_name, $t_email, $t_gender, $t_password, $t_cell)
	{
		$q = "Insert into user(Name, Gender, Password, Email, Cell_phone) values('$t_name', '$t_gender', '$t_password', '$t_email', $t_cell)";
		$data = $this->conn->prepare($q);
		$data->execute();

	}

	function email_validation_check($t_email)
	{
		$q = "Select * from user where Email = '$t_email'";
		$data = $this->conn->prepare($q);
		$data->execute();

		while($row = $data->fetchObject())
		{
			if($row->Email == $t_email)
				header("Location: user_registration_page.php?same_email=Email Already Exists.");
		}
	}

	function login_user($username, $password)
	{
		$q = "Select * from user where Name='$username' and Password='$password'";
		$data=$this->conn->prepare($q);
		$data->execute();

		while($row = $data->fetchObject())
		{
			if($row->Name == $username && $row->Password == $password)
				header("Location: Client_login.php?done=Login Successful");
			else
				header("Location: Client_login.php?not_done=Login Unsuccessful");
		}


	}

	function get_user()
	{
		$q="Select * from user where Name='$_SESSION[user]'";
		$stmt=$this->conn->prepare($q);

		return $stmt;
	}
}

?>