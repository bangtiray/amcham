<?php 
class Notification extends CI_Controller {
  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -  
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in 
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function __construct()
    {
        parent::__construct();
        $params = array('server_key' => MIDTRANS_SERVER_KEY, 'production' => MIDTRANS_IS_PRODUCTION);
        $this->load->library('midtrans');
        $this->veritrans->config($params);
        $this->load->helper('url');
    
    }
  public function index()
  {
    $json_result = file_get_contents('php://input');
    $notif       = json_decode($json_result);

    if(!$config){
      $config = $this->db->get('email_config')->row_array();
    }
    $this->load->library('email');
    $this->email->initialize(array(
      'protocol' => 'smtp',
      'smtp_host' => $config['smtp_host'],
      'smtp_user' => $config['smtp_user'],
      'smtp_pass' => $config['smtp_pass'],
      'smtp_port' => $config['port'],
      'crlf' => "\r\n",
      'newline' => "\r\n",
      'mailtype' => 'html', 
      'charset' => 'iso-8859-1'
    ));
    $this->email->from('system@amcham.or.id', 'amcham');
    $this->email->to('amar.ronaldo.m@gmail.com');
    $this->email->subject('midtrans notif');
    $this->email->message(json_encode($notif));

    $check_status_email_sent  = $this->email->send();
    exit;

    $insert_log['result']         = json_encode($notif);
    $insert_log['invoice_number'] = $order_id;
    $insert_log['payment_type']   = $type;
    $insert_log['status']         = $transaction;
    $insert_log                   = array_merge(load_conf_fr_api(), $insert_log);
    $data                         = $this->futuready_api_library->call("veritrans_log/insert_log", $insert_log);

    //notification handler sample
    $transaction      = $notif->transaction_status;
    $type             = $notif->payment_type;
    $order_id         = $notif->order_id;
    $gross_amount     = $notif->gross_amount;
    $transaction_time = $notif->transaction_time;
    $fraud            = $notif->fraud_status;
    if ($transaction == 'capture') {
      // For credit card transaction, we need to check whether transaction is challenge by FDS or not
      if ($type == 'credit_card'){
        if($fraud == 'challenge'){
          // TODO set payment status in merchant's database to 'Challenge by FDS'
          // TODO merchant should decide whether this transaction is authorized or not in MAP
          $insert_log["message"] = "Transaction Order ID: " . $order_id ." is challenged by FDS";
          } 
          else {
          // TODO set payment status in merchant's database to 'Success'
          $insert_log["message"] = "Transaction Order ID: " . $order_id ." successfully captured using " . $type;
          }
        }
      }
    else if ($transaction == 'settlement'){
      // TODO set payment status in merchant's database to 'Settlement'
     
        if ($type == 'bank_transfer'){
          $post['invoice_number'] = $order_id;
          $this->futuready_asuransi_library->call('payment_confirmation',$post);
        }
        $update_settlement = $this->mitraplus_library->call(array(
                'funcName'       => 'updateinvoice',
                'notapremi'       => $order_id,
                'settledate'       => $transaction_time,
                'settlevalue'       => $gross_amount,
                'settlebank'       => 'BNI'
        ));
      // }
    } 
    else if($transaction == 'pending'){
      // TODO set payment status in merchant's database to 'Pending'
      $insert_log["message"] = "Waiting customer to finish transaction Order ID: " . $order_id . " using " . $type;
    } 
    else if ($transaction == 'deny') {
      // TODO set payment status in merchant's database to 'Denied'
      $insert_log["message"] = "Payment using " . $type . " for transaction Order ID: " . $order_id . " is denied.";
    } else if($transaction == 'expire'){
      // TODO set payment status in merchant's database to 'expire'
      $get_cq['table_name'] = 'customer_quote';
      $get_cq['where'] = array("invoice_number"=>$order_id);
      $get_cq['is_single_data'] = 1;
      $get_cq = array_merge(load_conf_fr_api(),$get_cq);
      $cq = $this->futuready_api_library->call('products/v2/query/get',$get_cq);
      if($cq['id']){
        $get_customer['table_name'] = 'customer';
        $get_customer['where'] = array("customer_quote_id"=>$cq['id'],'ref_relation_id'=>0);
        $get_customer['is_single_data'] = 1;
        $get_customer = array_merge(load_conf_fr_api(),$get_customer);
        $customer = $this->futuready_api_library->call('products/v2/query/get',$get_customer);
        $data_email['fullname'] = $customer['fullname'];
        $data_email['invoice_number'] = $cq['invoice_number'];
        $data_email['quote_number'] = $cq['quote_number'];
        // sent_email_by_category(43,$data_email,$customer['email']);
        // sent_email_notification(43, $data_email);
        $insert_log["message"] = "Payment using " . $type . " for transaction Order ID: " . $order_id . " is expired. using " . $type;

        if ($type == 'bank_transfer' && $cq['is_bypass_veritrans'] != 1){
          $delete_payment['customer_quote_id'] = $cq['id'];
          $delete_payment = array_merge(load_conf_fr_api(),$delete_payment);
          $this->futuready_api_library->call("products/v2/payment/delete_payment", $delete_payment);
          $update_quote['field_name'] = 'customer_quote';
          $update_quote['data'] = array("is_payment_expired"=>1);
          $update_quote['where'] = array("id"=>$cq['id']);
          $update_quote = array_merge(load_conf_fr_api(),$update_quote);
          $this->futuready_api_library->call('products/v2/query/update',$update_quote);
        }
      }

    }
    $insert_log['result']         = json_encode($notif);
    $insert_log['invoice_number'] = $order_id;
    $insert_log['payment_type']   = $type;
    $insert_log['status']         = $transaction;
    $insert_log                   = array_merge(load_conf_fr_api(), $insert_log);
    $data                         = $this->futuready_api_library->call("veritrans_log/insert_log", $insert_log);
  }
  
}