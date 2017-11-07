var selectUserType = document.getElementById('selectUserType');
var newStudForm = document.getElementById('newStudentForm');
var newOrgForm = document.getElementById('newOrgForm');

selectUserType.style.display="block";
newStudForm.style.display="none";
newOrgForm.style.display="none";

var studentRadio = document.getElementById('studentRadio');

//

function showUserForm(){
    if (studentRadio.checked == true) {
        selectUserType.style.display="none";
        newStudForm.style.display="block";
        newOrgForm.style.display="none";
   }else{
       selectUserType.style.display="none";
        newStudForm.style.display="none";
        newOrgForm.style.display="block";
   }
    
}


