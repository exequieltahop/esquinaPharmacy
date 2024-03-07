<?php 
    // DB CONNECTION
    include_once '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        $data =  getTableData($conn);
        header('Content-Type: application/json');
        echo json_encode(['data'=> $data]);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // GET TABLE DATA
    function getTableData(mysqli $conn) : string {
        try {
            $return = '';
            $stmt = $conn->prepare('SELECT added_to_cart_item.date_received AS date_received,
                                           added_to_cart_item.brand_name AS bname,
                                           added_to_cart_item.generic_name AS gname,
                                           added_to_cart_item.price AS price,
                                           products.stock_on_hand AS stock,
                                           added_to_cart_item.id AS id
                                    FROM added_to_cart_item
                                    INNER JOIN products
                                    ON added_to_cart_item.base_id = products.id
                                    WHERE status = "Unchecked Out"');
            if(!$stmt){
                throw new Exception('getTableData() stmt not prepared - '
                                    .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $newDateReceived = new DateTime($row['date_received']);
                    $dateReceived = $newDateReceived->format('M. d, Y');
                    $return .= '<tr class="tr-item-list">
                                    <td class="td-item-list">'.htmlspecialchars($dateReceived, ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td-item-list">'.htmlspecialchars($row['bname'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td-item-list">'.htmlspecialchars($row['gname'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td-item-list">'.htmlspecialchars($row['stock'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td-item-list">'.htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8').'</td>
                                    <td class="td-item-list">
                                        <input type="hidden" value="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'"
                                        class="hidden-id-for-checkout">
                                        <input type="number" class="input-quantity" min="0" value="0" 
                                        max="'.htmlspecialchars($row['stock'], ENT_QUOTES, 'UTF-8').'">
                                    </td>   
                                </tr>';  
                }
            }else{
                $return .= '<tr class="tr-item-list">
                                <td class="td-item-list" colspan="6" style="text-align: center;"><h1>No Data</h1></td>
                            </tr>';
            }
            $stmt->close();
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>