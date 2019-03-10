<?php namespace Extroniks\CheckoutFields\Observer;

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

class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface {

    /**
     *
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     *
     * @var \Extroniks\CheckoutFields\Model\Order\FieldFactory
     */
    private $orderFieldFactory;

    /**
     *
     * @var \Extroniks\CheckoutFields\Helper\Data
     */
    private $helper;

    public function __construct(
    \Magento\Checkout\Model\Session $checkoutSession,
    \Extroniks\CheckoutFields\Model\Order\FieldFactory $orderFieldFactory,
    \Extroniks\CheckoutFields\Helper\Data $helper
    ) {
        $this->checkoutSession   = $checkoutSession;
        $this->orderFieldFactory = $orderFieldFactory;
        $this->helper            = $helper;
    }

    private function filterValue($value) {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        if (!$this->helper->isEnabled()) {
            return $this;
        }

        $order          = $observer->getEvent()->getOrder();
        $checkoutFields = $this->checkoutSession->getData('checkoutFields');
        $fields         = [];

        if ($checkoutFields) {
            foreach ($checkoutFields as $fieldData) {
                $model    = $this->orderFieldFactory->create();
                $model->setOrder($order);
                $model->setLabel($this->filterValue($fieldData['label']));
                $model->setValue($this->filterValue($fieldData['value']));
                $fields[] = $model;
            }
        }

        $order->setCheckoutFields($fields);
        $this->checkoutSession->unsetData('checkoutFields');
    }

}
