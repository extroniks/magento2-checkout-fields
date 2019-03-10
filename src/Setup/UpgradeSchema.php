<?php namespace Extroniks\CheckoutFields\Setup;

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

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(
    \Magento\Framework\Setup\SchemaSetupInterface $setup,
    \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), '0.1.0', '<')) {
            $installer = $setup;

            $installer->startSetup();

            /**
             * Create table 'checkoutfields_order_field'
             */
            $table = $installer->getConnection()->newTable($installer->getTable('checkoutfields_order_field'))
                    ->addColumn('value_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Value ID')
                    ->addColumn('order_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => true], 'Order ID')
                    ->addColumn('order_increment_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 32, [], 'Order Increment ID')
                    ->addColumn('label', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false], 'Field Label')
                    ->addColumn('value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2M', ['nullable' => false], 'Field Value')
                    ->addForeignKey(
                            $installer->getFkName('checkoutfields_order_field', 'order_id', 'sales_order', 'entity_id'), 'order_id', $installer->getTable('sales_order'), 'entity_id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment('Checkout Fields Order Fields');
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
        }
    }

}
