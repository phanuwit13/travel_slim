<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Datapath
{
    public function getPathSelect(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * FROM `path` where firstPath =
        '" . $formData['firstPath'] . "' && endPath = '" . $formData['endPath'] . "'"
        );

        if ($result['rowCount'] > 0) {

            $responseData = array(
                "message" => "พบสถานที่",
                "success" => true,
                "data" => $result['result'][0],
            );
        } else {
            $responseData = array(
                "message" => "ไม่พบสถานที่",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function getPathSelectOne(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $arr = explode(",",$formData['placeSuc']);
        $responseData = null;

        $n = count($arr)-1;
        $str = "firstPath = '" . $formData['firstPath'] . "' && endPath != '" . $formData['firstPath'] . "'";
        for($i = 0 ; $i < $n ; $i++)
        {
            $str =$str." && endPath != '" . $arr[$i] . "'" ;
        }

        $result = $db->query("SELECT * FROM `path` where ($str) ORDER BY distance"
        );

        if ($result['rowCount'] > 0) {

            $responseData = array(
                "message" => "พบสถานที่",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "ไม่พบสถานที่",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function setPath(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("INSERT into path values(null,'" . $formData['firstPath'] . "','" . $formData['endPath'] . "',
        '" . $formData['distance'] . "','" . $formData['distanceText'] . "','" . $formData['fare'] . "')");

        if ($result['rowCount'] > 0) {

            $responseData = array(
                "message" => "บันทึกสำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "บันทึกเส้นทางซ้ำ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function updateFare(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $baseFare =  $formData['baseFare'];
        $nextFare =  $formData['nextFare'];

        $result = $db->query("UPDATE base_fare SET baseFare = '" . $formData['baseFare'] . "' , nextFare = '" . $formData['nextFare'] . "'
        where baseNo = 1");

        if ($result['result'] == "true") {

            $result = $db->query("UPDATE path SET fare = $baseFare + ceiling((DISTANCE/1000)-1)*$nextFare ");

            if ($result['result']  == "true" ) {
                $result = $db->query("UPDATE path SET fare = $baseFare where DISTANCE <= 1000 ");

                $responseData = array(
                    "message" => "บันทึกสำเร็จ",
                    "success" => true,
                    "data" => $result['result'],
                );
            } else {
                $responseData = array(
                    "message" => "บันทึกค่าโดยสารไม่สำเร็จ",
                    "success" => false,
                );
            }

        } else {
            $responseData = array(
                "message" => "บันทึกค่าโดยสารไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function getFare(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * from base_fare");

        if ($result['rowCount'] > 0) {

            
                $responseData = array(
                    "message" => "พบค่าโดยสาร",
                    "success" => true,
                    "data" => $result['result'],
                );

        } else {
            $responseData = array(
                "message" => "ไม่พบค่าโดยสาร",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }

}
