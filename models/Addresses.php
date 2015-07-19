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

use lithium\core\Environment;
use lithium\util\Validator;
use lithium\util\Inflector;
use lithium\g11n\Message;
use CommerceGuys\Addressing\Model\Address as FormalAddress;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
use CommerceGuys\Addressing\Repository\AddressFormatRepository;
use CommerceGuys\Addressing\Repository\CountryRepository;
use CommerceGuys\Addressing\Repository\SubdivisionRepository;

class Addresses extends \base_core\models\Base {

	protected $_actsAs = [
		'base_core\extensions\data\behavior\User',
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Searchable' => [
			'fields' => [
				'recipient',
				'organization',
				'address_line_1',
				'locality',
				'postal_code',
				'country'
			]
		]
	];

	public $belongsTo = [
		'User' => [
			'to' => 'base_core\models\Users',
			'key' => 'user_id'
		],
		'VirtualUser' => [
			'to' => 'base_core\models\VirtualUsers',
			'key' => 'virtual_user_id'
		]
	];

	public static function init() {
		extract(Message::aliases());
		$model = static::_object();

		// The following rules only check for existencce of fields.
		// The rest of the work is left to the addressing validator.

		$model->validates['recipient'] = [
			'or' => [
				'recipientOrOrganization',
				'on' => ['create', 'update'],
				'message' => $t('Please provide either name or company.', ['scope' => 'base_address'])
			]
		];
		Validator::add('recipientOrOrganization', function($value, $format, $options) {
			return !empty($value) || !empty($options['values']['organization']);
		});

		$model->validates['address_line_1'] = [
			'notEmpty' => [
				'notEmpty',
				'on' => ['create', 'update'],
				'last' => true,
				'message' => $t('This field cannot be empty.', ['scope' => 'base_address'])
			],
			'streetName' => [
				'streetName',
				'on' => ['create', 'update'],
				'last' => true,
				'message' => $t('Missing street name.', ['scope' => 'base_address'])
			],
			'streetNo' => [
				'streetNo',
				'on' => ['create', 'update'],
				'message' => $t('Missing street number.', ['scope' => 'base_address'])
			],
		];
		Validator::add('streetName', function($value, $format, $options) {
			return preg_match('/^\w+/i', $value);
		});
		Validator::add('streetNo', function($value, $format, $options) {
			return preg_match('/\s[0-9]+/', $value);
		});

		$model->validates['locality'] = [
			'notEmpty' => [
				'notEmpty',
				'on' => ['create', 'update'],
				'message' => $t('This field cannot be empty.', ['scope' => 'base_address'])
			]
		];

		$model->validates['postal_code'] = [
			'notEmpty' => [
				'notEmpty',
				'on' => ['create', 'update'],
				'last' => true,
				'message' => $t('This field cannot be empty.', ['scope' => 'base_address'])
			]
		];
		$model->validates['country'] = [
			'notEmpty' => [
				'notEmpty',
				'on' => ['create', 'update'],
				'message' => $t('A country must be selected.', ['scope' => 'base_address'])
			],
			'countryCode' => [
				'countryCode',
				'on' => ['create', 'update'],
				'message' => $t('Invalid country.', ['scope' => 'base_address'])
			]
		];
		Validator::add('countryCode', function($value, $format, $options) {
			return in_array($value, explode(' ', PROJECT_COUNTRIES));
		});

		// Phone

		$model->validates['phone'] = [
			'phone' => [
				'phone',
				'on' => ['create', 'update'],
				'skipEmpty' => true,
				'message' => $t('The field is not correctly formatted.', ['scope' => 'base_address'])
			],
		];
	}

	public function title($entity) {
		return $entity->format('oneline');
	}

	public static function findExact($data) {
		return static::find('first', [
			'conditions' => $data
		]);
	}

	public function copy($entity, $object, $prefix = null) {
		$skipFields = ['id', 'user_id', 'created', 'modified'];

		foreach ($entity->data() as $field => $value) {
			if (in_array($field, $skipFields)) {
				continue;
			}
			$field = $prefix ? $prefix . $field : $field;
			$object->{$field} = $value;
		}
		return $object;
	}

	public static function createFromPrefixed($prefix, array $data) {
		$item = [];

		foreach ($data as $field => $value) {
			// Also includes unprefixed virtual_user_id and user_id.
			if (strpos($field, 'user_') !== false) {
				$item[$field] = $value;
				continue;
			}
			if (strpos($field, $prefix) !== false) {
				// Field might be address_address_line_1 thus we cannot
				// use str_replace().
				$field = preg_replace('/^' . $prefix . '/', '', $field);
				$item[$field] = $value;
				continue;
			}
		}
		return static::create($item);
	}

	public function format($entity, $type, $originLocale = null, $originCountry = null) {
		if (!$originLocale) {
			$originLocale = PROJECT_LOCALE;
		}
		if (!$originCountry) {
			$originCountry = PROJECT_COUNTRY;
		}
		$formatter = new PostalLabelFormatter(
			new AddressFormatRepository(),
			new CountryRepository(),
			new SubdivisionRepository(),
			$originCountry,
			$originLocale
		);

		if ($type == 'oneline') {
			$result = [];

			$result[] = $entity->organization;
			$result[] = $entity->recipient;
			$result[] = $entity->address_line_1;
			$result[] = $entity->locality;

			return implode(', ', array_filter($result));
		}
		if ($type == 'postal') {
			return $formatter->format($entity->formal($originLocale));
		}
	}

	public function formal($entity, $locale = null) {
		if (!$locale) {
			$locale = Environment::get('locale');
		}
		return (new FormalAddress())
			->setLocale($locale)
			->setCountryCode($entity->country) // compare country_code
			->setAdministrativeArea($entity->administrative_area)
			->setLocality($entity->locality)
			->setDependentLocality($entity->dependent_locality)
			->setPostalCode($entity->postal_code)
			->setSortingCode($entity->sorting_code)
			->setAddressLine1($entity->address_line_1)
			->setAddressLine2($entity->address_line_2)
			->setOrganization($entity->organization)
			->setRecipient($entity->recipient);
	}

	// FIXME Auto map regions to prefixes.
	public static function completePhone($value, $region) {
		if (!empty($value)) {
			// First remove any whitespace characters so we can match more easily.
			$value = preg_replace('/\s/', '', $value);

			// If the number doesn't not already have a national prefix, add
			// one according to region.
			if (!preg_match('/^(\+|00)/', $value)) {
				if ($region === 'DE') {
				// Assume we have a leading 0 for the city.
				$value = preg_replace('/^(0)/','+49', $value);
				}
			}
		}
		return $value;
	}
}

Addresses::init();

?>