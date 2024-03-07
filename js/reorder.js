import {GET, POST, PUT, DELETE} from './httpReq.js';
document.addEventListener('DOMContentLoaded', ()=>{
    // display table data
    getTableData();
});
// get table data
function getTableData() {
    const tbody = document.getElementById('tbody');
    const url = '../process/getReorderTableData.php';
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            tbody.innerHTML = res.data;
        }
    })
    .catch(error => {
        console.error(error);
    })
}