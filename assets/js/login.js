
function Login() {
    this.init();
}
Login.prototype.init = function() {
    var self = this;
    $(document).ready(function(){
        $('#signUp').click(function(){self.signUp();});
        $('#signIn').click(function(){self.signIn();});
    });
};

Login.prototype.sendLoginForm = function(url) {
    $.ajax({
        method: 'post',
        url: url,
        data: $('.form-signin').serialize(),
        success: function(data){
            if(data.error){
                alert(data.message);
            }else{
                document.location.href = '/';
            }
        },
        dataType: 'json'
    });
};

Login.prototype.signUp = function() {
    if($('.sign-up-container').is(':hidden')){
        $('#repeatPassword').attr('disabled', false);
        $('.sign-up-container').show();
    }else{
        var form = $('.form-signin')[0];

        if (form.checkValidity()) {
            var password = $('#inputPassword').val();
            var repeatPassword = $('#repeatPassword').val();
            if(password != repeatPassword){
                alert('Password is not fit to repeat password');
                return false;
            }
        }else{
            return true;
        }
        this.sendLoginForm('sign-up');
    }
};

Login.prototype.signIn = function() {
    $('#repeatPassword').attr('disabled', true);
    $('.sign-up-container').hide();
    var form = $('.form-signin')[0];
    if (!form.checkValidity()) {
        return true;
    }
    this.sendLoginForm('sign-in');
};
var login = new Login();