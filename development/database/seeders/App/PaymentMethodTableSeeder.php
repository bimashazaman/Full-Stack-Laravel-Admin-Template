<?php


namespace Database\Seeders\App;


use App\Models\App\PaymentMethods\PaymentMethod;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'name' => 'Cash',
                'alias' => 'cash',
                'is_default' => 1,
                'status_id' => Status::query()
                    ->where('type','payment_method')
                    ->where('name','status_active')
                    ->first()->id,
                'created_by' => User::query()->first()->id
            ]
        ];
        PaymentMethod::query()->insert($methods);
    }

}