<?php 
    // SESSION START 
    SESSION_START();
    // DB CONN
    include_once '../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Method Not POST');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $sortData = $json['sortData'];
        $sortType = $json['sortType'];
        // $sortData = 'ASC';
        // $sortType = 'brand_name';
        $data = getSortedData($sortData, $sortType, $conn);
        header('Content-Type: application/json');
        echo json_encode(['data'=>$data]);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // GET SORTED DATA
     function getSortedData(string $sortData, string $sortType, mysqli $conn) : string {
        try {
            $return = '';
            $filteredSortData = htmlspecialchars($sortData, ENT_QUOTES, 'UTF-8');
            $filteredSortType = htmlspecialchars($sortType, ENT_QUOTES, 'UTF-8');
            $query = 'SELECT * FROM products
                      ORDER BY '.$filteredSortType.' '.$filteredSortData.' LIMIT 0, 20';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('getSortedData() stmt not prepare - '
                                     .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
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
                                        .htmlspecialchars($row['threshold'], ENT_QUOTES, 'UTF-8').
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
                                        <img src="../assets/edit.png" alt="edit" class="img-edit-icon" 
                                        data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                        <img src="../assets/trash.png" alt="delete" class="img-delete-icon" 
                                        data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                    </td>
                                </tr>';
                }
            }else{
                $return .= '<tr class="tr">
                                <td class="td" style="text-align: center;"><h1>No Data...</h1></td>
                           </tr>';
            }
            $_SESSION['sortBy'] = $filteredSortType;
            $_SESSION['sortType'] = $filteredSortData;
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
     }
?>