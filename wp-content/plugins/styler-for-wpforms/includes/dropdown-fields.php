<?php
/**
 * Dropdown controls in Customizer.
 */

$wp_customize->add_section(
	'sfwf_form_id_dropdown_fields',
	array(
		'title' => 'Dropdown Fields',
		'panel' => 'sfwf_panel',
	)
);

// //Label
$wp_customize->add_control(
	new Sfwf_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Width' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);

$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Desktop_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);

// Tablet.
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width-tab]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Tab_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width-tab]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);

// Mobile.
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width-phone]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Mobile_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][max-width-phone]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);


// font style buttons.
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-style]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Font_Style_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-style]',
		array(
			'label'   => 'Font Style',
			'section' => 'sfwf_form_id_dropdown_fields',
			'type'    => 'font_style',
			'choices' => $font_style_choices,
		)
	)
);

	// Font size label.
$wp_customize->add_control(
	new Sfwf_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Font Size' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);
/* for pc*/
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Desktop_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);
/* for_tablet*/
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size-tab]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Tab_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size-tab]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);


/* for mobile*/
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size-phone]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Mobile_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-size-phone]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);

// Label height.
$wp_customize->add_control(
	new Sfwf_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Height' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);

$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Desktop_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);

// Tablet.
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height-tab]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Tab_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height-tab]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);

// Mobile.
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height-phone]',
	array(
		'default'   => '',
		'transport' => 'refresh',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new Sfwf_Mobile_Text_Input_Option(
		$wp_customize,
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][height-phone]',
		array(
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
			'label'       => '',
			'input_attrs' => array(
				'placeholder' => 'Ex.40px',
			),
		)
	)
);


$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-color]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][font-color]', // Setting id.
		array( // Args, including any custom ones.
			'label'   => __( 'Font Color' ),
			'section' => 'sfwf_form_id_dropdown_fields',
		)
	)
);

// Border Label.
$wp_customize->add_control(
	new Sfwf_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Dropdown Border' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-size]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-size]',
	array(
		'type'        => 'text',
		'priority'    => 10, // Within the section.
		'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
		'label'       => __( 'Size' ),
		'input_attrs' => array(
			'placeholder' => 'Example: 4px or 10%',
		),
	)
);

$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-type]',
	array(
		'default'   => 'solid',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-type]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
		'label'    => __( 'Type' ),
		'choices'  => $border_types,
	)
);
$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-radius]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-radius]',
	array(
		'type'        => 'text',
		'priority'    => 10, // Within the section.
		'section'     => 'sfwf_form_id_dropdown_fields', // Required, core or custom.
		'label'       => __( 'Border Radius' ),
		'input_attrs' => array(
			'placeholder' => 'Ex.4px',
		),
	)
);

$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-color]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][border-color]', // Setting id.
		array( // Args, including any custom ones.
			'label'   => __( 'Border Color' ),
			'section' => 'sfwf_form_id_dropdown_fields',
		)
	)
);


$wp_customize->add_setting(
	'sfwf_form_id_' . $current_form_id . '[dropdown-fields][background-color]',
	array(
		'default'   => '',
		'transport' => 'postMessage',
		'type'      => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][background-color]', // Setting id.
		array( // Args, including any custom ones.
			'label'   => __( 'Background Color' ),
			'section' => 'sfwf_form_id_dropdown_fields',
		)
	)
);


$wp_customize->add_control(
	new WP_Customize_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][margin-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Margin' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);

sfwf_margin_padding_controls( $wp_customize, $current_form_id, 'sfwf_form_id_dropdown_fields', 'dropdown-fields', 'margin' );

$wp_customize->add_control(
	new WP_Customize_Label_Only(
		$wp_customize, // WP_Customize_Manager.
		'sfwf_form_id_' . $current_form_id . '[dropdown-fields][padding-label-only]', // Setting id.
		array( // Args, including any custom ones.
			'label'    => __( 'Padding' ),
			'section'  => 'sfwf_form_id_dropdown_fields',
			'settings' => array(),
		)
	)
);

sfwf_margin_padding_controls( $wp_customize, $current_form_id, 'sfwf_form_id_dropdown_fields', 'dropdown-fields', 'padding' );
