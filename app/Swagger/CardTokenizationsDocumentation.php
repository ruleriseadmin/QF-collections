<?php

namespace App\Swagger;

class CardTokenizationsDocumentation
{
    /**
     * @OA\Post(
     *     path="/card-tokenization/initiate",
     *     operationId="cardTokenizationInitiation",
     *     tags={"Card Tokenization"},
     *     summary="Generating a Payment Link for card tokenization",
     *     description="The generates the payment link to handle card tokenization",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="customer email",
     *                     example="test@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="callbackUrl",
     *                     type="string",
     *                     description="optional",
     *                     example="http://test.url.com"
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
    public function initiateCardTokenizationDocumentation(){}
}
