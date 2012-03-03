<?php
/**
 * Add Product SKUs column to orders grid
 *
 * @author Colin Mollenhour <colin@mollenhour.com> http://colin.mollenhour.com
 */
class Cm_OrderProducts_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{

    // MUST override setCollection rather than _prepareCollection to get filtering and paging both working
    public function setCollection($collection)
    {
        parent::setCollection($collection);
        if ($this->_isExport) return;

        $collection->getSize(); // Get size before adding join
        $collection->join(
            'sales/order_item',
            '`sales/order_item`.order_id=`main_table`.entity_id',
            array(
                'skus'  => new Zend_Db_Expr('group_concat(`sales/order_item`.sku SEPARATOR "<br/>")'),
//                'names' => new Zend_Db_Expr('group_concat(`sales/order_item`.name SEPARATOR "<br/>")'),
            )
        );
        $collection->getSelect()->group('entity_id');
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        if($this->_isExport) return;

        $this->addColumnAfter('skus', array(
            'header'    => Mage::helper('sales')->__('Product Skus'),
            'index'     => 'skus',
            'type'      => 'text',
            'filter_index' => '`sales/order_item`.sku',
            'sortable'  => FALSE,
        ), 'shipping_name');
/*
        $this->addColumnAfter('names', array(
            'header'    => Mage::helper('sales')->__('Product Names'),
            'index'     => 'names',
            'type'      => 'text',
            'filter_index' => '`sales/order_item`.name',
            'sortable'  => FALSE,
        ), 'skus');
*/
        $this->sortColumnsByOrder();
    }

}