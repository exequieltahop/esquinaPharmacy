<?php 
    // SESSION START
    SESSION_START();
    // MAIN
    include_once '../../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Method Not POST!');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $ids = $json['ids'] ?? NULL;
        $quantities = $json['quantities'] ?? NULL;
        if($ids === NULL){  
            throw new Exception('ids var was NULL');
        }if($quantities === NULL){
            throw new Exception('quantities var was NULL');
        }
        // $ids = [7, 8, 11];
        // $quantities = [1, 1, 1];
        $finalRes = addCheckOut($_SESSION['username'],
                                $ids, 
                                $quantities, 
                                $conn);
        if($finalRes === true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Added!']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if($conn){
            $conn->close();
        }
    }
    // ADD IN DB
    function addCheckOut(string $username,
                         array $ids, 
                         array $quantities, 
                         mysqli $conn) : bool {
        try {
            $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $dateNow = $now->format('Y-m-d');
            for($i = 0; $i < count($ids); $i++){
                $fetchDataId = intval($ids[$i]);
                $fetchData = fetchData($fetchDataId, $conn);
                $totalPrice = $fetchData['price'] * $quantities[$i];
                // QUERY INSERT DATA TO SALES TABLE
                if($quantities[$i] != 0){
                    $query = 'INSERT INTO sales(brand_name,
                                                generic_name,
                                                item_base_id,
                                                price,
                                                quantity,
                                                total_price,
                                                timestamp,
                                                seller)
                              VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $conn->prepare($query);
                    if(!$stmt){
                    throw new Exception('addCheckOut() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
                    }
                    $stmt->bind_param('ssiiiiss', $fetchData['brand_name'],
                                                    $fetchData['generic_name'],
                                                    $ids[$i],
                                                    $fetchData['price'],
                                                    $quantities[$i],
                                                    $totalPrice,
                                                    $dateNow,
                                                    $username
                                        );
                    $stmt->execute();
                    // QUERY 2 UPDATE DATABASE IN THE STOCK ON HAND
                    $update = updateData($fetchData['stock'], 
                                $ids[$i], 
                                $quantities[$i], 
                                $conn);
                    // QUERY 3 UPDATE ADDED_TO_CART_ITEM TABLE
                    $updateAddedToCartItemTable = updateDataAddedToCartItem( $ids[$i], $conn);
                    if(!$update || !$updateAddedToCartItemTable){
                    throw new Exception('Failed To Update Tables!');
                    }
                }else{
                    deleteItemInDb($fetchDataId, $conn);
                }
            }
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // FETCH DATA AT ID
    function fetchData(int $id, mysqli $conn) : array{
        try {
            $return = [];
            $query = 'SELECT added_to_cart_item.id AS id,
                                added_to_cart_item.brand_name AS brand_name,
                                added_to_cart_item.generic_name AS generic_name,
                                added_to_cart_item.price AS price,
                                added_to_cart_item.date_received AS date_received,
                                products.stock_on_hand AS stocks
                        FROM added_to_cart_item
                        INNER JOIN products
                        ON added_to_cart_item.base_id = products.id
                        WHERE added_to_cart_item.id = ?;';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('fetchData');
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                if($row = $result->fetch_assoc()){
                    $return = [
                        'id'=> $id,
                        'brand_name'=> $row['brand_name'],
                        'generic_name'=>$row['generic_name'],
                        'price'=> $row['price'],
                        'stock'=> $row['stocks']
                    ];
                }
            }else{
                throw new Exception('fetchData() no result!');
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // update data in the database 
    function updateData(int $preStock, 
                        int $id, 
                        int $quantity, 
                        mysqli $conn) : bool {
        try {
            $stockUpdate = $preStock - $quantity;
            $stmt = $conn->prepare('UPDATE products
                                    SET stock_on_hand = ?
                                    WHERE id = ?');
            if(!$stmt){
                throw new Exception('updateData() stmt not prepare - '
                                    .$conn->errno.'/'. $conn->error);
            }
            $stmt->bind_param('ii', $stockUpdate, $id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // UPDATE ADDED_TO_CART_ITEM
    function updateDataAddedToCartItem(int $id, mysqli $conn){
        try {
            $query = 'UPDATE added_to_cart_item
                      SET status = "Checked Out"
                      WHERE base_id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('updateDataAddedToCartItem() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('i', $id);
            if(!$stmt->execute()){
                throw new Exception('updateDataAddedToCartItem() stmt not executed - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // DELET ITEM FROM DATABASE
    function deleteItemInDb(int $id, mysqli $conn) : bool {
        try {
            $query = 'DELETE FROM added_to_cart_item
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('deleteItemInDb() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('i', $id);
            if(!$stmt->execute()){
                throw new Exception('deleteItemInDb() stmt not execute() - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>