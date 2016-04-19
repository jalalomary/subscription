<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Subscription\Model;

use \Magento\Framework\HTTP\ZendClient;

class Subscription extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Default admin user ID
     */
    const DEFAULT_ADMIN_USER_ID = 1;

    /**
     * Subscription URL
     */
    const SUBSCRIPTION_URL = 'http://billing.shopgo.me/merchant/login';

    /**
     * Subscription token request URL
     */
    const SUBSCRIPTION_TOKEN_URL = 'http://billing.shopgo.me/authenticate/request-token';

    /**
     * Admin user (store owner) xml path
     */
    const XML_PATH_ADMIN_USER = 'subscription/general/admin_user';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\User\Model\UserFactory
     */
    protected $_userFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\User\Model\UserFactory $userFactory
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\User\Model\UserFactory $userFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_httpClientFactory = $httpClientFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_userFactory = $userFactory;

        parent::__construct($context, $registry);
    }

    /**
     * Get store owner (admin user) ID
     *
     * @return int
     */
    protected function _getStoreOwnerId()
    {
        $userId = $this->_scopeConfig->getValue(
            self::XML_PATH_ADMIN_USER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $userId ? $userId : self::DEFAULT_ADMIN_USER_ID;
    }

    /**
     * Get admin user email
     *
     * @return string
     */
    protected function _getAdminUserEmail()
    {
        $email = '';
        $admin = $this->_userFactory->create()->load($this->_getStoreOwnerId());

        if ($admin) {
            $email = $admin->getEmail();
        }

        return $email;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    protected function _getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get ShopGo site ID
     *
     * @return string
     */
    protected function _getSiteId()
    {
        $code = $this->_scopeConfig->getValue(
            \ShopGo\SiteId\Helper\Data::XML_PATH_SHOPGO_SITE_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $code;
    }

    /**
     * Get subscription token
     *
     * @return string
     */
    public function _getSubscriptionToken()
    {
        $token = '';
        $siteId = $this->_getSiteId();

        if (!$siteId) {
            return $token;
        }

        /** @var \Magento\Framework\HTTP\ZendClient $httpClient */
        $httpClient = $this->_httpClientFactory->create();

        $httpClient->setUri(self::SUBSCRIPTION_TOKEN_URL);
        $httpClient->setMethod(ZendClient::POST);

        $params = [
            'url'     => $this->_getBaseUrl(),
            'site_id' => $siteId
        ];

        $httpClient->setParameterPost($params);

        try {
            $response = $httpClient->request();
        } catch (\Zend_Http_Client_Exception $e) {}

        if (($response->getStatus() < 200 || $response->getStatus() > 210)) {
            return $token;
        }

        $token = json_decode($response->getBody(), true);
        $token = (isset($token['token']) && $token['status'])
            ? $token['token'] : '';

        return $token;
    }

    /**
     * Get subscription URL
     *
     * @return string
     */
    public function getSubscriptionUrl()
    {
        $token = $this->_getSubscriptionToken();

        return $token
            ? self::SUBSCRIPTION_URL . '?token=' . $token
            : $token;
    }
}
