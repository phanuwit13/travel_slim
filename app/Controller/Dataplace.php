<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Dataplace
{
    public function getPlace(Request $request, Response $response, $args)
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * FROM `place` LEFT JOIN category ON place.categoryNo = category.categoryNo"
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
    //////////////////////////
    public function getCategory(Request $request, Response $response, $args)
    {
      
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * FROM `category` "
        );

        if ($result['rowCount'] > 0) {

            $responseData = array(
                "message" => "พบประเภทสถานที่",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "ไม่พบประเภทสถานที่",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function editCategory(Request $request, Response $response, $args)
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("UPDATE category SET
            categoryTH = '" . $formData['categoryTH'] . "',
            categoryEN = '" . $formData['categoryEN'] . "'
            WHERE categoryNo = '" . $formData['categoryNo'] . "'"
        );

        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "แก้ไขประเภทสำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "แก้ไขประเภทไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function addCategory(Request $request, Response $response, $args)
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("INSERT into category (categoryTH,categoryEN) VALUES('" . $formData['categoryTH'] . "','" . $formData['categoryEN'] . "')");

        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "เพิ่มประเภทสำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "เพิ่มประเภทไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function delCategory(Request $request, Response $response, $args)
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("DELETE from category WHERE categoryNo = '" . $formData['categoryNo'] . "' ");

        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "ลบประเภทสำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "ลบประเภทไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    /////////////////////////////////
    public function getPlaceCategory(Request $request, Response $response, $args)
    {
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * FROM `place` LEFT JOIN category
        ON place.categoryNo = category.categoryNo where place.categoryNo = '" . $formData['categoryNo'] . "'"
        );

        if ($result['rowCount'] > 0) {
            $responseData = array(
                "message" => "พบประเภทสถานที่",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "ไม่พบประเภทสถานที่",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function getPlaceSearch(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("SELECT * FROM `place` LEFT JOIN category ON place.categoryNo = category.categoryNo
        where placeTH like '%" . $formData['key'] . "%' ||placeEN like '%" . $formData['key'] . "%' ||
        categoryTH like '%" . $formData['key'] . "%'");

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

    public function setPlace(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("INSERT into place values(null,'" . $formData['categoryNo'] . "','" . $formData['placeTH'] . "',
        '" . $formData['placeEN'] . "','" . $formData['img'] . "','" . $formData['detail'] . "' ,'" . $formData['latitude'] . "','" . $formData['longitude'] . "')");

        $files = $request->getUploadedFiles();
        if (empty($files['image'])) {

        } else {
            $myFile = $files['image'];
            if ($myFile->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $myFile->getClientFilename();
                $myFile->moveTo('img/' . $uploadFileName);
                $responseData = array(
                    "message" => "เพิ่มรูปสำเร็จ",
                    "success" => true,
                );
            }
        }

        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "เพิ่มสถานที่สำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "เพิ่มสถานที่ไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function delPlace(Request $request, Response $response, $args)
    {

        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("DELETE from path WHERE firstPath = '" . $formData['placeNo'] . "' || endPath = '" . $formData['placeNo'] . "' ");
        $result = $db->query("DELETE from place WHERE placeNo = '" . $formData['placeNo'] . "' ");
        
        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "ลบสถานที่สำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "ลบสถานที่ไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }

    public function setPlaceEdit(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        $formData = $request->getParams();
        $db = new \App\Tools\Database();
        $responseData = null;
        $result = $db->query("UPDATE place SET
        categoryNo = '" . $formData['categoryNo'] . "',
        placeTH = '" . $formData['placeTH'] . "',
        placeEN = '" . $formData['placeEN'] . "',
        img = '" . $formData['img'] . "',
        detail = '" . $formData['detail'] . "'
        where placeNo = '" . $formData['placeNo'] . "'
        ");
        $files = $request->getUploadedFiles();
        if (empty($files['image'])) {

        } else {
            $myFile = $files['image'];
            if ($myFile->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $myFile->getClientFilename();
                $myFile->moveTo('img/' . $uploadFileName);
                $responseData = array(
                    "message" => "เพิ่มรูปสำเร็จ",
                    "success" => true,
                );
            }
        }

        if ($result['result'] == "true") {

            $responseData = array(
                "message" => "แก้ไขสถานที่สำเร็จ",
                "success" => true,
                "data" => $result['result'],
            );
        } else {
            $responseData = array(
                "message" => "แก้ไขสถานที่ไม่สำเร็จ",
                "success" => false,
            );
        }

        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
    public function uploadImg(Request $request, Response $response, $args)
    {
        $files = $request->getUploadedFiles();
        if (empty($files['image'])) {
            $responseData = array(
                "message" => "เพิ่มรูปไม่สำเร็จ",
                "success" => false,
            );
            $response->getBody()->write(\json_encode($responseData));
            return $response;
        }
        $myFile = $files['image'];
        if ($myFile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $myFile->getClientFilename();
            $myFile->moveTo('img/' . $uploadFileName);
            $responseData = array(
                "message" => "เพิ่มรูปสำเร็จ",
                "success" => true,
            );
        }
        $response->getBody()->write(\json_encode($responseData));
        return $response;
    }
}
