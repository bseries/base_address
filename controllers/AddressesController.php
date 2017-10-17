<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_address\controllers;

use base_core\models\Users;
use base_address\models\Addresses;
use base_address\models\Countries;

class AddressesController extends \base_core\controllers\BaseController {

	use \base_core\controllers\AdminIndexTrait;
	use \base_core\controllers\AdminAddTrait;
	use \base_core\controllers\AdminEditTrait;
	use \base_core\controllers\AdminDeleteTrait;
	use \base_core\controllers\UsersTrait;

	protected function _selects($item = null) {
		if ($item) {
			$users = $this->_users($item, ['field' => 'user_id', 'empty' => true]);
			$countries = Countries::find('list', [
				'available' => true
			]);
			return compact('users', 'countries');
		}
		return [];
	}
}

?>