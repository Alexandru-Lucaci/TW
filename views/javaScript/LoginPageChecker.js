var request,typeOperation,idLabel,textLabel;

function loadXML (url) {
    if (window.XMLHttpRequest) { 
        request = new XMLHttpRequest ();
    }
    else if (window.ActiveXObject) {   
        request = new ActiveXObject ("Microsoft.XMLHTTP");
    }

    if (request) {	

        request.onreadystatechange = handleResponse;

        request.open ("GET", url, true);
        request.send (null);
    } 
    else {
        console.error('Nu suporta AJAX');
    }
}

function handleResponse () {
    if (request.readyState == 4) {

        if (request.status == 200) {
            var root = request.responseXML.documentElement;

            var res = root.getElementsByTagName('result')[0].firstChild.data;

            display_text(res);
        }
        else { 
            console.error ("A aparut o problema (XML transfer):\n"+response.statusText);
        }
    } 
}

function check_username_exists( username, typeOperation, idLabel){
    if(username){

        //set parameters to be displayed if needed
        globalThis.typeOperation=typeOperation;
        globalThis.idLabel=idLabel;

        loadXML ('models/LoginPageAJAX.php?username=' + username);
    }
}

function display_text(response){
    if(typeOperation){
        var label=document.getElementById(idLabel);

        if(typeOperation=='register'){
            label.className=(response==1)?'show':'hidden';
        }
        else if(typeOperation=='login'){
            label.className=(response==0)?'show':'hidden';
        }
    }
}

