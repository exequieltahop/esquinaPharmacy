<?php 
    SESSION_START();
    // DB CONNECTION
    include_once '../db/conn.php';
    // CIPHER
    require '../cipher/cipher.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'PUT'){
            throw new Exception('Server Request Method Not PUT!');
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
            throw new Exception('JSON Data Has NULL Value');
        }
        $encPass = Encipher($pass, 'esquina');
        $updateRes = updateProfile($name,
                                   $uname,
                                   $encPass, 
                                   $pin,
                                   $_SESSION['username'],
                                   $conn);
        if(!$updateRes) {
            throw new Exception('updateProfile() has return false!');
        }else{
            $_SESSION['username'] = $uname;
            header('Content-Type: application/json');
            echo json_encode(['status' => 'Successfully Update Profile']);
        }
    } catch (\Throwable $th) {
        // THROW EXCEPTIONS AND ERROR AS JSON
        if($th instanceof \Exception || $th instanceof \Error) { 
            header('Content-Type: application/json');
            echo json_encode(['err' => $th->getMessage()]);
        }
    } finally {
        // IF CONNECTION IS SET CLOSE IT
        if(isset($conn)){
            $conn->close();
        }
    }
    // UPDATE DATA IN DB
    function updateProfile(string $name,
                           string $uname,
                           string $pass, 
                           int $pin,
                           string $curUname,
                           mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('UPDATE user_table
                                    SET full_name = ?,
                                        username = ?,
                                        password = ?,
                                        security_pin = ?
                                    WHERE position = "admin"
                                    AND BINARY username = ?');
            if(!$stmt){
                throw new Exception('updateProfile() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }    
            $stmt->bind_param('sssis', $name, 
                                        $uname,
                                        $pass,
                                        $pin,
                                        $curUname);
            if($stmt->execute()){
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
?>