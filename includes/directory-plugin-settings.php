<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Directory_Plugin_Template_Settings {
    private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;
	private $settings_base;
	private $settings;

	public function __construct( $file ) {
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->settings_base = 'ncstate_directory_';

		// Initialise settings
		add_action( 'admin_init', array( $this, 'init' ) );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item() {
		$page = add_options_page( __( 'NC State Directory Settings', 'ncstate-directory' ) , __( 'NC State Directory Settings', 'ncstate-directory' ) , 'manage_options' , 'plugin_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets() {

    // We're including the WP media scripts here because they're needed for the image upload field
    // If you're not including an image upload then you can leave this function call out
    wp_enqueue_media();

    wp_register_script( 'wpt-admin-js', $this->assets_url . 'js/settings.js', array( 'jquery' ), '1.0.0' );
    wp_enqueue_script( 'wpt-admin-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=plugin_settings">' . __( 'Settings', 'ncstate-directory' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {

		$settings['standard'] = array(
			'title'					=> __( 'Standard', 'ncstate-directory' ),
			'description'			=> __( 'These are fairly standard form input fields.', 'ncstate-directory' ),
			'fields'				=> array(
				array(
					'id' 			=> 'url',
					'label'			=> __( 'Directory URL Structure' , 'ncstate-directory' ),
					'description'	=> __( 'This is the URL subdirectory where the directory will be available. https://your-site.ncsu.edu/subdirectory', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> 'people',
					'placeholder'	=> __( 'people', 'ncstate-directory' )
				),
				array(
					'id' 			=> 'bulk_import_ids',
					'label'			=> __( 'Unity ID Bulk Import' , 'ncstate-directory' ),
					'description'	=> __( 'Multiple Unity IDs can be provided in a comma separated list for a one-time import.', 'ncstate-directory' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( 'Comma separated list of Unity IDs.', 'ncstate-directory' )
				),
				array(
					'id' 			=> 'index_view_type',
					'label'			=> __( 'Directory Index View', 'ncstate-directory' ),
					'description'	=> __( 'Controls how a listing of individuals will appear on page.', 'ncstate-directory' ),
					'type'			=> 'radio',
					'options'		=> array( 'grid' => 'Grid', 'row' => 'Row' ),
					'default'		=> 'row'
				),
				array(
					'id' 			=> 'display_images',
					'label'			=> __( 'Display Headshot Images', 'ncstate-directory' ),
					'description'	=> __( 'Toggles the display of headshot images on index views.', 'ncstate-directory' ),
					'type'			=> 'radio',
					'options'		=> array( 'true' => 'Yes', 'false' => 'No' ),
					'default'		=> 'row'
				),
			)
		);
		
		$settings['listing_view'] = array(
			'title'					=> __( 'Listing View', 'ncstate-directory' ),
			'description'			=> __( 'Controls information that is displayed on directory index pages.', 'ncstate-directory' ),
			'fields'				=> array(
				array(
					'id' 			=> 'main_intro_text',
					'label'			=> __( 'Main Intro Text' , 'ncstate-directory' ),
					'description'	=> __( 'Will display on main directory index. Intros for subgroups are managed within the \'description\' field of a subgroup.', 'ncstate-directory' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( '', 'ncstate-directory' )
				),
				array(
					'id' 			=> 'filter_subgroups',
					'label'			=> __( 'Filter Options' , 'ncstate-directory' ),
					'description'	=> __( 'Provide a comma separated list of slugs as filter options.', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'Comma separated list of subgroup slugs.', 'ncstate-directory' )
				),
				array(
					'id' 			=> 'displayed_subgroups_in_index',
					'label'			=> __( 'Displayed Subgroups', 'ncstate-directory' ),
					'description'	=> __( 'Listing of subgroups to display within a person\'s index view listing.', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'leaders',
					'label'			=> __( 'Leaders', 'ncstate-directory' ),
					'description'	=> __( 'Provide comma separated and ordered list of individuals who should be included in leadership section of directory.', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
			)
		);
		
		$settings['multisite'] = array(
			'title'					=> __( 'Multisite Settings', 'ncstate-directory' ),
			'description'			=> __( 'Allows a basic index page to be rendered with individuals from another site within the multisite install.', 'ncstate-directory' ),
			'fields'				=> array(
				array(
					'id' 			=> 'repo_site_id',
					'label'			=> __( 'Repository Site ID', 'ncstate-directory' ),
					'description'	=> __( 'If using WordPress\'s multisite functionality, you may want to have all individuals within single site. Providing the ID of that site here will pull all data from that site.', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'repo_site_subgroups',
					'label'			=> __( 'Repository Site Subgroups', 'ncstate-directory' ),
					'description'	=> __( 'Specify subgroups to filter by when displaying information from other site. Subgroups must be present in site being used as source.', 'ncstate-directory' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				),
			)
		);

		$settings = apply_filters( 'plugin_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings() {
		if( is_array( $this->settings ) ) {
			foreach( $this->settings as $section => $data ) {

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), 'plugin_settings' );

				foreach( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->settings_base . $field['id'];
					register_setting( 'plugin_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), 'plugin_settings', $section, array( 'field' => $field ) );
				}
			}
		}
	}

	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Generate HTML for displaying fields
	 * @param  array $args Field data
	 * @return void
	 */
	public function display_field( $args ) {

		$field = $args['field'];

		$html = '';

		$option_name = $this->settings_base . $field['id'];
		$option = get_option( $option_name );

		$data = '';
		if( isset( $field['default'] ) ) {
			$data = $field['default'];
			if( $option ) {
				$data = $option;
			}
		}

		switch( $field['type'] ) {

			case 'text':
			case 'password':
			case 'number':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . $data . '"/>' . "\n";
			break;

			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value=""/>' . "\n";
			break;

			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
			break;

			case 'checkbox':
				$checked = '';
				if( $option && 'on' == $option ){
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;

			case 'checkbox_multi':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( in_array( $k, $data ) ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'radio':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( $k == $data ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;

			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( in_array( $k, $data ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '" />' . $v . '</label> ';
				}
				$html .= '</select> ';
			break;

			case 'image':
				$image_thumb = '';
				if( $data ) {
					$image_thumb = wp_get_attachment_thumb_url( $data );
				}
				$html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
				$html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __( 'Upload an image' , 'ncstate-directory' ) . '" data-uploader_button_text="' . __( 'Use image' , 'ncstate-directory' ) . '" class="image_upload_button button" value="'. __( 'Upload new image' , 'ncstate-directory' ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. __( 'Remove image' , 'ncstate-directory' ) . '" />' . "\n";
				$html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
			break;

			case 'color':
				?><div class="color-picker" style="position:relative;">
			        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
			        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
			    </div>
			    <?php
			break;

		}

		switch( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
			break;

			default:
				$html .= '<label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";
			break;
		}

		echo $html;
	}

	/**
	 * Validate individual settings field
	 * @param  string $data Inputted value
	 * @return string       Validated value
	 */
	public function validate_field( $data ) {
		if( $data && strlen( $data ) > 0 && $data != '' ) {
			$data = urlencode( strtolower( str_replace( ' ' , '-' , $data ) ) );
		}
		return $data;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page() {

		// Build page HTML
		$html = '<div class="wrap" id="plugin_settings">' . "\n";
			$html .= '<h2>' . __( 'NC State Directory Settings' , 'ncstate-directory' ) . '</h2>' . "\n";
			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Setup navigation
				$html .= '<ul id="settings-sections" class="subsubsub hide-if-no-js">' . "\n";
					$html .= '<li><a class="tab all current" href="#all">' . __( 'All' , 'ncstate-directory' ) . '</a></li>' . "\n";

					foreach( $this->settings as $section => $data ) {
						$html .= '<li>| <a class="tab" href="#' . $section . '">' . $data['title'] . '</a></li>' . "\n";
					}

				$html .= '</ul>' . "\n";

				$html .= '<div class="clear"></div>' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( 'plugin_settings' );
				do_settings_sections( 'plugin_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'ncstate-directory' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

}