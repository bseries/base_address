<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
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