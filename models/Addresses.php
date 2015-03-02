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
use CommerceGuys\Addressing\Formatter\PostalFormatter;
use CommerceGuys\Addressing\Provider\DataProvider;
use CommerceGuys\Addressing\Validator\Constraints\AddressFormat;
use CommerceGuys\Addressing\Validator\Constraints\Country;

class Addresses extends \base_core\models\Base {

	use \base_core\models\UserTrait;

	protected static $_actsAs = [
		'base_core\extensions\data\behavior\Timestamp'
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


	public static function _validateAddress($address) {
		$validates = [];

//		$countryCode = $address->getCountryCode();

		$fields = [
			'administrativeArea',
			'locality',
			'dependentLocality',
			'postalCode',
			'sortingCode',
			'addressLine1',
			// skip address line 2
			'organization',
			'recipient'
		];

		$values = [];
        foreach ($this->fieldMapping as $field) {
            $getter = 'get' . ucfirst($field);
            $values[Inflector::underscore($field)] = $address->$getter();
        }

		$dataProvider = new DataProvider();
		$addressFormat = $dataProvider->getAddressFormat($address->getCountryCode());

		// Require fields

	}

	protected static function _validateSubdivision($field, $dataProvider, $addressFormat) {
		$parentId = ($fieldConstant == 'root') ? 0 : $values[$fieldConstant];
		$children = $dataProvider->getSubdivisions($countryCode, $parentId);

		$levels = array(
            'root',
            AddressFormatInterface::FIELD_ADMINISTRATIVE_AREA,
            AddressFormatInterface::FIELD_LOCALITY,
            AddressFormatInterface::FIELD_DEPENDENT_LOCALITY,
        );

	}

	protected function validateSubdivisions($values, AddressFormatInterface $addressFormat, $constraint)
    {
        $dataProvider = $this->getDataProvider();
        $countryCode = $addressFormat->getCountryCode();
        $subdivisionLevels = array(
            'root',
            AddressFormatInterface::FIELD_ADMINISTRATIVE_AREA,
            AddressFormatInterface::FIELD_LOCALITY,
            AddressFormatInterface::FIELD_DEPENDENT_LOCALITY,
        );
        $subdivisions = array();
        foreach ($subdivisionLevels as $index => $fieldConstant) {
            $parentId = ($fieldConstant == 'root') ? 0 : $values[$fieldConstant];
            $children = $dataProvider->getSubdivisions($countryCode, $parentId);
            $nextIndex = $index + 1;
            if (!$children || !isset($subdivisionLevels[$nextIndex])) {
                // This level has no children, stop.
                break;
            }
            $nextFieldConstant = $subdivisionLevels[$nextIndex];
            if (empty($values[$nextFieldConstant])) {
                // The child value is empty, stop.
                break;
            }

            $found = false;
            foreach ($children as $child) {
                if ($child->getId() == $values[$nextFieldConstant]) {
                    $found = true;
                    $subdivisions[] = $child;
                    break;
                }
            }

            if (!$found) {
                $subPath = '[' . $this->fieldMapping[$nextFieldConstant] . ']';
                $invalidValue = $values[$nextFieldConstant];
                $this->context->addViolationAt($subPath, $constraint->invalidMessage, array(), $invalidValue);
                break;
            }
        }

        return $subdivisions;
    }


	public static function init() {
		extract(Message::aliases());
		$model = static::_object();


		static::applyFilter('validate', function() {
			$sfValidator = SymfonyValidation::createValidator();

			$violations = $sfValidator->validateValue($entity->formal()->getContryCode(), new Country());
			$violations = $sfValidator->validateValue($entity->formal(), new AddressFormat());


			return $chain;
		});

		// The following rules only check for existencce of fields.
		// The rest of the work is left to the addressing validator.

		$model->validates['recipient'] = [
			[
				'nameOrCompany',
				'on' => ['create', 'update'],
				'message' => 'Bitte geben Sie einen Namen und/oder eine Firma an.'
			]
		];
		Validator::add('recipientOrOrganization', function($value, $format, $options) {
			return !empty($value) || !empty($options['values']['organization']);
		});
		$model->validates['address_line_1'] = [
			[
				'notEmpty',
				'on' => ['create', 'update'],
				'last' => true,
				'message' => $t('This field cannot be empty.')
			],
			[
				'streetNo',
				'on' => ['create', 'update'],
				'message' => $t('Missing street number.')
			],
		];
		Validator::add('streetNo', function($value, $format, $options) {
			return preg_match('/\s[0-9]/', $value);
		});

		$locale = Environment::get('locale');
		if (strpos($locale, '_') === false) {
			$locale = $locale . '_' . strtoupper($locale);
		}
		$model->validates['postal_code'] = [
			[
				'notEmpty',
				'on' => ['create', 'update'],
				'last' => true,
				'message' => $t('This field cannot be empty.')
			]
		];
		$model->validates['locality'] = [
			[
				'notEmpty',
				'on' => ['create', 'update'],
				'message' => $t('This field cannot be empty.')
			],
		];
		$model->validates['country_code'] = [
			[
				'notEmpty',
				'on' => ['create', 'update'],
				'message' => $t('A country must be selected.')
			],
		];

		$model->validates['phone'] = [
			[
				'phone',
				'on' => ['create', 'update'],
				'skipEmpty' => true,
				'message' => $t('The field is not correctly formatted.')
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
				$field = str_replace($prefix, '', $field);
				$item[$field] = $value;
				continue;
			}
		}
		return static::create($item);
	}

	public function format($entity, $type, $originCountryCode, $originLocale = null) {
		if (!$originLocale) {
			$originLocale = ($user = $entity->user()) ? $user->locale : Environment::get('locale');
		}
		$formatter = new PostalFormatter(new DataProvider());

		if ($type == 'oneline') {
			$result = [];

			$result[] = $entity->organization;
			$result[] = $entity->recipient;
			$result[] = $entity->address_line_1;
			$result[] = $entity->locality;

			return implode(', ', array_filter($result));
		}
		if ($type == 'postal') {
			return $formatter->format($entity->formal($originLocale), $originCountryCode, $originLocale);
		}
	}

	public function formal($entity, $locale = null) {
		if (!$locale) {
			$locale = Environment::get('locale');
		}
		return (new FormalAddress())
			->setLocale($locale)
			->setCountryCode($entity->country_code)
			->setAdministrativeAreay($entity->administrative_area)
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