<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Body_Colors_Configs' ) ) {

	/**
	 * Register Body Color Customizer Configurations.
	 */
	class Astra_Body_Colors_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Body Color Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'colors-and-background' ) ) ? 'section-colors-body' : 'section-colors-background';

			$_configs = array(
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[global-color-palette]',
					'type'      => 'control',
					'control'   => 'ast-hidden',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'astra' ),
					'default'   => astra_get_option( 'global-color-palette' ),
					'transport' => 'postMessage',
				),

				array(
					'name'      => 'astra-color-palettes',
					'type'      => 'control',
					'control'   => 'ast-color-palette',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'astra' ),
					'default'   => get_option(
						'astra-color-palettes',
						array(
							'currentPalette' => 'palette_1',
							'palettes'       => array(
								'palette_1' => array(
									'#3a3a3a',
									'#0274be',
									'#0274b2',
									'#3a3a31',
									'#3a3a3b',
									'#7B8794',
									'#52606D',
									'#3E4C59',
									'#F3F4F7',
								),
								'palette_2' => array(
									'#26bcdb',
									'#1f90a6',
									'#121212',
									'#1a1a1a',
									'#1a1a1a',
									'#7B8794',
									'#52606D',
									'#3E4C59',
									'#F3F4F7',
								),
								'palette_3' => array(
									'#77b978',
									'#f37262',
									'#0e509a',
									'#393939',
									'#3a3a3b',
									'#7B8794',
									'#52606D',
									'#3E4C59',
									'#F3F4F7',
								),
							),
						)
					),
					'transport' => 'postMessage',
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[text-color]',
					'default'  => astra_get_option( 'text-color', '#3a3a3a' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'priority' => 5,
					'title'    => __( 'Text Color', 'astra' ),
				),

				/**
				 * Option: Theme Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[theme-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'default'  => astra_get_option( 'theme-color', '#0274be' ),
					'priority' => 5,
					'title'    => __( 'Theme Color', 'astra' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[link-color]',
					'section'  => $_section,
					'type'     => 'control',
					'control'  => 'ast-color',
					'default'  => astra_get_option( 'link-color', '#0274be' ),
					'priority' => 5,
					'title'    => __( 'Link Color', 'astra' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[link-h-color]',
					'section'  => $_section,
					'default'  => astra_get_option( 'link-h-color', '#3a3a3a' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'priority' => 15,
					'title'    => __( 'Link Hover Color', 'astra' ),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Body_Colors_Configs();
