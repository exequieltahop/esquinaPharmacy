<?php 
    // SESSION
    session_start();
    // DB CONNECTION
    include_once '../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Not GET');
        }
        $secPin = getSecurityPin($_SESSION['username'], $conn);
        header('Content-Type: application/json');
        echo json_encode(['data' => $secPin]);
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
    // GET SECURITY PIN
    function getSecurityPin(string $username, mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT security_pin FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('getSecurityPin() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows < 1){
                throw new Exception('Can\'t Get Security Pin');
            }else{
                if($row = $result->fetch_assoc()){
                    $return = $row['security_pin'];
                }
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>