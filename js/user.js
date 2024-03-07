// IMPORT HTTP REQUEST METHODS
import {GET, POST, PUT, DELETE} from './httpReq.js';
// MAIN CONTENT
document.addEventListener('DOMContentLoaded', ()=>{
    // display the data for the tble
    fetchTableData();
    // init labelTransitioning()
    labelTransitioning();
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
    // display admin data
    getAdminData();
    // EDIT BTN CLICK FOR UPDATE PROFILE
    document.querySelector('.img-edit-icon').addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        e.preventDefault();
        const btnWrapper = document.querySelector('.btn-changes-wrapper');
        const inputs = document.querySelectorAll('.input-profile');
        Array.from(inputs, item => {
            item.readOnly = false;
        })
        btnWrapper.style.display = 'flex';
        btnWrapper.style.opacity = '100%';
        document.querySelector('.btn-save').disabled = false;
        document.querySelector('.my-profile-h1').textContent = 'Update My Profile';
    });
    // cancel btn click
    document.querySelector('.btn-cancel').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const inputs = document.querySelectorAll('.input-profile');
        const btnWrapper = document.querySelector('.btn-changes-wrapper');
        Array.from(inputs, item => {
            item.readOnly = true;
        })
        btnWrapper.style.display = 'none';
        document.querySelector('.my-profile-h1').textContent = 'My Profile';
    });
    // MAKE AN ACCOUNT FOR MANINDAHAY
    document.querySelector('.btn-create').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const name = document.getElementById('createAccName');
        const uname = document.getElementById('createAccUName');
        const pass = document.getElementById('createAccPassword');
        const pin = document.getElementById('createAccSecPin');
        if(name.value == '' ||
           uname.value == '' ||
           pass.value == '' ||
           pin.value == ''){
            alert('Don\'t Leave The Imporatant Field Empty!');
        } else if(isNaN(pin.value)){
            alert('Pin is not a number!');
        } else{
            const url = '../process/makeAnAccount.php';
            const data = JSON.stringify({
                name: name.value,
                uname: uname.value,
                pass: pass.value,
                pin: pin.value
            });
            const dataType = 'JSON';
            createAccount(url, data, dataType);
        }
    });
    // CLOSE ADMIN PROMPT
    closeAdminEditPrompt();
    // CLOSE PROMPT FOR DELETE ACCOUNT FOR SALESMAN
    closeDeleteSalesmanAccountPrompt();
    // EDIT SALESMAN ACCOUNT PROMPT CLOSE
    closeEditSalesmanAccountPrompt();
});
// get table data for the table 
function fetchTableData(){
    const url = '../../process/getSalesMan.php';
    const tbody = document.querySelector('.tbody');
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            tbody.innerHTML = res.data;
            editbtnClick();
            hider();
            // INIT DELETE ICON
            deleteAccount();
        }
    })
    .catch(error => {
        console.error(error);
        alert('Something Went Wrong in Fetching the Data in the Database!');
    });
}
// lbl transitioning 
function labelTransitioning(){
    const inputs = document.querySelectorAll('.edit-input');
    Array.from(inputs, input => {
        const label = input.previousElementSibling;
        if(input.value != ''){
            label.style.top = '-5px';
            label.style.transform = 'none';
            label.style.fontSize = '0.8rem';
        }
        input.addEventListener('focus', (e)=>{
            if(e.target.value == ''){
                label.style.top = '-5px';
                label.style.transform = 'none';
                label.style.fontSize = '0.8rem';
            }
        });
        input.addEventListener('blur', (e)=>{
            if(e.target.value == ''){
                label.style.top = '50%';
                label.style.transform = 'translateY(-50%)';
                label.style.fontSize = '1rem';
            }
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
// make the password and security pin to * char
function hider(){
    const secPin = document.querySelectorAll('.sec-pin');
    const pasword = document.querySelectorAll('.pass-td');
    Array.from(secPin, item => {
        const elemLenght = item.textContent.length;
        let charElem = '*';
        // const newSecPin = charElem.repeat(elemLenght);
        item.textContent = charElem.repeat(elemLenght);
        // console.log(newSecPin);
    });
    Array.from(pasword, item => {
        const elemLenght = item.textContent.length;
        let charElem = '*';
        item.textContent = charElem.repeat(elemLenght);
    });
}
// get admin credentials
function getAdminData(){
    const url = '../process/getAdminCred.php';
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            document.getElementById('name').value = res.data.fullname;
            document.getElementById('uname').value = res.data.username;
            document.getElementById('pass').value = res.data.password;
            document.getElementById('secPin').value = res.data.security_pin;
            // EDIT BTN CLICKED ADMIN ACCOUNT
            editAdminAccount();
        }
    })
    .catch(error => {
        console.error(error);
        alert('Can\'t Display Admin Info ');
    });
}
// USING THE POST METHOD FOR CREATING THE ACC
function createAccount(url, data, dataType){
    POST(url, data, dataType)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.status == 'Success'){
            alert('Successfully Create Account');
            fetchTableData();
            Array.from(document.querySelectorAll('.create-acc-input-name'), item => {
                item.value = '';
            });
        }
        if(res.status != 'Success'){
            alert(res.status);
        }
    })
    .catch(error => {
        console.error(error);
        alert('Can\'t Create Account! Please Try Again!');
    });
}
// DELETE SALESMAN ACCOUNT 1ST STEP
function deleteAccount(){
    const deleteBtns = document.querySelectorAll('.delete-icon');
    Array.from(deleteBtns, btn => {
        btn.addEventListener('click', (e)=>{
            e.preventDefault();
            e.stopImmediatePropagation();    
            const id = e.target.getAttribute('data-record-id');
            const encodeId = encodeURIComponent(id);
            const urlGetPin = '../process/getAdminPin.php';  
            getAdminPinForValidation(urlGetPin, encodeId);
        });
    });
}
// GET ADMIN PIN
function getAdminPinForValidation(urlGetPin, encodeId){
    let adminPin = 0;
    const blur = document.querySelector('.blur');
    const promptz = document.getElementById('deleteSalesmanPrompt');
    
    GET(urlGetPin)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            adminPin = parseInt(res.data);
            blur.style.display = 'flex';
            promptz.style.display = 'flex';
            promptz.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'start'
            });
            submitBtnClick(adminPin, encodeId);
        }
    })
    .catch(error => {
        console.error(error);
        alert('Can\'t Get Admin Pin!');
    });
}

// SUBMIT BTN CLICK FOR PIN VALIDATION
function submitBtnClick(adminPin, encodeId){
    const btn = document.getElementById('btnSubmitDeleteSalesmanAcc');
    const inputPin = document.getElementById('deleteSalesmanAccountPromptInput');
    btn.addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        e.preventDefault();
        if(isNaN(inputPin.value)){
            alert('You Entered A Invalid Pin(Pin Not A Number)');
        }else{
            if(inputPin.value == adminPin){
                // DELETE METHOD INIT
                iniGETDelete(encodeId);
            }else{
                alert('Wrong Pin!');
            }
        }
    });
}
// DELETE THE ACC DELETE INI
function iniGETDelete(encodeId){
    const url = `../process/deleteAccount.php?id=${encodeId}`;
    DELETE(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.status){
            alert(res.status);
            fetchTableData();
            document.querySelector('.blur').style.display = 'none';
            document.getElementById('deleteSalesmanPrompt').style.display = 'none';
            document.getElementById('deleteSalesmanAccountPromptInput').value = '';
        }
    })
    .catch(error => {
        console.error(error);
        alert('Error! Can\'t Delete Account!');
    });
}
// admin edit btn clicked
function editAdminAccount(){
    const btn = document.querySelector('.btn-save');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const name = document.getElementById('name');
        const uname = document.getElementById('uname');
        const pass = document.getElementById('pass');
        const pin = document.getElementById('secPin');
        const blur = document.querySelector('.blur');
        const prompts = document.getElementById('editAdminPrompt');
        if(name.value == '' ||
           uname.value == '' ||
           pass.value == '' ||
           pin.value == ''){
            alert('All Fields Must Not Be Empty!');
        }else{
            blur.style.display = 'flex';
            prompts.style.display = 'flex';
            prompts.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'start'
            });
            getAdminPinForEditProfile(name, uname, pass, pin);
        }
    })
}
// GET ADMIN PIN FOR EDITING THE PROFILE
function getAdminPinForEditProfile(name, uname, pass, pin){
    let adminPin;
    const urlGinPin = '../process/getAdminPin.php';
    GET(urlGinPin)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            adminPin = res.data;
            inputValForEditProfileAdmin(adminPin, name, uname, pass, pin);
            
        }
    })
    .catch(error => {
        console.error(error);
    });
}
// INPUT VALIDATE FOR ADMIN PIN FOR EDITING THE PROFILE
function inputValForEditProfileAdmin(adminPin, name, uname, pass, pin){
    const input = document.getElementById('editAdminProfilePromptInput');
    const btnSubmitPin = document.getElementById('btnSubmitAdminProfile');
    btnSubmitPin.addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        e.preventDefault();
        if(isNaN(input.value)){
            alert('You Entered A Invalid Pin (Pin Not A Number)');
        }else{
            if(input.value == adminPin){
                editAdminProfile(name, uname, pass, pin);
            }else{
                alert('Wrong Pin!');
            }
        }
    });
}
// EDIT PROFILE
function editAdminProfile(name, uname, pass, pin){
    const blur = document.querySelector('.blur');
    const secPinPrompt = document.getElementById('editAdminPrompt');
    const pinInput = document.getElementById('editAdminProfilePromptInput');
    const data = JSON.stringify({
        name: name.value,
        uname: uname.value,
        pass: pass.value,
        pin: pin.value
    });
    const dataType = 'JSON';
    const url = '../process/updateAdminProfile.php';
    PUT(url, data, dataType)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.status){
            alert(res.status);
            blur.style.display = 'none';
            secPinPrompt.style.display = 'none';
            pinInput.value = '';
            getAdminData();
        }
    })
    .catch(error => {
        console.error(error);
        alert('Can\'t Update Profile! Please Try Again!');
    });
}
// GET ADMIN SEC PIN
function getAdminSecPinForSalesman(url, id, blur, promptForm){
    let secPin;
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            secPin = res.data;
            submitBtnClickSalemanEdit(secPin, id, blur, promptForm);
        }
    })
    .catch(error => {
        console.error(error);
        alert(error);
    });
}
// SECPIN FOR EDITING THE SALEMAN
function submitBtnClickSalemanEdit(secPin, id, blur, promptForm){
    const editBtn = document.getElementById('submitBtnEditSalesmanPrompt');
    editBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const inputSecpin = document.getElementById('editSalesmanPromptInput');
        if(isNaN(inputSecpin.value)){
            alert('You Entered A Invalid Pin(Pin Not A Number)');
        }else{
            if(inputSecpin.value == secPin){
                // blur.style.display = 'none';
                promptForm.style.display = 'none';
                inputSecpin.value = '';
                displaySalesmanData(id);
            }else{
                alert('Wrong Pin!');
            }
        }
    });
}
// DISPLAY THE SALESMAN DATA TO THE EDIT FORM
function displaySalesmanData(id){
    const editForm = document.querySelector('.section-profile-editor');
    const hiddenId = document.getElementById('salesmanHiddenId');
    const name = document.getElementById('editFullname');
    const uname = document.getElementById('editUsername');
    const pass = document.getElementById('editPass');
    const pin = document.getElementById('editSecPin');
    const encodeId = encodeURIComponent(id);
    const url = `../process/getSalesManDataForEdit.php?id=${encodeId}`;
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            editForm.style.display = 'flex';
            editForm.scrollIntoView({
                behavior: 'smooth', 
                inline: 'center', 
                block: 'center'});
            hiddenId.value = res.data.id;
            name.value = res.data.name;
            uname.value = res.data.uname;
            pass.value = res.data.pass;
            pin.value = res.data.pin;
        }
        labelTransitioning();
        // EDIT BTN SALESMAN EDIT PROFILE CLICK;
        editBtnSalesmanProfile(hiddenId);
    })
    .catch(error => {
        console.error(error);
        alert('Can\'t Display Data!');
    });
}
// EDIT BTN FOR EDITING SALESMAN PROFILE CLICKED
function editBtnSalesmanProfile(id) {
    const btn = document.getElementById('btnEditSalesmanProf');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const name = document.getElementById('editFullname');
        const uname = document.getElementById('editUsername');
        const pass = document.getElementById('editPass');
        const pin = document.getElementById('editSecPin');
        const url = '../process/editSalesmanProfile.php';
        const dataType = 'JSON';
        const data = JSON.stringify({
            id: id.value,
            name: name.value,
            uname: uname.value,
            pass: pass.value,
            pin: pin.value
        });
        if(name.value == '' ||
           uname.value == '' ||
           pass.value == '' ||
           pin.value == ''){
            alert('Don\'t Leave The Important Field Empty!');
        }else{
            // HTTP REQUEST
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }
                if(res.status){
                    alert(res.status);
                    $('.section-profile-editor').style.display = 'none';
                    $('#editFullname').value = '';
                    $('#editUsername').value = '';
                    $('#editPass').value = '';
                    $('#editUsername').value = '';
                }
            })
            .catch(error => {
                console.error(error);
                alert('Failed To Edit Profile!');
            });

        }
    });
}
// CLOSE EDIT ADMIN PROFILE PROMPT
function closeAdminEditPrompt(){
    const btn = document.getElementById('cancelBtnAdminProfile');
    const promptDiv = document.getElementById('editAdminPrompt');
    const input = document.getElementById('editAdminProfilePromptInput');
    const blur = document.querySelector('.blur');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        input.value = '';
        promptDiv.style.display = 'none';
        blur.style.display = 'none';
    });
}
// CLOSE DELETE SALESMAN ACCOUNT PROMPT
function closeDeleteSalesmanAccountPrompt(){
    const btn = document.getElementById('cancelBtnDeleteAcc');
    const promptDiv = document.getElementById('deleteSalesmanPrompt');
    const input = document.getElementById('deleteSalesmanAccountPromptInput');
    const blur = document.querySelector('.blur');
    btn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        input.value = '';
        promptDiv.style.display = 'none';
        blur.style.display = 'none';
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
// JQUERY ALIKE
function $(e) {
    return document.querySelector(e);
}