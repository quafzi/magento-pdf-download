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
        $ranges = [
            'q1' => '0{1,2,3}',
            'q2' => '0{4,5,6}',
            'q3' => '0{7,8,9}',
            'q4' => '{10,11,12}',
            'year' => '*',
        ];
        $storeCode = Mage::getModel('core/store')->load((int) $this->getRequest()->getParam('store'))->getCode();
        $year = (int) $this->getRequest()->getParam('year');
        $month = $this->getRequest()->getParam('month');

        $docDir = Mage::getBaseDir('var') . DS . 'export' . DS . 'documents';
        $glob = $docDir . DS . $storeCode . DS . $year . DS;
        if ((string)(int) $month === $month) {
            $glob .= str_pad($month, 2, '0', STR_PAD_LEFT) . DS . '*/*.pdf';
        } elseif (isset($ranges[$month])) {
            $glob .= $ranges[$month] . DS . '*/*.pdf';
        } else {
            Mage::getSingleton('adminhtml/session')->addError('Invalid month range');
            return $this->_redirect('ad4max/quafzi_pdfdownload/index');
        }
        if (0 === count(glob($glob))) {
            Mage::getSingleton('adminhtml/session')->addError('No documents for this range');
            return $this->_redirect('ad4max/quafzi_pdfdownload/index');
        }
        $zipName = tempnam(sys_get_temp_dir(), 'pdf_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipName, ZipArchive::CREATE);
        $zip->addGlob($glob, 0, ['remove_path' => $docDir]);
        $zip->close();
        $this->_prepareDownloadResponse(
            $year . '_' . $month . '_' . $storeCode .'.zip',
            ['type' => 'filename', 'value' => $zipName],
            'application/zip'
        );
    }
}
