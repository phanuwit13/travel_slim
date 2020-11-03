<?php

require './vendor/autoload.php';

$app = new \Slim\App;
//config
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST')
        ->withHeader('Content-Type', 'application/json');
});

//login
$app->post('/login', \App\Controller\Member::class . ":login");
//สถานที่ทั้งหมด
$app->post('/getPlace', \App\Controller\Dataplace::class . ":getPlace");
//ประเภทสถานที่
$app->post('/getCategory', \App\Controller\Dataplace::class . ":getCategory");
//แก้ไขประเภท
$app->post('/editCategory', \App\Controller\Dataplace::class . ":editCategory");
//เพิ่มไขประเภท
$app->post('/addCategory', \App\Controller\Dataplace::class . ":addCategory");
//ลบประเภท
$app->post('/delCategory', \App\Controller\Dataplace::class . ":delCategory");

//สถาที่เรียงตามประเภท
$app->post('/getPlaceCategory', \App\Controller\Dataplace::class . ":getPlaceCategory");
//ค้นหาสถานที่
$app->post('/getPlaceSearch', \App\Controller\Dataplace::class . ":getPlaceSearch");
//เพิ่มสถานที่
$app->post('/setPlace', \App\Controller\Dataplace::class . ":setPlace");
//แก้ไขสถานที่
$app->post('/setPlaceEdit', \App\Controller\Dataplace::class . ":setPlaceEdit");
//ลบสถานที่
$app->post('/delPlace', \App\Controller\Dataplace::class . ":delPlace");

//คนหาระยะทาง
$app->post('/getPathSelect', \App\Controller\Datapath::class . ":getPathSelect");
//คนหาระยะทางต้นทาง
$app->post('/getPathSelectOne', \App\Controller\Datapath::class . ":getPathSelectOne");
//บันทึกเส้นทาง
$app->post('/setPath', \App\Controller\Datapath::class . ":setPath");
//อัพเดทค่าโดยสาร
$app->post('/updateFare', \App\Controller\Datapath::class . ":updateFare");
//อ่านค่าโดยสาร
$app->post('/getFare', \App\Controller\Datapath::class . ":getFare");

$app->post('/uploadImg', \App\Controller\Dataplace::class . ":uploadImg");

$app->run();
