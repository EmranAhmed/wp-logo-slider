<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );


	// Add settings -> Add Section -> Add fields

	function wp_logo_slider_basic_settings( $fields ) {
		return array_merge( $fields, array(
			'basic-settings' => array(
				'id'       => 'basic-settings',
				'title'    => 'Basic Settings',
				'active'   => TRUE,
				'sections' => array(
					'display' => array(
						'id'     => 'display',
						'title'  => 'Display Settings',
						'desc'   => 'Display settings for slider',
						'fields' => array(
							array(
								'id'      => 'display_item',
								'type'    => 'number',
								'title'   => 'Display logo at a time',
								'desc'    => 'Display logo at a time on slider',
								'default' => 5,
								'min'     => 5,
								'max'     => 100,
								'suffix'  => 'items',
							),
							array(
								'id'      => 'navigation',
								'type'    => 'checkbox',
								'title'   => 'Display navigation',
								'desc'    => 'Display "next" and "prev" buttons.',
								'value'   => 1,
								'default' => FALSE
							),
							array(
								'id'      => 'navigation-group',
								'type'    => 'checkbox-group',
								'title'   => 'Display Group',
								'desc'    => 'Group....',
								'options' => array(
									array( 'id' => 'a', 'title' => 'A', 'value' => 1, 'default' => 1 ),
									array( 'id' => 'b', 'title' => 'B', 'value' => 1 )
								)
							),
							array(
								'id'      => 'navigation-radio-group',
								'type'    => 'radio',
								'title'   => 'Display Radio',
								'desc'    => 'Group....',
								'default' => 'aa',
								'options' => array(
									array( 'id' => 'aa', 'title' => 'Aaaa', 'value' => 'a' ),
									array( 'id' => 'bb', 'title' => 'Bbbb', 'value' => 'b' )
								)
							),
						)
					),
					'slide'   => array(
						'id'     => 'slide',
						'title'  => 'Slider Settings',
						'desc'   => 'Setting sliders',
						'fields' => array(
							array(
								'id'      => 'autoplay',
								'type'    => 'checkbox',
								'title'   => 'Slide autoplay',
								'desc'    => 'Automatically play slider',
								'value'   => 1,
								'default' => 1
							),
							array(
								'id'      => 'autoplay_speed',
								'type'    => 'number',
								'title'   => 'Autoplay speed',
								'desc'    => 'Slider autoplay speed',
								'default' => 5,
								'min'     => 5,
								'step'    => 5,
								'max'     => 20,
								'suffix'  => 'seconds',
								'require' => array(
									'autoplay' => array(
										'type'  => 'equal',
										'value' => 1
									)
								)
							),
						)
					),
				)
			)
		) );
	}

	add_filter( 'wp_logo_slider_settings', 'wp_logo_slider_basic_settings' );

	function wp_logo_slider_advanced_settings( $fields ) {
		return array_merge( $fields, array(
			'advanced-settings' => array(
				'id'       => 'advanced-settings',
				'title'    => 'Advanced Settings',
				'sections' => array(
					'display' => array(
						'id'     => 'display',
						'title'  => 'Display Settings',
						'desc'   => 'Display Settings',
						'fields' => array(
							array(
								'id'      => 'display_settings',
								'type'    => 'post_select',
								'title'   => 'New ticket Page',
								'desc'    => 'Show If New ticket with registration enabled and user loggedin, or redirect to login / my account page',
								'options' => get_pages(),
							),
						)
					)
				)
			),
		) );
	}

	add_filter( 'wp_logo_slider_settings', 'wp_logo_slider_advanced_settings' );

	function wp_logo_slider_custom_css_settings( $fields ) {
		return array_merge( $fields, array(
			'css-settings' => array(
				'id'       => 'css-settings',
				'title'    => 'Custom CSS',
				'sections' => array(
					'display' => array(
						'id'     => 'css-section',
						'title'  => 'Custom CSS Section',
						'desc'   => 'Put Custom CSS',
						'fields' => array(
							array(
								'id'      => 'custom_css',
								'type'    => 'textarea',
								'title'   => 'Custom CSS',
								'desc'    => '',
								'default' => '',
							),
						)
					)
				)
			),
		) );
	}

	add_filter( 'wp_logo_slider_settings', 'wp_logo_slider_custom_css_settings' );


