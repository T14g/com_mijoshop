<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// Text
$_['text_title']       = 'Pay with Credit Card (eWAY)';
$_['text_credit_card'] = 'Credit Card Details';
$_['text_testing']     = 'This payment gateway is currently being tested. Your credit card will not be charged.<br />If this is a real order, please use an alternate method of payment at this time.';

$_['text_basket']   = 'Basket';
$_['text_checkout'] = 'Checkout';
$_['text_success']  = 'Success';
$_['text_shipping'] = 'Shipping';

// Entry
$_['entry_cc_number']      = 'Card number';
$_['entry_cc_name']        = 'Cardholder name';
$_['entry_cc_expire_date'] = 'Card expiry date';
$_['entry_cc_cvv2']        = 'Card security code (CVV2)';

$_['button_pay'] = 'Pay now';

$_['text_card_accepted'] = 'Accepted cards: ';
$_['text_card_type_m']   = 'Mastercard';
$_['text_card_type_v']   = 'Visa (Credit/Debit/Electron/Delta)';
$_['text_card_type_c']   = 'Diners';
$_['text_card_type_a']   = 'American Express';
$_['text_card_type_j']   = 'JCB';
$_['text_card_type_pp']  = 'Paypal';
$_['text_card_type_mp']  = 'Masterpass';
$_['text_card_type_vm']  = 'Visa Checkout';
$_['text_type_help']     = 'After you click "Confirm Order"  you will be redirected to ';

$_['text_transaction_failed'] = 'Sorry, your payment has been declined.';

// Help
$_['help_cvv']      = 'For Mastercard or Visa, this is the last three digits in the signature area on the back of your card.';
$_['help_cvv_amex'] = 'For American Express, it\'s the four digits on the front of the card';

// Validation Error codes
$_['text_card_message_Please check the API Key and Password'] = 'Please check the API Key and Password';

$_['text_card_message_V6000'] = 'Undefined Validation Error';
$_['text_card_message_V6001'] = 'Invalid Customer IP';
$_['text_card_message_V6002'] = 'Invalid DeviceID';
$_['text_card_message_V6011'] = 'Invalid Amount';
$_['text_card_message_V6012'] = 'Invalid Invoice Description';
$_['text_card_message_V6013'] = 'Invalid Invoice Number';
$_['text_card_message_V6014'] = 'Invalid Invoice Reference';
$_['text_card_message_V6015'] = 'Invalid Currency Code';
$_['text_card_message_V6016'] = 'Payment Required';
$_['text_card_message_V6017'] = 'Payment Currency Code Required';
$_['text_card_message_V6018'] = 'Unknown Payment Currency Code';
$_['text_card_message_V6021'] = 'Cardholder Name Required';
$_['text_card_message_V6022'] = 'Card Number Required';
$_['text_card_message_V6023'] = 'CVN Required';
$_['text_card_message_V6031'] = 'Invalid Card Number';
$_['text_card_message_V6032'] = 'Invalid CVN';
$_['text_card_message_V6033'] = 'Invalid Expiry Date';
$_['text_card_message_V6034'] = 'Invalid Issue Number';
$_['text_card_message_V6035'] = 'Invalid Start Date';
$_['text_card_message_V6036'] = 'Invalid Month';
$_['text_card_message_V6037'] = 'Invalid Year';
$_['text_card_message_V6040'] = 'Invalid Token Customer Id';
$_['text_card_message_V6041'] = 'Customer Required';
$_['text_card_message_V6042'] = 'Customer First Name Required';
$_['text_card_message_V6043'] = 'Customer Last Name Required';
$_['text_card_message_V6044'] = 'Customer Country Code Required';
$_['text_card_message_V6045'] = 'Customer Title Required';
$_['text_card_message_V6046'] = 'Token Customer ID Required';
$_['text_card_message_V6047'] = 'RedirectURL Required';
$_['text_card_message_V6051'] = 'Invalid First Name';
$_['text_card_message_V6052'] = 'Invalid Last Name';
$_['text_card_message_V6053'] = 'Invalid Country Code';
$_['text_card_message_V6054'] = 'Invalid Email';
$_['text_card_message_V6055'] = 'Invalid Phone';
$_['text_card_message_V6056'] = 'Invalid Mobile';
$_['text_card_message_V6057'] = 'Invalid Fax';
$_['text_card_message_V6058'] = 'Invalid Title';
$_['text_card_message_V6059'] = 'Redirect URL Invalid';
$_['text_card_message_V6060'] = 'Redirect URL Invalid';
$_['text_card_message_V6061'] = 'Invalid Reference';
$_['text_card_message_V6062'] = 'Invalid Company Name';
$_['text_card_message_V6063'] = 'Invalid Job Description';
$_['text_card_message_V6064'] = 'Invalid Street1';
$_['text_card_message_V6065'] = 'Invalid Street2';
$_['text_card_message_V6066'] = 'Invalid City';
$_['text_card_message_V6067'] = 'Invalid State';
$_['text_card_message_V6068'] = 'Invalid Postalcode';
$_['text_card_message_V6069'] = 'Invalid Email';
$_['text_card_message_V6070'] = 'Invalid Phone';
$_['text_card_message_V6071'] = 'Invalid Mobile';
$_['text_card_message_V6072'] = 'Invalid Comments';
$_['text_card_message_V6073'] = 'Invalid Fax';
$_['text_card_message_V6074'] = 'Invalid Url';
$_['text_card_message_V6075'] = 'Invalid Shipping Address First Name';
$_['text_card_message_V6076'] = 'Invalid Shipping Address Last Name';
$_['text_card_message_V6077'] = 'Invalid Shipping Address Street1';
$_['text_card_message_V6078'] = 'Invalid Shipping Address Street2';
$_['text_card_message_V6079'] = 'Invalid Shipping Address City';
$_['text_card_message_V6080'] = 'Invalid Shipping Address State';
$_['text_card_message_V6081'] = 'Invalid Shipping Address PostalCode';
$_['text_card_message_V6082'] = 'Invalid Shipping Address Email';
$_['text_card_message_V6083'] = 'Invalid Shipping Address Phone';
$_['text_card_message_V6084'] = 'Invalid Shipping Address Country';
$_['text_card_message_V6091'] = 'Unknown Country Code';
$_['text_card_message_V6100'] = 'Invalid Card Name';
$_['text_card_message_V6101'] = 'Invalid Card Expiry Month';
$_['text_card_message_V6102'] = 'Invalid Card Expiry Year';
$_['text_card_message_V6103'] = 'Invalid Card Start Month';
$_['text_card_message_V6104'] = 'Invalid Card Start Year';
$_['text_card_message_V6105'] = 'Invalid Card Issue Number';
$_['text_card_message_V6106'] = 'Invalid Card CVN';
$_['text_card_message_V6107'] = 'Invalid AccessCode';
$_['text_card_message_V6108'] = 'Invalid CustomerHostAddress';
$_['text_card_message_V6109'] = 'Invalid UserAgent';
$_['text_card_message_V6110'] = 'Invalid Card Number';
$_['text_card_message_V6111'] = 'Unauthorised API Access, Account Not PCI Certified';
$_['text_card_message_V6112'] = 'Redundant card details other than expiry year and month';
$_['text_card_message_V6113'] = 'Invalid transaction for refund';
$_['text_card_message_V6114'] = 'Gateway validation error';
$_['text_card_message_V6115'] = 'Invalid DirectRefundRequest, Transaction ID';
$_['text_card_message_V6116'] = 'Invalid card data on original TransactionID';
$_['text_card_message_V6124'] = 'Invalid Line Items. The line items have been provided however the totals do not match the TotalAmount field';
$_['text_card_message_V6125'] = 'Selected Payment Type not enabled';
$_['text_card_message_V6126'] = 'Invalid encrypted card number, decryption failed';
$_['text_card_message_V6127'] = 'Invalid encrypted cvn, decryption failed';
$_['text_card_message_V6128'] = 'Invalid Method for Payment Type';
$_['text_card_message_V6129'] = 'Transaction has not been authorised for Capture/Cancellation';
$_['text_card_message_V6130'] = 'Generic customer information error';
$_['text_card_message_V6131'] = 'Generic shipping information error';
$_['text_card_message_V6132'] = 'Transaction has already been completed or voided, operation not permitted';
$_['text_card_message_V6133'] = 'Checkout not available for Payment Type';
$_['text_card_message_V6134'] = 'Invalid Auth Transaction ID for Capture/Void';
$_['text_card_message_V6135'] = 'PayPal Error Processing Refund';
$_['text_card_message_V6140'] = 'Merchant account is suspended';
$_['text_card_message_V6141'] = 'Invalid PayPal account details or API signature';
$_['text_card_message_V6142'] = 'Authorise not available for Bank/Branch';
$_['text_card_message_V6150'] = 'Invalid Refund Amount';
$_['text_card_message_V6151'] = 'Refund amount greater than original transaction';

// Payment failure messages
$_['text_card_message_D4401'] = 'Refer to Issuer';
$_['text_card_message_D4402'] = 'Refer to Issuer, special';
$_['text_card_message_D4403'] = 'No Merchant';
$_['text_card_message_D4404'] = 'Pick Up Card';
$_['text_card_message_D4405'] = 'Do Not Honour';
$_['text_card_message_D4406'] = 'Error';
$_['text_card_message_D4407'] = 'Pick Up Card, Special';
$_['text_card_message_D4409'] = 'Request In Progress';
$_['text_card_message_D4412'] = 'Invalid Transaction';
$_['text_card_message_D4413'] = 'Invalid Amount';
$_['text_card_message_D4414'] = 'Invalid Card Number';
$_['text_card_message_D4415'] = 'No Issuer';
$_['text_card_message_D4419'] = 'Re-enter Last Transaction';
$_['text_card_message_D4421'] = 'No Method Taken';
$_['text_card_message_D4422'] = 'Suspected Malfunction';
$_['text_card_message_D4423'] = 'Unacceptable Transaction Fee';
$_['text_card_message_D4425'] = 'Unable to Locate Record On File';
$_['text_card_message_D4430'] = 'Format Error';
$_['text_card_message_D4431'] = 'Bank Not Supported By Switch';
$_['text_card_message_D4433'] = 'Expired Card, Capture';
$_['text_card_message_D4434'] = 'Suspected Fraud, Retain Card';
$_['text_card_message_D4435'] = 'Card Acceptor, Contact Acquirer, Retain Card';
$_['text_card_message_D4436'] = 'Restricted Card, Retain Card';
$_['text_card_message_D4437'] = 'Contact Acquirer Security Department, Retain Card';
$_['text_card_message_D4438'] = 'PIN Tries Exceeded, Capture';
$_['text_card_message_D4439'] = 'No Credit Account';
$_['text_card_message_D4440'] = 'Function Not Supported';
$_['text_card_message_D4441'] = 'Lost Card';
$_['text_card_message_D4442'] = 'No Universal Account';
$_['text_card_message_D4443'] = 'Stolen Card';
$_['text_card_message_D4444'] = 'No Investment Account';
$_['text_card_message_D4451'] = 'Insufficient Funds';
$_['text_card_message_D4452'] = 'No Cheque Account';
$_['text_card_message_D4453'] = 'No Savings Account';
$_['text_card_message_D4454'] = 'Expired Card';
$_['text_card_message_D4455'] = 'Incorrect PIN';
$_['text_card_message_D4456'] = 'No Card Record';
$_['text_card_message_D4457'] = 'Function Not Permitted to Cardholder';
$_['text_card_message_D4458'] = 'Function Not Permitted to Terminal';
$_['text_card_message_D4460'] = 'Acceptor Contact Acquirer';
$_['text_card_message_D4461'] = 'Exceeds Withdrawal Limit';
$_['text_card_message_D4462'] = 'Restricted Card';
$_['text_card_message_D4463'] = 'Security Violation';
$_['text_card_message_D4464'] = 'Original Amount Incorrect';
$_['text_card_message_D4466'] = 'Acceptor Contact Acquirer, Security';
$_['text_card_message_D4467'] = 'Capture Card';
$_['text_card_message_D4475'] = 'PIN Tries Exceeded';
$_['text_card_message_D4482'] = 'CVV Validation Error';
$_['text_card_message_D4490'] = 'Cutoff In Progress';
$_['text_card_message_D4491'] = 'Card Issuer Unavailable';
$_['text_card_message_D4492'] = 'Unable To Route Transaction';
$_['text_card_message_D4493'] = 'Cannot Complete, Violation Of The Law';
$_['text_card_message_D4494'] = 'Duplicate Transaction';
$_['text_card_message_D4496'] = 'System Error';
$_['text_card_message_D4497'] = 'MasterPass Error Failed';
$_['text_card_message_D4498'] = 'PayPal Create Transaction Error Failed';
$_['text_card_message_D4499'] = 'Invalid Transaction for Auth/Void';

$_['text_card_message_F7000'] = 'Undefined Fraud Error';
$_['text_card_message_F7001'] = 'Challenged Fraud';
$_['text_card_message_F7002'] = 'Country Match Fraud';
$_['text_card_message_F7003'] = 'High Risk Country Fraud';
$_['text_card_message_F7004'] = 'Anonymous Proxy Fraud';
$_['text_card_message_F7005'] = 'Transparent Proxy Fraud';
$_['text_card_message_F7006'] = 'Free Email Fraud';
$_['text_card_message_F7007'] = 'International Transaction Fraud';
$_['text_card_message_F7008'] = 'Risk Score Fraud';
$_['text_card_message_F7009'] = 'Denied Fraud';
$_['text_card_message_F7010'] = 'Denied by PayPal Fraud Rules';
$_['text_card_message_F9010'] = 'High Risk Billing Country';
$_['text_card_message_F9011'] = 'High Risk Credit Card Country';
$_['text_card_message_F9012'] = 'High Risk Customer IP Address';
$_['text_card_message_F9013'] = 'High Risk Email Address';
$_['text_card_message_F9014'] = 'High Risk Shipping Country';
$_['text_card_message_F9015'] = 'Multiple card numbers for single email address';
$_['text_card_message_F9016'] = 'Multiple card numbers for single location';
$_['text_card_message_F9017'] = 'Multiple email addresses for single card number';
$_['text_card_message_F9018'] = 'Multiple email addresses for single location';
$_['text_card_message_F9019'] = 'Multiple locations for single card number';
$_['text_card_message_F9020'] = 'Multiple locations for single email address';
$_['text_card_message_F9021'] = 'Suspicious Customer First Name';
$_['text_card_message_F9022'] = 'Suspicious Customer Last Name';
$_['text_card_message_F9023'] = 'Transaction Declined';
$_['text_card_message_F9024'] = 'Multiple transactions for same address with known credit card';
$_['text_card_message_F9025'] = 'Multiple transactions for same address with new credit card';
$_['text_card_message_F9026'] = 'Multiple transactions for same email with new credit card';
$_['text_card_message_F9027'] = 'Multiple transactions for same email with known credit card';
$_['text_card_message_F9028'] = 'Multiple transactions for new credit card';
$_['text_card_message_F9029'] = 'Multiple transactions for known credit card';
$_['text_card_message_F9030'] = 'Multiple transactions for same email address';
$_['text_card_message_F9031'] = 'Multiple transactions for same credit card';
$_['text_card_message_F9032'] = 'Invalid Customer Last Name';
$_['text_card_message_F9033'] = 'Invalid Billing Street';
$_['text_card_message_F9034'] = 'Invalid Shipping Street';
$_['text_card_message_F9037'] = 'Suspicious Customer Email Address';
