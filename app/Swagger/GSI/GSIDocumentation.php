<?php

namespace App\Swagger\GSI;

class GSIDocumentation
{
    /**
     * @OA\Post(
     *     path="/gsi/profile-customer",
     *     operationId="GSIProfileCustomer",
     *     tags={"GSI: Global Standing Instruction"},
     *     summary="Profile Customer",
     *     description="Profiles a customer and returns a GSI ID for the customer.",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "lastName", "firstName", "address", "phoneNumber"},
     *                 @OA\Property(
     *                     property="identity",
     *                     type="object",
     *                     @OA\Property(property="type", type="string", example="nin"),
     *                     @OA\Property(property="number", type="string", example="72232218030")
     *                 ),
     *                 @OA\Property(property="email", type="string", example="johndoe@gmail.com"),
     *                 @OA\Property(property="lastName", type="string", example="Doe"),
     *                 @OA\Property(property="firstName", type="string", example="John"),
     *                 @OA\Property(property="address", type="string", example="Some Address"),
     *                 @OA\Property(property="phoneNumber", type="string", example="09112345678")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request processed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="200"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="firstName", type="string", example="Doe"),
     *                 @OA\Property(property="lastName", type="string", example="John"),
     *                 @OA\Property(property="email", type="string", example="johndoe@gmail.com"),
     *                 @OA\Property(property="phoneNumbers", type="string", nullable=true, example=null),
     *                 @OA\Property(property="address", type="string", nullable=true, example=null),
     *                 @OA\Property(property="gsiId", type="string", example="673d9784e215dd6a14e1f71a")
     *             ),
     *             @OA\Property(property="message", type="string", example="Request processed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid authentication",
     *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     */
    public function monoProfileCustomer() {}
}
