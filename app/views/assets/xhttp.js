var xhttp = new XMLHttpRequest;

function xmlHttpGet(url, callback, parameters=''){

    xhttp.onreadystatechange = callback;

    xhttp.open("GET", url+parameters,true)

    xhttp.send();

}

function xmlHttpPost(url, callback, parameters = ''){
    
    xhttp.onreadystatechange = callback;
    xhttp.open("POST", url, true);

    if(typeof(parameters) != "object"){
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    }
   
    xhttp.send(parameters);
}

function beforeSend(callback){

    if(xhttp.readyState < 4){
        callback();
    }

}

function success(callback){

    if(xhttp.readyState == 4 && xhttp.status == 200){
        callback();
    }
}

function erro(callback){
    xhttp.onerror = callback;
}