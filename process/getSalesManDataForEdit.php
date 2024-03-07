<?php
    // DB CONNECTION
    include_once '../db/conn.php';
    // CIPHER
    require '../cipher/cipher.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET') {
            throw new Exception('Server Request Not GET');
        }
        $id = intval(urldecode($_GET['id']));
        $data = getData($id, $conn);
        if(!empty($data)){
            header('Content-Type: application/json');
            echo json_encode(['data'=>$data]);
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
    // GET DATA IN DB
    function getData(int $id, mysqli $conn) : array {
        try {
            $stmt = $conn->prepare('SELECT * FROM user_table
                                    WHERE id = ?');
            if(!$stmt){
                throw new Exception('getData() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            } 
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                if($row = $result->fetch_assoc()) {
                    $password = Decipher($row['password'], 'esquina');
                    $return = [
                        'id'=> $row['id'],
                        'name'=> $row['full_name'],
                        'uname'=> $row['username'],
                        'pass'=> $password,
                        'pin'=> $row['security_pin']
                    ];
                }
            }else{
                $stmt->close();
                throw new Exception('getData() don\'t have result!');
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>