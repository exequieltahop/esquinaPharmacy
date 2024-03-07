// async function in get and post method and ect
// get
// const { json } = require("body-parser")
// const express = require("express")

// const app = express()

// const port = 5173
// app.get('/', (REQUEST, response) => {
//     try {
//         response.status(200).send('help')
//     }
//     catch {
//         response.status(500)
//     }
// })


// app.listen(port, () => {
//     console.log('server on')
// })

export async function GET(url){
    try {
        const response = await fetch(url);
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
        }
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
// post
export async function POST(url, data, dataType){
    try {
        let fetchObject;
        if (dataType == 'JSON') {
            fetchObject = {
                method: 'post',
                headers: {'Content-Type':'application/json'},
                body: data
            };
        } else {
            fetchObject = {
                method: 'post',
                body: data
            };
        }
        const response = await fetch(url, fetchObject);
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
        }
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
// put
export async function PUT(url, data, dataType){
    try {
        let fetchObject;
        if (dataType == 'JSON') {
            fetchObject = {
                method: 'put',
                headers: {'Content-Type':'application/json'},
                body: data
            };
        } else {
            fetchObject = {
                method: 'put',
                body: data
            };
        }
        const response = await fetch(url, fetchObject);
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
        }
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
// delete
export async function DELETE(url){
    try {

        const response = await fetch(url, {
            method: 'delete'
        });
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
        }
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