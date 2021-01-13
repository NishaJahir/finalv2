<?php
/**
 * This module is used for real time processing of
 * Novalnet payment module of customers.
 * This free contribution made by request.
 *
 * If you have found this script useful a small
 * recommendation as well as a comment on merchant form
 * would be greatly appreciated.
 *
 * @author       Novalnet AG
 * @copyright(C) Novalnet
 * All rights reserved. https://www.novalnet.de/payment-plugins/kostenlos/lizenz
 */

namespace Novalnet\Methods;

use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Payment\Method\Services\PaymentMethodBaseService;
use Plenty\Plugin\Application;
use Novalnet\Helper\PaymentHelper;
use Novalnet\Services\PaymentService;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;

/**
 * Class NovalnetInsbyInvPaymentMethod
 * @package Novalnet\Methods
 */
class NovalnetInsbyInvPaymentMethod extends PaymentMethodBaseService
{
    /**
     * @var ConfigRepository
     */
    private $config;

    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    
    /**
     * @var Basket
     */
    private $basket;
    
     /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * NovalnetInsbyInvPaymentMethod constructor.
     *
     * @param ConfigRepository $config
     * @param PaymentHelper $paymentHelper
     * @param BasketRepositoryContract $basket
     */
    public function __construct(ConfigRepository $config,
                                PaymentHelper $paymentHelper,
                                BasketRepositoryContract $basket,
                                PaymentService $paymentService
                               )
    {
        $this->config = $config;
        $this->paymentHelper = $paymentHelper;
        $this->basket = $basket->load();
        $this->paymentService = $paymentService;
    }

    /**
     * Check the configuration if the payment method is active
     *
     * @return bool
     */
    public function isActive():bool
    {
		return true;
    }
	
    /**
     * Get the name of the payment method. The name can be entered in the configuration.
     *
     * @return string
     */
    public function getName(string $lang = 'de'):string
    {
        $paymentName = trim($this->config->get('Novalnet.novalnet_instalment_invoice_payment_name'));
        return ($paymentName ? $paymentName : $this->paymentHelper->getTranslatedText('novalnet_instalment_invoice'));
    }

    /**
     * Returns a fee amount for this payment method.
     *
     * @return float
     */
    public function getFee(): float
    {
        return 0.00;
    }


}
