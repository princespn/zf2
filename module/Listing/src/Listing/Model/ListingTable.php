<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Listing\Model;

 use Zend\Db\TableGateway\TableGateway;

 class ListingTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getListing($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveListing(Listing $listing)
     {
         $data = array(
             'product_code' => $listing->product_code,
             'item_sku'  => $listing->item_sku,
         );

         $id = (int) $listing->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getListing($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Listing id does not exist');
             }
         }
     }

     public function deleteListing($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }