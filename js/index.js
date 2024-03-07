document.addEventListener('DOMContentLoaded', ()=>{
    // show/ hide password
    document.querySelector('.input-show-hide-password')
    .addEventListener('change', (e)=>{
        if(e.target.checked){
            document.getElementById('password')
            .type = 'text';
        }else{
            document.getElementById('password')
            .type = 'password';
        }
    });
    // login
    $('#btnLogin')
    .addEventListener('click', (e)=>{
        e.preventDefault();
        const username = $('#username');
        const password = $('#password');
        if(username.value == '' || password.value == ''){
            alert('Please Don\'t Leave The Username or Password Empty!');
        }else{
            const url = 'process/loginProcess.php';
            const data = JSON.stringify({
                username: username.value,
                password: password.value
            });
            const dataType = 'JSON';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    if(res.status == 'Invalid Account!'){
                        alert(res.status);
                    }else if(res.status == 'Wrong Password!'){
                        alert(res.status);
                    }else{
                        if(res.position == 'admin'){
                            window.location.href = 'pages/home.php';
                        }else if(res.position == 'sale_man'){
                            window.location.href = 'pages/client/sales.php';
                        }
                    }
                }
            })
            .catch(error => {
                console.error(error);
                alert('Can\'t Login! Please Try Again!');
            });
        }
    });
});
// POST METHOD
async function POST(url, data, dataType){
    try {
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'post',
                headers: {'Content-Type':'application/json'},
                body: data
            };
        }else{
            fetchObject = {
                method: 'post',
                body: data
            };
        }
        const response = await fetch(url, fetchObject);
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!')
        }
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseBlod = await response.arrayBuffer();
            return new Uint8Array(responseBlod);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    }
}
async function GET(url){
    try {
        const response = await fetch(url);
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseBlob = await response.arrayBuffer();
            return new Uint8Array(responseBlob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    } 
}
// jquery look alike fn
function $(e){
    return document.querySelector(e);
}
