<?php
    include_once '../db/conn.php';
    try {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('Server Request Method Not GET');
        }
        // get data
        $expiryData = new Expiration($conn);
        $data = $expiryData->main();
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
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
    // get table data
    class Expiration{
        // connection from db
        private $conn;
        // constructor function
        public function __construct($conn) { 
            $this->conn = $conn;
        }
        // main fn
        public function main() : string{
            $data = $this->fetchDataCurrentMonth();
            return $data;
        }
        // fetcing data from database
        private function fetchDataCurrentMonth() : string {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                // curdate
                $newDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
                $monthNow = $newDate->format('m');
                // return string var
                $return = '';
                // 1st query
                $query = 'SELECT * FROM products
                          WHERE MONTH(expiry_date) = ?
                          ORDER BY id ASC';
                $stmt = $this->conn->prepare($query);
                if(!$stmt){
                    throw new \Exception('fetchDataCurrentMonth() stmt not prepare - ');
                }
                $stmt->bind_param('s', $monthNow);
                $stmt->execute();
                $result1 = $stmt->get_result();
                if($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $newDate = new DateTime($row1['date_received']);
                        $dateReceived = $newDate->format('F j, Y');
                        $newDate1 = new DateTime($row1['expiry_date']);
                        $dateReceived1 = $newDate1->format('F j, Y');
                        $return .= '<tr class="tr">
                                        <td class="td">'.htmlspecialchars($row1['id'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($dateReceived, ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['brand_name'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['generic_name'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['dosage'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['stock_received'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['stock_on_hand'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row1['lot_no'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($dateReceived1, ENT_QUOTES, 'UTF-8').'</td>
                                    </tr>';
                    }
                }else{
                    $return .= '<tr class="tr"><td class="td" colspan="9" style="text-align: center;">No Data...</td></tr>';
                }
                // query 2
                $stmt->close();
                // query next month 
                $stmt2 = $this->conn->prepare('SELECT * FROM products
                                         WHERE MONTH(expiry_date) = ?
                                         ORDER BY id ASC');
                if(!$stmt2){
                    throw new \Exception('fetchDataCurrentMonth() stmt2 not prepare - '
                                         .$this->conn->errno.'/'. $this->conn->error);
                }
                // next month
                $nextMonth = intval($monthNow) + 1;
                $stmt2->bind_param('s', $nextMonth);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){
                        $newDate = new DateTime($row2['date_received']);
                        $dateReceived = $newDate->format('F j, Y');
                        $newDate1 = new DateTime($row2['expiry_date']);
                        $dateReceived1 = $newDate1->format('F j, Y');
                        $return .= '<tr class="tr">
                                        <td class="td">'.htmlspecialchars($row2['id'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($dateReceived, ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['brand_name'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['generic_name'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['dosage'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['stock_received'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['stock_on_hand'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($row2['lot_no'], ENT_QUOTES, 'UTF-8').'</td>
                                        <td class="td">'.htmlspecialchars($dateReceived1, ENT_QUOTES, 'UTF-8').'</td>
                                    </tr>';
                    }
                } else {
                    $return .= '';
                }
                $stmt2->close();
                // commit
                $this->conn->commit();
                if($return == ''){
                    return '<tr class="tr"><td colspan="9" style="text-align: center; font-weight: bold;">No Data...</td></tr';
                }else{
                    return $return;
                }
            } catch (\Throwable $th) {
                $this->conn->rollback();
                throw $th;
            }
        }
    }
    