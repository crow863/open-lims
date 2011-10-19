<?php
/**
 * @package base
 * @version 0.4.0.0
 * @author Roman Konertz <konertz@open-lims.org>
 * @copyright (c) 2008-2011 by Roman Konertz
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
require_once("..//base/ajax.php");

/**
 * List AJAX IO Class
 * @package base
 */
class ListAjax extends Ajax
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_page_bar($page, $number_of_pages, $css_page_id)
	{
		$pagebar .= "<table style='display: inline;'><tr><td><span class='smallTextBlack'>Page ".$page." of ".$number_of_pages."</span></td>";
	
		// Previous
		if ($page == 1)
		{
			$pagebar .= "<td><img src='images/icons/previous_d.png' alt='Previous' border='0' /></td>";		
		}
		else
		{
			
			$previous_page = $page - 1;
			

			$pagebar .= "<td><a href='#' class='".$css_page_id."' id='".$css_page_id."".$previous_page."'><img src='images/icons/previous.png' alt='Previous' border='0' /></a></td>";
		}	
		
		$displayed = false;
					
		for ($i=1;$i<=$number_of_pages;$i++)
		{
			$display = false;
			
			if ($max_page < 5)
			{
				$display = true;
			}
			else
			{

				if ($i <= 2) {
					$display = true;
				}
				
				if ($i > $max_page-2) {
					$display = true;
				}
				
				if ($display == false and $page+1 == $i) {
					$display = true;
				}
				
				if ($display == false and $page-1 == $i) {
					$display = true;
				}
				
				if ($display == false and $page == $i) {
					$display = true;
				}
				if ($i == $page+10 and $display == false) {
					$display = true;
				}
				
				if ($i == $page-10 and $display == false) {
					$display = true;
				}

			}
			
			if ($display == true)
			{
				if ($page == $i)
				{
					$pagebar .= "<td><span class='bold'><a href='#' class='".$css_page_id."' id='".$css_page_id."".$i."'>".$i."</a></span></td>";
				}
				else
				{
					$pagebar .= "<td><a href='#' class='".$css_page_id."' id='".$css_page_id."".$i."'>".$i."</a></td>";
				}						
				$displayed = true;
			}
			elseif ($displayed == true)
			{
				$pagebar .= "<td>..</td>";
			}
			
			if ($display == false)
			{
				$displayed = false;
			}
		}

		// Next
		if($page == $number_of_pages)
		{
			$pagebar .= "<td><img src='images/icons/next_d.png' alt='Next' border='0' /></td>";		
		}
		else
		{
			$next_page = $page + 1;
			$pagebar .= "<td><a href='#' class='".$css_page_id."' id='".$css_page_id."".$next_page."'><img src='images/icons/next.png' alt='Previous' border='0' /></a></td>";
		}
		
		$pagebar .= "</tr></table>";
		
		$pagebar .= "</div>";
		
		return $pagebar;
	}
	
	public function get_page_information($results, $pages)
	{
		return Common_IO::results_on_page($results, $pages);
	}
	
	public function handler()
	{
		global $session;
		
		if ($session->is_valid())
		{
			switch($_GET['run']):
			
				case "get_page_bar":
					echo $this->get_page_bar($_GET['page'], $_GET['number_of_pages'], $_GET['css_page_id']);
				break;
				
				case "get_page_information":
					echo $this->get_page_information($_GET['number_of_entries'], $_GET['number_of_pages']);
				break;
				
			endswitch;
		}
	}
}

$list_ajax = new ListAjax();
$list_ajax->handler();

?>