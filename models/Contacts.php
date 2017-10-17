<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace base_address\models;

use base_address\models\Addresses;
use Exception;

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
				$isCountryCode = Countries::find('first', [
					'conditions' => [
						'id' => $data[$key]
					],
					'available' => true
				]);
				if ($isCountryCode) {
					$address[$value] = $data[$key];
				} else {
					$country = Countries::find('first', [
						'conditions' => [
							'name' => $data[$key]
						],
						'available' => true
					]);
					if (!$country) {
						$message  = "Failed to convert contact country `{$data[$key]}` ";
						$message .= "to address country code.";
						throw new Exception($message);
					}
					$address[$key] = $country->id;
				}
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