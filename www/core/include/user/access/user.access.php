<?php
/**
 * @package user
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
 * User Access Class
 * @package user
 */
class User_Access {
	
	const USER_TABLE = 'core_users';
	const USER_PK_SEQUENCE = 'core_users_id_seq';
	
	private $user_id;
	
	private $username;
	private $password;

	private $user_filesize;

	/**
	 * @param integer $user_id
	 */
	function __construct($user_id)
	{
		global $db;
		
		if ($user_id == null)
		{
			$this->user_id = null;
		}
		else
		{
			$sql = "SELECT * FROM ".self::USER_TABLE." WHERE id='".$user_id."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				$this->user_id 				= $user_id;
				
				$this->username				= $data[username];
				$this->password				= $data[password];

				$this->user_filesize		= $data[user_filesize];
			}
			else
			{
				$this->user_id			= null;
			}
		}
	}
	
	function __destruct()
	{
		if ($this->user_id)
		{
			unset($this->user_id);
	
			unset($this->username);
			unset($this->password);

			unset($this->user_filesize);
		}
	}
	
	/**
	 * @param integer $username
	 * @param integer $password
	 * @return integer
	 */
	public function create($username, $password)
	{
		global $db;
		
		$datetime = date("Y-m-d H:i:s");
		
		if ($username and strlen($password) == 32)
		{	
			$sql_write = "INSERT INTO ".self::USER_TABLE." (id," .
															"username," .
															"password," .
															"user_filesize) " .
						"VALUES (nextval('".self::USER_PK_SEQUENCE."'::regclass)," .
															"'".$username."'," .
															"'".$password."'," .
															"0)";
																	
			$res_write = $db->db_query($sql_write);
			
			if ($db->db_affected_rows($res_write) != 1)
			{
				return null;
			}
			else
			{
				$sql_read = "SELECT id FROM ".self::USER_TABLE." WHERE id = currval('".self::USER_PK_SEQUENCE."'::regclass)";
				$res_read = $db->db_query($sql_read);
				$data_read = $db->db_fetch_assoc($res_read);
				
				$this->__construct($data_read[id]);
				
				return $data_read[id];
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

		if ($this->user_id)
		{	
			$user_id_tmp = $this->user_id;
			
			$this->__destruct();

			$sql = "DELETE FROM ".self::USER_TABLE." WHERE id = ".$user_id_tmp."";
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
	public function get_username()
	{
		if ($this->username)
		{
			return $this->username;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return string
	 */
	public function get_password()
	{
		if ($this->password)
		{
			return $this->password;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */	
	public function get_user_filesize()
	{
		if ($this->user_filesize)
		{
			return $this->user_filesize;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param string $username
	 * @return bool
	 */	
	public function set_username($username)
	{
		global $db;

		if ($this->user_id and $username)
		{
			$sql = "UPDATE ".self::USER_TABLE." SET username = '".$username."' WHERE id = ".$this->user_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->username = $username;
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
	 * @param string $password
	 * @return bool
	 */
	public function set_password($password)
	{
		global $db;
			
		if ($this->user_id and strlen($password) == 32)
		{
			$sql = "UPDATE ".self::USER_TABLE." SET password = '".$password."' WHERE id = ".$this->user_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->password = $password;
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
	 * @param integer $user_filesize
	 * @return bool
	 */
	public function set_user_filesize($user_filesize)
	{
		global $db;
			
		if ($this->user_id and is_numeric($user_filesize))
		{
			$sql = "UPDATE ".self::USER_TABLE." SET user_filesize = ".$user_filesize." WHERE id = ".$this->user_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->user_filesize = $user_filesize;
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
	 * @param string $username
	 * @return integer
	 */	
	public static function get_user_id_by_username($username)
	{
		global $db;
		
		if ($username)
		{						
			$sql = "SELECT id FROM ".self::USER_TABLE." WHERE LOWER(username) = '".$username."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				return $data[id];
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
	 * @return integer
	 */
	public static function get_number_of_users()
	{
		global $db;
									
		$sql = "SELECT COUNT(id) AS result FROM ".self::USER_TABLE."";
		$res = $db->db_query($sql);
		$data = $db->db_fetch_assoc($res);
		
		if ($data[result])
		{
			return $data[result];
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return integer
	 */
	public static function get_used_user_space()
   	{
   		global $db;
		
		$sql = "SELECT SUM(user_filesize) AS size FROM ".self::USER_TABLE."";
		$res = $db->db_query($sql);
		$data = $db->db_fetch_assoc($res);
		
		if ($data[size])
		{
			return $data[size];
		}
		else
		{
			return null;
		}
   	}
	
	/**
	 * @return array
	 */
	public static function list_entries()
	{
		global $db;
		
		$return_array = array();	
											
		$sql = "SELECT id FROM ".self::USER_TABLE." ORDER BY id";
		$res = $db->db_query($sql);
		while ($data = $db->db_fetch_assoc($res))
		{
			array_push($return_array, $data[id]);
		}
		
		if (is_array($return_array))
		{
			return $return_array;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public static function exist_user($user_id)
	{
		global $db;
		
		if (is_numeric($user_id))
		{
			$return_array = array();	
												
			$sql = "SELECT id FROM ".self::USER_TABLE." WHERE id = ".$user_id."";
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
	 * @return integer
	 */
	public static function count_users()
	{
		global $db;
											
		$sql = "SELECT COUNT(id) AS result FROM ".self::USER_TABLE."";
		$res = $db->db_query($sql);
		$data = $db->db_fetch_assoc($res);
		
		if ($data[result])
		{
			return $data[result];
		}
		else
		{
			return null;
		}
	}
	
}

?>
