<?php 
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'PUT'){
            throw new Exception('Server Request Not PUT!');
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        // data
        $id = $data['id'];
        $brandName = $data['editBrandName'];
        $genericName = $data['editgenericName'];
        $dosage = $data['editdosage'];
        $stockRecieved = $data['editstockRecieved'];
        $stockOnHand = $data['editstockOnHand'];
        $lotNo = $data['editlotNo'];
        $expiryDate = $data['editexpiryDate'];
        $price = $data['editprice'];
        $dateRecieved = $data['editdateRecieved'];
        $retailPrice = $data['editretailPrice'];
        $prescription = $data['editPrescription'];

        $res = updateData($brandName, 
                          $genericName,
                          $dosage,
                          $stockRecieved,
                          $stockOnHand,
                          $dateRecieved,
                          $lotNo,
                          $prescription,
                          $expiryDate,
                          $price,
                          $retailPrice,
                          $id,
                          $conn);
        if($res === true){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'Successfully Edited Items']);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // udpate data in db
    function updateData(string $brandName, 
                        string $genericName,
                        string $dosage,
                        string $stockRecieved,
                        int $stockOnHand,
                        string $dateRecieved,
                        string $lotNo,
                        string $prescription,
                        string $expiryDate,
                        int $price,
                        int $retailPrice,
                        int $id,
                        mysqli $conn) : bool {
        try {
            $query = 'UPDATE products
                      SET brand_name = ?,
                          generic_name = ?,
                          dosage = ?,
                          stock_received = ?,
                          stock_on_hand = ?,
                          date_received = ?,
                          lot_no = ?,
                          prescription = ?,
                          expiry_date = ?,
                          price = ?,
                          retail_price = ?
                      WHERE id = ?';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('updateData() stmt not prepare - '
                                    .$conn->errno.'/'. $conn->error);
            }
            $stmt->bind_param('sssiissssiii', $brandName,
                                  $genericName,
                                  $dosage,
                                  $stockRecieved,
                                  $stockOnHand,
                                  $dateRecieved,
                                  $lotNo,
                                  $prescription,
                                  $expiryDate,
                                  $price,
                                  $retailPrice,
                                  $id);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>