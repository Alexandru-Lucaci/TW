let indexSlide=1;
showSlides(indexSlide);

function changeSlides(increment){
    showSlides(indexSlide+=increment);
}

function setCurrentSlides(indexNewSlide){
    showSlides(indexSlide=indexNewSlide);
}

function updateSlides(){
    showSlides(indexSlide+=1);
}

function showSlides(n){
    let i;
    let slides=document.getElementsByClassName("mySlide");
    let dots=document.getElementsByClassName("dot");

    if(n>slides.length){
        indexSlide=1;
    }

    if(n<1){
        indexSlide=slides.length;
    }

    for(i=0;i<slides.length;i++){
        slides[i].style.display="none";
    }

    for(i=0;i<dots.length;i++){
        dots[i].className=dots[i].className.replace(" active","");
    }

    slides[indexSlide-1].style.display="block";
    dots[indexSlide-1].className+=" active";

    //setTimeout(updateSlides,5000);
}