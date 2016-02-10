<?php
/**
 * Base Address
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * Licensed under the AD General Software License v1.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * You should have received a copy of the AD General Software
 * License. If not, see http://atelierdisko.de/licenses.
 */

namespace base_address\config;

use base_core\extensions\cms\Panes;
use lithium\g11n\Message;

extract(Message::aliases());

Panes::register('user.addresses', [
	'title' => $t('Addresses', ['scope' => 'base_address']),
	'url' => [
		'library' => 'base_address',
		'controller' => 'Addresses', 'action' => 'index',
		'admin' => true
	],
	'weight' => 3
]);

?>