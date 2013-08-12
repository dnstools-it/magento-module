<?php
/**
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapoolcategories_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_attributeTabBlock = 'adminhtml/perftestmanager_datapool_tabs';

    public function __construct()
    {
        parent::__construct();
        $this->setId('perftestmanager_datapoolcategories_tabs');
        $this->setDestElementId('datapoolcategories_grid');
        $this->setTitle(Mage::helper('perftestmanager')->__('Datapool'));
    }

    protected function _prepareLayout()
    {
        $curctrl=strtolower(Mage::app()->getRequest()->getControllerName());
        $curformsec=array();

        
       
        if ($curctrl=='datapool')
        {
            $tabs=array('form_section1'=>array('url'=>'/datapool/index','label'=>'DP Users','active'=>true, 'hidden'=>false),
                    'form_section2'=>array('url'=>'/datapoolproducts/index','label'=>'DP Products','active'=>false, 'hidden'=>false),
                    //'form_section3'=>array('url'=>'/datapoolorders/index','label'=>'DP Orders','active'=>false, 'hidden'=>false),
                    'form_section4'=>array('url'=>'/datapoolstore/index','label'=>'DP Store','active'=>false, 'hidden'=>false),
                    //'form_section5'=>array('url'=>'/datapoolstoreviews/index','label'=>'DP StoreViews','active'=>false, 'hidden'=>true),
                    //'form_section6'=>array('url'=>'/datapoolfastgreace/index','label'=>'DP FastGrease','active'=>false, 'hidden'=>true)
                      'form_section5'=>array('url'=>'/infosystem','label'=>'InfoSystem','active'=>false, 'hidden'=>false),
                    );
        } 

        if ($curctrl=='datapoolproducts')
        {
            $tabs=array('form_section1'=>array('url'=>'/datapool/index','label'=>'DP Users','active'=>false, 'hidden'=>false),
                    'form_section2'=>array('url'=>'/datapoolproducts/index','label'=>'DP Products','active'=>true, 'hidden'=>false),
                    //'form_section3'=>array('url'=>'/datapoolorders/index','label'=>'DP Orders','active'=>false, 'hidden'=>false),
                    'form_section4'=>array('url'=>'/datapoolstore/index','label'=>'DP Store','active'=>false, 'hidden'=>false),
                    //'form_section5'=>array('url'=>'/datapoolstoreviews/index','label'=>'DP StoreViews','active'=>false, 'hidden'=>true),
                    //'form_section6'=>array('url'=>'/datapoolfastgreace/index','label'=>'DP FastGrease','active'=>false, 'hidden'=>true)
                    'form_section3'=>array('url'=>'/datapoolcategories/index','label'=>'DP Categories','active'=>false, 'hidden'=>false),
                    
                        'form_section5'=>array('url'=>'/infosystem','label'=>'InfoSystem','active'=>false, 'hidden'=>false),
                  
                    );
        } 

         if ($curctrl=='datapoolstore')
        {
            $tabs=array('form_section1'=>array('url'=>'/datapool/index','label'=>'DP Users','active'=>false, 'hidden'=>false),
                    'form_section2'=>array('url'=>'/datapoolproducts/index','label'=>'DP Products','active'=>false, 'hidden'=>false),
                    //'form_section3'=>array('url'=>'/datapoolorders/index','label'=>'DP Orders','active'=>false, 'hidden'=>false),
                    'form_section4'=>array('url'=>'/datapoolstore/index','label'=>'DP Store','active'=>true, 'hidden'=>false),
                    //'form_section5'=>array('url'=>'/datapoolstoreviews/index','label'=>'DP StoreViews','active'=>false, 'hidden'=>true),
                    //'form_section6'=>array('url'=>'/datapoolfastgreace/index','label'=>'DP FastGrease','active'=>false, 'hidden'=>true)
                    'form_section3'=>array('url'=>'/datapoolcategories/index','label'=>'DP Categories','active'=>false, 'hidden'=>false),
                    
                       'form_section5'=>array('url'=>'/infosystem','label'=>'InfoSystem','active'=>false, 'hidden'=>false),
                  
                    );
        } 

         if ($curctrl=='datapoolcategories')
        {
            $tabs=array('form_section1'=>array('url'=>'/datapool/index','label'=>'DP Users','active'=>false, 'hidden'=>false),
                    'form_section2'=>array('url'=>'/datapoolproducts/index','label'=>'DP Products','active'=>false, 'hidden'=>false),
                    //'form_section3'=>array('url'=>'/datapoolorders/index','label'=>'DP Orders','active'=>false, 'hidden'=>false),
                    'form_section4'=>array('url'=>'/datapoolstore/index','label'=>'DP Store','active'=>false, 'hidden'=>false),
                    //'form_section5'=>array('url'=>'/datapoolstoreviews/index','label'=>'DP StoreViews','active'=>false, 'hidden'=>true),
                    //'form_section6'=>array('url'=>'/datapoolfastgreace/index','label'=>'DP FastGrease','active'=>false, 'hidden'=>true)
                    'form_section3'=>array('url'=>'/datapoolcategories/index','label'=>'DP Categories','active'=>true, 'hidden'=>false),
                    
                    'form_section5'=>array('url'=>'/infosystem','label'=>'InfoSystem','active'=>false, 'hidden'=>false),
                  
                    );
        } 
        /*if ($curctrl=='datapoolorders')
        {    
            $tabs=array('form_section1'=>array('url'=>'/datapool/index','label'=>'DP Users','active'=>false, 'hidden'=>false),
                    'form_section2'=>array('url'=>'/datapoolproducts/index','label'=>'DP Products','active'=>false, 'hidden'=>false),
                    //'form_section3'=>array('url'=>'/datapoolorders/index','label'=>'DP Orders','active'=>true, 'hidden'=>false),
                    'form_section4'=>array('url'=>'/datapoolstore/index','label'=>'DP Store','active'=>false, 'hidden'=>false),
                    //'form_section5'=>array('url'=>'/datapoolstoreviews/index','label'=>'DP StoreViews','active'=>false, 'hidden'=>true),
                    //'form_section6'=>array('url'=>'/datapoolfastgreace/index','label'=>'DP FastGrease','active'=>false, 'hidden'=>true)
                    );
        }*/

        foreach ($tabs as $tabkey=> $tabvalue)
        {
          
           $this->addTab($tabkey, array(
                                 'label'     => Mage::helper('perftestmanager')->__($tabvalue['label']),
                                 'title'     => Mage::helper('perftestmanager')->__($tabvalue['label']),
                                 'url'       => $this->getUrl('*'.$tabvalue['url'],$curformsec ),
                                 'active'    => $tabvalue['active'],
                                 'hidden'    => $tabvalue['hidden']
                                 //'class'     => 'ajax',
                        )); 
        }   

 
        return parent::_prepareLayout();
    }

    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
