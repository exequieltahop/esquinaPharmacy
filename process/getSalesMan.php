<?php
    include_once '../db/conn.php';
    require '../cipher/cipher.php';
    try {
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            throw new \Exception('Server Request Method Not GET!');
        }
        // echo 1;
        $data = fetchDataInDb($conn);
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
    function fetchDataInDb(mysqli $conn) : string {
        try {
            $return = '';
            $query = 'SELECT * FROM user_table
                      WHERE position = "sale_man"
                      ORDER BY timestamp ASC';
            $stmt = $conn->prepare($query);
            if(!$stmt){
                throw new \Exception('fetchDataInDb stmt not prepare - '
                                      .$conn->errno.'/'.$conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $decryptedPassword = Decipher($row['password'], 'esquina');
                    $newDate = new DateTime($row['timestamp']);
                    $registeredDate = $newDate->format('F j, Y h:i A');  
                    $return .= '<tr class="tr">
                                    <td class="td">'.$row['full_name'].'</td>
                                    <td class="td">'.$row['username'].'</td>
                                    <td class="td pass-td">'.$decryptedPassword.'</td>
                                    <td class="td sec-pin">'.$row['security_pin'].'</td>
                                    <td class="td">'.$registeredDate.'</td>
                                    <td class="td">
                                        <div class="action-wrapper">
                                            <img src="../assets/edit.png" alt="edit" class="action-icon edit-icon"
                                            data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                            <img src="../assets/trash.png" alt="edit" class="action-icon delete-icon"
                                            data-record-id="'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'">
                                        </div>
                                    </td>
                                </tr>';
                }
            }else{
                $return .= '<tr class="tr"><td class="td" colspan="6" style="text-align: center; font-weight: bold;">No Data...</td></tr>';
            }
            $stmt->close();
            return $return;
        } catch (\Exception $th) {
            throw $th;
        }
    }
