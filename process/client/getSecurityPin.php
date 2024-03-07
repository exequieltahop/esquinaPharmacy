<?php 
    session_start();
    include_once '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET!');
        }
        $secPin = getSecPin($_SESSION['username'], $conn);
        // echo '12';
        if($secPin === 0){
            throw new Exception('Security Not Found in Db');
        }else{
            header('Content-Type: application/json');
            echo json_encode(['secPin'=>$secPin]);
        }
    } catch (\Throwable $th) {
        if($th instanceof Exception || $th instanceof Error){
            header('Content-Type: application/json');
            echo json_encode(['err'=>$th->getMessage()]);
        }
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // GET SECURITY PIN IN THE DB
    function getSecPin(string $usename, mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT security_pin 
                                    FROM user_table
                                    WHERE username = ?');
            if(!$stmt){
                throw new Exception('getSecPin() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $usename);
            $stmt->execute();
            $result = $stmt->get_result();  
            if($result->num_rows > 0){
                if($row = $result->fetch_assoc()){
                    $return = $row['security_pin'];
                }
            }else{
                $return = 0;
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }