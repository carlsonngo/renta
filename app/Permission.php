<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'permissions';
	
	protected $fillable = array(
							'group_id',
							'module',
							'roles');
	
	// --------------------------------------------------------------------
	
	public static function roles($group_id) 
	{
		$roles = array(
			'users' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Trash / Restore',										
				'login_as'      => 'Login As',
			),
			'groups' => array(
				'view'   		=> 'View',
				'add_edit'      => 'Add / Edit',
				'trash_restore' => 'Trash / Restore',									
				'permission'    => 'Manage Permissions',
			),
			'media' => array(
				'view'   		=> 'View',								
				'add'           => 'Upload File',
				'trash'         => 'Delete File',		
			),
			'pages' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'duplicate'       => 'Duplicate',
				'trash_restore'   => 'Trash / Restore',	
			),
			'posts' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'duplicate'       => 'Duplicate',
				'trash_restore'   => 'Trash / Restore',	
				'manage_category' => 'Manage Categories',									
			),
			'events' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'duplicate'       => 'Duplicate',
				'trash_restore'   => 'Trash / Restore',	
				'manage_category' => 'Manage Categories',									
			),
			'bookings' => array(
				'view'   		  => 'View',
				'trash_restore'   => 'Trash / Restore',	
				'book_now'        => 'Book Now',									
				'calendar'        => 'Calendar',	
				'report'          => 'Report',	
			),
			'galleries' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'trash_restore'   => 'Trash / Restore',										
			),
			'memberships' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'trash_restore'   => 'Trash / Restore',										
			),
			'domains' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'trash_restore'   => 'Trash / Restore',										
			),
			'settings' => array(
				'general' 		 => 'General',
				'menu' 			 => 'Menu',
				'slider' 		 => 'Slider',
				'theme_style' 	 => 'Theme Style',
				'email_template' => 'Email Template',
				'localization'   => 'Localization',
			),
			'error-reports' => array(
				'view'   		  => 'View',
				'edit'            => 'Change Status',
				'trash_restore'   => 'Trash / Restore',										
			),
			'products' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'trash_restore'   => 'Trash / Restore',	
				'manage_category' => 'Manage Categories',									
				'manage_attribute' => 'Manage Attributes',	
			),
			'coupons' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Add / Edit',
				'trash_restore'   => 'Trash / Restore',	
			),
			'orders' => array(
				'view'   		  => 'View',
				'add_edit'        => 'Edit',
				'trash_restore'   => 'Trash / Restore',										
			),
			'shop' => array(
				'settings' 		 => 'settings',
				'email_template' => 'Email Template',
			),
		);		  

		return $roles;
	}
	
	// --------------------------------------------------------------------
	
	public static function has_access($module, $roleInarray = array()) 
	{
		$res = FALSE;

		$auth = \Auth::User();

		$info = Post::where('post_status', 'actived')
		            ->where('post_name', $auth->group)
		            ->first();

		$post = json_decode(@$info->post_content, true);

		$roles = @$post[$module];

		if( ! $roleInarray && $roles ) {
			$res = TRUE;
		}

		// if administrator
		if($auth->group == 'admin' || $auth->id == 1) return TRUE; 

	 	if( @$roles ) {
			foreach($roles as $role) {
				if(in_array($role, $roleInarray)) {
					$res = TRUE;
				}
			}
		}
						
		return $res;
	}
	
	// --------------------------------------------------------------------	
}
