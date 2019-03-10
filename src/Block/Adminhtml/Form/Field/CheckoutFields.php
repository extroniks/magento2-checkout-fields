<?php namespace Extroniks\CheckoutFields\Block\Adminhtml\Form\Field;

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

class CheckoutFields extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray {

    /**
     * @var string
     */
    protected $_template = 'system/config/form/field/checkout_fields.phtml';

    /**
     * Check if columns are defined, set template

      /**
     * @var InputType
     */
    protected $inputType;

    /**
     * @var FrontendArea
     */
    protected $frontendArea;

    protected function getInputTypeRenderer() {
        if (!$this->inputType) {
            $this->inputType = $this->getLayout()->createBlock(
                    \Extroniks\CheckoutFields\Block\Adminhtml\Form\Field\InputType::class, '', ['data' => ['is_render_to_js_template' => true]]
            );
            $this->inputType->setClass('input_type_select');
        }

        return $this->inputType;
    }

    protected function getFrontendAreaRenderer() {
        if (!$this->frontendArea) {
            $this->frontendArea = $this->getLayout()->createBlock(
                    \Extroniks\CheckoutFields\Block\Adminhtml\Form\Field\FrontendArea::class, '', ['data' => ['is_render_to_js_template' => true]]
            );
            $this->frontendArea->setClass('frontend_area_select');
        }

        return $this->frontendArea;
    }

    /**
     * Prepare to render.
     */
    protected function _prepareToRender() {
        $this->addColumn(
                'field_label', ['label' => __('Label'), 'size' => '120px']
        );

        $this->addColumn(
                'frontend_area', ['label' => __('Frontend Area'), 'size' => '100px', 'renderer' => $this->getFrontendAreaRenderer()]
        );

        $this->addColumn(
                'element_input_type', ['label' => __('Input Type'), 'renderer' => $this->getInputTypeRenderer()]
        );

        $this->addColumn(
                'sort_number', ['label' => __('Sort')]
        );

        $this->_addAfter = false;
    }

    /**
     * Prepare existing row data object.
     *
     * @param \Magento\Framework\DataObject $row
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row) {
        $optionExtraAttr = [];

        $optionExtraAttr['option_' . $this->getFrontendAreaRenderer()->calcOptionHash($row->getData('frontend_area'))]   = 'selected="selected"';
        $optionExtraAttr['option_' . $this->getInputTypeRenderer()->calcOptionHash($row->getData('element_input_type'))] = 'selected="selected"';

        $row->setData(
                'option_extra_attrs', $optionExtraAttr
        );
    }

    public function getArrayRows() {
        $result  = [];
        /** @var \Magento\Framework\Data\Form\Element\AbstractElement */
        $element = $this->getElement();
        if ($element->getValue() && is_array($element->getValue())) {
            foreach ($element->getValue() as $rowId => $row) {
                $row['_id']           = $rowId;
                $row['column_values'] = $row;
                $result[$rowId]       = new \Magento\Framework\DataObject($row);
                $this->_prepareArrayRow($result[$rowId]);
            }
        }
        $this->_arrayRowsCache = $result;

        return $this->_arrayRowsCache;
    }

}
