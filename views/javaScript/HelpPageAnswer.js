var questions=document.getElementsByClassName("help-question-button");
var i;

for(i=0;i<questions.length;i++){
    questions[i].addEventListener("click", function(){
        this.classList.toggle("active");
        
        var answer=this.nextElementSibling;
        if(answer.style.display==="block"){
            answer.style.display="none";
        }
        else{
            answer.style.display="block";
        }
    });
}