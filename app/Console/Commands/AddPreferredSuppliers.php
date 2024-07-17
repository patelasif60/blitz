<?php

namespace App\Console\Commands;

use App\Models\InviteBuyer;
use App\Models\PreferredSupplier;
use Illuminate\Console\Command;

class AddPreferredSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addPreferredSuppliers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all_invitee_Supplier = InviteBuyer::join('suppliers', 'suppliers.contact_person_email', '=', 'invite_buyer.user_email')
        ->where('invite_buyer.role_id', 3)
        ->where('invite_buyer.user_id','<>', 1)
        ->get(['invite_buyer.user_id','invite_buyer.user_email','suppliers.id as supplier_id']);
        //dd($all_invitee_Supplier->toArray());
        
        $insert_all = [];
        foreach ($all_invitee_Supplier as $data) { 
            $insert_all[] = array(
                'user_id' => $data->user_id??null,
                'supplier_id' => $data->supplier_id??null,
                'is_active' => 1
            );   
        }
        PreferredSupplier::insert($insert_all);
        echo 'Done';
    }
}
