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
					'display'   => array(
						'id'     => 'display',
						'title'  => 'Display Settings',
						'desc'   => 'Display settings for slider',
						'help'   => '<iframe width="480" height="270" src="https://www.youtube.com/embed/B-tvZAC-eik?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>',
						'fields' => array(
							array(
								'id'      => 'items',
								'type'    => 'number',
								'title'   => 'Show logo at a time',
								'desc'    => 'Show logo at a time on slider',
								'default' => 5,
								'min'     => 5,
								'max'     => 100,
								'suffix'  => 'items',
							),
							array(
								'id'      => 'navigation',
								'type'    => 'checkbox',
								'title'   => 'Show Navigation',
								'desc'    => 'Show "Next" and "Previous" navigation.',
								'value'   => 1,
								'default' => FALSE
							),
							array(
								'id'      => 'pagination',
								'type'    => 'checkbox',
								'title'   => 'Show Pagination',
								'desc'    => 'Show Slider Pagination at bottom of slider.',
								'value'   => 1,
								'default' => FALSE
							),
						)
					),
					'slide'     => array(
						'id'     => 'slide',
						'title'  => 'Slider Settings',
						'desc'   => 'Setting sliders',
						'help'   => '<iframe width="480" height="270" src="https://www.youtube.com/embed/VTE5U_QhyNU?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>',
						'fields' => array(
							array(
								'id'      => 'autoplay',
								'type'    => 'checkbox',
								'title'   => 'Auto Slider',
								'desc'    => 'Automatically play slider',
								'value'   => 1,
								'default' => 1
							),
							array(
								'id'      => 'autoplay_speed',
								'type'    => 'number',
								'title'   => 'Autoplay speed',
								'desc'    => 'Slider Autoplay speed in seconds',
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
					'animation' => array(
						'id'     => 'animation',
						'title'  => 'Animation Settings',
						'desc'   => 'Slider Animation Settings',
						'help'   => '<iframe width="480" height="270" src="https://www.youtube.com/embed/VTE5U_QhyNU?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>',
						'fields' => array(
							array(
								'id'      => 'enable_animation',
								'type'    => 'checkbox',
								'title'   => 'Slider Animation',
								'desc'    => 'Enable Slider Animation',
								'value'   => 1,
								'default' => 1
							),
							array(
								'id'      => 'animation_style',
								'type'    => 'select',
								'title'   => 'Animation Style',
								'desc'    => 'Slider Animation Style',
								'options' => array(
									'fade'      => 'Fade',
									'backSlide' => 'Back Slide',
									'goDown'    => 'Go Down',
									'fadeUp'    => 'Fade Up',
								),
								'require' => array(
									'enable_animation' => array(
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
						'title'  => 'Link Settings',
						'desc'   => 'Slider Item Link Settings',
						'fields' => array(
							array(
								'id'      => 'enable_link',
								'type'    => 'checkbox',
								'title'   => 'Enable Link',
								'desc'    => 'Enable or disable slider link',
								'value'   => 1,
								'default' => 1
							),
							array(
								'id'      => 'link_target',
								'type'    => 'select',
								'title'   => 'Slider link target',
								'desc'    => 'Slider link open target',
								'options' => array(
									'_self'   => 'Load in the same window / tab',
									'_blank'  => 'Load in the new window / tab',
									'_parent' => 'Load in the parent window / tab',
								),
								'require' => array(
									'enable_link' => array(
										'type'  => 'equal',
										'value' => 1
									)
								)
							),
							array(
								'id'      => 'rel_no_follow',
								'type'    => 'checkbox',
								'title'   => 'SEO No Follow',
								'desc'    => 'Enable no follow link',
								'value'   => 1,
								'default' => 1,
								'require' => array(
									'enable_link' => array(
										'type'  => 'equal',
										'value' => 1
									)
								)
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

