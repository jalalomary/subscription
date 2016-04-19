<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace ShopGo\Subscription\Block\Adminhtml\Page;

/**
 * Adminhtml header block
 */
class Header extends \Magento\Backend\Block\Page\Header
{
    /**
     * Subscribe URL
     */
    const SUBSCRIBE_URL = 'shopgo_subscription/subscription/subscribe';

    /**
     * @var \ShopGo\Subscription\Model\Subscription
     */
    protected $_subscription;

    /**
     * @var \ShopGo\Subscription\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Backend\Helper\Data $backendData
     * @param \ShopGo\Subscription\Model\Subscription $subscription
     * @param \ShopGo\Subscription\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\Helper\Data $backendData,
        \ShopGo\Subscription\Model\Subscription $subscription,
        \ShopGo\Subscription\Helper\Data $helper,
        array $data = []
    ) {
        $this->_subscription = $subscription;
        $this->_helper = $helper;
        parent::__construct($context, $authSession, $backendData, $data);
    }

    /**
     * Get expiry date
     *
     * @return string
     */
    public function getExpiryDate()
    {
        $expiryDate = $this->_helper->getManagementApiStoreExpiryDate();
        return $expiryDate ? 'Expires At: ' . $expiryDate : '';
    }

    /**
     * Get subscribe URL
     *
     * @return string
     */
    public function getSubscribeUrl()
    {
        return $this->getUrl(self::SUBSCRIBE_URL);
    }
}
