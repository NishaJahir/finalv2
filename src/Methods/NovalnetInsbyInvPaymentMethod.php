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
	if ($this->config->get('Novalnet.novalnet_instalment_invoice_payment_active') == 'true') {
            $instalmentPaymentMimimumAmount = true;
            $minimumAmount = trim($this->config->get('Novalnet.novalnet_instalment_invoice_min_amount'));
            if (!empty($minimumAmount) && is_numeric($minimumAmount) && $minimumAmount < 1998) {
                $instalmentPaymentMimimumAmount = false;
            }
            

            return (bool)($this->paymentService->isPaymentActive($this->basket, 'novalnet_instalment_invoice') && $instalmentPaymentMimimumAmount);
        }
        return false;
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
	
    /**
     * Retrieves the description of the payment. The description can be entered in the configuration.
     *
     * @return string
     */
    public function getDescription(string $lang = 'de'):string
    {
        $description = trim($this->config->get('Novalnet.novalnet_instalment_invoice_description'));
        return ($description ? $description : $this->paymentHelper->getTranslatedText('invoicePrepaymentPaymentDescription'));
    }

    /**
     * Check if it is allowed to switch to this payment method
     *
     * @return bool
     */
    public function isSwitchableTo(): bool
    {
        return false;
    }

    /**
     * Check if it is allowed to switch from this payment method
     *
     * @return bool
     */
    public function isSwitchableFrom(): bool
    {
        return false;
    }

    /**
     * Check if this payment method should be searchable in the backend
     *
     * @return bool
     */
    public function isBackendSearchable():bool
    {
        return true;
    }

    /**
     * Check if this payment method should be active in the backend
     *
     * @return bool
     */
    public function isBackendActive():bool
    {
        return false;
    }

   /**
     * Get name for the backend
     *
     * @param  string  $lang
     * @return string
     */
    public function getBackendName(string $lang = 'de'):string
    {
        return 'Novalnet Instalment by Invoice';
    }
	
   /**
     * Check if this payment method can handle subscriptions
     *
     * @return bool
     */
    public function canHandleSubscriptions():bool
    {
        return false;
    }

    /**
     * Get icon for the backend
     *
     * @return string
     */
    public function getBackendIcon():string 
    {
        /** @var Application $app */
        $app = pluginApp(Application::class);
        $logoUrl = $app->getUrlPath('novalnet') .'/images/logos/novalnet_instalment_invoice_backend_icon.svg';
        return $logoUrl;
    }

}
