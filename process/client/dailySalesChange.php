<?php 
    // SESSION
    session_start();
    // DB CONNECTION
    include_once '../../db/conn.php';
    // MAIN
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new \Exception('Server Request Method Not GET!');
        }
        $date = urldecode($_GET['date']);
        $data = fetchDataDailySales($_SESSION['username'], $date, $conn);
        header('Content-Type: application/json');
        echo json_encode(['data' => $data['data'],
                           'total' => $data['total']
                        ], JSON_PRETTY_PRINT);
    } catch (Exception $th) {
        header('Content-Type: application/json');
        echo json_encode(['err'=>$th->getMessage()]);
    } finally {
        if(isset($conn)){
            $conn->close();
        }
    }
    // GET DAILY SALES
    function fetchDataDailySales(string $username, string $date, mysqli $conn) : array {
        try {
            $totalPrice = 0;
            $return = '';
            $stmt = $conn->prepare('SELECT * FROM sales
                                    WHERE timestamp = ?
                                    AND BINARY seller = ?
                                    ORDER BY id ASC');
            if(!$stmt){
                throw new \Exception('fetchDataDailySales() stmt not prepare - '
                                      .$conn->errno.'/'.$conn->error);
            }
            $stmt->bind_param('ss', $date, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $totalPrice += $row['total_price'];
                    $return .= '<tr class="tr-daily-sales">
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['brand_name'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['generic_name'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['total_price'], ENT_QUOTES, 'UTF-8').
                                    ' Php
                                    </td>
                                    <td class="td-daily-sales">'
                                        .htmlspecialchars($row['seller'], ENT_QUOTES, 'UTF-8').
                                    '</td>
                                </tr>';
                }
            }else{
                $return .= '<tr class="tr-daily-sales">
                                <td class="td-daily-sales" colspan="6" style="text-align: center; color: rgb(32, 32, 32)">
                                    <h1>No Data Yet!</h1>
                                </td>
                            </tr>';
            }
            $stmt->close();
            return [
                    'data' => $return,
                    'total' => $totalPrice
                    ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>