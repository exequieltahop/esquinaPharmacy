<?php 
    // SESSION
    SESSION_START();
    // DB CONNECTION
    include_once '../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $_SESSION['pageNo'] = 1;
        $limitStart = 0;
        $data = previousPage($limitStart, $conn);
        header('Content-Type: application/json');
        echo json_encode(['data' => $data,
                          'curpage' => $_SESSION['pageNo']
                        ]);
    } catch (\Throwable $th) {
        if($th instanceof \Exception || $th instanceof \Error) {
            header('Content-Type: application/json');
            echo json_encode(['err' => $th->getMessage()]);
        }
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    function previousPage(int $limitStart, mysqli $conn) : string {
        try {
            $return = '';
            $query = 'SELECT  * FROM products
                      ORDER BY '.$_SESSION['sortBy'].' '.$_SESSION['sortType'].'
                      LIMIT '.$limitStart.', 20';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('nextPage() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
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
                $stmt->close();
                throw new Exception('There\'s No Row Result');
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>