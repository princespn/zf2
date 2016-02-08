<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
// module/Album/src/Album/Controller/AlbumController.php:
namespace Listing\Controller;


 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Listing\Model\Listing;          // <-- Add this import
 use Listing\Form\ListingForm;       // <-- Add this import


 class ListingController extends AbstractActionController
 {
     protected $listingTable;
	 
	 public function getListingTable()
     {
         if (!$this->listingTable) {
             $sm = $this->getServiceLocator();
             $this->listingTable = $sm->get('Listing\Model\ListingTable');
         }
         return $this->listingTable;
     }
	 public function indexAction()
     {
		  return new ViewModel(array(
             'listings' => $this->getListingTable()->fetchAll(),
         ));
     }

      public function addAction()
     {
         $form = new ListingForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $listing = new Listing();
             $form->setInputFilter($listing->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $listing->exchangeArray($form->getData());
                 $this->getListingTable()->saveListing($listing);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('listing');
             }
         }
         return array('form' => $form);
     }


    public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('listing', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $listing = $this->getListingTable()->getListing($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('listing', array(
                 'action' => 'index'
             ));
         }

         $form  = new ListingForm();
         $form->bind($listing);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($listing->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getListingTable()->saveListing($listing);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('listing');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }

      public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('listing');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getListingTable()->deleteListing($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('listing');
         }

         return array(
             'id'    => $id,
             'listing' => $this->getListingTable()->getListing($id)
         );
     }
 }