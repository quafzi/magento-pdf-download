<?php
/**
 * Quafzi_PdfDownload_Quafzi_PdfdownloadController
 *
 * @package Quafzi_PdfDownload
 * @author  Thomas Birke <magento@netextreme.de>
 */
class Quafzi_PdfDownload_Quafzi_PdfdownloadController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('PDF Download'));
        $this->_setActiveMenu('report/quafzi_pdfdownload');
        $this->_addContent(
            $this->getLayout()->createBlock('quafzi_pdfdownload/container')
        );
        $this->renderLayout();
    }

    /**
     * Access allowed?
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('report/quafzi_pdfdownload');
    }

    protected function getUtcStringFromParam($param)
    {
        $date = new Zend_Date();
        $input = $this->getRequest()->getParam($param);
        return $input ? $date->setTimezone(Mage::getStoreConfig('general/locale/timezone'))
            ->setDate($input)
            ->setHour(0)
            ->setMinute(0)
            ->setSecond(0)
            ->setTimezone('UTC')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT) : null;
    }

    /**
     * Download PDFs
     */
    public function downloadAction()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $mergedPdf = null;
        foreach (['invoice', 'creditmemo'] as $type) {
            $query = 'SELECT increment_id FROM ' . $resource->getTableName('sales/' . $type)
                . ' WHERE created_at >= "' . $this->getUtcStringFromParam('dateFrom') . '"';
            $to = $this->getUtcStringFromParam('dateTo');
            if ($to) {
                $query .= ' AND created_at <= "' . $to . '"';
            }
            $storeId = $this->getRequest()->getParam('store');
            if ($storeId) {
                $query .= ' AND store_id = "' . $storeId . '"';
            }
            $incrementIds = $readConnection->fetchCol($query);
            foreach ($incrementIds as $incrementId) {
                $filename = Mage::getBaseDir('var') . DS . 'export' . DS . 'documents' . DS
                    . $type . '-' . $incrementId . '.pdf';
                $pdf = Zend_Pdf::load($filename);
                if (is_null($mergedPdf)) {
                    $mergedPdf = $pdf;
                } else {
                    foreach ($pdf->pages as $page) {
                        $mergedPdf->pages[] = clone $page;
                    }
                }
            }
        }
        $this->_prepareDownloadResponse('documents.pdf', $mergedPdf->render(), 'application/pdf');
    }
}
