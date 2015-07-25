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

namespace base_address\models;

use base_address\models\Addresses;

class Contacts extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	public static function create(array $data = [], array $options = []) {
		$fields = array_diff(array_keys(Addresses::schema()->fields()), [
			'id',
			'user_id',
			'created',
			'modified'
		]);
		$map = array_combine($fields, $fields) + [
			'name' => 'recipient'
		];
		$address = [];
		foreach ($map as $key => $value) {
			if (!isset($data[$key])) {
				continue;
			}
			$address[$value] = $data[$key];
		}
		$address = Addresses::create($address);

		$map = [
			'name' => 'name',
			'organization' => 'name',
			'email' => 'email',
			'website' => 'website'
		];
		$contact = [];
		foreach ($map as $key => $value) {
			if (!isset($data[$key])) {
				continue;
			}
			$contact[$value] = $data[$key];
		}
		return parent::create($contact + [
			'name' => null,
			'email' => null,
			'website' => null,
			'address' => $address
		]);
	}

	public function address($entity) {
		return $entity->address;
	}
}

?>