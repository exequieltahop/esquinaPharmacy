<?php 
    // DATABASE CONNECTION 
    include_once '../db/conn.php';
    // CIPHER
    include_once '../cipher/cipher.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('Server Request Not POST!');
        }
        $data = file_get_contents('php://input');
        $json = json_decode($data, true);
        $id = $json['id'] ?? NULL;
        $name = $json['name'] ?? NULL;
        $uname = $json['uname'] ?? NULL;
        $pass = $json['pass'] ?? NULL;
        $pin = $json['pin'] ?? NULL;
        
        if($name === NULL ||
        $uname === NULL ||
        $pass === NULL ||
        $pin === NULL ||
        $id === NULL){   
            throw new Exception('JSON Has Null Value');
        }

        $sanitizeId = intval($id);
        $sanitizeName = strval($name);
        $sanitizedUname = strval($uname);
        $sanitizePassword = strval($pass);
        $sanitizePin = intval($pin);
        $secPinCheck = securityPinChecker($sanitizePin, $sanitizeId, $conn);
        
        $usernameChecker = usernameChecker($sanitizedUname, $sanitizeId, $conn); 
        if($usernameChecker === TRUE){
            header('Content-Type: application/json');
            echo json_encode(['status' => 'Username Already Exist!']);
        }else{
            if($secPinCheck === TRUE){
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Security Pin Already Exist!']);
            }else{
                $encryptedPass = Encipher($sanitizePassword, 'esquina');
    
                $status = updateNow( $conn,
                                    $sanitizeId,
                                    $sanitizeName,
                                    $sanitizedUname,
                                    $encryptedPass,
                                    $sanitizePin
                                    );
    
                if($status === TRUE){
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'Successfully Edited Account!']);
                }else{
                    throw new Exception('Failed To Edit Profile!');
                }
            }
        }
    } catch (\Throwable $th) {
        if($th instanceof \Exception || $th instanceof \Error) {
            header('Content-Type: application/json');
            echo json_encode(['err'=>$th->getMessage()]);
        }
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // SECURITY PIN CHECKER
    function securityPinChecker(int $pin, int $id, mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('SELECT security_pin FROM user_table
                                    WHERE security_pin = ?
                                    AND id != ?');
            if(!$stmt){
                throw new Exception('securityPinChecker() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->errno);
            }
            $stmt->bind_param('ii', $pin, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $stmt->close();
                return TRUE;
            }else{
                $stmt->close();
                return FALSE;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // UPDATE DATA IN DB
    function updateNow(mysqli $conn,
                       int $id,
                       string $name,
                       string $uname,
                       string $pass,
                       int $pin) : bool{
        try {
            $query = 'UPDATE user_table
                      SET full_name = ?,
                          username = ?,
                          password = ?,
                          security_pin = ?
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('udpateNow() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssssi', $name, $uname, $pass, $pin, $id);
            if(!$stmt->execute()) {
                throw new Exception('updateNow() stmt not execute - '.
                                    $conn->errno.'/'.$conn->error);
            }
            $stmt->close();
            return TRUE;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // USERNAME CHECKER 
    function usernameChecker(string $username, int $id, mysqli $conn) : bool {
        try {
            $query = 'SELECT * FROM user_table
                      WHERE BINARY username = ?
                      AND id != ?'; 
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('usernameChecker');
            }
            $stmt->bind_param('si', $username, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $stmt->close();
                return TRUE;
            }else{
                $stmt->close();
                return FALSE;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>
