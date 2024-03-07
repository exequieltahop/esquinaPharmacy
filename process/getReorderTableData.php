<?php 
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $data = getData($conn);
        header('Content-Type: application/json');
        echo json_encode(['data'=>$data]);
    } catch (\Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // fetch data in db
    function getData(mysqli $conn) : string {
        try {
            $return = '';
            $query = 'SELECT * FROM products
                      WHERE stock_on_hand < threshold';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new \Exception('getData() stmt not prepare - '
                                      .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $newDate = new DateTime($row['date_received']);
                    $dateReceived = $newDate->format('F j, Y');
                    $newDate1 = new DateTime($row['expiry_date']);
                    $dateReceived1 = $newDate1->format('F j, Y');
                    $return .= '<tr class="tr">
                                    <td class="td">'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($dateReceived, ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['brand_name'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['generic_name'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['dosage'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['stock_received'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['stock_on_hand'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($row['lot_no'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td">'.htmlspecialchars($dateReceived1, ENT_QUOTES, 'UTF-8').'</td>
                                </tr>';
                }
            }else{
                $return .= '<tr><td colspan="10"> No Items</td></tr>';
            }
            $stmt->close();
            return $return;
        } catch (\Exception $th) {
            throw $th;
        }
    }