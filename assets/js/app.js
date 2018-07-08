
function Tickets() {
    this.init();
}
Tickets.prototype.init = function() {
    var self = this;
    $(document).ready(function(){
        $('#logoutButton').click(function(){self.logout();});
    });
};

Tickets.prototype.logout = function() {
    if(!confirm('Are you sure to logout?')){
        return false;
    }
    $.ajax({
        method: 'post',
        url: '/logout',
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

var tickets = new Tickets();