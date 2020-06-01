function handleSignUp() {
    $('.connexion').addClass('remove-section');
    $('.enregistrer').removeClass('active-section');
    $('.btn-enregistrer').removeClass('active');
    $('.btn-connexion').addClass('active');
};

function handleSignIn() {
    $('.connexion').removeClass('remove-section');
    $('.enregistrer').addClass('active-section');
    $('.btn-enregistrer').addClass('active');
    $('.btn-connexion').removeClass('active');
};
function handleUserSignIn(e){
   var $this = document.getElementById('loginUserName').value;
   var $that = document.getElementById('loginUserPassword').value;
   if ($this.trim() === '' || $that.trim() === ''){
       alert('Please make you have filled in your username or password');
       e.preventDefault();
       return false;
   }
else{
    var userCredentials = [];
       userCredentials.push({
           'username': $this,
           'password': $that
       });
    var url = 'userCredentialValidation.php';
var data = {'userCredentials': userCredentials};
       $.post(url, data, function (response) {
           response = JSON.parse(response);
           if (response.status === 'Locked') {
               $this.attr("disabled", "disabled");
               $this.button('refresh');
           }
       });
   }
}
$(document).on('click', '.btn-enregistrer', handleSignUp);
$(document).on('click', '.btn-connexion', handleSignIn);
$(document).on('click', '#loginUserCredentialsSubmit', handleUserSignIn);

