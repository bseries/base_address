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

namespace base_address\controllers;

use base_address\models\Users;
use base_address\models\VirtualUsers;
use base_address\models\Addresses;
use base_address\models\Countries;
use lithium\core\Environment;

class AddressesController extends \base_address\controllers\BaseController {

	use \base_address\controllers\AdminAddTrait;
	use \base_address\controllers\AdminEditTrait;
	use \base_address\controllers\AdminDeleteTrait;

	public function admin_index() {
		$data = Addresses::find('all', [
			'order' => ['created' => 'DESC']
		]);
		return compact('data');
	}

	protected function _selects($item = null) {
		$virtualUsers = [null => '-'] + VirtualUsers::find('list', ['order' => 'name']);
		$users = [null => '-'] + Users::find('list', ['order' => 'name']);
		$countries = Countries::find('list');

		return compact('users', 'virtualUsers', 'countries');
	}
}

?>