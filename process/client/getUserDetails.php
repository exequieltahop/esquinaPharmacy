<?php
    session_start();
    include '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Not GET');
        }
        $data = getDetails($_SESSION['username'], $conn);
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
    // function get user details
    function getDetails(string $username, mysqli $conn) : array {
        try {   
            $stmt = $conn->prepare('SELECT * FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('getDetails() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $return = [
                        'name'=> $row['full_name'],
                        'username'=> $row['username'],
                        'password'=> $row['password'],
                        'sec_pin'=>$row['security_pin'],
                    ];
                }
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }