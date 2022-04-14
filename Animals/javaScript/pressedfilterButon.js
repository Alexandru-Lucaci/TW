function functionButt()
{
    var x= document.getElementById("myDIV");
    if(x.style.display==="none")
    {
        x.style.display="block";
    } else{ x.style.display="none";}
}

const todos= document.querySelectorAll(".todo");


todos.forEach(todo=>{
    todo.addEventListener('click',()=>{
        todo.classList.toggle('active');
    });
})