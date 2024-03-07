<?php
    session_start();
    // DB CONNECTION
    include_once '../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Not GET');
        }
        // GET SECURITY PIN AND ECHO IT AS JSON
        $data = getAdminPin($_SESSION['username'], $conn);
        // echo $data;
        header('Content-Type: application/json');
        echo json_encode(['data'=>$data]);
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
    // GET ADMIN PIN
    function getAdminPin(string $uname, mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT security_pin AS pin
                                    FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('getAdminPin() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $uname);
            $stmt->execute();
            $result = $stmt->get_result();  
            if($result->num_rows > 0) {
                if($row = $result->fetch_assoc()){
                    $return = $row['pin'];
                }
            }else{
                $stmt->close();
                throw new Exception('getAdminPin() Can\'t Seems To Get The Pin!');
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>
