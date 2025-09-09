<?php

namespace App\Swagger;

class PaymentLinksDocumentation
{
        /**
     * @OA\Post(
     *     path="/payment-links/initiate",
     *     operationId="paymentLinksInitiate",
     *     tags={"Payment Link"},
     *     summary="Generating a Payment Link",
     *     description="The generates the payment links",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="customer email"
     *                 ),
     *                 @OA\Property(
     *                     property="amountInKobo",
     *                     type="string",
     *                     description="amount in kobo",
     *                     example="500000"
     *                 ),
     *                 @OA\Property(
     *                     property="callbackUrl",
     *                     type="string",
     *                     description="optional",
     *                     example="http://test.url.com"
     *                 ),
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
    public function generatePaymentLink(){}
}
