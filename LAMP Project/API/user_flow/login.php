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
		$stmt = $conn->prepare("SELECT id,firstname,lastname,email FROM Users WHERE email =? AND password =?");
		$stmt->bind_param("ss", $inData["email"], $inData["password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			returnWithInfo( $row['firstname'], $row['lastname'], $row['id'], $row['email'] );
		}
		else
		{
			returnWithError("No User Found");
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
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstname":"","lastname":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstname":"' . $firstName . '","lastname":"' . $lastName . '","email":"' . $email . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>