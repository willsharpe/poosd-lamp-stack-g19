<?php

    $inData = getRequestInfo();
    $searchResults = array();


    $conn = new mysqli("localhost", "lamp_G19", "WeLoveCOP4331", "ContactManager");

    if($conn->connect_error)
    {
        returnWithError($conn->correct_error, 500);
    }

    else
    {
        $stmt = $conn->prepare("SELECT firstname, lastname FROM Contacts WHERE firstname = ? OR lastname = ?");
        $firstname = $inData["firstname"];
        $lastname = $inData["lastname"];
        $stmt->bind_param("ss", $firstname, $lastname);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc())
            {
                $data[] = $row;
            }

            sendResultInfo(json_encode($data));
        }

        else
        {
            returnWithError("No records found");
        }

        $stmt->close();
        $conn->close();

        
    }




    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }


    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnwithInfo($message, $firstName, $lastName, $id, $email)
    {

        $retValue = '{"firstName": "'. $firstName .'", "lastName": "'. $lastName .'", "email": "'. $email.'"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithError($err, $code)
    {
        http_response_code($code);
        $retValue = '{"error: "' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }



?>