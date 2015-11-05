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

namespace base_address\models;

use lithium\g11n\Catalog;

class Countries extends \base_core\models\BaseG11n {

	protected static function _available() {
		return explode(' ', PROJECT_COUNTRIES);
	}

	protected static function _data(array $options) {
		$data = [];
		$results = Catalog::read(true, 'territory', $options['translate']);

		foreach ($options['available'] as $available) {
			$data[$available] = [
				'id' => $available,
				'name' => $results[$available]
			];
		}
		return $data;
	}
}

?>