<?php

function Shop_Element() {
    if ( class_exists( 'Fusion_Element' ) ) {
        class FusionSC_Shop extends Fusion_Element {
            
            public function __construct() {
                parent::__construct();
                add_filter( 'fusion_attr_shop-element', array( $this, 'attr' ) );
                $this->add_shortcode();
                $this->map_shortcode();
            }

            public function render( $args, $content = '' ) {
                $defaults = FusionBuilder::set_shortcode_defaults(
                    array(
                        'title'       => '',
                        'number_of_products' => 4,
                    ),
                    $args,
                    'shop-element'
                );

                extract( $defaults );

                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $number_of_products,
                    'post_status' => 'publish',
                    'orderby'     => 'rand',
                );

                $loop = new WP_Query( $args );

                ob_start();
                ?>
                    <div <?php echo $this->attributes( 'shop-wrapper' ); ?>>
                        <div class="shop_wrapper_area">
                            <div class="row">
                                <?php while ( $loop->have_posts() ) : 
                                    $loop->the_post(); 
                                    global $product; 
                                    $subtitle = get_field('products_meta');
                                ?>
                                <div class="col-lg-6">
                                    <div class="row shop_wrapper_item">
                                        <div class="col-lg-6">
                                            <div class="shop_wrapper_content">
                                                <div class="shop_subtitle">
                                                    <span><?php echo esc_html($subtitle); ?></span>
                                                </div>
                                                <div class="shop_title">
                                                    <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                                                </div>
                                                <div class="shop_description">
                                                    <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                                                </div>
                                                <div class="shop_item_btn">
                                                    <a href="<?php the_permalink(); ?>">Shop Now <i class="fa-arrow-right fas button-icon-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="shop_thumbnail">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <img src="<?php echo get_the_post_thumbnail_url( $product->get_id(), 'full' ); ?>" alt="<?php the_title(); ?>">
                                                <?php else : ?>
                                                    <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title(); ?>">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; wp_reset_query(); ?>
                            </div>
                        </div>
                    </div>
                <?php
                return ob_get_clean();
            }

            public function attr() {
                $attr = array(
                    'class' => 'shop-element',
                );
                return $attr;
            }

            public function attributes( $shortcode ) {
                $attr = $this->attr();
                $attributes = '';
                foreach ( $attr as $name => $value ) {
                    $attributes .= ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
                }
                return $attributes;
            }

            public function add_shortcode() {
                add_shortcode( 'shop-element', array( $this, 'render' ) );
            }

            public function map_shortcode() {
                fusion_builder_map(
                    array(
                        'name'            => esc_attr__( 'Shop Section', 'fusion-builder' ),
                        'shortcode'       => 'shop-element',
                        'icon'            => 'fusiona-info-circle',
                        'params'          => array(
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_attr__( 'Title', 'fusion-builder' ),
                                'description' => esc_attr__( 'Add the title of the slider.', 'fusion-builder' ),
                                'param_name'  => 'title',
                                'value'       => '',
                            ),
                            array(
                                'type'        => 'textfield',
                                'heading'     => esc_attr__( 'Number of Products', 'fusion-builder' ),
                                'description' => esc_attr__( 'Specify the number of products to display.', 'fusion-builder' ),
                                'param_name'  => 'number_of_products',
                                'value'       => '4',
                            ),
                        ),
                    )
                );
            }
        }

        new FusionSC_Shop();
    }
}
add_action( 'wp_loaded', 'Shop_Element' );