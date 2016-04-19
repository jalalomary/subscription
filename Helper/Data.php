<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Subscription\Helper;

use \Magento\Framework\HTTP\ZendClient;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Subscription token request URL
     */
    const MANAGEMENT_API_STORE_URL = 'https://services.myshopgo.me/api/management/store';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_httpClientFactory = $httpClientFactory;

        parent::__construct($context);
    }

    /**
     * Get domain
     *
     * @return string
     */
    protected function _getDomain()
    {
        $baseUrl = rtrim($this->_storeManager->getStore()->getBaseUrl(), '/');
        $domain  = substr($baseUrl, strpos($baseUrl, '://'), strlen($baseUrl));

        return $domain;
    }

    /**
     * Get management API store expiry date
     *
     * @return string
     */
    public function getManagementApiStoreExpiryDate()
    {
        $storeInfo = '';

        /** @var \Magento\Framework\HTTP\ZendClient $httpClient */
        $httpClient = $this->_httpClientFactory->create();

        $httpClient->setUri(self::MANAGEMENT_API_STORE_URL);
        $httpClient->setMethod(ZendClient::POST);

        $params = [
            'domain' => $this->_getDomain()
        ];

        $httpClient->setParameterPost($params);

        try {
            $response = $httpClient->request();
        } catch (\Zend_Http_Client_Exception $e) {}

        if (($response->getStatus() < 200 || $response->getStatus() > 210)) {
            return $storeInfo;
        }

        $storeInfo = json_decode($response->getBody(), true);

        return isset($storeInfo['expires_at'])
            ? $storeInfo['expires_at'] : '';
    }
}
