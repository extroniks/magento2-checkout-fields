<?php
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
namespace Extroniks\CheckoutFields\Observer;

/**
 * Description of OrderSaveAfter
 *
 * @author stefan
 */
class OrderSaveAfter implements \Magento\Framework\Event\ObserverInterface {

    /**
     *
     * @var \Extroniks\CheckoutFields\Model\Order\FieldRepository
     */
    private $orderFieldRepository;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
    \Extroniks\CheckoutFields\Model\Order\FieldRepository $orderfieldRepository,
    \Psr\Log\LoggerInterface $logger
    ) {
        $this->orderFieldRepository = $orderfieldRepository;
        $this->logger               = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $order = $observer->getEvent()->getOrder();

        if ($order->getCheckoutFields()) {
            $checkoutFields = $order->getCheckoutFields();
            foreach ($checkoutFields as $checkoutField) {
                try {
                    $checkoutField->setOrderId($order->getId());
                    $checkoutField->setOrderIncrementId($order->getIncrementId());
                    $this->orderFieldRepository->save($checkoutField);
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }
        $order->unsetCheckoutFields();
    }

}
