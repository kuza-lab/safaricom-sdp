<?php


/**
 * Handle response formatting
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;


final class Utils {

    /**
     * Get response
     *
     * @param Request $response
     * @param null $debugLevel
     * @return array
     */
    public static function getResponse(Request $response, $debugLevel = null) {

        return [
            "success" => $response->success,
            "statusCode" => $response->statusCode,
            "statusText" => $response->statusText,
            "errorCode" => $response->errorCode,
            "errorMessage" => $response->errorMessage,
            "data" => $response->responseBody,
            "debugTrace" => (!is_null($debugLevel) && $debugLevel == 1) ? [
                'request' => $response->debugRequestTrace,
                'response' => $response->debugResponseTrace
            ] : null
        ];
    }

    /**
     * Get callback data
     */
    public static function getCallback() {

        $response = [
            "success" => false,
            "statusCode" => "",
            "statusText" => "",
            "errorCode" => "",
            "errorMessage" => "",
            "data" => null,
            "debugTrace" => null
        ];

        $bodyString = file_get_contents("php://input");

        if (!empty($bodyString)) {

            $body = json_decode(file_get_contents("php://input"), JSON_FORCE_OBJECT);

            if (isset($body['error']) && !empty($body['error']) && $body['error'] != 0) {
                $response['statusCode'] = isset($body['status']) ? $body['status'] : "";
                $response['statusText'] = $body['error'];
                $response['errorMessage'] = $body['message'];
                $response['errorCode'] = $response['status'];
            } else {
                $response['success'] = true;
                $response['statusCode'] = 200;
                $response['statusText'] = "OK";
                $response['errorCode'] = 0;
                $response['data'] = $body;
            }
        }
        return $response;
    }

    /**
     * Escape the given string
     * @param $input string to be escaped
     * @return string escaped string
     */
    private static function escape($input) {
        if(is_array($input)){
            array_walk_recursive($input,function (&$val,$key){
                $val = htmlspecialchars(trim($val), ENT_QUOTES);
            });
            return $input;
        }
        return htmlspecialchars(trim($input), ENT_QUOTES);
    }

    /**
     * Function to search array data for a specific value by the provided key
     * Returns the found array keys
     * @param array $arrayData
     * @param string $searchKey
     * @param string $searchValue
     * @return array|boolean
     */
    private static function searchMultiArrayByKeyReturnKeys($arrayData, $searchKey, $searchValue) {
        $size = is_array($arrayData) ? sizeof($arrayData) : 0;
        for ($i = 0; $i < $size; $i++) {
            if (strtolower($arrayData[$i][$searchKey]) == strtolower($searchValue)) {
                return $arrayData[$i];
            }
        }
        return false;
    }

    /**
     * Get SDP callback data from an array
     * @param $responseData
     * @param $item
     * @return mixed|string
     */
    public static function getCallbackResponseDataItemValue($responseData, $item) {

        $itemValue = "";

        $search = self::searchMultiArrayByKeyReturnKeys($responseData, "name", $item);

        if (sizeof($search) > 0) {
            $itemValue = $search['value'];
        }

        return $itemValue;
    }
}