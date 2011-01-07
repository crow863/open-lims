<?
/**
 * @package data
 * @version 0.4.0.0
 * @author Roman Konertz
 * @copyright (c) 2008-2010 by Roman Konertz
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
 * Object Management Interface
 * @package data
 */
interface ObjectInterface
{
	function __construct($object_id);
	function __destruct();
	
	public function get_file_id();
	public function get_value_id();
	public function get_item_id();
	public function get_toid();
	
	public static function get_file_array($folder_id);
	public static function get_value_array($folder_id);
	public static function get_object_array($folder_id);
	
	// protected static function get_id_by_value_id($value_id);
	// protected static function get_id_by_file_id($file_id);
}

?>
