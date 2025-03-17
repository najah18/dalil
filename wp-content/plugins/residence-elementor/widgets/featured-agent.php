<?php
namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wpresidence_Featured_Agent extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'WpResidence_Featured_Agent';
	}

        public function get_categories() {
		return [ 'wpresidence' ];
	}


	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Featured Agent', 'residence-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wpresidence-note eicon-lock-user';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
	return [ '' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

	public function elementor_transform($input){
		$output=array();
		if( is_array($input) ){
			foreach ($input as $key=>$tax){
				$output[$tax['value']]=$tax['label'];
			}
		}
		return $output;
	}

	protected function register_controls() {

	$agent_array    = wpestate_return_agent_array(true);

	$agent_array_elementor      =   $this->elementor_transform($agent_array);

	$this->start_controls_section(
		'section_content',
		[
			'label' => __( 'Content', 'residence-elementor' ),
		]
	);

		$this->add_control(
			'idul',
			[
				'label' => __( 'Select The Agent', 'residence-elementor' ),
				'label_block'=>true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => $agent_array_elementor,
			]
		);

		$this->add_control(
			'notes',
			[
				'label' => __( 'Agent Notes', 'residence-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 5, // Specify the number of rows for height
				'placeholder' => __( 'Enter notes here...', 'residence-elementor' ),
			]
		);

		$this->add_control(
			'container_height',
			[
				'label' => __( 'Agent Container Height', 'residence-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .agent_unit_featured' => 'height: {{SIZE}}px;',
				],
			]
		);

	$this->end_controls_section();
                
	/*
	* -------------------------------------------------------------------------------------------------
	* Start shadow section
	*/
        
	$this->start_controls_section(
			'section_grid_box_shadow', [
		'label' => esc_html__('Box Shadow', 'residence-elementor'),
		'tab' => Controls_Manager::TAB_STYLE,
			]
	);
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(), [
				'name' => 'box_shadow',
				'label' => esc_html__('Box Shadow', 'residence-elementor'),
				'selector' => '{{WRAPPER}} .agent_unit_featured ',
			]
        );

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

         public function wpresidence_send_to_shortcode($input){
            $output='';
            if($input!=='' ){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }

	protected function render() {
            $settings = $this->get_settings_for_display();
            $attributes['id']       =   $settings['idul'] ;
            $attributes['notes']    =   $settings['notes'];

            echo  wpestate_featured_agent($attributes);
	}


}
