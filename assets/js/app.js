
function Tickets() {
    this.init();
}
Tickets.prototype.init = function() {
    var self = this;
    $(document).ready(function(){
        $('#logoutButton').click(function(){self.logout();});
        $('#createTicketButton').click(function(){self.showEditTicketForm();});
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

Tickets.prototype.saveTicket = function() {
    var self = this;

    $.ajax({
        method: 'post',
        data: $('.editTicketForm').serialize(),
        url: '/save-ticket',
        success: function(data){
            if(data.error){
                alert(data.message);
            }else{
                self.clearEditForm();
                self.reloadTicketsList();
            }
        },
        dataType: 'json'
    });
};

Tickets.prototype.reloadTicketsList = function(ticketId) {

};

Tickets.prototype.clearEditForm = function(ticketId) {
    $('.editTicketContainer').html('');
};

Tickets.prototype.showEditTicketForm = function(ticketId) {
    var self = this;
    $.ajax({
        data: {ticketId: ticketId, dataFormat: 'text'},
        url: '/edit-ticket-form',
        success: function(data){
            if(data){
                $('.editTicketContainer').html(data);
                $('.saveTicket').click(function(){self.saveTicket();});
            }
        },
        dataType: 'html'
    });
};

var tickets = new Tickets();