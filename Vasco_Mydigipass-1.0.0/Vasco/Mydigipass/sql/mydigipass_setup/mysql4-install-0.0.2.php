<?php

$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$installer->addAttribute('customer', 'digipass_uuid', array(
    'label'             => 'MyDIGIPASS UUID',
    'type'              => 'varchar',
    'input'             => 'text',
    'backend'           => 'eav/entity_attribute_backend_array',
    'frontend'          => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,      
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'searchable'        => true,
    'filterable'        => false,
    'comparable'        => false,
    'default'           => 'user'
));

$installer->endSetup();