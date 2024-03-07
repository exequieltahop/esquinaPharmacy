// IMPORT HTTP REQUEST METHODS
import {GET, POST, PUT, DELETE} from './httpReq.js';
// DOMCONTENTLOADED
document.addEventListener('DOMContentLoaded', ()=>{
    // display table data
    const data = new Data();
    data.getTableData();
    data.displayTableDataExpiration();
    // show hide menu with burger menu
    document.querySelector('.img-burger-menu')
    .addEventListener('click', ()=>{
        const navi = document.querySelector('.nav');
        if(navi.style.display == 'flex'){
            navi.style.display = 'none';
        }else{
            navi.style.display = 'flex';
        }
    });
});
// DATA CLASS
class Data{
    getTableData() {
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
    // display the table data for the expiration
    displayTableDataExpiration(){
        const url = '../process/getExpirationTableData.php';
        const tbody = document.querySelector('.near-expired-tbody');
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
            alert('Expiration Table Data Can\'t Fetch In The Database');
        });
    }
}