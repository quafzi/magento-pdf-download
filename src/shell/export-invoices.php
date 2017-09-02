<?php
/**
 * @copyright  Copyright (c) 2017 Thomas Birke
 * @author     Thomas Birke <magento@netextreme.de>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (file_exists(dirname(__FILE__) . '/abstract.php')) {
    // if this file is no symlink
    require_once dirname(__FILE__) . '/abstract.php';
} elseif (file_exists(dirname(__FILE__) . '/../../../../../shell/abstract.php')) {
    // if this file is a symlinked pointing to ../composer/quafzi/magento-address-validation/src/shell/fix-customer-data.php
    require_once dirname(__FILE__) . '/../../../../../shell/abstract.php';
} elseif (file_exists(dirname(__FILE__) . '/../../../shell/abstract.php')) {
    // if this file is a symlinked pointing to ../.modman/…/src/shell/fix-customer-data.php
    require_once dirname(__FILE__) . '/../../../shell/abstract.php';
}

/**
 * Magento Shell Script to export invoices and credit memos
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Thomas Birke <magento@netextreme.de>
 */
class Mage_Shell_ExportInvoices extends Mage_Shell_Abstract
{
    protected $_includeMage = true;

    protected function createFolder($path)
    {
        if (!is_dir($path)) {
            $parentPath = dirname($path);
            $this->createFolder($parentPath);
            mkdir($path);
        }
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        $varDir = Mage::getBaseDir('var') . DS . 'export' . DS . 'documents';
        if (!is_dir($varDir)) {
            mkdir($varDir);
        }
        Mage::app()->setCurrentStore(0);
        foreach (['invoice', 'creditmemo'] as $docType) {
            $configPath = 'pdf_export/' . $docType . 's/latest';
            //$latestExportedId = Mage::getConfig()->loadConfig($configPath, 'default', 0);
            $latestExportedId = Mage::getResourceModel('core/config_data_collection')
                ->addFieldToFilter('path', $configPath)
                ->getFirstItem()
                ->getValue();

            $docIds = Mage::getResourceModel('sales/order_' . $docType . '_collection')
                ->addFieldToFilter('entity_id', ['gt' => $latestExportedId])
                ->getAllIds();

            echo 'Exporting ' . count($docIds) . ' ' . $docType . 's (starting after id ' . $latestExportedId . '):' . PHP_EOL;

            foreach ($docIds as $progress => $docId) {
                echo "\r" . $progress . '/' . count($docIds);
                $doc = Mage::getModel('sales/order_' . $docType)->load($docId);
                $pdf = Mage::getModel('sales/order_pdf_' . $docType)->getPdf(array($doc));
                $createdAt = current(explode(' ', $doc->getCreatedAt()));
                $folder = $varDir . DS . $doc->getStore()->getCode() . DS . str_replace('-', DS, $createdAt);
                $this->createFolder($folder);
                $filename = $folder . DS . $createdAt . '-' . $docType . '-' . $doc->getIncrementId() . '.pdf';
                $pdf->save($filename);
                Mage::getConfig()->saveConfig($configPath, $docId, 'default', 0);
            }
            echo "\r✓ " . ($progress + 1) . '/' . count($docIds) . PHP_EOL;
        }
    }

    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f export-invoices.php

USAGE;
    }
}

ini_set('display_errors', 1);
$shell = new Mage_Shell_ExportInvoices();
$shell->run();
