<?php
/**
 * Quafzi_PdfDownload_Block_FormContainer
 *
 * @package Quafzi_PdfDownload
 * @author  Thomas Birke <magento@netextreme.de>
 */
class Quafzi_PdfDownload_Block_Container
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_blockGroup = 'quafzi_pdfdownload';
    protected $_controller = 'quafzi_pdfdownload';
    protected $_mode       = null;

    public function __construct()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('quafzi_pdfdownload')->__('Download PDFs');

        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_removeButton('back');
        $this->_removeButton('save');

        $this->_addButton('quafzi_pdfdownload', array(
            'label'   => Mage::helper('quafzi_pdfdownload')->__('Download'),
            'onclick' => 'downloadForm.submit();',
            'class'   => 'quafzi_pdfdownload',
        ), 1);
    }

    /**
     * Prepare the layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('form',
            $this->getLayout()->createBlock('quafzi_pdfdownload/form')
        );

        return parent::_prepareLayout();
    }
}
