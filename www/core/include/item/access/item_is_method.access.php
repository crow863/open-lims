<?php
/**
 * @package item
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
 * Item Is Method Access Class
 * @package item
 */
class ItemIsMethod_Access
{
	const ITEM_IS_METHOD_TABLE = 'core_item_is_method';
	const ITEM_IS_METHOD_PK_SEQUENCE = 'core_item_is_method_primary_key_seq';
	
	private $primary_key;
	
	private $method_id;
	private $item_id;
	
	/**
	 * @param $primary_key
	 */
	function __construct($primary_key)
	{
		global $db;
		
		if ($primary_key == null)
		{
			$this->primary_key = null;
		}
		else
		{
			$sql = "SELECT * FROM ".self::ITEM_IS_METHOD_TABLE." WHERE primary_key='".$primary_key."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[primary_key])
			{
				$this->primary_key	= $primary_key;
				
				$this->method_id	= $data[method_id];
				$this->item_id		= $data[item_id];
			
			}
			else
			{
				$this->primary_key	= null;
			}
		}
	}
	
	function __destruct()
	{
		if ($this->primary_key)
		{
			unset($this->primary_key);
			unset($this->method_id);
			unset($this->item_id);
		}
	}
	
	/**
	 * @param integer $method_id
	 * @param integer $item_id
	 * @return integer
	 */
	public function create($method_id, $item_id)
	{
		global $db;
		
		if (is_numeric($method_id) and is_numeric($item_id))
		{
			$sql_write = "INSERT INTO ".self::ITEM_IS_METHOD_TABLE." (primary_key,method_id,item_id) " .
					"VALUES (nextval('".self::ITEM_IS_METHOD_PK_SEQUENCE."'::regclass),".$method_id.",".$item_id.")";
					
			$res_write = $db->db_query($sql_write);	
			
			if ($db->db_affected_rows($res_write) == 1)
			{	
				$sql_read = "SELECT primary_key FROM ".self::ITEM_IS_METHOD_TABLE." WHERE primary_key = currval('".self::ITEM_IS_METHOD_PK_SEQUENCE."'::regclass)";
				$res_read = $db->db_query($sql_read);
				$data_read = $db->db_fetch_assoc($res_read);
									
				$this->__construct($data_read[primary_key]);
				
				return $data_read[primary_key];
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function delete()
	{
		global $db;
			
		if ($this->primary_key)
		{
			$primary_key_tmp = $this->primary_key;
			
			$this->__destruct();
			
			$sql = "DELETE FROM ".self::ITEM_IS_METHOD_TABLE." WHERE primary_key = ".$primary_key_tmp."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res) == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_method_id()
	{
		if ($this->method_id)
		{
			return $this->method_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_item_id()
	{
		if ($this->item_id)
		{
			return $this->item_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $method_id
	 * @return bool
	 */
	public function set_method_id($method_id)
	{		
		global $db;
	
		if ($this->primary_key and is_numeric($method_id))
		{
			$sql = "UPDATE ".self::ITEM_IS_METHOD_TABLE." SET method_id = ".$method_id." WHERE primary_key = ".$this->primary_key."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->method_id = $method_id;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}	
	}
	
	/**
	 * @param integer $item_id
	 * @return bool
	 */
	public function set_item_id($item_id)
	{	
		global $db;
	
		if ($this->primary_key and is_numeric($item_id))
		{
			$sql = "UPDATE ".self::ITEM_IS_METHOD_TABLE." SET item_id = ".$item_id." WHERE primary_key = ".$this->primary_key."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->item_id = $item_id;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * @param integer $item_id
	 * @return integer
	 */
	public static function get_entry_by_item_id($item_id)
	{
		global $db;
		
		if (is_numeric($item_id))
		{
			$sql = "SELECT primary_key FROM ".self::ITEM_IS_METHOD_TABLE." WHERE item_id = ".$item_id."";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[primary_key])
			{
				return $data[primary_key];	
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $method_id
	 * @return integer
	 */
	public static function get_entry_by_method_id($method_id)
	{
		global $db;
		
		if (is_numeric($method_id))
		{
			$sql = "SELECT primary_key FROM ".self::ITEM_IS_METHOD_TABLE." WHERE method_id = ".$method_id."";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[primary_key])
			{
				return $data[primary_key];	
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	
}
	
?>
