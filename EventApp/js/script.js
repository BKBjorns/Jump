/*****This is the menu******/
//Here we set the variable Opened to flase
var opened = false;

//This is the function that open and closes the menu
$(document).ready(function(){
	$('#hamburger').click(function(){
        
        //If the opened is true and you click the cross, the dropdown should slide up and in the less file the hamburger changes from cross to hamburger, we set a background color and opened to false
        //The else statment tells us the oposite
        if(opened){
            $('#dropDown').slideUp(1000);
            $('#hamburger').css('margin-top', '-37px');
            $('#logo').show();
            $('header').css('background-color', '#87d7e2');
            opened = false;
        } else {
            $('#dropDown').slideDown(1000);
            $('#logo').hide();
            $('header').css('background-color', 'transparent');
            $('#hamburger').css({'margin-top': '10px', 'z-index': '10', 'position': 'fixed'});
            opened = true;
            
        }
        //Here we add a toggleClass to all the children in hamburger, wich is all the lines, so that it will responed to the styling making it to a cross
		$('#hamburger').children().toggleClass('open');
	});
});




//------------------------------------------EXPANDER
var expandContent= document.getElementsByClassName('eventDescription');
var expandBtn= document.getElementsByClassName('expanderBtn');



//function expand(event){
//    if((expandContent.style.display = 'block') == false){
//       expandContent.style.display = 'block'; 
//    } else{
//        expandContent.style.display = 'none';
//    }
//}


for (var i = 0; i<expandBtn.length; i++){
    expandBtn[i].addEventListener("click" ,function (event){
        event.currentTarget.nextElementSibling.classList.toggle('show')
//        if((event.currentTarget.nextElementSibling.style.display = 'block') == false) {
//           event.currentTarget.nextElementSibling.style.display = 'block'; 
//        } else{
//            event.currentTarget.nextElementSibling.style.display = 'none';
//        }
    });

}





