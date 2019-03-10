<?php namespace Extroniks\CheckoutFields\Plugin;

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

use Extroniks\CheckoutFields\Block\Adminhtml\Form\Field\FrontendArea;

class LayoutProcessorPlugin {

    /**
     *
     * @var \Extroniks\CheckoutFields\Helper\Data
     */
    private $helper;

    public function __construct(
    \Extroniks\CheckoutFields\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    public function afterProcess(
    \Magento\Checkout\Block\Checkout\LayoutProcessor $subject, $jsLayout
    ) {
        $shippingConfiguration = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children'];

        $areas = [
            FrontendArea::AREA_BEFORE_SHIPPING_FORM        => &$shippingConfiguration['before-fields']['children'],
            FrontendArea::AREA_BEFORE_SHIPPING_METHOD_FORM => &$shippingConfiguration['before-shipping-method-form']['children']
        ];

        $checkoutFields = $this->helper->getCheckoutFields();

        foreach ($checkoutFields as $fieldId => $field) {

            $areas[$field['frontend_area']][] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'config'    => [
                    'customScope'       => 'checkoutFields',
                    'deps'              => 'checkoutProvider',
                    'template'          => 'ui/form/field',
                    'elementTmpl'       => $field['element_input_type'],
                    'additionalClasses' => 'checkoutField'
                ],
                'provider'  => 'checkoutProvider',
                'dataScope' => 'checkoutFields.' . $fieldId,
                'label'     => $field['field_label'],
                'sortOrder' => $field['sort_number']
            ];
        }

        return $jsLayout;
    }

}
