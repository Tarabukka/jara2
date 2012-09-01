<?php
	/*
	 * Jara v2.1, the lightweight PHP/MySQL blogging platform.
	 * 
	 * classes/FileCacheDriver.php
	 * A cache driver that uses files on the local filesystem to cache data.
	 *
	 */

	/*
	 * Copyright 2012 Tarabukka.

	 * Licensed under the Apache License, Version 2.0 (the "License");
	 * you may not use this file except in compliance with the License.
	 * You may obtain a copy of the License at
	 *
	 * http://www.apache.org/licenses/LICENSE-2.0

	 * Unless required by applicable law or agreed to in writing, software
	 * distributed under the License is distributed on an "AS IS" BASIS,
	 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	 * See the License for the specific language governing permissions and
	 * limitations under the License.
	 *
	*/

	class FileCacheDriver implements CacheDriver {
		public $cache_directory = '';

		public function __construct($cache_directory) {
			if(!file_exists($cache_directory) || !is_dir($cache_directory)) {
				throw new RuntimeException('Cache directory does not exist.');
			}

			if(!is_writable($cache_directory)) {
				throw new RuntimeException('Cannot write to cache directory.')
			}

			if(!is_readable($cache_directory)) {
				throw new RuntimeException('Cannot read cache directory.');
			}

			$this->cache_directory = $cache_directory;
		}

		public function getFileName($key) {
			return $this->cache_directory . '/' . $key;
		}

		public function get($key) {
			return file_get_contents($this->getFileName($key));
		}

		public function set($key, $value) {
			file_put_contents($this->getFileName($key), $value);
		}

		public function exists($key) {
			return file_exists($this->getFileName($key));
		}
	}