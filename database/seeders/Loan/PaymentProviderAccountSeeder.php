<?php

namespace Database\Seeders\Loan;

use App\Models\PaymentProviderAccount;
use Faker\Provider\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class PaymentProviderAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        PaymentProviderAccount::truncate();

        PaymentProviderAccount::insert([
            ['payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'], 'name' => 'Blitznet Credit', 'email' => 'credit@blitznet.co.id', 'payment_provider_ac_id' => '6321ac167217a8b97b53ffcc', 'environment_type' => PaymentProviderAccount::LIVE_ENV, 'account_type' => PaymentProviderAccount::CREDIT_AC, 'response_data' => '{
                                                                                                                                "account_email": "credit@blitznet.co.id",
                                                                                                                                "user_id": "6321ac167217a8b97b53ffcc",
                                                                                                                                "created": "2022-09-14T10:25:26.118Z",
                                                                                                                                "status": "SUCCESSFUL",
                                                                                                                                "type": "OWNED"
                                                                                                                            }','description'=>'{
                                                                                                                                "is_closed": false,
                                                                                                                                "status": "PENDING",
                                                                                                                                "currency": "IDR",
                                                                                                                                "owner_id": "6321ac167217a8b97b53ffcc",
                                                                                                                                "external_id": "blitznet-credit",
                                                                                                                                "bank_code": "MANDIRI",
                                                                                                                                "merchant_code": "88608",
                                                                                                                                "name": "blitznet Credit",
                                                                                                                                "account_number": "88608893776481551",
                                                                                                                                "is_single_use": false,
                                                                                                                                "expiration_date": "2053-09-13T17:00:00.000Z",
                                                                                                                                "id": "6321ad9edd14bc0bd97c428c"
                                                                                                                            }'],
            ['payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'], 'name' => 'Blitznet Debit', 'email' => 'repayment@blitznet.co.id', 'payment_provider_ac_id' => '6321c394722c193c525b1cd1', 'environment_type' => PaymentProviderAccount::LIVE_ENV, 'account_type' => PaymentProviderAccount::DEBIT_AC, 'response_data' => '{
                                                                                                                                "account_email": "repayment@blitznet.co.id",
                                                                                                                                "user_id": "6321c394722c193c525b1cd1",
                                                                                                                                "created": "2022-09-14T10:25:26.118Z",
                                                                                                                                "status": "SUCCESSFUL",
                                                                                                                                "type": "OWNED"
                                                                                                                            }','description'=>'{
                                                                                                                                "is_closed": false,
                                                                                                                                "status": "PENDING",
                                                                                                                                "currency": "IDR",
                                                                                                                                "owner_id": "6321c394722c193c525b1cd1",
                                                                                                                                "external_id": "blitznet-debit",
                                                                                                                                "bank_code": "MANDIRI",
                                                                                                                                "merchant_code": "88608",
                                                                                                                                "name": "blitznet Debit",
                                                                                                                                "account_number": "88608893773050330",
                                                                                                                                "is_single_use": false,
                                                                                                                                "expiration_date": "2053-09-13T17:00:00.000Z",
                                                                                                                                "id": "6321c424dd14bcd9677c702e"
                                                                                                                            }'],
            ['payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'], 'name' => 'Koinwork Credit', 'email' => 'koinworks_credit@yopmail.com', 'payment_provider_ac_id' => '631ee67d49ef8e381cc7eb93', 'environment_type' => PaymentProviderAccount::BETA_ENV, 'account_type' => PaymentProviderAccount::CREDIT_AC, 'response_data' => '{
                                                                                                                                                "type": "OWNED",
                                                                                                                                                "status": "SUCCESSFUL",
                                                                                                                                                "created": "2022-09-12T07:57:49.888Z",
                                                                                                                                                "user_id": "631ee67d49ef8e381cc7eb93",
                                                                                                                                                "account_email": "koinworks_credit@yopmail.com"
                                                                                                                                            }','description'=>'{
                                                                                                                                                "is_closed": false,
                                                                                                                                                "status": "ACTIVE",
                                                                                                                                                "currency": "IDR",
                                                                                                                                                "owner_id": "631ee67d49ef8e381cc7eb93",
                                                                                                                                                "external_id": "blitznet-credit",
                                                                                                                                                "bank_code": "MANDIRI",
                                                                                                                                                "merchant_code": "88608",
                                                                                                                                                "name": "blitznet Credit",
                                                                                                                                                "account_number": "886089999434134",
                                                                                                                                                "is_single_use": false,
                                                                                                                                                "expiration_date": "2053-09-13T17:00:00.000Z",
                                                                                                                                                "id": "63216ba7ec992230ed342f99"
                                                                                                                                            }'],
            ['payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'], 'name' => 'Koinwork Debit', 'email' => 'koinworks_debit@yopmail.com', 'payment_provider_ac_id' => '631efaf3722c191783f5d641', 'environment_type' => PaymentProviderAccount::BETA_ENV, 'account_type' => PaymentProviderAccount::DEBIT_AC, 'response_data' => '{
                                                                                                                                            "account_email": "koinworks_debit@yopmail.com",
                                                                                                                                            "user_id": "631efaf3722c191783f5d641",
                                                                                                                                            "created": "2022-09-12T09:25:07.785Z",
                                                                                                                                            "status": "SUCCESSFUL",
                                                                                                                                            "type": "OWNED"
                                                                                                                                        }','description'=>''],
        ]);


        Schema::enableForeignKeyConstraints();
    }
}
