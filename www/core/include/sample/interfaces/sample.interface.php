<?php
/**
 * @package sample
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
 * Sample Interface
 * @package sample
 */ 		 
interface SampleInterface
{
	function __construct($sample_id);
	function __destruct();
	
	public function set_template_data($type, $type_id, $array);
	
	public function create($organisation_unit_id, $template_id, $name, $supplier, $depository_id, $desc);
	public function delete();
	
	public function get_requirements();
	public function get_fulfilled_requirements();
	public function get_sub_folder($folder_id, $gid);
	
	public function link_sample($sample_id);
	public function unlink_sample($sample_id);
	public function link_parent_sample($sample_id);
	public function unlink_parent_sample($sample_id);
	public function list_related_samples();
	public function list_parent_samples();
	
	public function add_depository($depository_id);
	public function get_all_depositories();
	public function get_all_depository_information();
	public function get_current_depository();
	
	public function get_item_id();
	public function get_name();
	public function get_datetime();
	public function get_owner_id();
	public function get_supplier();
	public function get_template_id();
	public function get_availability();
	
	public function get_current_depository_name();
	public function get_template_name();
	public function get_formatted_id();
	public function get_organisation_unit_id();
	
	public function set_name($name);
	public function set_owner_id($owner_id);
	public function set_supplier($supplier);
	public function set_availability($availability);
	
	public static function exist_sample($sample_id);
	public static function list_user_related_samples($user_id);
	public static function list_organisation_unit_related_samples($organisation_unit_id);
	public static function count_samples($user_id);
	public static function search_samples($string, $organisation_unit_array, $template_array, $in_id, $in_name);
}
?>
