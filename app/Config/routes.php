<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'Shares', 'action' => 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

#pragma mark - USERS

    //API
    Router::connect('/api/user/add', array('controller' => 'ApiUsers', 'action' => 'add'));
    Router::connect('/api/user/details/:externalId', array('controller' => 'ApiUsers', 'action' => 'details'), array(
        'pass' => array('externalId'),
        'externalId' => '[A-Za-z0-9]+'
    ));
    Router::connect('/api/user/shares', array('controller' => 'ApiUsers', 'action' => 'shares'));
    Router::connect('/api/user/requests', array('controller' => 'ApiUsers', 'action' => 'requests'));
    Router::connect('/api/user/registerpush', array('controller' => 'ApiUsers', 'action' => 'registerPush'));

    //General
    Router::connect('/user/add', array('controller' => 'Users', 'action' => 'add'));
    Router::connect('/user/details/:externalId', array('controller' => 'Users', 'action' => 'details'), array(
        'pass' => array('externalId'),
        'externalId' => '[A-Za-z0-9]+'
    ));
    Router::connect('/user/account', array('controller' => 'Users', 'action' => 'account'));
    Router::connect('/user/shares', array('controller' => 'Users', 'action' => 'shares'));
    Router::connect('/user/requests', array('controller' => 'Users', 'action' => 'requests'));
    Router::connect('/user/authenticate', array('controller' => 'Users', 'action' => 'authenticate'));
    Router::connect('/user/logout', array('controller' => 'Users', 'action' => 'logout'));

#pragma mark - SHARE TYPE CATEGORIES

    //API
    Router::connect('/api/share_type_categories/get', array('controller' => 'ApiShareTypeCategories', 'action' => 'get'));
    
    //General
    Router::connect('/share_type_categories/get', array('controller' => 'ShareTypeCategories', 'action' => 'get'));

#pragma mark - SHARE TYPES
    
    //API
    Router::connect('/api/share_types/get', array('controller' => 'ApiShareTypes', 'action' => 'get'));

    //General
    Router::connect('/share_types/get', array('controller' => 'ShareTypes', 'action' => 'get'));

#pragma mark - SHARES

    //API
    Router::connect('/api/share/search', array('controller' => 'ApiShares', 'action' => 'search'));
    Router::connect('/api/share/add', array('controller' => 'ApiShares', 'action' => 'add'));
    Router::connect('/api/share/details/:shareId', array('controller' => 'ApiShares', 'action' => 'details'), array(
        'pass' => array('shareId'),
        'shareId' => '[0-9]+'
    ));
    Router::connect('/api/share/cancel/:shareId', array('controller' => 'ApiShares', 'action' => 'cancel'), array(
        'pass' => array('shareId'),
        'shareId' => '[0-9]+'
    ));

    //General
    Router::connect('/share/search/:shareTypeCategory', array('controller' => 'Shares', 'action' => 'search'), array(
        'pass' => array('shareTypeCategory'),
        'shareTypeCategory' => '[A-Za-z0-9]+'
    ));
    Router::connect('/share/search/:shareTypeCategory/:shareType', array('controller' => 'Shares', 'action' => 'search'), array(
        'pass' => array('shareTypeCategory', 'shareType'),
        'shareTypeCategory' => '[A-Za-z0-9]+',
        'shareType' => '[A-Za-z0-9]+'
    ));

    Router::connect('/share/add', array('controller' => 'Shares', 'action' => 'add'));
    Router::connect('/share/details/:shareId', array('controller' => 'Shares', 'action' => 'details'), array(
        'pass' => array('shareId'),
        'shareId' => '[0-9]+'
    ));
    Router::connect('/share/view/:shareId', array('controller' => 'Shares', 'action' => 'view'), array(
        'pass' => array('shareId'),
        'shareId' => '[0-9]+'
    ));
    Router::connect('/share/cancel/:shareId', array('controller' => 'Shares', 'action' => 'cancel'), array(
        'pass' => array('shareId'),
        'shareId' => '[0-9]+'
    ));

#pragma mark - COMMENTS
    
    //API
    Router::connect('/api/comment/add', array('controller' => 'ApiComments', 'action' => 'add'));
    Router::connect('/api/comment/get', array('controller' => 'ApiComments', 'action' => 'get'));

    //General
    Router::connect('/comment/add', array('controller' => 'Comments', 'action' => 'add'));
    Router::connect('/comment/get', array('controller' => 'Comments', 'action' => 'get'));

#pragma mark - REQUESTS
    
    //API
    Router::connect('/api/request/add', array('controller' => 'ApiRequests', 'action' => 'add'));
    Router::connect('/api/request/accept/:requestId', array('controller' => 'ApiRequests', 'action' => 'accept'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));
    Router::connect('/api/request/decline/:requestId', array('controller' => 'ApiRequests', 'action' => 'decline'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));
    Router::connect('/api/request/cancel/:requestId', array('controller' => 'ApiRequests', 'action' => 'cancel'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));

    //General
    Router::connect('/request/add', array('controller' => 'Requests', 'action' => 'add'));
    Router::connect('/request/accept/:requestId', array('controller' => 'Requests', 'action' => 'accept'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));
    Router::connect('/request/decline/:requestId', array('controller' => 'Requests', 'action' => 'decline'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));
    Router::connect('/request/cancel/:requestId', array('controller' => 'Requests', 'action' => 'cancel'), array(
        'pass' => array('requestId'),
        'requestId' => '[0-9]+'
    ));

#pragma mark - EVALUATIONS

    //API
    Router::connect('/api/evaluation/add', array('controller' => 'ApiEvaluations', 'action' => 'add'));

    //General
    Router::connect('/evaluation/add', array('controller' => 'Evaluations', 'action' => 'add'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
