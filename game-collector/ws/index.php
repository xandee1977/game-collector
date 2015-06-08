<?php
header('Access-Control-Allow-Origin: *'); // Allow Cross-Domain
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

header("Content-type: application/json; charset=utf", true);// Setting charset

//ini_set('max_execution_time', 0);
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting( E_ALL );
//error_reporting(1);

/* Returns data on JSONP format */
include_once 'configs/WS_CONF.php';
include_once 'class/db.class.php';
require_once('lib/smarty/libs/Smarty.class.php');

$smarty = new Smarty();
$database = new Database();

$status = "OK";
$message = "Dale!";

try {
    if( !isset($_REQUEST['service'])) {
        throw new Exception("Por favor informe o service.");
    }
    if( !isset($_REQUEST['action'])) {
        throw new Exception("Por favor informe o action.");
    }

    // Verifying the service
    switch($_REQUEST['service']) {
        case 'game';
            include_once 'class/game.class.php';
            // Se recebeu id do usuário
            if(isset($_REQUEST["user_id"])) {
                include_once 'class/user.class.php';
                $userObj = new User($_REQUEST["user_id"]);
                $user = $userObj->getData();
                
                if( !$user ) {
                    throw new Exception( $userObj->getError() );
                }  
            }

            switch($_REQUEST['action']) {
                // Game detail
                case 'detail':
                break;
                
                // List the games
                case 'list';
                    $gameObj = new Game();
                    
                    $search = false;
                    if($_REQUEST["search"]) {
                        $search = $_REQUEST["search"];
                    }                    

                    $limit = false;
                    if($_REQUEST["limit"]) {
                        $limit = $_REQUEST["limit"];
                    }

                    $user_id = false;
                    if($_REQUEST["user_id"]) {
                        $user_id = $_REQUEST["user_id"];
                    }

                    $flag = false;
                    if($_REQUEST["flag"]) {
                        $flag = $_REQUEST["flag"];
                    }

                    $system = 1; // System 1 = SNES
                    if($_REQUEST["system"]) {
                        $flag = $_REQUEST["system"];
                    }

                    $games = $gameObj->findGames($limit, $user_id, $search, $flag, $system);

                    if(!$games) {
                        throw new Exception($gameObj->getError());
                    }
                    $smarty->assign("games", $games);
                break;
            }
        break;

        case 'user':
            include_once 'class/user.class.php';
            if($_REQUEST['action'] != "login" && $_REQUEST['action'] != "save" && $_REQUEST['action'] != "save-profile") {
                $userObj = new User($_REQUEST["user_id"]);
                $user = $userObj->getData();
                
                if( !$user ) {
                    throw new Exception( $userObj->getError() );
                }                
            }

            switch($_REQUEST['action']) {
                case 'save':
                    $json_data = $HTTP_RAW_POST_DATA;
                    //$json_data = file_get_contents("php://input");
                    $array_data = json_decode($json_data, true);
                    $user = new User();
                    $result = $user->saveData($array_data);
                    if(!$result) {
                        throw new Exception($user->getError());
                    }                    
                    $smarty->assign('user_id', $result);
                break;

                case 'save-profile':
                    $json_data = $HTTP_RAW_POST_DATA;
                    //$json_data = file_get_contents("php://input");
                    $array_data = json_decode($json_data, true);
                    $user = new User();
                    $result = $user->saveProfile($array_data);
                    if(!$result) {
                        throw new Exception($user->getError());
                    }
                    
                    $profile_data = $user->getProfile($result);

                    $smarty->assign('profile_data', $profile_data);
                    $smarty->assign('profile_id', $result);
                break;

                case 'login':
                    include_once 'class/login.class.php';
                    $login = new Login();
                    $loginData = $login->doLogin($_REQUEST["email"], $_REQUEST["password"]);
                    if(!$loginData) {
                        throw new Exception( $login->getError() );
                    }
                    $smarty->assign('user', $loginData);
                break;

                case 'flag-watch':
                    $req_fields = ['game_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }
                    $result = $userObj->flagWatch($_REQUEST["game_id"]);
                    if(!$result) {
                        throw new Exception( $userObj->getError() );
                    }

                    $smarty->assign('flag_id', $result);
                    $smarty->assign('game_id', $_REQUEST["game_id"]);
                    $smarty->assign('user_id', $_REQUEST["user_id"]);
                break;

                case 'flag-favorite':
                    $req_fields = ['game_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }
                    $result = $userObj->flagFavorite($_REQUEST["game_id"]);
                    if(!$result) {
                        throw new Exception( $userObj->getError() );
                    }

                    $smarty->assign('flag_id', $result);
                    $smarty->assign('game_id', $_REQUEST["game_id"]);
                    $smarty->assign('user_id', $_REQUEST["user_id"]);
                break;

                case 'flag-have':
                    $req_fields = ['game_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }
                    $result = $userObj->flagHave($_REQUEST["game_id"]);
                    if(!$result) {
                        throw new Exception( $userObj->getError() );
                    }

                    $smarty->assign('flag_id', $result);
                    $smarty->assign('game_id', $_REQUEST["game_id"]);
                    $smarty->assign('user_id', $_REQUEST["user_id"]);
                break;

                case 'remove-flag':
                    $req_fields = ['game_id', 'flag'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }

                    $result = $userObj->removeFlag($_REQUEST["game_id"], $_REQUEST["flag"]);
                    
                    if(!$result) {
                        throw new Exception( $userObj->getError() );
                    }

                    $smarty->assign('user_id', $_REQUEST["user_id"]);
                    $smarty->assign('game_id', $_REQUEST["game_id"]);
                    $smarty->assign('flag', $_REQUEST["flag"]);
                break;

                case 'feedback':
                    include_once 'class/sendmail.class.php';
                    $mail  = new Sendmail();

                    $json_data = $HTTP_RAW_POST_DATA;
                    $array_data = json_decode($json_data, true);

                    $mail->setFrom($user["user_email"], $user["user_email"]);
                    $mail->setTo("hadougame@gmail.com", "Hadougame App");

                    $mail->setSubject( sprintf("[Feedback] - %s", $array_data["subject"]) );
                    $mail->setBody($array_data["message"]);

                    if(!$mail->send()) {
                        throw new Exception( $mail->getError() );   
                    }

                    $smarty->assign('feedback_result', "Mensagem enviada com sucesso.");
                break;                
            }
        break;

        case 'address':
            include_once 'class/address.class.php';
            $address = new Address();
            
            switch($_REQUEST['action']) {
                case 'list-states':

                    $state_list = $address->listState();
                    if(!$state_list) {
                        throw new Exception($address->getError());
                    }
                    // Assign data to template
                    $smarty->assign('liststates', $state_list);
                break;
                case 'list-cities':
                    if( !isset($_REQUEST['state_id'])) {
                        throw new Exception("Por favor informe o state_id.");
                    }
                    // Search filter
                    $search=null;
                    if( isset($_REQUEST['search'])) {
                        $search = $_REQUEST['search'];
                    }
                    
                    $state_id = (int) $_REQUEST['state_id'];
                    $city_list = $address->listCity($state_id, $search);

                    if(!$city_list) {
                        throw new Exception($address->getError());
                    }
                    // Assign data to template
                    $smarty->assign('citylist', $city_list);
                break;               
            }
        break;

        default:
            // Case passed service (and|or) action are unexpected
            $status = "NOT_OK";
            $message = "Action (and|or) Service was unexpected.";
            $dataPartial = "error.tpl";
        break;
    }

    // Defining sub-template "service-action.tpl"
    $dataPartial = sprintf("%s-%s.tpl", $_REQUEST['service'], $_REQUEST['action']);    
} catch (Exception $e) {
    $status = "NOT_OK";
    $message = $e->getMessage();
    $dataPartial = "error.tpl";
}

// Show template
$smarty->assign("status", $status);
$smarty->assign("message", $message);
$smarty->assign("dataPartial", $dataPartial);

//$smarty->display("result.tpl");
$output  = $smarty->fetch("result.tpl");

// If passa callback returns JSONP
if( isset($_REQUEST['callback'])) {
    echo sprintf("%s(%s);", $_REQUEST['callback'], $output);
} else {
    echo $output;
}
?>