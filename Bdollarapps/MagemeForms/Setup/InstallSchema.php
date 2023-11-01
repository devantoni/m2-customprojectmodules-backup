<?php

namespace Bdollarapps\MagemeForms\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'mm_webforms_field';

    public function __construct(
    )
    {

    }

    /**
     * @inheritDoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $fieldTable = $setup->getTable(static::TABLE_NAME);

        $setup->startSetup();
        $setup->getConnection()->addColumn($fieldTable, 'twilio_sms', [
            Table::OPTION_TYPE => Table::TYPE_BOOLEAN,
            Table::OPTION_UNSIGNED => true,
            Table::OPTION_NULLABLE => false,
            Table::OPTION_DEFAULT => 0,
            'comment' => 'Twilio Sms flag'
        ]);
        $setup->endSetup();
    }
}
