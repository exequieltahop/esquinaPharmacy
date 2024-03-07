<?php 
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
            throw new Exception('Server Request Not DELETE');
        }
        $id = urlencode($_GET['id']);
        $res = deleteAcc($id, $conn);
        if($res){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Deleted Account']);
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
    // DELETE ACCOUNT IN DB
    function deleteAcc(string $id, mysqli $conn) : bool {
        try {
            $intId = intval($id);
            $stmt = $conn->prepare('DELETE FROM user_table
                                    WHERE id = ?');
            if(!$stmt){
                throw new Exception('deleteAcc() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('s', $intId);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }