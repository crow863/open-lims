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
 * 
 */
require_once("interfaces/object_permission.interface.php");

/**
 * Object Permission Management Class
 * @package data
 */
class ObjectPermission implements ObjectPermissionInterface
{
	private $permission;
	private $automatic;
	private $owner_id;
	private $owner_group_id;
	
	private $project_id;
	private $sample_id;
	private $organisation_unit_id;
	
	private $folder_flag;
	
	/**
	 * @param integer $permission
	 * @param bool $automatic
	 * @param integer $owner_id
	 * @param integer $owner_group_id
	 */
	function __construct($permission, $automatic, $owner_id, $owner_group_id)
	{
		if (isset($automatic) and is_numeric($owner_id))
		{
			$this->permission = $permission;
			$this->automatic = $automatic;
			$this->owner_id = $owner_id;
			$this->owner_group_id = $owner_group_id;
		}
		else
		{
			$this->permission = null;
			$this->automatic = null;
			$this->owner_id = null;
			$this->owner_group_id = null;
		}
	}
	
	function __destruct()
	{
		unset($this->permission);
		unset($this->automatic);
		unset($this->owner_id);
		unset($this->owner_group_id);
		
		unset($this->project_id);
		unset($this->sample_id);
		unset($this->organisation_unit_id);
		
		unset($this->folder_flag);
	}
	
	/**
	 * @todo extrat method from class due to loose dependency
	 * @param integer $project_id
	 * @return bool
	 */
	public function set_project_id($project_id)
	{
		if ($project_id)
		{
			$this->project_id = $project_id;
			return true;
		}
		else
		{
			$this->project_id = null;
			return false;
		}
	}
	
	/**
	 * @todo extrat method from class due to loose dependency
	 * @param integer $sample_id
	 * @return bool
	 */
	public function set_sample_id($sample_id)
	{
		if ($sample_id)
		{
			$this->sample_id = $sample_id;
			return true;
		}
		else
		{
			$this->sample_id = null;
			return false;
		}
	}
	
	/**
	 * @param integer $organisation_unit_id
	 * @return bool
	 */
	public function set_organisation_unit_id($organisation_unit_id)
	{
		if ($organisation_unit_id)
		{
			$this->organisation_unit_id = $organisation_unit_id;
			return true;
		}
		else
		{
			$this->organisation_unit_id = null;
			return false;
		}
	}
	
	/**
	 * @param integer $folder_flag
	 * @return bool
	 */
	public function set_folder_flag($folder_flag)
	{
		if ($folder_flag)
		{
			$this->folder_flag = $folder_flag;
			return true;
		}
		else
		{
			$this->folder_flag = null;
			return false;
		}
	}
	
	/**
	 * Checks the permission
	 * @param integer $intention
	 *	1 = read
	 * 	2 = write
	 * 	3 = delete
	 * 	4 = control
	 * @return bool
	 */	
	public function is_access($intention)
	{
		global $user;

		if ($user->is_admin())
		{	
			return true;	
		}
		else
		{
			if ($this->automatic == true)
			{
				if ($this->project_id != null)
				{
					// problematic dependency
					$project_security = new ProjectSecurity($this->project_id);
					
					switch($intention):
						case 1:
							if ($project_security->is_access(1, false) or $project_security->is_access(2, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						case 2:
							if ($project_security->is_access(3, false) or $project_security->is_access(4, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						case 3:
							if ($project_security->is_access(5, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						default:
							if ($project_security->is_access(7, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;							
					endswitch;			
				}
				elseif ($this->sample_id != null)
				{
					// problematic dependency
					$sample_security = new SampleSecurity($this->sample_id);
					
					switch($intention):
						case 1:
							if ($sample_security->is_access(1, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						case 2:
							if ($sample_security->is_access(2, false))
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						case 3:
							if ($user->is_admin() == true)
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
						default:
							if ($user->is_admin() == true)
							{
								return true;
							}
							else
							{
								return false;
							}
						break;
						
					endswitch;	
				}
				else
				{						
					if ($this->folder_flag == 1 and $intention == 1)
					{
						return true;
					}
					else
					{
						if ($this->owner_id == $user->get_user_id())
						{
							return true;
						}
						else
						{
							return false;
						}
					}	
				}
			}
			else
			{	
				$permission_bin = decbin($this->permission);
				
				$permission_bin = str_pad($permission_bin, 16, "0", STR_PAD_LEFT);
				$permission_bin = strrev($permission_bin);	

				// Owner		
				if ($this->owner_id == $user->get_user_id())
				{
					switch($intention):
						case 1:
							return true;
						break;
						
						case 2:
							if ($permission_bin{1} == "1")
							{
								return true;
							}
						break;
						
						case 3:
							if ($permission_bin{2} == "1")
							{
								return true;
							}
						break;
						
						default:
							if ($permission_bin{3} == "1")
							{
								return true;
							}
						break;							
					endswitch;	
				}
				
				// Group
				if ($this->owner_group_id != 0)
				{
					$group = new Group($this->owner_group_id);
					
					if ($group->is_user_in_group($user->get_user_id()))
					{
						switch($intention):
							case 1:
								if ($permission_bin{4} == "1")
								{
									return true;
								}
							break;
							
							case 2:
								if ($permission_bin{5} == "1")
								{
									return true;
								}
							break;
							
							case 3:
								if ($permission_bin{6} == "1")
								{
									return true;
								}
							break;
							
							default:
								if ($permission_bin{7} == "1")
								{
									return true;
								}
							break;							
						endswitch;	
					}
				}

				if ($this->project_id)
				{
					// problematic dependency
					$project_security = new ProjectSecurity($this->project_id);
					
					switch($intention):
						case 1:
							if ($permission_bin{8} == "1" and ($project_security->is_access(1, false) or $project_security->is_access(2, false)))
							{
								return true;
							}
						break;
						
						case 2:
							if ($permission_bin{9} == "1" and ($project_security->is_access(3, false) or $project_security->is_access(4, false)))
							{
								return true;
							}
						break;
						
						case 3:
							if ($permission_bin{10} == "1" and $project_security->is_access(5, false))
							{
								return true;
							}
						break;
						
						default:
							if ($permission_bin{11} == "1" and $project_security->is_access(7, false))
							{
								return true;
							}
						break;							
					endswitch;	
				}
				
				// Public				
				switch($intention):
					case 1:
						if ($permission_bin{12} == "1")
						{
							return true;
						}
					break;
					
					case 2:
						if ($permission_bin{13} == "1")
						{
							return true;
						}
					break;
					
					case 3:
						if ($permission_bin{14} == "1")
						{
							return true;
						}
					break;
					default:
						if ($permission_bin{15} == "1")
						{
							return true;
						}
					break;							
				endswitch;	
				
				return false;	
			}	
		}
	}
	
	/**
	 * Returns the permission string; like: rwdc----r---r---
	 * @return string
	 */	
	public function get_permission_string()
	{
		global $db;
				
		if ($this->automatic == true)
		{	
			return "automatic";	
		}
		else
		{
			$dec_string = decbin($this->permission);
			$dec_string = str_pad($dec_string, 16 ,'0', STR_PAD_LEFT);	
					
			$counter = 1;
						
			for ($i=0;$i<=15;$i++)
			{			
				if ($dec_string{$i} == '1')
				{
					switch ($counter):
						case 1:
						$returnstring = "c".$returnstring;
						break;
						
						case 2:
						$returnstring = "d".$returnstring;
						break;
						
						case 3:
						$returnstring = "w".$returnstring;
						break;
						
						default:
						$returnstring = "r".$returnstring;
						break;
					endswitch;
				}
				else
				{
					$returnstring = "-".$returnstring;
				}

				if ($counter >= 4)
				{
					$counter = 1;
				}
				else
				{
					$counter++;
				}
			}
			return $returnstring;	
		}	
	}	
	
}

?>
