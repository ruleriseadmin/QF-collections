<?php

namespace App\Swagger;


class DirectDebitDocumentation
{
    /**
     * @OA\Post(
     *     path="/direct-debit/initiate",
     *     operationId="directDebitInitiate",
     *     tags={"Direct Debit"},
     *     summary="",
     *     description="",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
    *                  required={"email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="customer email",
     *                     example="ravi@demo.com"
     *                 ),
     *                 @OA\Property(
     *                     property="callbackUrl",
     *                     type="string",
     *                     description="optional",
     *                     example="http://test.url.com"
     *                 ),
     *                 @OA\Property(
     *                     property="account",
     *                     type="object",
     *                      @OA\Property(property="number", type="string", example="0128034955"),
     *                      @OA\Property(property="bank_code", type="string", example="058")
     *                 ),
     *                   @OA\Property(
     *                     property="address",
     *                     type="object",
     *                      @OA\Property(property="state", type="string", example="Lagos"),
     *                      @OA\Property(property="city", type="string", example="Akoka"),
     *                      @OA\Property(property="street", type="string", example="17 Beckley Avenue")
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
    public function initiate(){}
}
