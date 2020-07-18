<?php

class migla_stripe_webhook_handler
{
	public function __construct()
	{
		$this->log_file    = TD_DIR_PATH . "logs/td_stripe.log" ;
		$this->is_debug_on = true;
	}
	
	public function migla_stripe_webhook_frontend()
	{
	   	// retrieve the request's body and parse it as JSON
	   	$body = @file_get_contents('php://input');
	   	$event_json = json_decode($body);

		if ( $event_json->type == 'charge.succeeded' )
		{
            
		}else if ( $event_json->type == 'customer.subscription.created' )
		{
		    
		}else if( $event_json->type == 'invoice.payment_succeeded' ) 
		{
  
		}else if( $event_json->type == 'charge.refunded' ) 
		{
            if ( !class_exists( '\Stripe\Stripe' ) ){
	           include_once TD_DIR_PATH . 'gateways/stripe/stripe-php-6.43.0/init.php';
            }

            \Stripe\Stripe::setApiKey( migla_getSK() );
                
            $charge_id = $event_json->data->object->id;
            
            $objD = new CLASS_MIGLA_DONATION;
            
    		$pid = $objD->get_any_donationmeta(   'donation_id',
            		                              'miglad_transactionId',
            		                              $charge_id, 
    		                                      '%s',
    		                                      '%s',
    		                                      'id',
    		                                      'ASC',
    		                                      ''
    		        );   
    		        
    		$objD->update_column( array( "status" => 3, "gateway" => "Stripe-Refunded" ), 
    		                      array( "id" => $pid ), 
    		                      array( "%d", "%s" ), 
    		                      array( "%d" ) );
    
            $data = $objD->get_detail( $pid, 3);
    
            if(isset($data['miglad_form_id']))
            {
                $objE = new MIGLA_EMAIL;
                
                $form_id = $data['miglad_form_id'];
                
                $NotifEmails = $objE->get_column( $form_id, 'notify_emails' );
                
                $content = "Reason of this refunded:" . $event_json->data->object->refunds->object->data[0]->reason. "<br>";
                
                $extra = array( "subject" => "You got a refunded donation",
                                "content" => $content
                                );
                
                if(!empty( $NotifEmails ))
                {
                    $emails = (array)unserialize( $NotifEmails );
                
                    foreach( $emails as $nf ){
                        $objE->send_change_notification_mail( $nf, $data, get_locale(), $extra ) ;    
                    }
                }
                
                error_log( date('[Y-m-d H:i e] ') . 'Refunded transaction ' . $charge_id .  "\n" , 3 , TD_DIR_PATH . "Logs/td_stripe.log" );
            }          
         
                
		}else if ( $event_json->type == 'charge.dispute.created' )
		{
			//Ok get this charge id
			$charge_id = $event_json->data->object->charge;

			$m = "This transaction with id ".$charge_id." is suspected as dispute transaction. Check your stripe account for further action.";

			error_log( date('[Y-m-d H:i e] ') . $m ."\n" , 3 , TD_DIR_PATH . "Logs/td_stripe.log" );

		}else if ( $event_json->type == 'charge.dispute.closed' )
		{
            $charge_id = $event_json->data->object->charge;
			$m = "This transaction with id ".$charge_id." is closed from dispute accusition.";
			error_log( date('[Y-m-d H:i e] ') . $m ."\n" , 3 , TD_DIR_PATH . "Logs/td_stripe.log" );		

		}else if( $event_json->type == 'payment_intent.succeeded' ) //Subscription Made
		{
            
		}else{ // ELSE This will send receipts on succesful invoices

		}
	}
     
}

?>