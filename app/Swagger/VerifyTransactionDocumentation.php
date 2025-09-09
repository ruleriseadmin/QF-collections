<?php

namespace App\Swagger;

class VerifyTransactionDocumentation
{
        /**
     * @OA\Get(
     *     path="/transaction/verify/{reference}",
     *     operationId="directDebitVerify",
     *     tags={"Transactions"},
     *     summary="Verify transaction",
     *     description="Verify transaction either direct debit initiation status, transfer, card tokenization, payment links",
     *     security={{"BearerAuth": {}}},
     *      @OA\Parameter(
     *         name="reference",
     *         in="path",
     *         required=true,
     *         description="Unique reference from the initialization request",
     *         @OA\Schema(
     *             type="string",
     *             example="dfbzfotsrbv4n5s82t4mp5b5mfn51h"
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
    public function __invoke(){}
}
