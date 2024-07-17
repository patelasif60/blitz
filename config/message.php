<?php
return [
	'buyer_welcome_msg' => "\n We couldn't be happier to welcome you to the blitznet community. Blitznet helps you by:

    • Getting    multiple quotations from credible suppliers for a single RFQ
    • Automize by digitalizing the entire procurement processes
    • Procure raw material with discounted prices & working capital loan
    • Having access to a reliable supplier’s network
    • Secure payment system through escrow account
    • Reliable & transparent logistics through real-time tracking

	Click the link below to setup your account and get started. \n",
	
	'buyer_login_link' => "\n".env('APP_URL').('/signin'),
	'quote_received'=> "\n A response for your @RFQNumber has been received with Quote @QuoteNumber.\n",
	'quote_ending'=> "\n A response for your @RFQNumber has been received with @QuoteNumber and is going to end soon..\n",
	"order_placed" => "\n Your Order @OrderNumber has been placed, let us wait for the supplier to accept the order within next 24 hours.\n",
	"order_payment_pending" => "\n Your order @ordernumber amounting @amount has been accepted and is due for payment. \n",
	"order_status_updated" => "\n The Status has been updated for your Order @OrderNumber to @OrderStatus.\n",
	"quote_received_for_approval" => "\n A Quote @QuoteNumber for @RFQNumber has been received. Please review the quote and take necessary actions.\n",
	"quote_approved" => "\n @PersonName have successfully accepted the quote @QuotationNumber for RFQ @RFQNumber.\n",
	"quote_rejected" => "\n your Quotation @QuotationNumber for RFQ @RFQNumber is rejected by @PersonName.
	Kindly contact with Blitznet Team. \n",
	"order_credit_approved" => "\n your Credit is approved for order @OrderNumber. \n",
	"order_credit_rejected" => "\n  your Credit is rejected for order @OrderNumber. Kindly Contact with Blitznet team for further updates. \n",
	'supplier_welcome_msg' => "\n
    • Getting	 multiple RFQ from credible buyers.
    • Automize by digitalizing the entire procurement processes
    • Procure raw material with working capital loan.
    • Having access to a reliable buyer’s network
    • Secure payment system through escrow account
    • Reliable & transparent logistics through real-time tracking
	
	Click the link below to setup your account and get started. \n",
	"rfq_received" => "\n We have received a RFQ. Please submit your best possible quote for the RFQ.\n",
	"order_received" => "\n You have received a new Order @OrderNumber. Please start preparing the order and let us know when it is Ready for Dispatch. \n",
	"order_status_updated_supplier"=>"\n The Status has been updated for Order @OrderNumber to @OrderStatus. \n",
	'supplier_login_link' => "\n".env('APP_URL').('/admin/login'),
];

?>