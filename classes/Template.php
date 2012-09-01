<?php
	/*
	 * Jara v2.1, the lightweight PHP/MySQL blogging platform.
	 * 
	 * classes/Template.php
	 * Represents a Jara-formatted HTML template.
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

	class Template {`
		const ID_HEADER = 'header';
		const ID_CATEGORY = 'category';
		const ID_COMMENT = 'comment';
		const ID_COMMENT_FORM = 'comment_form';
		const ID_COMMENTS_END = 'comments_end';
		const ID_COMMENTS_START = 'comments_start';
		const ID_FOOTER = 'footer';
		const ID_LOGIN = 'login';
		const ID_PAGINATION = 'pagination';
		const ID_POST = 'post';
		const ID_POSTS_END = 'posts_end';
		const ID_POSTS_START = 'posts_start';
		const ID_SEARCH = 'search';
		const ID_USER = 'user';
		const FILE_EXTENSION = 'tpl';

		private static $cacheDriver;

		public $templateId = '';

		public $template_content = NULL;

		public static function SetCacheDriver($cacheDriver) {
			self::$cacheDriver = $cacheDriver;
		}

		public static function GetCacheDriver() {
			return self::$cacheDriver;
		}

		public function __construct($templateId) {
			$this->templateId = $templateId;

			$this->load();
		}

		public function load() {
			if(!file_exists($this->getPath())) {
				throw new RuntimeException('Template file was not found.');
			}

			$this->template_content = file_get_contents($this->getPath());
		}

		public function cacheKey($extra_parameters) {
			return 'template_' . md5($this->templateId . serialize($extra_parameters));
		}

		public function existsInCache($extra_parameters) {
			if(self::$cacheDriver != NULL)
				return self::$cacheDriver->exists($this->cacheKey($extra_parameters));
			else
				return false;
		}

		public function renderFromCache($extra_parameters) {
			if(self::$cacheDriver != NULL)
				echo self::$cacheDriver->get($this->cacheKey($extra_parameters));
		}

		public function writeIntoCache($extra_parameters, $html) {
			if(self::$cacheDriver != NULL)
				self::$cacheDriver->save($this->cacheKey($extra_parameters), $html);
			else
				throw new LogicException('Tried to write to a cache driver with no cache driver set.');
		}

		public function render($extra_parameters) {
			if($this->existsInCache($extra_parameters)) {
				$this->renderFromCache($extra_parameters);
				return;
			}

			ob_start();

			$html = ob_end_clean();

			$this->writeIntoCache($extra_parameters, $html);
		}

		public function getPath() {
			return Utilities::RealPrefix() . '/../templates/' . Settings::Get('template') . '/' . $this->template_id . '.' . self::FILE_EXTENSION;
		}
	}