<?php


/**
 * Subscription
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 * @copyright (c) 2020, Kuza Lab
 * @package Kuzalab
 */

namespace Phelix\SafaricomSDP;



final class Subscription {

    /**
     * @var SDP
     */
    private $SDP;

    /**
     * Subscription constructor.
     * @param SDP $SDP
     */
    public function __construct(SDP $SDP) {
        $this->SDP = $SDP;
    }

    /**
     * Activate subscription of a user to a given offer code
     *
     * @param $requestId
     * @param $offerCode
     * @param $phoneNumber
     * @return array
     */
    public function activateSubscription($requestId, $offerCode, $phoneNumber) {

        $body = [
            "requestId" => $requestId,
            "requestTimeStamp" => $this->SDP->generateTimestamp(),
            "Channel"=> "SMS",
            "Operation"=> "ACTIVATE",
            "requestParam"=> [
                "data"=> [
                    [
                        "name"=> "OfferCode",
                        "value"=> $offerCode
                    ],
                    [
                        "name"=> "Msisdn",
                        "value"=> $phoneNumber
                    ],
                    [
                        "name"=> "Language",
                        "value"=> "1"
                    ],
                    [
                        "name"=> "CpId",
                        "value"=> $this->SDP->cpId
                    ]
                ]
            ]
        ];

        $response = $this->SDP->request->request("post", "public/SDP/activate", $this->SDP->token, $body);

        return Response::getResponse($response);
    }

    /**
     * Unsubscribe a user from an offer code
     *
     * @param $requestId
     * @param $offerCode
     * @param $phoneNumber
     * @return array
     */
    public function deactivateSubscription($requestId, $offerCode, $phoneNumber) {

        $body =  [
            "requestId"=> $requestId,
            "requestTimeStamp"=> $this->SDP->generateTimestamp(),
            "channel"=> "3",
            "operation"=> "DEACTIVATE",
            "requestParam"=> [
                "data"=> [
                    [
                        "name"=> "OfferCode",
                        "value"=> $offerCode
                    ],
                    [
                        "name"=> "Msisdn",
                        "value"=> $phoneNumber
                    ],
                    [
                        "name"=> "CpId",
                        "value"=> $this->SDP->cpId
                    ]
                ]
            ]
        ];

        $response = $this->SDP->request->request("post", "public/SDP/deactivate", $this->SDP->token, $body);

        return Response::getResponse($response);
    }

}