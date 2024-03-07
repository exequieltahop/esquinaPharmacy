<?php
    session_start();
    include_once '../db/conn.php';
    require '../cipher/cipher.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST!');
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $username = $data['username'];
        $password = $data['password'];
        $enc = Encipher($password, 'esquina');
        $res = auth($username, $enc, $conn);
        if($res != ''){
            // echo $res;
            if($res == 'Invalid Account!'){
                // echo 'inval';
                header('Content-Type: application/json');
                echo json_encode(['status'=>'Invalid Account!']);
            }elseif($res == 'Wrong Password!'){
                // echo 'wrong';
                header('Content-Type: application/json');
                echo json_encode(['status'=>'Wrong Password!']);
            }else{
                $pageTotalNo = getPages($conn);
                // echo 'suc';
                $_SESSION['hasLog'] = 1;
                $_SESSION['username'] = $username;
                $_SESSION['position'] = $res;
                $_SESSION['sortBy'] = 'date_received';
                $_SESSION['sortType'] = 'ASC';
                $_SESSION['pageNo'] = 1;
                $_SESSION['totalPage'] = $pageTotalNo;
                header('Content-Type: application/json');
                echo json_encode(['position'=>$res, 
                                  'status'=>'success']);
            }
        }else{
            throw new Exception('Error fetching the data in db!');
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // authenticate username and password
    function auth(string $username, string $password, mysqli $conn) :string {
        try {
            $query = 'SELECT username 
                      FROM user_table
                      WHERE BINARY username = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('auth() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                // return 'username okay';
                while($row = $result->fetch_assoc()){
                    $checkRes = check_password($row['username'], $password, $conn);
                }
                if($checkRes != ''){
                    $stmt->close();
                    return $checkRes;
                }
                else{
                    $stmt->close();
                    return 'Wrong Password!';
                }
                
            }else{
                $stmt->close();
                return 'Invalid Account!';
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
    // function check password
    function check_password(string $username, string $password, mysqli $conn) : string {
        try {
            // return $password;
            $query = 'SELECT position
                      FROM user_table
                      WHERE BINARY username = ?
                      AND BINARY password = ?';

            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('auth() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
                    $return = $row['position'];
                }
                $stmt->close();
                return $return;
            }else{
                $stmt->close();
                return '';
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
    // GET PAGE NO
    function getPages(mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT * FROM products');
            if(!$stmt){
                throw new Exception('getPages() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCount = $result->num_rows;
            $pageNumber = ceil($rowCount / 20);
            $stmt->close();
            return $pageNumber;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>