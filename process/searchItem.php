<?php
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Not POST');
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $item = $data['item'];
        $type = $data['category'];
        $data = getData($item, $type, $conn);
        // echo $data;
        if($data == 'No Result'){
            header('Content-Type: application/json');
            echo json_encode(['emptyData' => 'no result']);    
        }else{
            header('Content-Type: application/json');
            echo json_encode(['data' => $data]);
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=> $th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // get item in db
    function getData(string $item, string $type, mysqli $conn) : string {
        try {
            
            $return = '';
            $sanitizeType = trim(htmlspecialchars($type, ENT_QUOTES, 'UTF-8'));
            if($type == 'expiry_date'){
                $query = 'SELECT * FROM products
                          WHERE MONTH(expiry_date) = ?';
                $intValMonth = intval($item);
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $intValMonth);
                // return '1';
            }else{
                $query = 'SELECT * FROM products
                          WHERE '.$sanitizeType.' = ?';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $item);
                // return '2';
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    // date recieved
                    $newDateRecieved = new DateTime($row['date_received']);
                    $dateRecieved = $newDateRecieved->format('M. d, Y');
                    // expiry date
                    $newExpiryDate = new DateTime($row['expiry_date']);
                    $dateExpiry = $newExpiryDate->format('M. d, Y');
                    $return .= '<tr class="tr">
                                    <td class="td">'
                                        .htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($dateRecieved, ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['brand_name'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['generic_name'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['dosage'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['stock_received'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['stock_on_hand'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['lot_no'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($dateExpiry, ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['retail_price'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td">'
                                        .htmlspecialchars($row['prescription'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td-action">
                                        <img src="../assets/edit.png" alt="edit" class="img-edit-icon" data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                        <img src="../assets/trash.png" alt="delete" class="img-delete-icon" data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                    </td>
                                </tr>';
                }
            }else{
                $stmt->close();
                return 'No Result';
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>