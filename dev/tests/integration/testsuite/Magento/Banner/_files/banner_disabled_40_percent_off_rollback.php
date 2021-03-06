<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

require __DIR__ . '/../../../Magento/SalesRule/_files/cart_rule_40_percent_off_rollback.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);

$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Banner\Model\Banner::class
);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

try {
    $banner->load('Get 40% Off on Large Orders', 'name');
    $banner->delete();
} catch (\Exception $ex) {
    //Nothing to remove
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
