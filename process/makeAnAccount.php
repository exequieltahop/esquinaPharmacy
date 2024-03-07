<?php 
    // DB CONNECTION
    include_once '../db/conn.php';
    require '../cipher/cipher.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('Server Request Not POST');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $name = $json['name'] ?? NULL;
        $uname = $json['uname'] ?? NULL;
        $pass = $json['pass'] ?? NULL;
        $pin = $json['pin'] ?? NULL;
        if($name === NULL || 
           $uname === NULL || 
           $pass === NULL || 
           $pin === NULL){
            throw new Exception('JSON data has NULL values');
        }
        // $name = 'qwe';
        // $uname = 'cks';
        // $pass = 'pass';
        // $pin = 4545;
        // VALIDATE USERNAME
        $unameValidation = usernameChecker($uname, $conn);
        if($unameValidation == true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Username Already Exist!']);
        }else{
            // PIN CHECKER
            $pinChecker = pinChecker($pin, $conn);
            if($pinChecker == true){
                header('Content-Type: application/json');
                echo json_encode(['status'=>'Pin Already Exist!']);
            }else{
                // ENCRYPT PASS
                $encryptedPassword = Encipher($pass, 'esquina');
                // ADD ACCOUNT TO DB
                $addAccountResult = addAccount($name, 
                                                $uname, 
                                                $encryptedPassword,
                                                $pin,
                                                $conn);
                if($addAccountResult == true){
                    header('Content-Type: application/json');
                    echo json_encode(['status'=>'Success']);
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
    // ADD ACCOUNT TO DATABASE
    function addAccount(string $name, 
                        string $uname, 
                        string $pass,
                        int $pin,
                        mysqli $conn) : bool {
        try {
            $now = getCurDate();
            $position = 'sale_man';
            $stmt = $conn->prepare('INSERT INTO user_table(full_name,
                                                           username,
                                                           password,
                                                           position,
                                                           security_pin,
                                                           timestamp)
                                    VALUES(?, ?, ?, ?, ?, ?)');
            if(!$stmt){
                throw new Exception('addAccount() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }   
            $stmt->bind_param('ssssis', $name, 
                                        $uname, 
                                        $pass, 
                                        $position, 
                                        $pin, 
                                        $now);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // GET CURRENT DATETIME WITH TIME ZONE ASIA/MANILA
    function getCurDate() : string {
        $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
        return $newDate->format('Y-m-d H-i-s');
    }
    // USERNAME CHECKER
    function usernameChecker(string $uname, mysqli $conn) : bool{
        try {
            $stmt = $conn->prepare('SELECT * FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('usernameChecker() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $uname);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $stmt->close();
                return true;
            }else{
                $stmt->close();
                return false;
            }
        } catch (\Exception $th) {
            throw $th;
        } catch (\Error $th) {
            throw $th;
        }
    }
    // CHECH PIN
    function pinChecker(string $pin, mysqli $conn) : bool{
        try {
            $intPin = intval($pin);
            $stmt = $conn->prepare('SELECT * FROM user_table
                                    WHERE BINARY security_pin = ?');
            if(!$stmt){
                throw new Exception('pinChecker() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $intPin);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $stmt->close();
                return true;
            }else{
                $stmt->close();
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }