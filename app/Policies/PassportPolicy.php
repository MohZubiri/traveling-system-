<?php

namespace App\Policies;

use App\Models\Passport;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class PassportPolicy
{
    use HandlesAuthorization;

    public function view(Customer $customer, Passport $passport)
    {
        return $customer->id === $passport->customer_id;
    }

    public function viewAny(Customer $customer)
    {
        return true;
    }

    public function create(Customer $customer)
    {
        return true;
    }

    public function update(Customer $customer, Passport $passport)
    {
        return $customer->id === $passport->customer_id && $passport->canModify();
    }

    public function delete(Customer $customer, Passport $passport)
    {
        return $customer->id === $passport->customer_id && $passport->canModify();
    }
}
