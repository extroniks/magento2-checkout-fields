<?php namespace Extroniks\CheckoutFields\Helper;

/*
 * The MIT License
 *
 * Copyright 2019 Stefan Erakovic.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_CONFIG_PATH_ENABLED = 'checkoutfields/general/enabled';
    const XML_CONFIG_PATH_FIELDS  = 'checkoutfields/general/fields';

    /**
     *
     * @var \Extroniks\CheckoutFields\Model\ResourceModel\Order\Field\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
    \Magento\Framework\App\Helper\Context $context,
    \Extroniks\CheckoutFields\Model\ResourceModel\Order\Field\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
    }

    public function isEnabled() {
        return ($this->scopeConfig->getValue(self::XML_CONFIG_PATH_ENABLED) == 1);
    }

    public function getFieldsConfig() {
        return $this->scopeConfig->getValue(self::XML_CONFIG_PATH_FIELDS);
    }

    public function getCheckoutFields() {
        return $this->getFieldsConfig() ? unserialize($this->getFieldsConfig()) : [];
    }

    public function getOrderCheckoutFields(\Magento\Sales\Model\Order $order) {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*')
                ->addFieldToFilter('order_id', ['eq' => $order->getId()])
                ->load();
        return $collection->getItems();
    }

}
