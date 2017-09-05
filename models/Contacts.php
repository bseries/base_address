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
 * License. If not, see https://atelierdisko.de/licenses.
 */

namespace base_address\models;

use base_address\models\Addresses;

class Contacts extends \base_core\models\Base {

	protected $_meta = [
		'connection' => false
	];

	// Creates a User-like entity with an attached address from a flat
	// data array.
	public static function create(array $data = [], array $options = []) {
		// Build a mapping of fields that belong into the address
		// entity.
		$fields = array_diff(array_keys(Addresses::schema()->fields()), [
			'id',
			'user_id',
			'created',
			'modified'
		]);
		$map = array_combine($fields, $fields) + [
			// These fields are copied.
			'name' => 'recipient'
		];
		$address = [];
		foreach ($map as $key => $value) {
			if (!isset($data[$key])) {
				continue;
			}
			if ($key === 'country') {
				$country = Countries::find('first', [
					'conditions' => [
						'name' => $data[$key]
					],
					'available' => true
				]);
				if (!$country) {
					$message = 'Failed to convert contact country to address country code.';
					throw new Exception($message);
				}
				$address[$key] = $country->id;
			} else {
				$address[$value] = $data[$key];
			}
		}
		$address = Addresses::create($address);

		// Remaps fields coming from a Settings::read()-contact.
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