<?php

namespace App\Swagger\Transfer;

class TransfersDocumentation
{
        /**
     * @OA\Post(
     *     path="/transfer/initiate",
     *     operationId="transferInitiate",
     *     tags={"Transfer"},
     *     summary="Initiate a bank transfer",
     *     description="Send money to your customers.",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
    *                  required={"name", "accountNumber", "bankCode", "amountInKobo"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The recipient's name according to their account registration",
     *                     example="Tolu Robert"
     *                 ),
     *                 @OA\Property(
     *                     property="accountNumber",
     *                     type="string",
     *                     description="",
     *                     example="0000000000"
     *                 ),
     *                 @OA\Property(
     *                     property="bankCode",
     *                     type="string",
     *                     description="",
     *                     example="057"
     *                 ),
     *                 @OA\Property(
     *                     property="amountInKobo",
     *                     type="string",
     *                     description="Amount in kobo",
     *                     example="1000"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Reason from the transfer",
     *                     example="payment for order 1234"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, Failure, or Validation Error",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(ref="#/components/schemas/FailedResponse"),
     *                 @OA\Schema(ref="#/components/schemas/ValidationErrorResponse")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid authentication",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     */
    public function initiateTransfer(){}

            /**
     * @OA\Post(
     *     path="/transfer/finalize",
     *     operationId="transferFinalizeInitiate",
     *     tags={"Transfer"},
     *     summary="Finalize an initiated transfer",
     *     description="Request only when transaction type gotten from initiate transfer value is 'initiate_transfer'",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
    *                  required={"reference", "otp"},
     *                 @OA\Property(
     *                     property="reference",
     *                     type="string",
     *                     description="The reference from initiate transfer",
     *                     example="TRF_vsyqdmlzble3uii"
     *                 ),
     *                 @OA\Property(
     *                     property="otp",
     *                     type="string",
     *                     description="",
     *                     example="928783"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, Failure, or Validation Error",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(ref="#/components/schemas/FailedResponse"),
     *                 @OA\Schema(ref="#/components/schemas/ValidationErrorResponse")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid authentication",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     */
    public function finalizeTransfer(){}

    /**
     * @OA\Post(
     *     path="/transfer/create-recipient",
     *     operationId="createTransferRecipient",
     *     tags={"Transfer"},
     *     summary="",
     *     description="",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "accountNumber", "bankCode"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The recipient's name according to their account registration",
     *                     example="Tolu Robert"
     *                 ),
     *                 @OA\Property(
     *                     property="accountNumber",
     *                     type="string",
     *                     description="",
     *                     example="0000000000"
     *                 ),
     *                 @OA\Property(
     *                     property="bankCode",
     *                     type="string",
     *                     description="",
     *                     example="057"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, Failure, or Validation Error",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(ref="#/components/schemas/FailedResponse"),
     *                 @OA\Schema(ref="#/components/schemas/ValidationErrorResponse")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid authentication",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     */
    public function createTransferRecipient(){}

    /**
     * @OA\Post(
     *     path="/transfer/initiate-via-recipient",
     *     operationId="initiateTransferViaRecipientCode",
     *     tags={"Transfer"},
     *     summary="Initiate a bank transfer via recipient code",
     *     description="Send money to your customers.",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"recipientCode", "amountInKobo"},
     *                 @OA\Property(
     *                     property="recipientCode",
     *                     type="string",
     *                     description="Amount in kobo",
     *                     example="1000"
     *                 ),
     *                  @OA\Property(
     *                     property="amountInKobo",
     *                     type="string",
     *                     description="Amount in kobo",
     *                     example="1000"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Reason from the transfer",
     *                     example="payment for order 1234"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success, Failure, or Validation Error",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(ref="#/components/schemas/FailedResponse"),
     *                 @OA\Schema(ref="#/components/schemas/ValidationErrorResponse")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid authentication",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     */
    public function initiateTransferViaRecipientCode(){}
}
