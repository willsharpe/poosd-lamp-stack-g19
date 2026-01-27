<?php
    $inData = getRequestInfo();

    $parent_id = 0;
    $firstName = "";
    $lastName = "";
    $email = "";
    $phone = "";
    $company = "";
   

    $conn = new mysqli("localhost", "lamp_G19", "WeLoveCOP4331", "ContactManager");

    if ($conn -> connect_error){
        return WithError($conn->connect_error);
    }

    else{
        $parent_id = $inData["parent_id"];
        $firstName = $inData["firstname"];
        $lastName = $inData["lastname"];
        $email = $inData["email"];
        $phone = $inData["phone"];
        $phone = $inData["company"];
        
        $check = $conn->prepare("SELECT email FROM Users WHERE email = ?");
        $check->bind_param("s", $inData["email"]);


        $check->execute();
        $result = $check->get_result();


        if ($row = $result->fetch_assoc()){
            returnWithError("Email already in use", 409);
        }

        else{
            $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, email, password) VALUES(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);


            $accountCreated = $stmt->execute();

            if($accountCreated){
                returnwithInfo("Account created successfully", $firstName, $lastName, $id, $email);
            }
            else{
                returnWithError("Account could not be created", 400);
            }
            
        }

        $stmt->close();
        $check->close();
        $conn->close();



    }


    function getRequestInfo(){
        return json_decode(file_get_contents('php://input'), true);
    }


    function sendResultInfoAsJson($obj){
        header('Content-type: application/json');
        echo $obj;
    }

    function returnwithInfo($message, $firstName, $lastName, $id, $email){

        $retValue = '{"id: " 0, "firstName:" '. $firstName .', "lastName:" '. $lastName .' "email: "'. $email.'}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithError($err, $code){
        http_response_code($code);
        $retValue = '{"error: "' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

?>