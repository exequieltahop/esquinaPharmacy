<?php
    // SESSION START
    SESSION_START();
    // DB CONNECTION
    include_once '../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new Exception('Server Request Method not GET');
        }
        $pageNo = $_SESSION['pageNo'];
        if($pageNo == 1){
            $pageLimitStart = $pageNo - 1;
        }else{
            $pageLimitStart = ($pageNo - 1) * 5;
        }
        $pageNo = 1;
        $res = getTableData($_SESSION['sortBy'], $_SESSION['sortType'], $pageLimitStart, $conn);
        $pages = getPages($conn);
        // echo $res;
        header('Content-Type: application/json');
        echo json_encode(['data'=>$res, 
                          'pages' => $pages, 
                          'curpage' => $_SESSION['pageNo']]);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // GET TABLE DATA
    function getTableData(string $sortBy, 
                          string $sortType, 
                          int $page,
                          mysqli $conn) : string {
        try {
            $return = '';
            $stmt =  $conn->prepare('SELECT * FROM products
                                     ORDER BY '.$sortBy.' '.$sortType.' LIMIT '.$page.', 20');
            if(!$stmt){
                throw new Exception('getTableData( stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows < 1){
                $return .= '<tr><td colspan="13" style="text-align: center;"><h1>No Data</h1></td></tr>';
            }else{
                while($row = $res->fetch_assoc()){
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
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
    // GET PAGES NUMBER
    function getPages(mysqli $conn) : int {
        try {
            $stmt = $conn->prepare('SELECT * FROM products');
            if(!$stmt){
                throw new Exception('getPages() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCount = $result->num_rows;
            $pageNumber = ceil($rowCount / 20);
            $stmt->close();
            return $pageNumber;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>