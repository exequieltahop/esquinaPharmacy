<?php
    // db conn
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST!');
        }
        // json
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        // data post
        $brandName = $data['brandName'];
        $genericName = $data['genericName'];
        $dosage = $data['dosage'];
        $stockRecieved = $data['stockRecieved'];
        $lotNo = $data['lotNo'];
        $expiryDate = $data['expiryDate'];
        $price = $data['price'];
        $dateRecieved = $data['dateRecieved'];
        $retailPrice = $data['retailPrice'];
        $prescription = $data['prescription'];
        $threshold = $data['threshold'];
        $result = addNewItem($brandName,
                             $genericName,
                             $dosage,
                             $stockRecieved,
                             $lotNo,
                             $expiryDate,
                             $price,
                             $dateRecieved,
                             $retailPrice,
                             $prescription,
                             $threshold,
                             $conn);
        if($result === true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Add An Item!']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // add data to db
    function addNewItem(string $brandName,
                        string $genericName,
                        string $dosage,
                        int $stockRecieved,
                        string $lotNo,
                        string $expiryDate,
                        int $price,
                        string $dateRecieved,
                        int $retailPrice,
                        string $prescription,
                        int $threshold,
                        mysqli $conn) : bool {
        try {
            $stmt = $conn->prepare('INSERT INTO 
                                        products(brand_name,
                                                 generic_name,
                                                 dosage,
                                                 stock_received,
                                                 date_received,
                                                 lot_no,
                                                 expiry_date,
                                                 price,
                                                 retail_price,
                                                 prescription,
                                                 stock_on_hand,
                                                 threshold)
                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            if(!$stmt){
                throw new Exception('addNewItem() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ssiisssiisii',$brandName,
                                             $genericName,
                                             $dosage,
                                             $stockRecieved,
                                             $dateRecieved,
                                             $lotNo,
                                             $expiryDate,
                                             $price,
                                             $retailPrice,
                                             $prescription,
                                             $stockRecieved,
                                             $threshold);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>