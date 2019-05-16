/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_GiftCardAccount/js/view/summary/gift-card-account',
    'mage/url',
    'Magento_Checkout/js/model/totals'
], function (Component, url, totals) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_GiftCardAccount/cart/totals/gift-card-account'
        },

        /**
         * @param {String} giftCardCode
         * @return {*|Boolean|Object|jQuery}
         */
        getRemoveUrl: function (giftCardCode) {
            return url.build('giftcard/cart/remove/code/' + giftCardCode);
        },

        /**
         * @override
         *
         * @returns {bool}
         */
        isAvailable: function () {
            return totals.getSegment('giftcardaccount') && totals.getSegment('giftcardaccount').value !== 0;
        }
    });
});
