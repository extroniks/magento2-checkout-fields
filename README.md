# Magento 2 - Checkout Fields

A module which allows addition of number of fields to the frontend checkout step.
Module configuration can be found under the "Sales" section of the system configuration once installed and is labeled as "Checkout Fields".

## Installation
1. Add this to your project dependecies list `composer require extroniks/magento2-checkout-fields`
2. Run `bin/magento setup:upgrade`
3. Run `bin/magento setup:di:compile`
4. Configure module in system configuration
5. Run `bin/magneto cache:flush`

## Compatibility/Tested on:
* Magento 2.2

## Todos
- Support for fields in billing section
- Better styling/theme
- Finish sorting implementation 
- Add validation support
- More input types

For more information about the module take a look at the [documentation](/docs/README.md)