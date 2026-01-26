<?php
    $inData = getRequestInfo();

    $id = 0;
    $firstName = "";
    $lastName = "";
    $email = "";
    $passsword = "";
    $outputMessage = "";

    $conn = new mysqli("localhost", "lamp_G19", "WeLoveCOP4331", "ContactManager");

    if ($conn -> connect_error){
        return WithError($conn->connect_error);
    }

    else{
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);


        
        $check = $stmt->prepare("SELECT email FROM Users WHERE email = ?");
        $check= bind_param("s", $inData["email"]);


        $result = $check->execute();


        if ($row = $result->fetch_assoc()){
            returnWithError("Email already in use");
        }

        else{
            $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, password) VALUES(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);


            $accountCreated = $stmt->execute();

            if($accountCreated){
                $outputMessage = "Account created successfully";
            }
            else{
                returnWithError("Account could not be created");

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

    function returnWithError($err){
        $retValue = '{"error: "' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

   

?>