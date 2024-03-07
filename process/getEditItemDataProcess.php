<?php 
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Not GET!');
        }
        $id = urldecode($_GET['id']);
        $res = getData($id, $conn);
        if(!empty($res)){
            header('Content-Type: application/json');
            echo json_encode($res);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // get input value in the db
    function getData(int $id, mysqli $conn) : array {
        try {
            $query = 'SELECT * FROM products
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('getData() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                while($row = $result->fetch_assoc()){
                    // date recieved
                    $newDateRecieved = new DateTime($row['date_received']);
                    $dateRecieved = $newDateRecieved->format('Y-m-d');
                    $newExpiryDate = new DateTime($row['expiry_date']);
                    $expiryDate = $newExpiryDate->format('Y-m-d');
                    $return = [
                        'brandName'=>$row['brand_name'],
                        'genericName'=>$row['generic_name'],
                        'dosage'=>$row['dosage'],
                        'stockRecieved'=>$row['stock_received'],
                        'dateRecieved'=>$dateRecieved,
                        'lotNo'=>$row['lot_no'],
                        'expiryDate'=>$expiryDate,
                        'price'=>$row['price'],
                        'retailPrice'=>$row['retail_price'],
                        'stockOnHand'=>$row['stock_on_hand'],
                        'prescription'=>$row['prescription']
                    ];
                }
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>