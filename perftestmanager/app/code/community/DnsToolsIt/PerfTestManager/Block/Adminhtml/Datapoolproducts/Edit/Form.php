<?php
class DnsToolsIt_PerfTestManager_Block_Adminhtml_Datapoolproducts_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getExampleData())
        {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        }
        elseif (Mage::registry('perftestmanager_data'))
        {
            $data = Mage::registry('perftestmanager_data')->getData();
        }
        else
        {
            $data = array();
        }
 
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
 
        $form->setUseContainer(true);
 
        $this->setForm($form);



        $fieldset = $form->addFieldset('datapool_form', array(
             'legend' =>Mage::helper('perftestmanager')->__('Performance Test Information')
        ));
 
        $fieldset->addField('name', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('Name'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'name',
             'note'     => Mage::helper('perftestmanager')->__('The name of the Performance Test.'),
        ));
 
        $fieldset->addField('desc', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('Description'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'desc',
             'note'     => Mage::helper('perftestmanager')->__('Short Description'),
        ));
	
		$fieldset->addField('type', 'hidden', array(
              'default_html'   => '<input id="type" name="type" value="users" type="hidden">'
        ));
		
		$fieldset->addField('qty', 'text', array(
             'label'     => Mage::helper('perftestmanager')->__('Products Number'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'qty',
             'note'     => Mage::helper('perftestmanager')->__('Number of Products that populate Database.'),
        ));
		
		
	
function nodeToArray(Varien_Data_Tree_Node $node)
	{
		$result = array();
		$result['category_id'] = $node->getId();
		$result['parent_id'] = $node->getParentId();
		$result['name'] = $node->getName();
		$result['is_active'] = $node->getIsActive();
		$result['position'] = $node->getPosition();
		$result['level'] = $node->getLevel();
		$result['children'] = array();
		
		foreach ($node->getChildren() as $child) {
			$result['children'][] = nodeToArray($child);
		}

	return $result;
}

function load_tree() {

	$tree = Mage::getResourceSingleton('catalog/category_tree')
	->load();
	
	$store = 1;
	$parentId = 1;
	
	$tree = Mage::getResourceSingleton('catalog/category_tree')
	->load();
	
	$root = $tree->getNodeById($parentId);
	
	if($root && $root->getId() == 1) {
		$root->setName(Mage::helper('catalog')->__('Root'));
	}
	
	$collection = Mage::getModel('catalog/category')->getCollection()
	->setStoreId($store)
	->addAttributeToSelect('name')
	->addAttributeToSelect('is_active');
	
	$tree->addCollectionData($collection, true);
	
	return nodeToArray($root);
	
	}

	
	function print_tree($tree,$level) {
		global $treeglob;
		
		$level+=1;
		
		foreach($tree as $item) {
			
			$treeglob[]['value'] = $item['category_id'];
			$treeglob[]['label'] = str_repeat("    ", $level).$item['name'];
			print_tree($item['children'],$level);
		}
	}

$tree = load_tree(); 
global $treeglob;
$treeglob=Array();
print_tree($tree['children'],0);

$out=array();

$lim = count($treeglob);
$k=0;

for ($k;$k<$lim;$k++){
	if ($k % 2 ==1){
	   array_push($out, array(key($treeglob[$k])=>$treeglob[$k][key($treeglob[$k])],key($treeglob[$k-1])=>$treeglob[$k-1][key($treeglob[$k-1])]));
	}
}




		$fieldset->addField('categories', 'multiselect', array(
                                'name' => 'categories',
                                'label' => Mage::helper('perftestmanager')->__('Categories'),
                                'title' => Mage::helper('perftestmanager')->__('Categories'),
                                'required' => false,
                                'values' => $out,
                                'style' => 'height:150px',
        ));

		$fieldset->addField('website_id', 'select', array(
                'name'      => 'website_id',
                'label'     => Mage::helper('perftestmanager')->__('WebSite'),
                'title'     => Mage::helper('perftestmanager')->__('WebSite'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true, false),
                 'note'     => Mage::helper('perftestmanager')->__('Web Site Id used for datapool'),
            )); 
		
	 	$fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('perftestmanager')->__('Store'),
                'title'     => Mage::helper('perftestmanager')->__('Store'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true, false),
                'note'     => Mage::helper('perftestmanager')->__('Store Id used for datapool'),
            )); 
			
	
			
			$fieldset->addField('group_id', 'select', array(
                'name'      => 'group_id',
                'label'     => Mage::helper('perftestmanager')->__('Group'),
                'title'     => Mage::helper('perftestmanager')->__('Group'),
                'required'  => true,
                'values'    => Mage::getModel('customer/group')->getCollection()->toOptionArray(),
                'note'     => Mage::helper('perftestmanager')->__('Group Id of Customer in Datapool'),
            )); 
	
		
		
 
        
 
        $form->setValues($data);
 
        return parent::_prepareForm();
    }
}