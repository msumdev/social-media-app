<?php

namespace App\Http\Controllers\Chat\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Message\CreateMessageRequest;
use App\Http\Requests\Chat\Message\DeleteMessageRequest;
use App\Http\Requests\Chat\Message\Report\CreateMessageReportRequest;
use App\Http\Requests\Chat\Rooms\CreateChatRequest;
use App\Http\Resources\Chat\Room\Message\RoomMessageReportResource;
use App\Http\Resources\Chat\Room\Message\RoomMessageResource;
use App\Services\Chat\Room\RoomMessageReportService;
use App\Services\Chat\Room\RoomMessageService;
use Illuminate\Http\JsonResponse;

/**
 * Class ChatRoomMessageController
 * @package App\Http\Controllers\Chat
 */
class RoomMessageReportController extends Controller
{
    public function __construct(private readonly RoomMessageReportService $roomMessageReportService)
    {

    }

    /**
     * @param CreateMessageReportRequest $request
     * @return RoomMessageReportResource
     */
    public function create(CreateMessageReportRequest $request): RoomMessageReportResource
    {
        return $this->roomMessageReportService->create($request);
    }
}
