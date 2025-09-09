<?php

namespace App\Swagger;

class VirtualAccountDocumentation
{
                /**
     * @OA\Post(
     *     path="/create-virtual-account",
     *     operationId="VirtualAccount",
     *     tags={"Virtual Account"},
     *     summary="",
     *     description="",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email", example="test@example.com"),
 *             @OA\Property(property="preferred_bank", type="string", example="test-bank"),
 *             @OA\Property(property="first_name", type="string", example="john"),
 *             @OA\Property(property="middle_name", type="string", example="joe"),
 *             @OA\Property(property="last_name", type="string", example="doe"),
 *             @OA\Property(property="phone", type="string", example="08012345678"),
 *             @OA\Property(property="account_number", type="string", example="1234567890"),
 *             @OA\Property(property="bvn", type="string", example="12345678901"),
 *             @OA\Property(property="bank_code", type="string", example="044")
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
    public function createVirtualAccountDocumentation(){}
}
