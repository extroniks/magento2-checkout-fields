define([
    'jquery',
], function ($) {
    'use strict';
    return function (payloadExtender) {

        let checkoutFields = [];

        $('.checkoutField input').each(function (index, elem) {
            checkoutFields.push({
                id: $(elem).closest('.checkoutField').attr('name'),
                value: $(elem).val()
            });
        });
        $('.checkoutField textarea').each(function (index, elem) {
            checkoutFields.push({
                id: $(elem).closest('.checkoutField').attr('name'),
                value: $(elem).val()
            });
        });

        payloadExtender.addressInformation['extension_attributes'] = {
            checkoutFields: checkoutFields
        };

        return payloadExtender;
    };
});