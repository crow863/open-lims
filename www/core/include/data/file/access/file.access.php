<?php
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
 * File Access Class
 * @package data
 */
class File_Access
{
	const FILE_TABLE = 'core_files';
	const FILE_PK_SEQUENCE = 'core_files_id_seq';

	private $file_id;
	
	private $datetime;
	private $owner_id;
	private $owner_group_id;
	private $permission;
	private $automatic;
	private $flag;

	/**
	 * @param integer $file_id
	 */
	function __construct($file_id)
	{
		global $db;
		
		if ($file_id == null)
		{
			$this->file_id = null;
		}
		else
		{
			$sql = "SELECT * FROM ".self::FILE_TABLE." WHERE id='".$file_id."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				$this->file_id			= $file_id;
				
				$this->datetime			= $data[datetime];
				$this->owner_id			= $data[owner_id];
				$this->owner_group_id	= $data[owner_group_id];
				$this->permission		= $data[permission];
				$this->flag				= $data[flag];
				
				if ($data[automatic] == "t")
				{
					$this->automatic	= true;
				}
				else
				{
					$this->automatic	= false;
				}
			}
			else
			{
				$this->file_id			= null;
			}				
		}
	}

	function __destruct()
	{
		if ($this->file_id)
		{
			unset($this->file_id);
	
			unset($this->datetime);
			unset($this->owner_id);
			unset($this->owner_group_id);
			unset($this->permission);
			unset($this->automatic);
			unset($this->flag);
		}
	}

	/**
	 * @param integer $owner_id
	 * @return integer
	 */
	public function create($owner_id)
	{
		global $db;
		
		if (is_numeric($owner_id))
		{
			$datetime = date("Y-m-d H:i:s");
			
			$sql_write = "INSERT INTO ".self::FILE_TABLE." (id,datetime,owner_id,owner_group_id,permission,automatic,flag) " .
					"VALUES (nextval('".self::FILE_PK_SEQUENCE."'::regclass),'".$datetime."',".$owner_id.",NULL,NULL,'t',0)";
					
			$db->db_query($sql_write);	
			
			$sql_read = "SELECT id FROM ".self::FILE_TABLE." WHERE id = currval('".self::FILE_PK_SEQUENCE."'::regclass)";
			$res_read = $db->db_query($sql_read);
			$data_read = $db->db_fetch_assoc($res_read);
								
			$this->__construct($data_read[id]);
			
			return $data_read[id];
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
			
		if ($this->file_id)
		{
			$file_id_tmp = $this->file_id;
			
			$this->__destruct();
			
			$sql = "DELETE FROM ".self::FILE_TABLE." WHERE id = ".$file_id_tmp."";
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
	 * @return string
	 */
	public function get_datetime()
	{
		if ($this->datetime)
		{
			return $this->datetime;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_owner_id()
	{
		if ($this->owner_id)
		{
			return $this->owner_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_owner_group_id()
	{
		if ($this->owner_group_id)
		{
			return $this->owner_group_id;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_permission()
	{
		if ($this->permission){
			return $this->permission;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function get_automatic()
	{
		if (isset($this->automatic))
		{
			return $this->automatic;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_flag()
	{
		if ($this->flag)
		{
			return $this->flag;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param string $datetime
	 * @return bool
	 */
	public function set_datetime($datetime)
	{		
		global $db;

		if ($this->file_id and $datetime)
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET datetime = '".$datetime."' WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->datetime = $datetime;
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
	 * @param integer $owner_id
	 * @return bool
	 */
	public function set_owner_id($owner_id)
	{
		global $db;

		if ($this->file_id and is_numeric($owner_id))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET owner_id = ".$owner_id." WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->owner_id = $owner_id;
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
	 * @param integer $owner_group_id
	 * @return bool
	 */
	public function set_owner_group_id($owner_group_id)
	{	
		global $db;

		if ($this->file_id and is_numeric($owner_group_id))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET owner_group_id = ".$owner_group_id." WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->owner_group_id = $owner_group_id;
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
	 * @param integer $permission
	 * @return bool
	 */
	public function set_permission($permission)
	{		
		global $db;

		if ($this->file_id and is_numeric($permission))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET permission = ".$permission." WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->permission = $permission;
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
	 * @param bool $automatic
	 * @return bool
	 */
	public function set_automatic($automatic)
	{
		global $db;

		if ($this->file_id and isset($automatic))
		{
			if ($automatic == true)
			{
				$automatic_insert = "t";
			}
			else
			{
				$automatic_insert = "f";
			}
			
			$sql = "UPDATE ".self::FILE_TABLE." SET automatic = '".$automatic_insert."' WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->automatic = automatic;
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
	 * @param integer $flag
	 * @return bool
	 */
	public function set_flag($flag)
	{
		global $db;

		if ($this->file_id and is_numeric($flag))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET flag = ".$flag." WHERE id = ".$this->file_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->flag = $flag;
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
	 * @param integer $file_id
	 * @return bool
	 */
	public static function exist_file_by_file_id($file_id)
	{
		global $db;
			
		if (is_numeric($file_id))
		{
			$sql = "SELECT id FROM ".self::FILE_TABLE." WHERE id = ".$file_id."";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
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
	 * @param integer $owner_id
	 * @return bool
	 */
	public static function set_owner_id_on_null($owner_id)
	{
		global $db;
			
		if (is_numeric($owner_id))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET owner_id = NULL WHERE owner_id = ".$owner_id."";
			$res = $db->db_query($sql);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param integer $owner_group_id
	 * @return bool
	 */
	public static function set_owner_group_id_on_null($owner_group_id)
	{
		global $db;
			
		if (is_numeric($owner_group_id))
		{
			$sql = "UPDATE ".self::FILE_TABLE." SET owner_group_id = NULL WHERE owner_group_id = ".$owner_group_id."";
			$res = $db->db_query($sql);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
}

?>