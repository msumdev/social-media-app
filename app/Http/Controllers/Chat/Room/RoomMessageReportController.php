<?php

namespace App\Http\Controllers\Chat\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Message\Report\CreateMessageReportRequest;
use App\Http\Resources\Chat\Room\Message\RoomMessageReportResource;
use App\Services\Chat\Room\RoomMessageReportService;

/**
 * Class ChatRoomMessageController
 */
class RoomMessageReportController extends Controller
{
    public function __construct(private readonly RoomMessageReportService $roomMessageReportService) {}

    public function create(CreateMessageReportRequest $request): RoomMessageReportResource
    {
        return $this->roomMessageReportService->create($request);
    }
}
