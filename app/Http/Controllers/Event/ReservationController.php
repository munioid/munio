<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\ReservationRequest;
use App\Models\Organization\Organization;
use App\Services\Event\ReservationService;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $organization = Organization::first();
            Filament::setTenant($organization, true);

            $reservation = ReservationService::createReservation($data);

            DB::commit();

            return $this->respondWithArray([
                'code' => $reservation->code,
                'id' => $reservation->id,
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}
