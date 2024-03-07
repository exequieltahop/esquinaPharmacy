<?php
    include_once '../../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            throw new Exception('Server Request Method Not POST');
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $category = $json['category'];
        $brandName = $json['brandName'];
        $data = fetchData($category, $brandName, $conn);
        if($data == 'none'){
            header('Content-Type: application/json');
            echo json_encode(['none'=>'']);
            // echo 'none';
        }else{
            header('Content-Type: application/json');
            echo json_encode(['data'=>$data]);
            // echo 'ok';
        }
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // fetch data
    function fetchData(string $category, string $brandName, mysqli $conn) : string {
        try {
            $filteredCategory = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
            $filteredBrandName = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');
            $wildCardItem = $filteredBrandName.'%';
            $return = '';
            $query = 'SELECT id,
                             date_received, 
                             brand_name, 
                             generic_name, 
                             stock_on_hand,
                             retail_price,
                             prescription
                      FROM products
                      WHERE '.$filteredCategory.' LIKE "'.$wildCardItem.'"
                      ORDER BY date_received ASC';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new Exception('fetchData() stmt not prepare - '
                                    .$conn->errno.'/'.$conn->error);
            }
            // $stmt->bind_param('s', $wildCardItem);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $return .=  '<table class="table">
                                <thead class="thead">
                                    <th class="th">Date Receive</th>
                                    <th class="th">
                                        Brand Name
                                    </th>
                                    <th class="th">
                                        Generic Name
                                    </th>
                                    <th class="th">
                                        Stock On Hand
                                        <div class="th-textContent-sort-icon-wrapper">
                                            <span></span>
                                        </div>
                                    </th>
                                    <th class="th">Retail Price</th>
                                    <th class="th">Prescription</th>
                                    <th class="th">Action</th>
                                </thead>
                                <tbody class="tbody">';
                while($row = $result->fetch_assoc()) { 
                    // date recieveds
                    $newDateRecieved = new DateTime($row['date_received']);
                    $dateRecieved = $newDateRecieved->format('M. d, Y');
                    if($row['stock_on_hand'] == 0){
                        $return .= '<tr class="tr">
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
                                            .htmlspecialchars($row['stock_on_hand'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td">'
                                            .htmlspecialchars($row['retail_price'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td">'
                                            .htmlspecialchars($row['prescription'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td-action">
                                            <input type="checkbox" 
                                            value="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'"
                                            class="checkbox-add-to-cart" disabled>
                                        </td>
                                    </tr>';
                    }else{
                        $return .= '<tr class="tr">
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
                                            .htmlspecialchars($row['stock_on_hand'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td">'
                                            .htmlspecialchars($row['retail_price'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td">'
                                            .htmlspecialchars($row['prescription'], ENT_QUOTES, 'UTF-8').
                                        '</td>
                                        <td class="td-action">
                                            <input type="checkbox" 
                                            value="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'"
                                            class="checkbox-add-to-cart">
                                        </td>
                                    </tr>';
                    }
                    
                }
                $return .= '</tbody>
                        </table>';
            }else{
                $return .= 'none';
            }
            $stmt->close();
            return $return;
        } catch (Exception $th) {
            throw $th;
        }
    }
?>