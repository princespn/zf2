<?php
 // Filename: /module/Blog/src/Blog/Factory/ListControllerFactory.php
 namespace Blog\Factory;

 use Blog\Service\PostService;
 use Zend\ServiceManager\FactoryInterface;
 use Zend\ServiceManager\ServiceLocatorInterface;

 class ListControllerFactory implements FactoryInterface
 {
     /**
      * Create service
      *
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
     public function createService(ServiceLocatorInterface $serviceLocator)
     {
         //return new PostService(
         //    $serviceLocator->get('Blog\Mapper\PostMapperInterface')
        // );
         
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $postService        = $realServiceLocator->get('Blog\Service\PostServiceInterface');

         return new ListController($postService);
     }
     
 }