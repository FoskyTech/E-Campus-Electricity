<?php
/**
 * 易校园电费获取类
 * @author  Mouse123s <i@mouse123.cn>
 * @version 1.0
 */
namespace FoskyTech;

class ECampusElectricity
{
    private $config = [
        'shiroJID'  =>  '',
        'ymId'  =>  ''
    ];

    public function __construct(array $config = []) {
        if (is_array($config) && !empty($config)) {
            $this->config = $config;
        }
    }

    public function setConfig(array $config = []) {
        if (is_array($config) && !empty($config)) {
            $this->config = $config;
        }
    }

    public function schoolInfo() {
        $data = $this->request('getCoutomConfig', ['customType' => 1]);

        if ($data['success'] === true) {
            return [
                'error' =>  0,
                'data'  =>  [
                    'schoolCode'    =>  $data['data']['schoolCode'],
                    'schoolName'    =>  $data['data']['schoolName']
                ]
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    public function queryArea() {
        $data = $this->request('queryArea', ['type' => 1]);

        if ($data['success'] === true) {
            foreach ($data['rows'] as $k => $v) {
                unset($data['rows'][$k]['paymentChannel']);
                unset($data['rows'][$k]['isBindAfterRecharge']);
                unset($data['rows'][$k]['bindRoomNum']);
            }
            return [
                'error' =>  0,
                'data'  =>  $data['rows']
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    /**
     * 方法 queryBuilding
     * @param mixed $areaId 校区ID
     * @return array
     */
    public function queryBuilding($areaId) {
        $data = $this->request('queryBuilding', ['areaId' => $areaId]);

        if ($data['success'] === true) {
            return [
                'error' =>  0,
                'data'  =>  $data['rows']
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    /**
     * 方法 queryFloor
     * @param mixed $areaId 校区ID
     * @param mixed $buildingCode 宿舍楼代码
     * @return array
     */
    public function queryFloor($areaId, $buildingCode) {
        $data = $this->request('queryFloor', ['areaId' => $areaId, 'buildingCode' => $buildingCode]);

        if ($data['success'] === true) {
            return [
                'error' =>  0,
                'data'  =>  $data['rows']
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    /**
     * 方法 queryRoom
     * @param mixed $areaId 校区ID
     * @param mixed $buildingCode 宿舍楼代码
     * @param mixed $floorCode 楼层代码
     * @return array
     */
    public function queryRoom($areaId, $buildingCode, $floorCode) {
        $data = $this->request('queryRoom', ['areaId' => $areaId, 'buildingCode' => $buildingCode, 'floorCode' => $floorCode]);

        if ($data['success'] === true) {
            return [
                'error' =>  0,
                'data'  =>  $data['rows']
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    /**
     * 方法 queryRoomSurplus
     * @param mixed $areaId 校区ID
     * @param mixed $buildingCode 宿舍楼代码
     * @param mixed $floorCode 楼层代码
     * @param mixed $roomCode 寝室代码
     * @return array
     */
    public function queryRoomSurplus($areaId, $buildingCode, $floorCode, $roomCode) {
        $data = $this->request('queryRoomSurplus', ['areaId' => $areaId, 'buildingCode' => $buildingCode, 'floorCode' => $floorCode, 'roomCode' => $roomCode]);

        if ($data['success'] === true) {
            return [
                'error' =>  0,
                'data'  =>  [
                    'surplus'    =>  $data['data']['amount'],
                    'roomName' =>  $data['data']['displayRoomName']
                ]
            ];
        }

        return [
            'error' =>  1,
            'error_description'    =>  $this->errcode($data['statusCode'])
        ];
    }

    private function errcode($code = 0) {
        switch ($code) {
            case 204:
                return 'shiroJID无效';
            case 0:
            default:
                return '未知错误';
        }
    }

    private function request(string $uri = '', array $param = []) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->get_uri($uri) . '?ymId=' . $this->config['ymId'] . '&platform=YUNMA_APP&' . http_build_query($param),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                'Cookie: shiroJID=' . $this->config['shiroJID']
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return false;
        }

        $data = json_decode($response, true);

        return $data;
    }
    private function get_uri(string $uri = '') {
        return 'https://application.xiaofubao.com/app/electric/' . $uri;
    }
}