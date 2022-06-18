var request;

/**
 * Begins the selection,and starts listening to clicks associated with a div
 */
function startSelection(){

    var start=sessionStorage.getItem('start');
    if(start==null){
        sessionStorage.setItem('start',true);
    }

    if(start){
        endSelection();
    }
}

/**
 * Adds an animal to the animal names if the user selected to mark the animals
 * @param {*} animalName the animal name
 */
function markAnimal(animalName){
    
    start=sessionStorage.getItem('start');

    if(start!=null){
        //mark it 
        marked=sessionStorage.getItem(animalName);

        //found it,so we remove it
        if(marked!=null){
            document.getElementById(animalName).style.borderLeft="0px solid #FF0000";
            
            sessionStorage.removeItem(animalName);
        }
        else{
            document.getElementById(animalName).style.borderLeft="10px solid #FFFFFF";

            sessionStorage.setItem(animalName,true);
        }
    }
}

/**
 * Used to remark the animals after changing the result page
 */
function remarkAnimals(){

    for(var i=0;i<sessionStorage.length;i++){
        
        animalName=sessionStorage.key(i);
        
        if(animalName!='start'){
            var element=document.getElementById(animalName);

            if(element!=null){
                element.style.borderLeft="10px solid #FFFFFF";
            }
        }
    }
}

/**
 * Unmarks everything and clears the session storage data
 */
function endSelection(){
    
    for(var i=0;i<sessionStorage.length;i++){
        
        animalName=sessionStorage.key(i);
        
        if(animalName!='start'){
            var element=document.getElementById(animalName);

            if(element!=null){
                element.style.borderLeft="0px solid #FF0000";
            }
        }
    }

    sessionStorage.clear();
}

/**
 * Compute the animal names from the storage
 */
function getAnimalNames(){
    animalNames='';
    nrAnimals=0;
    for(var i=0;i<sessionStorage.length;i++){
        
        animalName=sessionStorage.key(i);
        
        if(animalName!='start'){
            if(nrAnimals>0){
                animalNames+=',';
            }
            animalNames+=animalName;

            nrAnimals++;
        }
    }
    return animalNames; 
}

/**
 * Save the animals marked to user
 * @param {*} username the username of the user
 */
function saveMarkedAnimals(username){

    start=sessionStorage.getItem('start');

    if(start!=null){

        if (window.XMLHttpRequest) { 
            request = new XMLHttpRequest ();
        }
        else if (window.ActiveXObject) {   
            request = new ActiveXObject ("Microsoft.XMLHTTP");
        }
    
        if(request){
            
            request.onreadystatechange = handleResponse;

            animalNames=getAnimalNames();
    
            url="models/ajax/saveAnimals.php?animal_names="+animalNames+"&username="+username;
    
            request.open ("GET", url, true);
            request.send (null);
        }
    }

    endSelection();
}

function handleResponse(){

    if (request.readyState == 4) {

        if (request.status == 200) {

            console.log('Ok');

        }
        else { 
            console.error ("A aparut o problema (XML transfer):\n"+response.statusText);
        }
    } 
}

/**
 * Download the information about the animals
 */
function downloadMarkedAnimals(fileFormat){

    start=sessionStorage.getItem('start');

    if(start!=null){
        
        animlaNames=getAnimalNames();

        endSelection();
    
        window.location.href="models/ajax/downloadAnimalsInfo.php?animal_names="+animalNames+"&file_format="+fileFormat;
    }
}