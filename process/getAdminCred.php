<?php
    session_start();
    // setcookie("","", time() -0,"/");
    include_once '../db/conn.php';
    require '../cipher/cipher.php';
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $data = getAdminCred($_SESSION['username'], $conn);
        if(!empty($data)){
            header('Content-Type: application/json');
            echo json_encode(['data' => $data]);
        }else{
            throw new Exception('no data');
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
    // GET ADMIN CREDENTIALS
    function getAdminCred(string $username, mysqli $conn) : array {
        try {
            $stmt = $conn->prepare('SELECT * FROM user_table
                                    WHERE BINARY username = ?');
            if(!$stmt){
                throw new Exception('getAdminCred() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                if($row = $result->fetch_assoc()) {
                    $password = Decipher($row['password'], 'esquina');
                    $return = [
                        'fullname' => $row['full_name'],
                        'username' => $row['username'],
                        'password'=> $password,
                        'security_pin' => $row['security_pin']
                    ];
                }
            }else{
                $return = [];
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }