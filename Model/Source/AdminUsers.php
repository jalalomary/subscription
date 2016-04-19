<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ShopGo\Subscription\Model\Source;

/**
 * Used in creating options for admin users config value selection
 */
class AdminUsers implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * User factory model
     *
     * @var \Magento\User\Model\UserFactory
     */
    protected $_userFactory;

    /**
     * @param \Magento\User\Model\UserFactory $userFactory
     */
    public function __construct(\Magento\User\Model\UserFactory $userFactory)
    {
        $this->_userFactory = $userFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $userCollection = $this->_userFactory->create()
                          ->getCollection()
                          ->getItems();

        $options = [
            ['value' => '', 'label' => '--Please Select--']
        ];

        foreach ($userCollection as $user) {
            $options[] = [
                'value' => $user->getUserId(),
                'label' => $user->getUsername()
            ];
        }

        return $options;
    }
}
