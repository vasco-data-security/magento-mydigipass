<?php

$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$attributeId = $installer->getAttributeId('customer', 'digipass_uuid');
$installer->run("DELETE FROM `customer_entity_varchar`
                WHERE attribute_id = $attributeId AND (value = '' OR value = 'user');"
        );
$installer->updateAttribute('customer', 'digipass_uuid', 'default_value', null);
$installer->endSetup();