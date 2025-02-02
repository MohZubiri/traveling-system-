<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function view(Customer $customer, Booking $booking)
    {
        return $customer->id === $booking->customer_id;
    }

    public function viewAny(Customer $customer)
    {
        return true;
    }

    public function create(Customer $customer)
    {
        return true;
    }

    public function update(Customer $customer, Booking $booking)
    {
        return $customer->id === $booking->customer_id && $booking->canModify();
    }

    public function delete(Customer $customer, Booking $booking)
    {
        return $customer->id === $booking->customer_id && $booking->canCancel();
    }
}
