<?php 
    // SESSION START
    session_start();
    // DB CONNECTION
    include_once '../../db/conn.php';
    // MAIN 
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'PUT'){
            throw new Exception('Server Request Not PUT');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $name = $json['name'] ?? throw new Exception('Json name was empty');
        $uname = $json['uname'] ?? throw new Exception('Json uname was emtpy');
        $pass = $json['pass'] ?? throw new Exception('Json pass was empty');
        $secPin = $json['secPin'] ?? throw new Exception('Json secPin was empty');
        // echo $uname;
        // USE FUNCTION
        $data = saveCred($name, 
                         $uname, 
                         $pass, 
                         $secPin, 
                         $conn);
        // IF TRUE ECHO THE JSON STATUS
        if($data){
            header('Content-Type: application/json');
            echo json_encode(['status' => 'Successfully Edited Profile!']);              
        }
    } catch (\Throwable $th) {
        if($th instanceof \Exception || $th instanceof \Error) {
            header('Content-Type: application/json');
            echo json_encode(['err' => $th->getMessage()]);
        }
    } finally {
        if(isset($conn)){
            $conn->close(); 
        }
    }
    // SAVE USER CREDENTIALS
    function saveCred(string $name, 
                      string $uname, 
                      string $pass, 
                      int $secPin, 
                      mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('UPDATE user_table
                                    SET full_name = ?,
                                        username = ?,
                                        password = ?,
                                        security_pin = ?
                                    WHERE username = ?');
            if(!$stmt){
                throw new Exception('saveCred() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('sssis', $name, 
                                       $uname, 
                                       $pass, 
                                       $secPin, 
                                       $_SESSION['username']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['username'] = $uname;
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }        
    }