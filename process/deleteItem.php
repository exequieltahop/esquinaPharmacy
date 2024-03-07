<?php
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'DELETE'){
            throw new Exception('Server Request Method Not DELETE');
        }
        // echo $_GET['id'];
        
        $id = urldecode($_GET['id']) ?? NULL;
        $res = deleteItem($id, $conn);
        if($res === true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Deleted An Item']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // delete item in db
    function deleteItem(int $id, mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('DELETE FROM products
                                    WHERE id = ?');
            if(!$stmt){
                throw new Exception('deleteItem() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>