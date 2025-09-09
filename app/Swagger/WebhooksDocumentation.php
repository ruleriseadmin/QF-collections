<?php

namespace App\Swagger;

class WebhooksDocumentation
{
    /**
     * @OA\Post(
     *     path="card_tokenization.success",
     *     operationId="cardTokenizationSuccess",
     *     tags={"Webhooks"},
     *     summary="Successful card tokenization",
     *     description="Notifies when a card tokenization is successful.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="card_tokenization.success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="authorizationCode", type="string", example="AUTH_gdutbv7fxk"),
     *                 @OA\Property(property="reusable", type="boolean", example=true),
     *                 @OA\Property(property="expYear", type="string", example="2030"),
     *                 @OA\Property(property="expMonth", type="string", example="12"),
     *                 @OA\Property(property="last4", type="string", example="4081"),
     *                 @OA\Property(property="active", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function cardTokenizationSuccess() {}

    /**
     * @OA\Post(
     *     path="payment_link.success",
     *     operationId="paymentLinkSuccess",
     *     tags={"Webhooks"},
     *     summary="Successful payment link",
     *     description="Notifies when a payment link transaction is successful.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="payment_link.success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="amount", type="number", example=5000),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function paymentLinkSuccess() {}

    /**
     * @OA\Post(
     *     path="transfer.success",
     *     operationId="transferSuccess",
     *     tags={"Webhooks"},
     *     summary="Successful transfer",
     *     description="Notifies when a transfer is successful.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="transfer.success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="amount", type="number", example=5000),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function transferSuccess() {}

    /**
     * @OA\Post(
     *     path="transfer.failed",
     *     operationId="transferFailed",
     *     tags={"Webhooks"},
     *     summary="Failed transfer",
     *     description="Notifies when a transfer fails.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="transfer.failed"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="amount", type="number", example=5000),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function transferFailed() {}

    /**
     * @OA\Post(
     *     path="transfer.reversed",
     *     operationId="transferReversed",
     *     tags={"Webhooks"},
     *     summary="Reversed transfer",
     *     description="Notifies when a transfer is reversed.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="transfer.reversed"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="amount", type="number", example=5000),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function transferReversed() {}

    /**
     * @OA\Post(
     *     path="direct_debit.success",
     *     operationId="directDebitSuccess",
     *     tags={"Webhooks"},
     *     summary="Successful direct debit",
     *     description="Notifies when a direct debit is successful.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="direct_debit.success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="reference", type="string", example="ref"),
     *                 @OA\Property(property="authorizationCode", type="string", example="AUTH_JV4T9Wawdj"),
     *                 @OA\Property(property="reusable", type="boolean", example=true),
     *                 @OA\Property(property="expYear", type="string", example="2034"),
     *                 @OA\Property(property="expMonth", type="string", example="1"),
     *                 @OA\Property(property="last4", type="string", example="1234"),
     *                 @OA\Property(property="active", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function directDebitSuccess() {}

    /**
     * @OA\Post(
     *     path="customer_identity.failed",
     *     operationId="customerIdentityFailed",
     *     tags={"Webhooks"},
     *     summary="Failed customer identity verification",
     *     description="Notifies when a customer identity verification fails.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="customer_identity.failed"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 ),
     *                 @OA\Property(property="reason", type="string", example="Account number or BVN is incorrect")
     *             )
     *         )
     *     )
     * )
     */
    public function customerIdentityFailed() {}

    /**
     * @OA\Post(
     *     path="virtual_account.failed",
     *     operationId="virtualAccountFailed",
     *     tags={"Webhooks"},
     *     summary="Failed virtual account creation",
     *     description="Notifies when virtual account creation fails.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="virtual_account.failed"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 ),
     *                 @OA\Property(property="reason", type="string", example="Virtual could not be created")
     *             )
     *         )
     *     )
     * )
     */
    public function virtualAccountFailed() {}

    /**
     * @OA\Post(
     *     path="virtual_account.success",
     *     operationId="virtualAccountSuccess",
     *     tags={"Webhooks"},
     *     summary="Successful virtual account creation",
     *     description="Notifies when virtual account creation is successful.",
     *     @OA\Response(
     *         response=200,
     *         description="Webhook Event Delivered Successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event", type="string", example="virtual_account.success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
     *                 ),
     *                 @OA\Property(property="accountName", type="string", example="PAYSTACK/John Doe"),
     *                 @OA\Property(property="accountNumber", type="string", example="1234567890"),
     *                 @OA\Property(property="currency", type="string", example="NGN"),
     *                 @OA\Property(property="bankName", type="string", example="Test Bank")
     *             )
     *         )
     *     )
     * )
     */
    public function virtualAccountSuccess() {}

    /**
 * @OA\Post(
 *     path="virtual_account.credit",
 *     operationId="virtualAccountCredit",
 *     tags={"Webhooks"},
 *     summary="Virtual Account Credit Event",
 *     description="Triggered when a virtual account receives a credit transaction.",
 *     @OA\Response(
 *         response=200,
 *         description="Webhook Event Delivered Successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="event", type="string", example="virtual_account.credit"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="customer",
 *                     type="object",
 *                     @OA\Property(property="email", type="string", example="customer@gmail.com")
 *                 ),
 *                 @OA\Property(property="amountInKobo", type="integer", example=10000),
 *                 @OA\Property(
 *                     property="sender",
 *                     type="object",
 *                     @OA\Property(property="accountNumber", type="string", example="XXXXXX1234"),
 *                     @OA\Property(property="accountName", type="string", example="John"),
 *                     @OA\Property(property="bankName", type="string", example="Test Bank")
 *                 ),
 *                 @OA\Property(property="customerAccountNumber", type="string", example="1234567890"),
 *                 @OA\Property(property="transactionRef", type="string", example="trx-MOOdlfcJGfJCSDiJ")
 *             )
 *         )
 *     )
 * )
 */
public function virtualAccountCredit() {}

}
