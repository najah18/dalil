<?php

namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wpresidence_ListItems_ByID extends Widget_Base {

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
        return 'WpResidence_List_Items_By_Id';
    }

    public function get_categories() {
        return ['wpresidence'];
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
        return __('List Items By Id', 'residence-elementor');
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
        return ' wpresidence-note eicon-post-list';
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
        return [''];
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
    protected function register_controls() {

        $featured_listings = array('no' => 'no', 'yes' => 'yes');
        $items_type = array('properties' => 'properties', 'articles' => 'articles');
        $alignment_type = array('vertical' => 'vertical', 'horizontal' => 'horizontal');

        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'residence-elementor'),
                ]
        );

        $this->add_control(
                'title_residence',
                [
                    'label' => __('Title', 'residence-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'Label Block'
                ]
        );

        $this->add_control(
                'type',
                [
                    'label' => __('What type of items', 'residence-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'properties',
                    'options' => $items_type
                ]
        );

        $this->add_control(
                'ids',
                [
                    'label' => __('Items IDs', 'residence-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'Label Block'
                ]
        );

        $this->add_control(
                'number',
                [
                    'label' => __('No of items', 'residence-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => 3,
                ]
        );

        $this->add_control(
                'rownumber',
                [
                    'label' => __('No of items per row', 'residence-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => 3,
                ]
        );

        $this->add_control(
                'show_featured_only',
                [
                    'label' => __('Show featured listings only?', 'residence-elementor'),
                    'condition' => [
                        'type' => 'properties'
                     ],          
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'no',
                    'options' => $featured_listings
                ]
        );
        $this->add_control(
                'align',
                [
                    'label' => __('What type of alignment?', 'residence-elementor'),
                    'condition' => [
                        'type' => 'properties'
                     ],  
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'vertical',
                    'options' => $alignment_type
                ]
        );

        $this->add_control(
                'link',
                [
                    'label' => __('Link to global listing page', 'residence-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'Label Block'
                ]
        );

        $this->add_control(
                'display_grid',
                [
                    'label' => esc_html__('Display as Grid?', 'residence-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'residence-elementor'),
                    'label_off' => esc_html__('No', 'residence-elementor'),
                    'return_value' => 'yes',
                    'default' => esc_html__('No', 'residence-elementor'),
                    'description' => esc_html__('There is no fixed number of units. The grid will auto adjust to display units with a minimum width set by the control below.?', 'residence-elementor'),
                ]
        );

        $this->add_responsive_control(
                'display_grid_unit_width',
                [
                    'label' => esc_html__('Unit Minimum Width', 'residence-elementor'),
                    'condition' => [
                        'display_grid' => 'yes'
                    ],
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 220,
                            'max' => 400,
                        ],
                    ],
                    'devices' => ['desktop', 'tablet', 'mobile'],
                    'desktop_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .items_shortcode_wrapper_grid' => '  grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, 1fr));',
                    ],
                ]
        );

        $this->add_responsive_control(
                'display_grid_unit_gap',
                [
                    'label' => esc_html__('Gap between units in px', 'residence-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [
                        'display_grid' => 'yes'
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'devices' => ['desktop', 'tablet', 'mobile'],
                    'desktop_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .items_shortcode_wrapper_grid' => 'gap: {{SIZE}}{{UNIT}};',
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
            'selector' => '{{WRAPPER}} .property_listing ',
                ]
        );
        $this->end_controls_section();

        /*
        *-------------------------------------------------------------------------------------------------
        * Load more section
        */

        $this->start_controls_section(
            'section_load_more',
            [
                'label' => esc_html__( 'Load More Button', 'residence-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_position',
            [
                'label' => __('Button Alignment', 'residence-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'residence-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'residence-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'residence-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .listinglink-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'residence-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpresidence_button' => 'background-color: {{VALUE}};background-image:linear-gradient(to right, transparent 50%, {{VALUE}} 50%);border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_color',
            [
                'label'     => esc_html__( 'Color', 'residence-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpresidence_button' => 'color: {{VALUE}}',

                ],
            ]
        );

        $this->add_control(
            'load_more_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color Hover', 'residence-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpresidence_button:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_color_hover',
            [
                'label'     => esc_html__( 'Color Hover', 'residence-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpresidence_button:hover' => 'color: {{VALUE}};',
                ],
            ]
        ); 
        
        $this->end_controls_section();
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
    public function wpresidence_send_to_shortcode($input) {
        $output = '';
        if ($input !== '') {
            $numItems = count($input);
            $i = 0;

            foreach ($input as $key => $value) {
                $output .= $value;
                if (++$i !== $numItems) {
                    $output .= ', ';
                }
            }
        }
        return $output;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $attributes['title'] = $settings['title_residence'];
        $attributes['type'] = $settings['type'];
        $attributes['ids'] = $settings['ids'];
        $attributes['number'] = $settings['number'];
        $attributes['rownumber'] = $settings['rownumber'];
        $attributes['align'] = $settings['align'];
        $attributes['link'] = $settings['link'];

        $attributes['display_grid'] = $settings['display_grid'];

        echo wpestate_list_items_by_id_function($attributes);

    }
}
