<?php
/**
 * Base Address
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

use lithium\net\http\Router;

$persist = ['persist' => ['admin', 'controller']];

Router::connect('/admin/addresses/{:action}/{:id:[0-9]+}', [
	'library' => 'base_address',
	'controller' => 'addresses',
	'admin' => true
], $persist);
Router::connect('/admin/addresses/{:action}/{:args}', [
	'library' => 'base_address',
	'controller' => 'addresses',
	'admin' => true
], $persist);

?>