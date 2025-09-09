<?php

namespace App\Swagger;

/**
 * @OA\Info(title="Collection Service Documentation", version="1.0.0")
  * @OA\Components(
     *     @OA\SecurityScheme(
     *         securityScheme="BearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT"
     *             ),
     *     @OA\Schema(
     *         schema="SuccessResponse",
     *         type="object",
     *         @OA\Property(property="status", type="string", example="200"),
     *         @OA\Property(property="message", type="string", example="Request processed successfully"),
     *         @OA\Property(property="data", type="object")
     *     ),
     *     @OA\Schema(
     *         schema="FailedResponse",
     *         type="object",
     *         @OA\Property(property="status", type="string", example="300"),
     *         @OA\Property(property="message", type="string", example="Failed to process request"),
     *         @OA\Property(property="data", type="object")
     *     ),
     *     @OA\Schema(
     *         schema="ValidationErrorResponse",
     *         type="object",
     *         @OA\Property(property="status", type="string", example="payloadValidationError"),
     *         @OA\Property(property="message", type="string", example="Validation error"),
     *         @OA\Property(property="data", type="object")
     *     ),
     *     @OA\Schema(
     *         schema="UnauthorizedResponse",
     *         type="object",
     *         @OA\Property(property="status", type="string", example="401"),
     *         @OA\Property(property="message", type="string", example="Unauthorized access"),
     *         @OA\Property(property="data", type="object")
     *     )
     * )
     */

class BaseDocumentation
{
}
