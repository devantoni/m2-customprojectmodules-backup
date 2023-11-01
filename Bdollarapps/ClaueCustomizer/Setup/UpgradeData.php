<?php

namespace Bdollarapps\ClaueCustomizer\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Bdollarapps\ClaueCustomizer\Model\Config\Options;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
	    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.4', '<=')) {

            /*customersetupfactory instead of eavsetupfactory */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            /* create customer Solution number attribute */
            $customerSetup->addAttribute(Customer::ENTITY,'solution_number',	[
                'type'         => 'varchar', // attribute with varchar type
                'label'        => 'Solution Number',
                'input'        => 'text',  // attribute input field is text
                'required'     => true,  // field is not required
                'visible'      => true,  
                'user_defined' => true,
                'position'     => 1000,
                'sort_order'  => 1000,
                'system'       => 0,
                'is_used_in_grid' => 0,   //setting grid options
                'is_visible_in_grid' => 0,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
            ]
            );
            $solution_number = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'solution_number')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $solution_number->save();


            // Contract Level
            // $customerSetup->addAttribute(Customer::ENTITY,'position_title',[
            //         'type'         => 'static',
            //         'label'        => 'Contract Level',
            //         'input'        => 'select',
            //         'required'     => true,
            //         'visible'      => true,
            //         'user_defined' => true,
            //         'position'     => 999,
            //         'system'       => 0,
            //         'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
            //         'option' => ['values' => ['Select Contract Level', 'Representative', 'Senior Representative', 'District Leader', 'Division Leader', 'Regional Leader', 'Senior Regional Leader']],
            //         'is_used_in_grid' => 0,   //setting grid options
            //         'is_visible_in_grid' => 0,
            //         'is_filterable_in_grid' => 1,
            //         'is_searchable_in_grid' => 1,
            //     ]
            // );
            // $positionTitle = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'position_title')
            // ->addData(
            // [
            //             'attribute_set_id' => $attributeSetId,
            //             'attribute_group_id' => $attributeGroupId,
            //             'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
            //         ]
            // // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            // );
            // $positionTitle->save();

            // Life Licensed
            $customerSetup->addAttribute(Customer::ENTITY,'life_licensed',[
                    'type'         => 'int',
                    'label'        => 'Life Licensed',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1002,
                    'sort_order'  => 1002,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $lifeLicensed = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'life_licensed')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $lifeLicensed->save();

            // Securities Licensed
            $customerSetup->addAttribute(Customer::ENTITY,'securities_licensed',[
                    'type'         => 'int',
                    'label'        => 'Securities Licensed',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1003,
                    'sort_order'  => 1003,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $securitiesLicensed = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'securities_licensed')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $securitiesLicensed->save();

            // Level 1 Course
            $customerSetup->addAttribute(Customer::ENTITY,'level_1',[
                    'type'         => 'int',
                    'label'        => 'Level 1',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1004,
                    'sort_order'  => 1004,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $levelOne = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'level_1')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $levelOne->save();

            // Level 2 Course
            $customerSetup->addAttribute(Customer::ENTITY,'level_2',[
                    'type'         => 'int',
                    'label'        => 'Level 2',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1005,
                    'sort_order'  => 1005,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $levelTwo = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'level_2')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $levelTwo->save();


            // Level 3 Course
            $customerSetup->addAttribute(Customer::ENTITY,'level_3',[
                    'type'         => 'int',
                    'label'        => 'Level 3',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1006,
                    'sort_order'  => 1006,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $levelThree = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'level_3')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $levelThree->save();

            // Level 4 Course
            $customerSetup->addAttribute(Customer::ENTITY,'level_4',[
                    'type'         => 'int',
                    'label'        => 'Level 4',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1007,
                    'sort_order'  => 1007,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $levelFour = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'level_4')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $levelFour->save();

            // App Access
            $customerSetup->addAttribute(Customer::ENTITY,'app_access',[
                    'type'         => 'int',
                    'label'        => 'App Access',
                    'input'        => 'boolean',
                    'required'     => true,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 1008,
                    'sort_order'  => 1008,
                    'system'       => 0,
                    'is_used_in_grid' => 0,   //setting grid options
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                ]
            );
            $appAccess = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'app_access')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $appAccess->save(); 
        }

        if (version_compare($context->getVersion(), '1.0.5', '<=')) {

            /*customersetupfactory instead of eavsetupfactory */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            /* create customer Contract Level attribute */
            $customerSetup->addAttribute(Customer::ENTITY,'position_title_new',	[
                'type'         => 'text', // attribute with varchar type
                'label'        => 'Contract Level (new)',
                'input'        => 'select',  // attribute input field is text
                'source' => Options::class,
                'required'     => false,  // field is not required
                'visible'      => true,  
                'user_defined' => true,
                'position'     => 1001,
                'sort_order'  => 1001,
                'system'       => 0,
                'is_used_in_grid' => 0,   //setting grid options
                'is_visible_in_grid' => 0,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
            ]
            );
            $position_title_new = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'position_title_new')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $position_title_new->save();
        }
        // New attribute for customer to handle access menu ( bdassist, report, etc,.. )
        if (version_compare($context->getVersion(), '1.0.6', '<=')) {

            /*customersetupfactory instead of eavsetupfactory */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            /* create customer Full Access attribute */
            $customerSetup->addAttribute(Customer::ENTITY,'full_access',	[
                'type'         => 'int',
                'label'        => 'Full Access',
                'input'        => 'boolean',
                'required'     => false,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 1009,
                'sort_order'  => 1009,
                'system'       => 0,
                'is_used_in_grid' => 0,   //setting grid options
                'is_visible_in_grid' => 0,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
            ]
            );
            $full_access = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'full_access')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $full_access->save();
        }

        // New attribute for RVP customer to handle Primerica validation
        if (version_compare($context->getVersion(), '1.0.7', '<=')) {

            /*customersetupfactory instead of eavsetupfactory */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();
            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            /* create customer Primerica attribute */
            $customerSetup->addAttribute(Customer::ENTITY,'primerica_active_status',	[
                'type'         => 'int',
                'label'        => 'Primerica Active Status',
                'input'        => 'boolean',
                'required'     => false,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 1009,
                'sort_order'  => 1009,
                'system'       => 0,
                'is_used_in_grid' => 0,   //setting grid options
                'is_visible_in_grid' => 0,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
            ]
            );
            $primerica_active_status = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'primerica_active_status')
            ->addData(
            [
                        'attribute_set_id' => $attributeSetId,
                        'attribute_group_id' => $attributeGroupId,
                        'used_in_forms' => ['adminhtml_customer','customer_account_edit','customer_account_create'],
                    ]
            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $primerica_active_status->save();
        }
    }

}
