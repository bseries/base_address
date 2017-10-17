<?php
/**
 * Copyright 2014 David Persson. All rights reserved.
 * Copyright 2016 Atelier Disko. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
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

		if ($options['available'] !== true) {
			$results = array_intersect_key($results, array_fill_keys($options['available'], null));
		}
		foreach ($results as $code => $name) {
			$data[$code] = [
				'id' => $code,
				'name' => $name
			];
		}
		return $data;
	}
}

?>