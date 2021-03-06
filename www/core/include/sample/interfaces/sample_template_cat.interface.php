<?php
/**
 * @package sample
 * @version 0.4.0.0
 * @author Roman Konertz <konertz@open-lims.org>
 * @copyright (c) 2008-2016 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Sample Template Category Interface
 * @package sample
 */ 	 
interface SampleTemplateCatInterface
{
	/**
	 * @param integer $sample_template_cat_id
	 */
	function __construct($sample_template_cat_id);
	
	function __destruct();
	
	/**
	 * Creates a new sample-tempalte-category
	 * @param string $name
	 * @return integer
	 */
	public function create($name);
	
	/**
	 * Deletes a sample-template-category
	 * @return bool
	 */
	public function delete();
	
	/**
	 * @return string
	 */
	public function get_name();
	
	/**
	 * @param string $name
	 * @return integer
	 */
	public function set_name($name);
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function exist_name($name);
	
	/**
	 * @param integer $id
	 * @return bool
	 */
	public static function exist_id($id);
	
	/**
	 * @return array
	 */
	public static function list_entries();
}
?>
