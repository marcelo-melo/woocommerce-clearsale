<?php

add_filter( 'woocommerce_general_settings', 'add_clearsale_integration_code_setting' );
function add_clearsale_integration_code_setting( $settings ) {
  $updated_settings = array();
  foreach ( $settings as $section ) {
    // at the bottom of the General Options section
    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
      $updated_settings[] = array(
        'name'     => __( 'Integração Clearsale', 'clearsale_integration_code' ),
        'desc_tip' => __( 'Entre com o seu código de integração do Clearsale', 'clearsale_integration_code' ),
        'id'       => 'clearsale_integration_code',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => '1',  // WC >= 2.0
        'desc'     => __( 'Exemplo: c1234123-123a-1d52-12e0-d6cc1432e2bd', 'integration_code' ),
      );
    }
    $updated_settings[] = $section;
  }
  return $updated_settings;
}
