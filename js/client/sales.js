// IMPORT HTTP REQUEST
import { GET, POST, PUT, DELETE} from '../httpReq.js';
// DOMCONTENTLOADED
document.addEventListener('DOMContentLoaded', ()=>{
    // select search category 
    document.getElementById('searchType')
    .addEventListener('change', (e)=>{
        const input = document.getElementById('inputSearch');
        if(e.target.value == ''){
            input.style.display = 'none';
            input.value = '';
        }else{
            input.style.display = 'flex';
        }
    })
    // search brand name
    document.querySelector('.span-search-text')
    .addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const category = document.getElementById('searchType').value;
        const brandName = document.getElementById('inputSearch').value;
        if(brandName == ''){
            alert('Empty Search Field!');
        }else{
            const url = '../../process/client/searchBrandName.php'
            const data = JSON.stringify({
                category: category,
                brandName: brandName
            })
            const dataType = 'JSON';
            POST(url,data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }
                else if(res.data){
                    document.querySelector('.section-table-wrapper').innerHTML = res.data;
                    document.querySelector('.img-cart-cart-span-wrapper').style.display = 'flex';
                    checkboxChanges();
                }
                else{
                    alert('No Result!');
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    });
    // open item list 
    document.querySelector('.added-item-list-wrapper')
    .addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const itemList = document.querySelector('.list-item-wrapper');
        const blur = document.querySelector('.blur');
        if(itemList.style.display == 'flex'){
            itemList.style.display = 'none';
            blur.style.display = 'none';
        }else{
            itemList.style.display = 'flex';
            blur.style.display = 'flex';
        }
    })
    // close item list 
    document.querySelector('.span-close-btn')
    .addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const itemList = document.querySelector('.list-item-wrapper');
        const blur = document.querySelector('.blur');
        if(itemList.style.display == 'flex'){
            itemList.style.display = 'none';
            blur.style.display = 'none';
        }else{
            itemList.style.display = 'flex';
            blur.style.display = 'flex';
        }
    });
    // display daily sales
    dailySales();
    // show hide menu with burger menu
    document.querySelector('.img-burger-menu')
    .addEventListener('click', ()=>{
        const navi = document.getElementById('nav');
        // console.log(navi);
        if(navi.style.display == 'flex'){
            navi.style.display = 'none';
        }else{
            navi.style.display = 'flex';
        }
    });
    // close nav
    document.getElementById('closeResponsiveNav').addEventListener('click', ()=>{
        const navi = document.getElementById('nav');
        // console.log(navi);
        navi.style.display = 'none';
    })
    // display added to cart item
    addedToCartTableData();
    // CLOSE PROMPT
    closeAdminEditPrompt()
    // DAILY SALES CHANGES
    dailySalesDynamicChange();
})
// check the checkboxes
function checkboxChanges(){
    const checkBoxes = document.querySelectorAll('.checkbox-add-to-cart');
    Array.from(checkBoxes, item => {
        item.addEventListener('change', (e)=>{
            e.stopImmediatePropagation();
            addToCart();
        });
    })
}
// btn addto cart
function addToCart(){
    const checkBoxes = document.querySelectorAll('.checkbox-add-to-cart');
    let idArray = [];
    let arrayEmpty = [];
    const btn = document.querySelector('.img-cart-cart-span-wrapper');
    btn.addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const url = '../../process/client/getAddedItemData.php';
        // let template = '';
        Array.from(checkBoxes, item => {
            if(item.checked){
                let checkedResult = arrayCheckerForCheckedInput(item, idArray);
                if(!checkedResult){
                    idArray.push(item.value);
                }
            }else{
                arrayChecker(idArray, item);
            }
        })
        const data = JSON.stringify(idArray);
        const dataType = 'JSON';
        if(idArray.length == 0){
            alert('Please Select An Item To Add!');
        }else{
            // console.log(idArray);
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    // alert('Successfully Added Items');
                    Array.from(document.querySelectorAll('.input-quantity'), item => {
                        if(item.checked){
                            console.log('true');
                        }else{
                            console.log('false');
                        }
                    });
                    alert(res.status);
                    addedToCartTableData();
                    idArray = [];
                    console.log(idArray);
                    checkboxUnchecker();
                    totalPrice();
                    checkOutItem();
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    })
}
// array checker
function arrayChecker(idArray, item){
    for(let i = 0; i < idArray.length; i++){
        if(idArray[i] == item.value){
            idArray.splice(i, 1);
        }
    }
}
// array checker if the checbox was checked
function arrayCheckerForCheckedInput(item, idArray){
    for(let i = 0; i < idArray.length; i++){
        if(idArray[i] == item.value){
            return true
        }
    }
}
// total price display 
function totalPrice(){
    const inputs = document.querySelectorAll('.input-quantity');
    const totalPriceDisplay = document.getElementById('totalPrice');
    Array.from(inputs, item => {
        const parentElements = item.parentElement;
        let unitPrice = parseFloat(parentElements.previousElementSibling.textContent);
        item.addEventListener('input', (e)=>{
            e.stopImmediatePropagation();
            calPrice(totalPriceDisplay, inputs);
        });
    });
}
// calculate price 
function calPrice(totalPriceDisplay, inputs){
    let totalP = 0;
    Array.from(inputs, input => {
        const parentElements = input.parentElement;
        const unitPrice = parseInt(parentElements.previousElementSibling.textContent);
        totalP += unitPrice * input.value;
    });
    totalPriceDisplay.textContent = totalP + ' ' + 'PHP'; 
}
// checkout added item in the list
function checkOutItem(){
    document.querySelector('.btn-checkout').addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const quantities = document.querySelectorAll('.input-quantity');
        let evaluator = false;
        const quantitiesLength = quantities.length;
        const ids = [];
        const quantitiesArray = [];
        Array.from(quantities, item => {
            const id = item.previousElementSibling.value;
            ids.push(id);
        });
        let pin;
        const getPinUrl = '../../process/client/getClientPin.php';
        GET(getPinUrl)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else{
                pin = res.pin;
                const promptPop = document.getElementById('editAdminPrompt');
                const inputPromptPop = document.getElementById('editAdminProfilePromptInput');
                // OPEN PROMPT
                promptPop.style.display = 'flex';
                const btnPrompt = document.getElementById('btnSubmitAdminProfile');
                // BTN PROMPT SUBMIT 
                btnPrompt.addEventListener('click', (e)=>{
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    if(parseInt(inputPromptPop.value) == parseInt(pin)){
                        // console.log(ids);
                        Array.from(quantities, item => {
                            quantitiesArray.push(item.value);
                        });
                        const url = '../../process/client/checkoutItemsProcess.php';
                        const data = JSON.stringify({
                            ids: ids,
                            quantities: quantitiesArray
                        });
                        const dataType = 'JSON';
                        // POST FOR ADDING THE DATA TO THE TABLE
                        POST(url, data, dataType)
                        .then(response => {
                            if(response.err){
                                throw new Error(response.err);
                            }else if(response.statusFailed){
                                alert(response.statusFailed);
                            }else{
                                alert(response.status);
                                promptPop.style.display = 'none';
                                document.querySelector('.tbody-item-list').innerHTML = '';
                                document.querySelector('.list-item-wrapper').style.display = 'none';
                                document.querySelector('.blur').style.display = 'none';
                                document.getElementById('totalPrice').textContent = '';
                                dailySales();
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Failed to checkout items! Please try again!');
                        });
                    }else{
                        alert('Your Are Not Authorize To Proceed This Transaction!');
                        promptPop.style.display = 'none';
                        // blur.style.display = 'none';
                        inputPromptPop.value = '';
                    }
                });
            }
        })
        .catch(error => {
            console.error(error)
        });
    });
}
// display daily sales 
function dailySales(){
    const url = '../../process/client/getDailySales.php';
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            document.querySelector('.tbody-daily-sales').innerHTML = res.data;
            document.getElementById('totalDailySales').innerHTML = res.total + ' Php';
        }
    })
    .catch(error => {
        console.error(error);
    });
}
// CHECKBOX UNCHECKER
function checkboxUnchecker(){
    const checboxes = document.querySelectorAll('.checkbox-add-to-cart');
    Array.from(checboxes, item => {
        item.checked = false;
    });
}
// GET ADDED TO CART TABLE DATA
function addedToCartTableData(){
    const url = '../../process/client/getAddedToCart.php';
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            document.querySelector('.tbody-item-list').innerHTML = res.data;
            totalPrice();
            checkOutItem();
        }
    })
    .catch(error => {
        console.error(error);
    });
}
// CLOSE EDIT ADMIN PROFILE PROMPT
function closeAdminEditPrompt(){
    const btn = document.getElementById('cancelBtnAdminProfile');
    const promptDiv = document.getElementById('editAdminPrompt');
    const input = document.getElementById('editAdminProfilePromptInput');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        input.value = '';
        promptDiv.style.display = 'none';
    });
}
// DAILY SALES CHANGES IN DATE
function dailySalesDynamicChange(){
    document.getElementById('dailySalesPickDate').addEventListener('change', (e)=>{
        let dateRaw = e.target.value;
        const encodedDate = encodeURIComponent(dateRaw);
        const url = `../../process/client/dailySalesChange.php?date=${encodedDate}`;
        GET(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }
            if(res.data){
                document.querySelector('.tbody-daily-sales').innerHTML = res.data;
                document.getElementById('totalDailySales').innerHTML = res.total + ' Php';
            }
        })
        .catch(error => {
            console.error(error);
            alert('Can\'t Display Daily Sales');
        });
    });
}