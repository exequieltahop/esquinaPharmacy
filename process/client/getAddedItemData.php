<?php 
    include_once '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $idArray = $json;
        $data = fetchData($idArray, $conn);
        if($data == true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Added To Cart!']);
        }else{
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Some Items Failed To Add']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // fet data to be dispayed
    function  fetchData(array $array, mysqli $conn) : bool {
        try {
            $validator = [];
            for($i = 0; $i < count($array); $i++){
                $query = 'SELECT id,
                                 date_received,
                                 brand_name,
                                 generic_name,
                                 stock_on_hand,
                                 retail_price
                          FROM products
                          WHERE id = ?';
                $stmt = $conn->prepare($query);
                if(!$stmt){
                    throw new Exception('fetchData() stmt not prepare - '
                                         .$conn->errno.'/'.$conn->error);
                }
                $stmt->bind_param('i', $array[$i]);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $addStatus = addToDb($row['id'],
                                            $row['date_received'],
                                            $row['brand_name'],
                                            $row['generic_name'],
                                            $row['retail_price'],
                                            $conn);
                        $validator[] = $addStatus;
                    }
                }else{
                    throw new Exception('No data Found in DataBase');
                }
            }
            $stmt->close();
            if(in_array(false, $validator)){
                return false;
            }else{
                return true;
            }
        } catch (Exception $th) {
            throw $th;
        }
    }
    // ADD DATA IN DATABASE
    function addToDb(int $id,
                   string $dateReceived,
                   string $brandName,
                   string $genericName,
                   float $price,
                   mysqli $conn
                   ) : bool {
        try {
            $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $now = $newDate->format('Y-m-d H:i:s');
            $stmt = $conn->prepare('INSERT INTO added_to_cart_item(base_id,
                                                                   date_received,
                                                                   brand_name,
                                                                   generic_name,
                                                                   price,
                                                                   timestamp)
                                    VALUES(?, ?, ?, ?, ?, ?)');
            if(!$stmt){
                throw new Exception('addToDb() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('isssis', $id, 
                                        $dateReceived, 
                                        $brandName, 
                                        $genericName, 
                                        $price, 
                                        $now);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>