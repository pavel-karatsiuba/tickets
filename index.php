<?
require __DIR__ . '/vendor/autoload.php';

use Tickets\Db\User;
session_start();

function renderScript($tplName, $html = ''){
    ob_start();
    require(__DIR__ . '/assets/tpl/' . $tplName);
    return ob_get_clean();
}

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
//die('<pre>' . print_r($request_uri,1));

switch ($request_uri[0]) {
    // Home page
    case '/':
        $html = false;
        try {
            $auth = new \Tickets\Auth();
            $loggedUser = $auth->getCurrentLoggedUser();
            if($loggedUser->id){
                $html = renderScript('tickets.tpl', $loggedUser);
            }
        }catch (\Exception $e){
            $html = renderScript('fatal-error.tpl');
        }
        $html = $html?$html:renderScript('login.tpl');
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
    default:
        header('HTTP/1.0 404 Not Found');
        $html = renderScript('fatal-error.tpl');
        break;
}



if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
    echo json_encode($html);
}else {
    echo renderScript('layout.tpl', $html);
}
die();
try {
    $user = new User();
    session_id();
}catch (Exception $e){
    echo $e->getMessage();
    die();
}






die('<pre>' . print_r($user,1));
echo 'Hello world';