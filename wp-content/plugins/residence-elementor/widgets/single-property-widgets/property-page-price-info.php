<?php

namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wpresidence_Property_Page_Additional_Price_Info  extends Widget_Base {

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
        return 'Additional Price Info ';
    }

    public function get_categories() {
        return ['wpresidence_property'];
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
        return __('Additional Price Info', 'residence-elementor');
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
        return ' wpresidence-note eicon-post-title';
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



        $this->start_controls_section(
            'typography_section', [
                'label' => esc_html__('Settings', 'residence-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'property_title',
                'label' => esc_html__('Price Typography', 'residence-elementor'),
               'global' => [
            'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
        ],
                'selector' => '{{WRAPPER}} .price_area',
            ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'property_title_span',
            'label' => esc_html__('Price Label Typography', 'residence-elementor'),
           'global' => [
            'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY
        ],
            'selector' => '{{WRAPPER}} .price_label',
                ]
        );


        $this->add_control(
            'price_area_color', [
                'label' => esc_html__('Color', 'residence-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .price_area' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(), [
                'name' => 'text_shadow',
                'label' => __('Text Shadow', 'wpresidence-core'),
                'selector' => '{{WRAPPER}} .price_area',
            ]
        );

        $this->add_responsive_control(
            'text_align', [
                'label' => __('Alignment', 'wpresidence-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'selectors' => [
                    '{{WRAPPER}} ' => 'text-align: {{VALUE}};',
                ],
                'options' => [
                    'left' => [
                        'title' => __('Left', 'wpresidence-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wpresidence-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'wpresidence-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
            ]
        );

        $this->add_responsive_control(
            'price_area_width',
            [
                'label' => __( 'Price Area Width', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ], // Allow both px and percentage units
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'desktop_default' => [
                    'unit' => '%',
                    'size' => 25, // Default for desktop
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 25, // Default for tablet
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100, // Default for mobile
                ],
                'selectors' => [
                    '{{WRAPPER}} .price_area' => 'max-width: {{SIZE}}{{UNIT}};',
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
    protected function render() {
        $settings = $this->get_settings_for_display();
        $attributes['is_elementor'] = 1;
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }
        echo wpestate_estate_property_page_price_info_section($attributes);
    }

}
