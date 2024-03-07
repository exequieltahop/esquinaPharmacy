import { GET, POST, PUT, DELETE} from './httpReq.js';
document.addEventListener('DOMContentLoaded', ()=>{
    // display table data
    getTableData();
    // dynamic changes in the label 
    dynamicChangesLabel();
    // open add form
    document.querySelector('.btn-add-item')
    .addEventListener('click', (e)=>{
        const form = document.querySelector('.add-item-form');
        const blur = document.querySelector('.blur');
        if(form.style.display == 'flex'){
            form.style.display = 'none';
            blur.style.display = 'none';
        }else{
            form.style.display = 'flex';
            blur.style.display = 'flex';
        }
    });
    // close add item form
    document.querySelector('.img-close-add-form')
    .addEventListener('click', (e)=>{
        const form = document.querySelector('.add-item-form');
        const blur = document.querySelector('.blur');
        if(form.style.display == 'flex'){
            form.style.display = 'none';
            blur.style.display = 'none';
        }else{
            form.style.display = 'flex';
            blur.style.display = 'flex';
        }
    });
    // clear all input value in the form add 
    document.querySelector('.btn-clear')
    .addEventListener('click', (e)=>{
        const inputs = document.querySelectorAll('.input-item');
        Array.from(inputs, item => {
            item.value = '';
            item.previousElementSibling.style.top = '50%';
        });
    });
    // add new Item
    document.getElementById('addItemBtn')
    .addEventListener('click', (e)=>{
        e.preventDefault();
        const brandName = document.getElementById('brandName');
        const genericName = document.getElementById('genericName');
        const dosage = document.getElementById('dosage');
        const stockRecieved = document.getElementById('stockRecieved');
        const lotNo = document.getElementById('lotNo');
        const expiryDate = document.getElementById('expiryDate');
        const price = document.getElementById('price');
        const dateRecieved = document.getElementById('dateRecieved');
        const retailPrice = document.getElementById('retailPrice');
        const prescription = document.getElementById('prescription');
        const threshold = document.getElementById('threshold');
        if(brandName.value == '' || 
           genericName.value == '' || 
           dosage.value == '' ||
           stockRecieved.value == '' ||
           lotNo.value == '' ||
           expiryDate.value == '' ||
           price.value == '' ||
           dateRecieved.value == '' ||
           retailPrice.value == '' ||
           prescription.value == '' || 
           threshold.value == ''){
            alert('Please Don\'t Leave The Important Field Empty!');
        }else{
            const url = '../process/addItemProcess.php';
            const data = JSON.stringify({
                brandName: brandName.value,
                genericName: genericName.value,
                dosage: dosage.value,
                stockRecieved: stockRecieved.value,
                lotNo: lotNo.value,
                expiryDate: expiryDate.value,
                price: price.value,
                dateRecieved: dateRecieved.value,
                retailPrice: retailPrice.value,
                prescription: prescription.value,
                threshold: threshold.value
            });
            const dataType = 'JSON';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    alert(res.status);
                    Array.from(document.querySelectorAll('.input-item'), item => {
                        item.value = '';
                    });
                    document.querySelector('.blur').style.display = 'none';
                    document.querySelector('.add-item-form').style.display = 'none';
                    getTableData();
                }
            })
            .catch(error => {
                console.error(error);
                alert('Unable to Add Item! Please Try Again!');
            });
        }
    });
    // close edit form
    document.querySelector('.img-close-edit-form')
    .addEventListener('click', (e)=>{
        const form = document.querySelector('.edit-item-form');
        const blur = document.querySelector('.blur');
        const inputs = document.querySelectorAll('.edit-input-item');
        if(form.style.display == 'flex'){
            form.style.display = 'none';
            blur.style.display = 'none';
            Array.from(inputs, item => {
                item.value = '';
                item.previousElementSibling.style.top = '50%';
                item.previousElementSibling.style.fontSize = '0.9rem';
            });
            document.getElementById('itemId').value = '';
        }else{
            form.style.display = 'flex';
            blur.style.display = 'flex';
        }
    });
    // sort data
    sortDataTable();
    //  search category input and open input search
    $('#searchType').addEventListener('change', (e)=>{
        if(e.target.value != ''){
            if(e.target.value == 'expiry_date'){
                $('#inputSearch').type = 'month';
                const month = document.createElement('select');
                month.className = 'input-search';
                month.id = 'selectMonth';
                let monthArr = [
                                'January', 
                                'February',
                                'March',
                                'April',
                                'May',
                                'June',
                                'July',
                                'August',
                                'September',
                                'October',
                                'November',
                                'December'
                            ];
                // loop the month while adding the option in the select tag
                for(let i = 0; i < 12; i++){
                    const option = document.createElement('option');
                    option.value = i + 1;
                    option.textContent = monthArr[i];
                    month.appendChild(option);
                }
                // add the month to the parent element
                $('.search-wrapper').appendChild(month);
                month.style.display = 'flex';
                //remove last child
                $('#inputSearch').style.display = 'none';
            }else{
                // const monthNow = $('#selectMonth');
                if($('.search-wrapper').contains($('#selectMonth'))){
                    $('.search-wrapper').removeChild($('#selectMonth'));
                }
                $('#inputSearch').type = 'text';
                $('#inputSearch').style.display = 'flex';
            }
        }else{
            if($('.search-wrapper').contains($('#selectMonth'))){
                $('.search-wrapper').removeChild($('#selectMonth'));
            }
            $('#inputSearch').style.display = 'none';
            $('#inputSearch').value = '';
        }
    });
    // search an item
    $('.span-search-text').addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const category = $('#searchType').value;
        let item;
        if(category == 'expiry_date'){
            item = $('#selectMonth').value;
        }else{
            item = $('#inputSearch').value;
        }
        if(item == ''){
            alert('Search Field is Empty!');
        }else{
            const data = JSON.stringify({
                item: item,
                category: category
            });;
            const url = '../process/searchItem.php';
            const dataType = 'JSON';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    if(res.emptyData){
                        alert('No Data Found!')
                    }else{
                        document.querySelector('.tbody').innerHTML = res.data;
                        deleteItem();
                        editItem();
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    });
    // show hide menu with burger menu
    document.querySelector('.img-burger-menu').addEventListener('click', ()=>{
        const navi = document.querySelector('.nav');
        if(navi.style.display == 'flex'){
            navi.style.display = 'none';
        }else{
            navi.style.display = 'flex';
        }
    });
    // NEXT PAGE
    nextPage();
    // PREVIOUS PAGE
    previousPage();
    // FIRST PAGE
    firstPage();
    // LAST PAGE
    lastpage();
    // CANCEL EDIT PROMPT
    closeEditSalesmanAccountPrompt();
    // OPEN EDIT PROMPT
    editbtnClick();
});
// display table data
function getTableData(){
    const url = '../process/tableDataProcess.php';
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            if(res.data){
                // alert('okay');
                document.querySelector('.tbody').innerHTML = res.data;
                document.querySelector('.span-page-no').textContent = res.pages;
                document.querySelector('.btn-span-cur-page').textContent = res.curpage;
                // delete item
                deleteItem();
                // edit item form open
                editItem();
            }
        }
    })
    .catch(error => {
        console.error(error);
    });
    
}
// dynamic changes in the label
function dynamicChangesLabel(){
    // add item form input
    const inputs = document.querySelectorAll('.input-item');
    Array.from(inputs, item => {
        const label = item.previousElementSibling;
        item.addEventListener('focus', ()=>{
            label.style.top = '0';
            label.style.fontSize = '0.7rem';
        });
        item.addEventListener('blur', (e)=>{
            if(e.target.value == ''){
                label.style.top = '50%';
                label.style.fontSize = '0.9rem';
            }
        });
    });
    // edit form input
    const inputsEdit = document.querySelectorAll('.edit-input-item');
    Array.from(inputsEdit, item => {
        const label = item.previousElementSibling;
        item.addEventListener('focus', ()=>{
            label.style.top = '0';
            label.style.fontSize = '0.7rem';
        });
        item.addEventListener('blur', (e)=>{
            if(e.target.value == ''){
                label.style.top = '50%';
                label.style.fontSize = '0.9rem';
            }
        });
    });
}
// delete item
function deleteItem(){
    const deleteBtn = document.querySelectorAll('.img-delete-icon');
    Array.from(deleteBtn, item => {
        item.addEventListener('click', (e)=>{
            e.stopImmediatePropagation();
            const id = e.target.getAttribute('data-record-id');
            const con = confirm('Do You Want To Delete This Item?');
            if(con){
                const encodeId = encodeURIComponent(id);
                const url = `../process/deleteItem.php?id=${encodeId}`;
                DELETE(url)
                .then(res => {
                    if(res.err){
                        throw new Error(res.err);
                    }else{
                        alert(res.status);
                        getTableData();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed To Delete Item! Please Try Again!');
                });
                // alert(encodeId);
            }
        });
    });
}
// edit item 
function editItem(){
    const editBtn = document.querySelectorAll('.img-edit-icon');
    Array.from(editBtn, btn => {
        btn.addEventListener('click', (e)=>{
            e.stopImmediatePropagation();
            const id = e.target.getAttribute('data-record-id');
            const editForm = document.querySelector('.edit-item-form');
            const blur = document.querySelector('.blur');
            const encodeId = encodeURIComponent(id);
            const url = `../process/getEditItemDataProcess.php?id=${encodeId}`;
            const prompt = document.getElementById('editSalesmanPrompt');
            const inputPrompt = document.getElementById('editSalesmanPromptInput');
            GET(url)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    prompt.style.display = 'flex';
                    blur.style.display = 'flex';
                    // const con = prompt('ENTER PIN: ');
                    let securityPin;
                    const urlSecPin = '../process/getSecurityPinForEditingItem.php';
                    GET(urlSecPin)
                    .then(response => {
                        if(response.err){
                            throw new Error(response.err);
                        }
                        if(response.data){
                            securityPin = response.data;
                            document.getElementById('submitBtnEditSalesmanPrompt').addEventListener('click', ()=>{
                                if(isNaN(inputPrompt.value)){
                                    alert('Not A Valid Security Pin');
                                }else if(parseInt(inputPrompt.value) == securityPin){
                                    prompt.style.display = 'none';
                                    inputPrompt.value = '';
                                    editForm.style.display = 'flex';
                                    blur.style.display = 'flex';
                                    document.getElementById('editBrandName').value = res.brandName;
                                    document.getElementById('editgenericName').value = res.genericName;
                                    document.getElementById('editdosage').value = res.dosage;
                                    document.getElementById('editstockRecieved').value = res.stockRecieved;
                                    document.getElementById('editdateRecieved').value = res.dateRecieved;
                                    document.getElementById('editlotNo').value = res.lotNo;
                                    document.getElementById('editexpiryDate').value = res.expiryDate;
                                    document.getElementById('editprice').value = res.price;
                                    document.getElementById('editretailPrice').value = res.retailPrice;
                                    document.getElementById('editstockOnHand').value = res.stockOnHand;
                                    document.getElementById('editPrescription').value = res.prescription;
                                    document.getElementById('itemId').value = id;
                                    Array.from(document.querySelectorAll('.edit-input-label'), item => {
                                        item.style.top = '0';
                                        item.style.fontSize = '0.7rem';
                                    });
                                    editBtnClick(editForm, blur);
                                }else{
                                    alert('Wrong Security Pin!');
                                }
                            });
                        }
                    })
                    .catch(error2 => {
                        console.error(error2);
                        alert('Can\'t Get Security Pin');
                    })
                    
                }
            })
            .catch(error => {
                console.error(error);
            });
            
        });
    });
}
// edit btn click 
function editBtnClick(editForm, blur){
    document.getElementById('editItemBtn')
    .addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        e.preventDefault();
        const id = document.getElementById('itemId');
        const editBrandName = document.getElementById('editBrandName');
        const editgenericName = document.getElementById('editgenericName');
        const editdosage = document.getElementById('editdosage');
        const editstockRecieved = document.getElementById('editstockRecieved');
        const editstockOnHand = document.getElementById('editstockOnHand');
        const editlotNo = document.getElementById('editlotNo');
        const editexpiryDate = document.getElementById('editexpiryDate');
        const editprice = document.getElementById('editprice');
        const editdateRecieved = document.getElementById('editdateRecieved');
        const editretailPrice = document.getElementById('editretailPrice');
        const editPrescription = document.getElementById('editPrescription');
        const url = '../process/editItemProcess.php';
        const data = JSON.stringify({
            id: id.value,
            editBrandName: editBrandName.value,
            editgenericName: editgenericName.value,
            editdosage : editdosage.value,
            editstockRecieved : editstockRecieved.value,
            editstockOnHand : editstockOnHand.value,
            editlotNo : editlotNo.value,
            editexpiryDate : editexpiryDate.value,
            editprice : editprice.value,
            editdateRecieved : editdateRecieved.value,
            editretailPrice : editretailPrice.value,
            editPrescription : editPrescription.value,
        });
        const dataType = 'JSON';
        PUT(url, data, dataType)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else{
                alert(res.status);
                Array.from(document.querySelectorAll('.edit-input-item'), item => {
                    item.value = '';
                });
                document.getElementById('itemId').value = '';
                editForm.style.display = 'none';
                blur.style.display = 'none';
                getTableData();
            }
        })
        .catch(error => {
            console.error(error);
            alert('Unable to Change Item!');
        })
    });
}
// jquery alike 
function $(e){
    return document.querySelector(e);
}
// sort data function
function sortDataTable(){
    const sortIcon = document.querySelectorAll('.sort-icon');
    Array.from(sortIcon, icon => {
        icon.addEventListener('click', (e)=>{
            const descIcon = icon.nextElementSibling;
            e.stopImmediatePropagation();
            let sortData = e.target.getAttribute('data-record-id');
            const sortType = e.target.previousElementSibling.value;
            const url = '../process/sortDataProcess.php';
            const data = JSON.stringify({
                sortData : sortData,
                sortType : sortType
            });
            const dataType = 'JSON';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    icon.style.display = 'none';
                    descIcon.style.display = 'flex';
                    document.querySelector('.tbody').innerHTML = res.data;
                    sortDataTableDesc();
                    deleteItem();
                    editItem();
                }
            })
            .catch(error => {
                console.error(error);
            });
        });
    });
}
// sort desc
function sortDataTableDesc(){
    const sortIcon = document.querySelectorAll('.sort-icon-desc');
    Array.from(sortIcon, icon => {
        const ascIcon = icon.previousElementSibling;
        icon.addEventListener('click', (e)=>{
            e.stopImmediatePropagation();
            let sortData = e.target.getAttribute('data-record-id');
            const elem = e.target.previousElementSibling;
            const sortType = elem.previousElementSibling.value;
            const url = '../process/sortDataProcess.php';
            const data = JSON.stringify({
                sortData : sortData,
                sortType : sortType
            });
            const dataType = 'JSON';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    ascIcon.style.display = 'flex';
                    icon.style.display = 'none';
                    document.querySelector('.tbody').innerHTML = res.data;
                    sortDataTable();
                    deleteItem();
                    editItem();
                }
            })
            .catch(error => {
                console.error(error);
            });
        });
    });
}
// NEXT BTN IN PAGANATION
function nextPage(){
    document.querySelector('.btn-next').addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        e.preventDefault();
        const url = '../process/nextPage.php';
        // alert(url);
        GET(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }
            if(res.data){
                document.querySelector('.tbody').innerHTML = res.data;
                document.querySelector('.btn-span-cur-page').textContent = res.curpage;
            }
            if(res.statusErr){
                alert(res.statusErr);
            }
        })
        .catch(error => {
            console.error(error);
            alert('Can\'t Get next Page');
        });
    });
}
// PREVIOUS PAGE
function previousPage(){
    document.querySelector('.btn-prev').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const url = '../process/previousPage.php';
        GET(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }
            if(res.data){
                document.querySelector('.tbody').innerHTML = res.data;
                document.querySelector('.btn-span-cur-page').textContent = res.curpage;
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ca\'t Get Previous Page!');
        });
    });
}
// FIRST PAGE REDIRECT
function firstPage(){
    document.querySelector('.btn-first-page').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const url = '../process/firstPage.php';
        GET(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }
            if(res.data){
                document.querySelector('.tbody').innerHTML = res.data;
                document.querySelector('.btn-span-cur-page').textContent = res.curpage;
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ca\'t Get Previous Page!');
        });
    });
}
// LAST PAGE
function lastpage(){
    document.querySelector('.btn-last-page').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const url = '../process/lastPage.php';
        GET(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }
            if(res.data){
                document.querySelector('.tbody').innerHTML = res.data;
                document.querySelector('.btn-span-cur-page').textContent = res.curpage;
            }
        })
        .catch(error => {
            console.error(error);
            alert('Ca\'t Get Previous Page!');
        });
    });
}
// EDIT SALESMAN PROFILE
function editbtnClick(){
    const editbtns = document.querySelectorAll('.edit-icon');
    const blur = document.querySelector('.blur');
    const promptForm = document.getElementById('editSalesmanPrompt');
    const urlGetPin = '../process/getAdminPin.php';
    Array.from(editbtns, item => {
        const id = item.getAttribute('data-record-id');
        item.addEventListener('click', (e) => {
            e.stopImmediatePropagation();
            blur.style.display = 'flex';
            promptForm.style.display = 'flex';
            promptForm.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'start'
            });
            getAdminSecPinForSalesman(urlGetPin, id, blur, promptForm);
        });
    });
}
// CLOSE EDIT SALESMAN ACCOUNT PROMPT
function closeEditSalesmanAccountPrompt(){
    const btn = document.getElementById('cancelBtnEditSalesmanAcc');
    const promptDiv = document.getElementById('editSalesmanPrompt');
    const input = document.getElementById('editSalesmanPromptInput');
    const blur = document.querySelector('.blur');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        input.value = '';
        promptDiv.style.display = 'none';
        blur.style.display = 'none';
    });
}


