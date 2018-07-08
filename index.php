<?
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('UTC');
use Tickets\Db\User;
session_start();

function renderScript($tplName, $html = ''){
    ob_start();
    require(__DIR__ . '/assets/tpl/' . $tplName . '.phtml');
    return ob_get_clean();
}

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
//die('<pre>' . print_r($request_uri,1));

$disableLayout = false;
try {
    $auth = new \Tickets\Auth();
    $loggedUser = $auth->getCurrentLoggedUser();
    if($loggedUser->id){
        $html = renderScript('tickets', $loggedUser);
    }
}catch (\Exception $e){
    $html = renderScript('fatal-error');
    echo renderScript('layout', $html);
    die();
}
switch ($request_uri[0]) {
    // Home page
    case '/':
        if($loggedUser->id){
            $ret = new \StdClass();
            $ret->loggedUser = $loggedUser;
            $tickets = new \Tickets\Ticket();
            $ticketsList = $tickets->loadTickets();
            $ret->tickets = renderScript('tickets-list', $ticketsList);
            $html = renderScript('tickets', $ret);
        }else{
            $html = $html?$html:renderScript('login');
        }
        break;
    // About page
    case '/sign-up':
        try {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $rePassword = $_POST['repeat-password'];
            $auth = new \Tickets\Auth();
            $auth->signUp($login, $password, $rePassword);
            $auth->login($login, $password);
            $html = ['error' => false];
        }catch (\Exception $e){
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    case '/sign-in':
        try {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $auth = new \Tickets\Auth();
            $auth->login($login, $password);
            $html = ['error' => false];
        }catch (\Exception $e){
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    case '/logout':
        try {
            $auth = new \Tickets\Auth();
            $auth->logout();
            $html = ['error' => false];
        }catch (\Exception $e){
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    case '/edit-ticket-form':
        if(!$loggedUser->id){
            header("Location:/");
        }
        try {
            $id = $_GET['ticketId'];
            if($id){
                $ticket = new \Tickets\Ticket();
                $ticketEntity = $ticket->loadTicket($id);
                $ticketEntity->date = $ticketEntity->date?date('Y-m-d', $ticketEntity->date):'';
            }else{
                $ticketEntity = new \Tickets\Entity\Ticket();
            }
            $html = renderScript('edit-ticket-form', $ticketEntity);
            $disableLayout = true;
        }catch (\Exception $e){
            header('HTTP/1.0 404 Not Found');
            $html = renderScript('fatal-error');        }
        break;
    case '/save-ticket':
        if(!$loggedUser->id){
            header("Location:/");
        }
        try {
            $ticket = new \Tickets\Ticket();
            $ticketEntity = new \Tickets\Entity\Ticket();
            $ticketEntity->id = $_POST['id'];
            $ticketEntity->title = $_POST['title'];
            $ticketEntity->text = $_POST['text'];
            $ticketEntity->date = $_POST['date'];
            $ticketEntity->status = $_POST['status'];
            $ticketEntity->filter();
            $ticketEntity->validate();
            $ticket->save($ticketEntity);
            $html = ['error' => false];
        }catch (\Exception $e) {
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    case '/change-ticket-status':
        if(!$loggedUser->id){
            header("Location:/");
        }
        try {
            $id = $_REQUEST['id'];
            $status = $_REQUEST['status'];
            $ticket = new \Tickets\Ticket();
            $ticketEntity = $ticket->loadTicket($id);
            $ticketEntity->status = $status;
            $ticketEntity->filterStatus();
            $ticket->save($ticketEntity);
            $html = ['error' => false];
        }catch (\Exception $e) {
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    case '/delete-ticket':
        if(!$loggedUser->id){
            header("Location:/");
        }
        try {
            $id = $_REQUEST['id'];
            $ticket = new \Tickets\Ticket();
            $ticketEntity = $ticket->loadTicket($id);
            $ticket->delete($ticketEntity);
            $html = ['error' => false];
        }catch (\Exception $e) {
            $html = ['error' => true, 'message' => $e->getMessage()];
        }
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        $html = renderScript('fatal-error');
        break;
}


$format = $_REQUEST['dataFormat'];
if(($format!='text') && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
    echo json_encode($html);
}else{
    if($disableLayout){
        echo $html;
    }else {
        echo renderScript('layout', $html);
    }
}