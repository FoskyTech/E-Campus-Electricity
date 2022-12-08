<?php

require_once '../vendor/autoload.php';

use FoskyTech\ECampusElectricity;

$config = [
    'shiroJID'  =>  '',
    'ymId'  =>  ''
];

// 初始化
$ECE = new ECampusElectricity($config);

// 也可以用这种方式
// $ECE = new ECampusElectricity();
// $ECE->setConfig($config);

// 获取学校校区列表
$areaInfo = $ECE->queryArea();
$areaId = $areaInfo['data'][0]['id'];
// print_r($areaInfo);
// 获取指定校区宿舍楼列表
$buildingList = $ECE->queryBuilding($areaId);
$buildingCode = $buildingList['data'][0]['buildingCode'];

// 获取指定校区的指定宿舍楼的楼层列表
$floorList = $ECE->queryFloor($areaId, $buildingCode);
$floorCode = $floorList['data'][0]['floorCode'];

// 获取指定校区的指定宿舍楼的指定楼层的房间列表
$roomList = $ECE->queryRoom($areaId, $buildingCode, $floorCode);
$roomCode = $roomList['data'][0]['roomCode'];

// 获取指定房间的电费和名称
$roomInfo = $ECE->queryRoomSurplus($areaId, $buildingCode, $floorCode, $roomCode);
$surplus = $roomInfo['data']['surplus'];
$name = $roomInfo['data']['roomName'];

echo '房间：' . $name . ' 当前余额：' . $surplus;