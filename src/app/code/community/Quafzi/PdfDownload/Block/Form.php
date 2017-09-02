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
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false)
        ));

        $fieldset->addField('year', 'select', array(
            'name'     => 'year',
            'required' => true,
            'label'    => $helper->__('Year:'),
            'title'    => $helper->__('Year:'),
            'values'   => array_combine(
                range(2014, date('Y')),
                range(2014, date('Y'))
            )
        ));

        $fieldset->addField('month', 'select', array(
            'name'     => 'month',
            'required' => true,
            'label'    => $helper->__('Month:'),
            'title'    => $helper->__('Month:'),
            'values'   => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12',
                'q1' => $helper->__('Quarter 1'),
                'q2' => $helper->__('Quarter 2'),
                'q3' => $helper->__('Quarter 3'),
                'q4' => $helper->__('Quarter 4'),
                'year' => $helper->__('whole year')
            ]
        ));

        $form->setAction($this->getUrl('*/quafzi_pdfdownload/download'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('downloadForm');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
