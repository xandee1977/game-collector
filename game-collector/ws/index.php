<?php
header('Access-Control-Allow-Origin: *'); // Allow Cross-Domain
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

header("Content-type: application/json; charset=utf-8", true);// Setting charset

ini_set('max_execution_time', 0);
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
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
    // Verifying the parameters
    if( !isset($_REQUEST['callback'])) {
        $callBack = 'collectorWSCallBack';
    } else {
        $callBack = $_REQUEST['callback'];
    }    
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

            switch($_REQUEST['action']) {
                // Game detail
                case 'detail':
                break;
                
                // List the games
                case 'list';
                    $gameObj = new Game();
                    
                    $limit = false;
                    if($_REQUEST["limit"]) {
                        $limit = $_REQUEST["limit"];
                    }

                    $games = $gameObj->findGames($limit);
                    
                    if(!$games) {
                        throw new Exception($gameObj->getError());
                    }
                    
                    $smarty->assign("games", $games);
                break;

                case 'flag-watch':
                    $req_fields = ['game_id', 'user_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }
                break;

                case 'flag-favorite':
                    $req_fields = ['game_id', 'user_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }        
                break;

                case 'flag-have':
                    $req_fields = ['game_id', 'user_id'];
                    foreach($req_fields as $field) {
                        if( !isset($_REQUEST[$field])) {
                            throw new Exception( sprintf("Por favor informe; %s.", $field) );
                        }
                    }        
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
//echo $output;
echo sprintf("%s(%s);", $callBack, $output);
?>