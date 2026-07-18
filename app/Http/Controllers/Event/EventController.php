<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Resources\Event\EventResource;
use App\Models\Event\Event;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->search;

        $events = Event::query()
            ->with(['cover', 'category'])
            ->when($search, function ($query, $searchKey) {
                $query->search($searchKey);
            })
            ->paginate(10);

        return $this->respondWithPagination($events, EventResource::class);
    }

    public function detail(string $id)
    {
        try {
            $event = Event::query()
                ->with(['category', 'cover'])
                ->find($id);

            if (!$event) {
                throw new Exception('Event not found.', 404);
            }

            return $this->respondWithItem(EventResource::make($event));
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}
