<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    Settings
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */

/** PHPExcel root directory */
if (!defined('ROOT')) {
	/**
	 * @ignore
	 */
	define('ROOT', dirname(__FILE__) . '/../');
	require(ROOT . 'PHPExcel/Autoloader.php');
	Autoloader::Register();
	Shared_ZipStreamWrapper::register();
	// check mbstring.func_overload
	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}


class Settings
{
	public static function getCacheStorageMethod() {
		return CachedObjectStorageFactory::$_cacheStorageMethod;
	}	//	function getCacheStorageMethod()


	public static function getCacheStorageClass() {
		return CachedObjectStorageFactory::$_cacheStorageClass;
	}	//	function getCacheStorageClass()


	public static function setCacheStorageMethod($method = CachedObjectStorageFactory::cache_in_memory, $arguments = array()) {
		return CachedObjectStorageFactory::initialize($method,$arguments);
	}	//	function setCacheStorageMethod()


	public static function setLocale($locale){
		return Calculation::getInstance()->setLocale($locale);
	}	//	function setLocale()

}