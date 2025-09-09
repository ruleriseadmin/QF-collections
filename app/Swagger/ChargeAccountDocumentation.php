<?php

namespace App\Swagger;

class ChargeAccountDocumentation
{

        /**
     * @OA\Post(
     *     path="/transaction/charge-account",
     *     operationId="chargeAccount",
     *     tags={"Transactions"},
     *     summary="Charging an account",
     *     description="This charges an account and receive payment via authorization code",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *               required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="customer email",
     *                     example="test@example.com"
     *                 ),
     *                 @OA\Property(property="amountInKobo", type="string", example="20000"),
 *                     @OA\Property(property="authorizationCode", type="string", example="AUTH_72btv547"),
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
    public function chargeAccountDocumentation(){}
}
