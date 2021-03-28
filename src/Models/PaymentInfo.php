<?php

namespace zkelo\Unitpay\Models;

/**
 * PaymentInfo model
 *
 * This is read-only model
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 * @property string $status Payment status
 * @property integer $paymentId Payment ID
 * @property integer $projectId Project ID
 * @property string $account Account ID
 * @property string $purse Personal account ID that used to pay
 * @property float $profit Income of payment
 * @property string $paymentType Payment method
 * @property float $orderSum Order amount
 * @property string $orderCurrency Order currency
 * @property string $date Order date and time in `Y-m-d H:i:s` format
 * @property float $payerSum Amount that paid from customer personal account
 * @property string $payerCurrency Currency of debiting from the customer personal account
 * @property string $receiptUrl Link to invoice
 * @property string|null $errorMessage Details of error _(returns only if payment has error status)_
 */
class PaymentInfo
{
    /**
     * Payment status: Success
     */
    const STATUS_SUCCESS = 'success';

    /**
     * Payment status: Waiting
     */
    const STATUS_WAIT = 'wait';

    /**
     * Payment status: Error
     */
    const STATUS_ERROR = 'error';

    /**
     * Payment status: Error on `PAY` stage
     */
    const STATUS_ERROR_PAY = 'error_pay';

    /**
     * Payment status: Error on `CHECK` stage
     */
    const STATUS_ERROR_CHECK = 'error_check';

    /**
     * Payment status: Refunding
     */
    const STATUS_REFUND = 'refund';

    /**
     * Payment status: Bank security service checks payment
     */
    const STATUS_SECURE = 'secure';

    /**
     * List of model attributes
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Construct a new model
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * Checks if attribute exists
     *
     * @param string $name Attribute name
     * @return boolean `true` if attribute exists or `false` if not
     */
    public function has(string $name): bool
    {
        return isset($this->attributes[$name]);
    }
}
