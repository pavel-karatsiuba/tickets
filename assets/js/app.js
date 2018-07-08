function Tickets() {
    this.init();
    this.initTicketsListActions();
}
Tickets.prototype.init = function() {
    var self = this;
    $(document).ready(function(){
        $('#logoutButton').click(function(){self.logout();});
        $('#createTicketButton').click(function(){self.showEditTicketForm();});
    });
};

Tickets.prototype.initTicketsListActions = function() {
    var self = this;
    $(document).ready(function(){
        $('.changeStatusButton').change(function(){
            if(!confirm('Do you really want to change status?')){
                self.reloadTicketsList();
                return false;
            }
            var id = $(this).data('id');
            var status = $('option:selected', this).val();
            self.changeTicketStatus(id, status);
        });
        $('.editButton').click(function(){
            var id = $(this).data('id');
            self.showEditTicketForm(id);
        });
        $('.deleteButton').click(function(){
            if(!confirm('Do you really want to delete ticket?')){
                return false;
            }
            var id = $(this).data('id');
            self.deleteTicket(id);
        });
    });
};

Tickets.prototype.changeTicketStatus = function(id, status) {
    $.ajax({
        method: 'post',
        data: {id: id, status: status},
        url: '/change-ticket-status',
        success: function(data){
            if(data.error){
                alert(data.message);
            }
        },
        dataType: 'json'
    });
};

Tickets.prototype.deleteTicket = function(id) {
    var self = this;
    $.ajax({
        method: 'post',
        data: {id: id},
        url: '/delete-ticket',
        success: function(data){
            if(data.error){
                alert(data.message);
            }else{
                self.reloadTicketsList();
            }
        },
        dataType: 'json'
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

Tickets.prototype.reloadTicketsList = function() {
    document.location.href = document.location.href;//@TODO dynamic loading of the list
    this.initTicketsListActions();
};

Tickets.prototype.clearEditForm = function() {
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