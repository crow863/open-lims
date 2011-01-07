<?php
/**
 * @package base
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
	 * @ignore
	 */
	define("UNIT_TEST", false);

	require_once("config/main.php");
	require_once("core/db/db.php");
	
	require_once("core/include/base/session.class.php");

	require_once("core/include/base/autoload.function.php");	
		
	if ($_GET[username] and $_GET[session_id] and $_GET[file_id])
	{
		$db = new Database("postgresql");
		$db->db_connect($GLOBALS[server],$GLOBALS[port],$GLOBALS[dbuser],$GLOBALS[password],$GLOBALS[database]);
		
		$session = new Session($_GET[session_id]);
		$user = new User($session->get_user_id());
		
		if ($session->is_valid() == true)
		{
			$file = new File($_GET[file_id]);
			
			if ($_GET[version])
			{
				$file->open_internal_revision($_GET[version]);
			}
			
			if ($file->is_read_access() == true)
			{
				$folder = new Folder($file->get_toid());
				$folder_path = $folder->get_path();
				
				$extension_array = explode(".",$file->get_name());
				$extension_array_length = substr_count($file->get_name(),".");
				
				if (!$_GET[version])
				{
					$file_path = $GLOBALS[base_dir]."/".$folder_path."/".$file->get_object_id()."-".$file->get_internal_revision().".".$extension_array[$extension_array_length];
				}
				else
				{
					$file_path = $GLOBALS[base_dir]."/".$folder_path."/".$file->get_object_id()."-".$_GET[version].".".$extension_array[$extension_array_length];
				}
				
				header("Content-Type: application/octet-stream");

				header("Content-Disposition: attachment; filename=\"".$file->get_name()."\"");
				
				readfile($file_path);
			}
			else
			{
				echo "Access Denied";
			}
		}
		else
		{
			echo "Access Denied";
		}
	}
	else
	{
		echo "Access Denied";
	}

?>
