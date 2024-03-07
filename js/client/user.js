// IMPORT HTTP REQ JS
import {GET, POST, PUT, DELETE} from '../httpReq.js';
// DOMCONTENTLOADED
document.addEventListener('DOMContentLoaded',()=>{
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
    document.getElementById('closeResponsiveNav')
    .addEventListener('click', ()=>{
        const navi = document.getElementById('nav');
        navi.style.display = 'none';
    })
    // display user credentials
    getUserDetails();
    // edit btn click
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
    // close prompt 
    document.querySelector('.btn-cancel-edit')
    .addEventListener('click', (e)=>{
        e.stopImmediatePropagation();
        const blur = document.querySelector('.blur');
        const promptForm = document.querySelector('.section-prompt');
        const input = document.querySelector('.input-prompt');
        const inputHidden = document.getElementById('hiddenSample');
        const catcher = document.getElementById('catcher');
        const label = document.getElementById('labelSecPinHide');
        blur.style.display = 'none';
        promptForm.style.display = 'none';
        input.value = '';
        inputHidden.value = '';
        catcher.value = '';
        label.style.top = '50%';
        label.style.fontSize = '1rem';
        catcher.style.outline = 'none';
        catcher.style.boxShadow = 'none';
     });
     //hidden input input event
     document.getElementById('hiddenSample').addEventListener('input', (e)=>{
        let inputLength = e.target.value.length;
        let star = '*';
        document.getElementById('catcher').value = star.repeat(inputLength);
    });
    // label to top
    document.getElementById('hiddenSample').addEventListener('focus', (e)=>{
        const catcher = document.getElementById('catcher');
        const label = document.getElementById('labelSecPinHide');
        label.style.top = '0px';
        label.style.fontSize = '0.8rem';
        catcher.style.outline = '0.5px solid rgb(255, 53, 194)';
        catcher.style.boxShadow = '0 0 30px -13px rgb(255, 53, 194)';
    });
    document.getElementById('catcher').addEventListener('focus', (e)=>{
        const inputHidden = document.getElementById('hiddenSample');
        const label = document.getElementById('labelSecPinHide');
        label.style.top = '0px';
        label.style.fontSize = '0.8rem';
        inputHidden.focus();
        e.target.style.outline = '0.5px solid rgb(255, 53, 194)';
        e.target.style.boxShadow = '0 0 30px -13px rgb(255, 53, 194)';
    });
    // label back to initial state
    labelToInitial();
});
// get user details
function getUserDetails(){
    const url = '../../process/client/getUserDetails.php';
    const name = document.getElementById('name');
    const uname = document.getElementById('uname');
    const pass = document.getElementById('pass');
    const secPin = document.getElementById('secPin');
    const id = document.getElementById('hiddenId');
    GET(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            name.value = res.data.name;
            uname.value = res.data.username;
            pass.value = res.data.password;
            secPin.value = res.data.sec_pin;
            saveBtnClicked();
        }
    })
    .catch(error => {
        console.error(error);
        // alert('Can\'t Display Your Credentials!');
    });
}
// save edited profile
function saveBtnClicked(){
    // let finalTrueVal;
    const btnSave = document.querySelector('.btn-save');
    btnSave.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const name = document.getElementById('name');
        const uname = document.getElementById('uname');
        const pass = document.getElementById('pass');
        const secPin = document.getElementById('secPin');
        const url = '../../process/client/saveEditProfile.php';
        const blur = document.querySelector('.blur');
        const promptForm = document.querySelector('.section-prompt');
        const secPinBtn = document.querySelector('.btn-submit-prompt');
        const inputSecPin = document.querySelector('.input-prompt'); 
        if(name.value == '' ||
            uname.value == '' ||
            pass.value == '' ||
            secPin.value == ''){
            alert('Don\'t Leave The Important Fields Empty!');
        }else{
            blur.style.display = 'flex';
            promptForm.style.display = 'flex';
            const urlGetSecPin = '../../process/client/getSecurityPin.php';
            let securePin;
            // GET SECURITY PIN
            GET(urlGetSecPin)
            .then(res => {
                if(res.err){
                    throw new Error();
                }else{
                    securePin = parseInt(res.secPin);
                    // console.log(securePin);
                }
            })
            .catch(error => {
                console.error(error);
            });
            
            // VALIDATE SEC PIN
            secPinBtn.addEventListener('click', (e)=>{
                e.preventDefault();
                e.stopImmediatePropagation();
                if(inputSecPin.value == securePin){
                    console.log('ok');
                    const data = JSON.stringify({
                        name: name.value,
                        uname: uname.value,
                        pass: pass.value,
                        secPin: secPin.value
                    });
                    const dataType = 'JSON';
                    initializePutSaveEditProfile(url, data, dataType);
                }else{
                    console.log('Wrong Security Pin!');
                }
            });
        }
    });
}
// extension for saveBtnClicked()
function initializePutSaveEditProfile(url, data, dataType){
    const blur = document.querySelector('.blur');
    const promptForm = document.querySelector('.section-prompt');
    const input = document.querySelector('.input-prompt');
    const inputHidden = document.getElementById('hiddenSample');
    const catcher = document.getElementById('catcher');
    const label = document.getElementById('labelSecPinHide');
    PUT(url, data, dataType)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            alert(res.status);
            getUserDetails();
            Array.from(document.querySelectorAll('.input-profile'), item => {
                item.readOnly = true;
            });
            document.querySelector('.btn-changes-wrapper').style.display = 'none';
            document.querySelector('.btn-save').disabled = true;
            blur.style.display = 'none';
            promptForm.style.display = 'none';
            input.value = '';
            inputHidden.value = '';
            catcher.value = '';
            label.style.top = '50%';
            label.style.fontSize = '1rem';
            catcher.style.outline = 'none';
            catcher.style.boxShadow = 'none';
        }
    })
    .catch(error => {
        console.error(error);
    });
}
// label to initial state
function labelToInitial(){
    document.getElementById('hiddenSample').addEventListener('blur', (e)=>{
        const label = document.getElementById('labelSecPinHide');
        const catcher = document.getElementById('catcher');
        if(e.target.value.length == 0){
            label.style.top = '50%';
            label.style.fontSize = '1rem';
            catcher.style.outline = 'none';
            catcher.style.boxShadow = 'none';
        }
    });
}