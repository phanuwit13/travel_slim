<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Member
{
    public function login( Request $request, Response $response, $args )
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query( "SELECT * FROM user
        where username = '" . $formData['username'] . "' LIMIT 1"
        );

        if ( $result['rowCount'] > 0 ) {
            if ( $result['result'][0]['password'] == $formData['password'] ) {
                $responseData = array(
                    "message" => "เข้าสู่ระบบสำเร็จ",
                    "success" => true,
                    "data"    => $result['result'][0],
                );
            } else {
                $responseData = array(
                    "message" => "เข้าสู่ระบบไม่สำเร็จ",
                    "success" => false,
                );
            }

        } else {
            $responseData = array(
                "message" => "ไม่พบผู้ข้อมูลผู้ใช้งาน",
                "success" => false,
            );
        }

        $response->getBody()->write( \json_encode( $responseData ) );
        return $response;
    }
}