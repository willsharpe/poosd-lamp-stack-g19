<?php

	$inData = getRequestInfo();
	
	$id = 0;
	$firstName = "";
	$lastName = "";
	$email = "";
	$conn = new mysqli("localhost", "lamp_G19", "WeLoveCOP4331", "ContactManager"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT id,firstname,lastname,email,password FROM Users WHERE email =?");
		$stmt->bind_param("s", $inData["email"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			$hash = $row['password'];
			if (password_verify($inData['password'], $hash)){
				returnWithInfo( $row['firstname'], $row['lastname'], $row['id'], $row['email'] );
			}
			else {
				returnWithError("Invalid Password", 401);
			}
		}
		else
		{
			returnWithError("No User Found", 404);
		}

		$stmt->close();
		$conn->close();
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err , $code)
	{
		http_response_code($code);
		$retValue = '{"id":0,"firstname":"","lastname":"", "email":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id, $email)
	{
		$retValue = '{"id":' . $id . ',"firstname":"' . $firstName . '","lastname":"' . $lastName . '","email":"' . $email . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>