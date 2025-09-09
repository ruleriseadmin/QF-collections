<?php

namespace App\Swagger\AccessGateway;

class WebhookDocumentation
{
    /**
     * @OA\Post(
     *     path="/settings/update-webhook-url",
     *     operationId="updateWebhookUrl",
     *     tags={"Settings"},
     *     summary="Update webhook url",
     *     description="This updates the webhook url",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *               required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="webhookUrl",
     *                     type="string",
     *                     example="https://www.example.com"
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
    public function updateWebhookUrlDocumentation(){}
}
