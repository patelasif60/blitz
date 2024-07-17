<?php

if (!defined('ACTIVE_STATUS')) define('ACTIVE_STATUS', 1);
if (!defined('DEACTIVE_STATUS')) define('DEACTIVE_STATUS', 0);

/*
 * Credit limits & Loans
 * */
const FIFTY_MILLION = 50000000;
const FIVE_HUNDRED_MILLION = 500000000;
const ADMIN = 1;

// email list admin user
if (!defined('OPRATION_EMAILS'))
    define('OPRATION_EMAILS', [
        'karina.mahardika@blitznet.co.id', 'rizko.siregar@blitznet.co.id', 'juan.caldera@blitznet.co.id', 'tejo.permadi@blitznet.co.id', 'harshil.shah@bcssarl.com', 'nimisha.vyas@bcssarl.com'
    ]);

    // email list testing user
if (!defined('TEST_EMAILS'))
    define('TEST_EMAILS', [
        'rohit.patidar@bcssarl.com','harshil.shah@bcssarl.com','mahendra.patil@bcssarl.com','sachin.sanchania@bcssarl.com','hitesh.varyani@bcssarl.com','nimisha.vyas@bcssarl.com','poonam.shete@bcssarl.com','havish.shah@blitznet.co.id'
    ]);

if (!defined('FIFTY_MILLION')) define('FIFTY_MILLION', 50000000);

if (!defined('FIVE_HUNDRED_MILLION')) define('FIVE_HUNDRED_MILLION', 500000000);

if (!defined('MIN_LOAN_AMOUNT')) define('MIN_LOAN_AMOUNT', 100000);

if (!defined('ADMIN')) define('ADMIN', 1);

//loan providers ids
if (!defined('LOAN_PROVIDERS')) define('LOAN_PROVIDERS', ['KOINWORKS'=>1]);

//order payment modes
if (!defined('ORDER_IS_CREDIT'))
    define('ORDER_IS_CREDIT', [
        'CASH'=>0,
        'CREDIT'=>1,
        'LOAN_PROVIDER_CREDIT'=>2
    ]);

//loan providers charges type ids
if (!defined('LOAN_PROVIDER_CHARGE_TYPE'))
    define('LOAN_PROVIDER_CHARGE_TYPE', [
        'INTEREST'=>1,
        'REPAYMENT_CHARGE'=>2,
        'INTERNAL_TRANSFER_CHARGE'=>3,
        'ORIGINATION_CHARGE'=>4,
        'VAT'=>5,
        'LATE_FEE'=>6
    ]);

/**
 * Koinworks account details
 */
if (!defined('KOINWORKS_ACCOUNT'))
    define('KOINWORKS_ACCOUNT', [
        'NAME'=>"Koinworks",
        'ID'=>NULL // account id need ask to munir
    ]);

//loan providers charges type ids
if (!defined('LOAN_APPLY'))
    define('LOAN_APPLY', [
        'REPAIDBYBUYER'=>22

    ]);

//loan transaction
if (!defined('LOAN_TRANSACTION'))
    define('LOAN_TRANSACTION', [
        'DEBIT' =>  1,
        'CREDIT'=>  2

    ]);

//loan status
if (!defined('LOAN_STATUS'))
    define('LOAN_STATUS', [
        'PARTNER_CANCELLED' => 11,
        'ASSESSMENT_APPROVED' => 12,
        'ONGOING' => 16,
        'PAID_OFF' => 17,
        'NPL' => 18,
        'LOAN_CONFIRMED' => 20,
        'REPAY' => 22,
        'COMPLETED'=>23,
        'FAILED'=>24,
        'BUYER_REPAID'=>22


    ]);

//transactions types ids
if (!defined('TRANSACTION_TYPES'))
    define('TRANSACTION_TYPES', [
        'INTERNAL_TRANSFER_XEN'=>1,
        'EXTERNAL_TRANSFER_XEN'=>2,
        'DISBURSEMENT_TO_SUPPLIER'=>3,
        'DISBURSMENT_KOINWORKS_TO_BLITZNET'=>4,
        'BUYER_REPAYMENT'=>5,
        'DISBURSEMENT_BLITZNET_TO_KOINWORKS'=>6,
        'COMMISSION_TRANSFER_TO_BLITZNET'=>7,
        'CHARGES'=>8,
        'INTERNAL_CHARGES_TRANSFER_TO_BLITZNET' => 9
    ]);


//payment providers ids
if (!defined('PAYMENT_PROVIDERS'))
    define('PAYMENT_PROVIDERS', [
        'XENDIT'=>1,
    ]);

//payment provider account ids
if (!defined('PAYMENT_PROVIDER_ACCOUNT'))
    define('PAYMENT_PROVIDER_ACCOUNT', [
        'CREDIT'=>1,
        'DEBIT'=>2,//repayment ac
    ]);

//payment transactions status
if (!defined('PAYMENT_TRANSACTION_STATUS'))
    define('PAYMENT_TRANSACTION_STATUS', [
        'PENDING'=>1,
        'FAILED'=>2,
        'COMPLETED'=>3,
    ]);

//transaction ac type
if (!defined('TRANSACTION_AC_TYPE'))
    define('TRANSACTION_AC_TYPE', [
        'DEBIT'=>0,
        'CREDIT'=>1,
    ]);

//order status
if (!defined('ORDER_STATUS'))
    define('ORDER_STATUS', [
        'CREDIT_REJECTED'=>10
    ]);

//xendit money in & money out
if (!defined('XEN_MONEY_IN_AMOUNT')) define('XEN_MONEY_IN_AMOUNT', 4995);
if (!defined('XEN_MONEY_OUT_AMOUNT')) define('XEN_MONEY_OUT_AMOUNT', 5550);
