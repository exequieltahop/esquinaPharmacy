<?php 
    session_start();
    include_once '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $pin = getPin($_SESSION['username'], $conn);
        header('Content-Type: application/json');
        echo json_encode(['pin'=>$pin]);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // get pin of the user
    function getPin(string $username, mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT security_pin FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('getPin() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                if($row = $result->fetch_assoc()){
                    $return = $row['security_pin'];
                }
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>