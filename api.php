<?php

header("Access-Control-Allow-Origin:* ");

header("Access-Control-Allow-Headers:* ");

header("Access-Control-Allow-Methods:* ");

$connect = new PDO("mysql:host=127.0.0.1;dbname=react_crud_php", "root", "");

$method = $_SERVER['REQUEST_METHOD']; //return GET, POST, PUT, DELETE

if($method === 'GET')
{
	if(isset($_GET['id']))
	{
		//fetch single user
		$query = "SELECT * FROM sample_users WHERE id = '".$_GET["id"]."'";

		$result = $connect->query($query, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$data['name'] = $row['name'];

			$data['mobile'] = $row['mobile'];

			$data['email'] = $row['email'];

			$data['id'] = $row['id'];
		}

		echo json_encode($data);
	}
	else 
	{
		//fetch all user

		$query = "SELECT * FROM sample_users ORDER BY id DESC";

		$result = $connect->query($query, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$data[] = $row;
		}

		echo json_encode($data);
	}

	
}

if($method === 'POST')
{
	//Insert User Data

	$form_data = json_decode(file_get_contents('php://input'));

	$data = array(
		':name'		=>	$form_data->name,
		':mobile'		=>	$form_data->mobile,
		':email'			=>	$form_data->email
	);

	$query = "INSERT INTO sample_users (name, mobile, email) VALUES (:name, :mobile, :email);";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}

if($method === 'PUT')
{
	//Update User Data

	$form_data = json_decode(file_get_contents('php://input'));

	$data = array(
		':name'		=>	$form_data->name,
		':mobile'		=>	$form_data->mobile,
		':email'			=>	$form_data->email,
		':id'				=>	$form_data->id
	);

	$query = " UPDATE sample_users SET name = :name, mobile = :mobile, email = :email WHERE id = :id ";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}
if($method === 'DELETE')
{
	//Delete User Data
	
	$data = array(
		':id' => $_GET['id']
	);

	$query = "DELETE FROM sample_users WHERE id = :id";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}
?>
