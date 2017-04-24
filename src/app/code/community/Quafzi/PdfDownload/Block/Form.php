<?php
/**
 * Quafzi_PdfDownload_Block_Form
 *
 * @package Quafzi_PdfDownload
 * @author  Thomas Birke <magento@netextreme.de>
 */
class Quafzi_PdfDownload_Block_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare the form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form     = new Varien_Data_Form();
        $helper   = Mage::helper('quafzi_pdfdownload');
        $fieldset = $form->addFieldset(
            'base_fieldset', array(
                'legend' => $helper->__('PDF download'))
        );

        $fieldset->addField('store', 'select', array(
            'name'     => 'store',
            'required' => true,
            'label'    => $helper->__('Store'),
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
        ));

        $fieldset->addField('dateFrom', 'date', array(
            'name'     => 'dateFrom',
            'required' => true,
            'label'    => $helper->__('Date From:'),
            'title'    => $helper->__('Date From:'),
            'note'     => $helper->__('Time starting at 0:00:00 o\'clock'),
            'image'    => Mage::getBaseUrl(
                Mage_Core_Model_Store::URL_TYPE_SKIN)
                . '/adminhtml/default/default/images/grid-cal.gif',
            'format'   => Mage::app()->getLocale()
                ->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
        ));

        $fieldset->addField('dateTo', 'date', array(
            'name'     => 'dateTo',
            'label'    => $helper->__('Date To:'),
            'title'    => $helper->__('Date To:'),
            'note'     => $helper->__('Time ending at 23:59:59 o\'clock. If no date is given, end date will be the current date.'),
            'image'    => Mage::getBaseUrl(
                Mage_Core_Model_Store::URL_TYPE_SKIN)
                . '/adminhtml/default/default/images/grid-cal.gif',
            'format'   => Mage::app()->getLocale()
                ->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
        ));

        $form->setAction($this->getUrl('*/quafzi_pdfdownload/download'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('downloadForm');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
