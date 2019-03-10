<?php namespace Extroniks\CheckoutFields\Block\Adminhtml\Sales\Order;

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

class CheckoutFields extends \Magento\Framework\View\Element\Template {

    /**
     * @var string
     */
    protected $_template = 'sales/order/checkout_fields.phtml';

    /**
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     *
     * @var \Extroniks\CheckoutFields\Helper\Data
     */
    private $helper;

    public function __construct(
    \Magento\Framework\Registry $coreRegistry,
    \Extroniks\CheckoutFields\Helper\Data $helper,
    \Magento\Framework\View\Element\Template\Context $context, $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
        $this->helper       = $helper;
    }

    /**
     * Retrieve order model object
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder() {
        return $this->coreRegistry->registry('sales_order');
    }

    public function getCheckoutFields() {
        return $this->helper->getOrderCheckoutFields($this->getOrder());
    }

}
