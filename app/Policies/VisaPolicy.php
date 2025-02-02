<?php

namespace App\Policies;

use App\Models\Visa;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisaPolicy
{
    use HandlesAuthorization;

    public function view(Customer $customer, Visa $visa)
    {
        return $customer->id === $visa->customer_id;
    }

    public function viewAny(Customer $customer)
    {
        return true;
    }

    public function create(Customer $customer)
    {
        return true;
    }

    public function update(Customer $customer, Visa $visa)
    {
        return $customer->id === $visa->customer_id;
    }

    public function delete(Customer $customer, Visa $visa)
    {
        return $customer->id === $visa->customer_id;
    }
}
