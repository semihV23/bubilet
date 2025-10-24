<?php

require_once __DIR__ . "/classes/User.php";

$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = strtok($request_uri, '?');

session_start();

switch ($request_uri) {
    case '/':
        require __DIR__ . '/templates/anasayfa.php';
        break;
    case '/girisyap':
        require __DIR__ . '/templates/girisyap.php';
        break;
    case '/kayitol':
        require __DIR__ . '/templates/kayitol.php';
        break;
    case '/hesabim':
        require __DIR__ . '/templates/hesabim.php';
        break;
    case '/musteri/biletal':
        require __DIR__ . '/templates/musteri/biletal.php';
        break;
    case '/setup/init':
        require __DIR__ . '/utils/init.php';
        break;
    case '/test':
        require __DIR__ . '/classes/User.php';
        break;
    case '/admin/panel':
        if($_SESSION["user_role"] != Role::ADMIN->value){
            header("Location: /hesabim");
            exit;   
        }
        require __DIR__ . '/templates/admin/adminpanel.php';
        break;
    case '/admin/firmakontrol':
        if($_SESSION["user_role"] != Role::ADMIN->value){
            header("Location: /hesabim");
            exit;
        }
        require __DIR__ . '/templates/admin/firmakontrol.php';
        break;
    case '/admin/panel':
        if($_SESSION["user_role"] != Role::ADMIN->value){
            header("Location: /hesabim");
            exit;
        }
        require __DIR__ . '/templates/admin/adminpanel.php';
        break;
    case '/firma/seferler':
        if($_SESSION["user_role"] != Role::COMPANY->value){
            header("Location: /hesabim");
            exit;
        }
        require __DIR__ . '/templates/firma/seferkontrol.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/templates/404.php';
        break;
}
