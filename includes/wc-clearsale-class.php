<?php

class Clearsale {
    public $integration_code = '';

    function __construct() {
        $this->integration_code = esc_attr( get_option('clearsale_integration_code') );
        add_action('add_meta_boxes', array($this, 'cs_add_custom_metabox'));
        add_action( 'save_post_shop_order', array($this, 'cs_save'), 10, 3 );
    }

    function cs_add_custom_metabox(){
        add_meta_box('cs_metabox', __('Clearsale','settings_page'),array($this, 'cs_callback_fields'),'shop_order', 'side', 'high' );
    }

    function cs_callback_fields($post){
      $cs_post_meta = get_post_meta( $post->ID );
      require_once( plugin_dir_path( __FILE__ ) . '/templates/wc-clearsale-order-return.php');
    }

    function cs_save($order_id, $post, $update) {
      // Orders in backend only
      if( ! is_admin() ) return;

      // if edditing
      if($update){
        $order = wc_get_order( $order_id );
        $order_data = $order->get_data();

        $tel = explode(' ', $order_data['billing']['phone']);

        if(isset($tel[1])){
          $ddd = preg_replace("/[^0-9]/","",$tel[0]);
          $number = preg_replace("/[^0-9]/","",$tel[1]);
        } else {
          $ddd = '14';
          $number = '996705445';
        }


        if(isset($_POST['clearsale_save']) && $_POST['clearsale_save'] == 1){
          $url = 'https://www.clearsale.com.br/start/Entrada/EnviarPedido.aspx';
          $fields = array(
          	'CodigoIntegracao' => $this->integration_code,
            'TipoPagamento' => 14,
            'PedidoID' => urlencode($order_data['id']),
            'Data' => urlencode($order_data['date_created']->date('d/m/Y H:i')),
            'Total' => urlencode(number_format($order_data['total'],2,',','.')),
            'Cobranca_Nome' => urlencode($order_data['billing']['first_name'].' '.$order_data['billing']['last_name']),
            'Cobranca_Email' => urlencode($order_data['billing']['email']),
            'Cobranca_Documento' => urlencode(get_post_meta( $order_id, '_billing_cpf', true )),
            'Cobranca_DDD_Telefone_1' => urlencode($ddd),
            'Cobranca_Telefone_1' => urlencode($number),
            'Entrega_Nome' => urlencode($order_data['shipping']['first_name'].' '.$order_data['shipping']['last_name']),
            'Entrega_Email' => urlencode($order_data['billing']['email']),
            'Entrega_Documento' => urlencode(get_post_meta( $order_id, '_billing_cpf', true )),
            'Entrega_Logradouro' => urlencode($order_data['shipping']['address_1']),
            'Entrega_Logradouro_Numero' => urlencode('01'),
            'Entrega_Logradouro_Complemento' => urlencode($order_data['shipping']['address_2']),
            'Entrega_Bairro' => urlencode('Centro'),
            'Entrega_Cidade' => urlencode($order_data['shipping']['city']),
            'Entrega_Estado' => urlencode($order_data['shipping']['state']),
            'Entrega_CEP' => urlencode($order_data['shipping']['postcode']),
            'Entrega_Pais' => urlencode($order_data['shipping']['country']),
            'Entrega_DDD_Telefone_1' => urlencode($ddd),
            'Entrega_Telefone_1' => urlencode($number),
            'Entrega_DDD_Celular' => urlencode($ddd),
            'Entrega_Celular' => urlencode($number),
          );

          // Iterating through each WC_Order_Item_Product objects
          $i=0; foreach ($order->get_items() as $item_key => $item_values){ $i++;

              $item_data = $item_values->get_data();
              $fields['Item_ID_'.$i] = urlencode($item_data['product_id']);
              $fields['Item_Nome_'.$i] = urlencode($item_data['name']);
              $fields['Item_Qtd_'.$i] = urlencode($item_data['quantity']);
              $fields['Item_Valor_'.$i] = urlencode(number_format($item_data['total'],2,',','.'));
              // $variation_id = $item_data['variation_id'];
          }

          //url-ify the data for the POST
          foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
          rtrim($fields_string, '&');

          //open connection
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL, $url);
          curl_setopt($ch,CURLOPT_POST, count($fields));
          curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
          $result = curl_exec($ch);
          curl_close($ch);

          update_post_meta( $order_id, 'clearsale', 'https://www.clearsale.com.br/start/Entrada/EnviarPedido.aspx?codigointegracao='.$this->integration_code.'&PedidoID='.$order_id );
        }
      }
    }

}

$cs = new Clearsale();
