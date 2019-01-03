<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;

class SecurityPlugin extends Plugin
{
	public const ADMIN = 'admin';
	public const GUEST = 'guest';

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // Check whether the 'auth' variable exists in session to define the active role
        $auth = $this->session->get('auth');

        if (!$auth) {
            $role = self::GUEST;
        } else {
        	//ENUM('admin', 'teach', 'student')
            $role = self::ADMIN;
        }

        // Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        // Obtain the ACL list
        $acl = $this->getAcl();

        // Check if the Role have access to the controller (resource)
        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed) {
            // If he doesn't have access forward him to the index controller
            $this->flash->error(
                "You don't have access to this module"
            );

            $dispatcher->forward(
                [
                    'controller' => 'index',
                    'action'     => 'index',
                ]
            );

            // Returning 'false' we tell to the dispatcher to stop the current operation
            return false;
        }
    }
    private function getAcl(){
    	// Create the ACL
		$acl = new AclList();

		// The default action is DENY access
		$acl->setDefaultAction(
		    Acl::DENY
		);

		// Register two roles, Users is registered users
		// and guests are users without a defined identity
		$roles = [
		    self::ADMIN  => new Role(self::ADMIN),
		    self::GUEST => new Role(self::GUEST),
		];

		foreach ($roles as $role) {
		    $acl->addRole($role);
		}
		// Private area resources (backend)
		$allPrivateResources = [
		    
		    'session'   	=> ['index','logout'],
		    'conference'    => ['create'],
		    'conference' 	=> ['index'],
		    'talks'    		=> ['index'],
		    'statistic'    	=> ['index'],
		    'user'    		=> ['settings','delete'],
		    'room'			=> ['edit'],

		];
		$adminResources = [
		    
		    'session'    => ['index','logout'],
		    'conference'    => ['create'],
		    'user'    => ['index','edit','create','search','new','save','delete'],
		    'room'	=> ['edit'],
		   
		];

		foreach ($allPrivateResources as $resourceName => $actions) {
		    $acl->addResource(
		        new Resource($resourceName),
		        $actions
		    );
		}

		// Public area resources (frontend)
		$publicResources = [
		    'index'    => ['index'],
		    'session'  => ['index'],
		    'user'     => ['index','create','search','new','save'],

		];

		foreach ($publicResources as $resourceName => $actions) {
		    $acl->addResource(
		        new Resource($resourceName),
		        $actions
		    );
		}
		// Grant access to public areas to both users and guests
		foreach ($roles as $role) {
		    foreach ($publicResources as $resource => $actions) {
		    	foreach ($actions as $action) {
			        $acl->allow(
			            $role->getName(),
			            $resource,
			            $action
			        );
			    }
		    }
		}

		// Grant access to private area only to role Users
		foreach ($adminResources as $resource => $actions) {
		    foreach ($actions as $action) {
		        $acl->allow(
		            self::ADMIN,
		            $resource,
		            $action
		        );
		    }
		}
		
		return $acl;
    }
}