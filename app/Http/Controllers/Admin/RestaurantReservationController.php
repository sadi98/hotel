<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class RestaurantReservationController extends Controller
{
    public function index(): View { return view('admin.restaurant.reservations.index',['reservations'=>TableReservation::with('restaurantTable')->latest('reservation_start')->paginate(20)]); }
    public function update(Request $request, TableReservation $reservation): RedirectResponse { $data=$request->validate(['status'=>['required',Rule::in([TableReservation::STATUS_CONFIRMED,TableReservation::STATUS_SEATED,TableReservation::STATUS_COMPLETED,TableReservation::STATUS_CANCELLED,TableReservation::STATUS_NO_SHOW])]]);$reservation->update($data);return back()->with('success','Reservation updated.'); }
}
