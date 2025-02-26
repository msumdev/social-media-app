<?php

namespace App\Services\Chat\Room;

use App\Http\Requests\Chat\Message\Report\CreateMessageReportRequest;
use App\Http\Resources\Chat\Room\Message\RoomMessageReportResource;
use App\Models\Room\RoomMessageReport;
use App\Models\Room\RoomMessageReportReason;

/**
 * Class RoomMessageReportService
 * @package App\Services\Chat
 */
class RoomMessageReportService
{
    /**
     * @param CreateMessageReportRequest $request
     * @return RoomMessageReportResource
     */
    public function create(CreateMessageReportRequest $request): RoomMessageReportResource
    {
        $report = RoomMessageReport::create([
            'message_id' => $request->message_id,
            'user_id' => auth()->id(),
            'description' => $request->description,
        ]);

        foreach ($request->reasons as $reason) {
            RoomMessageReportReason::create([
                'room_message_report_id' => $report->id,
                'reason_id' => $reason,
            ]);
        }

        return new RoomMessageReportResource($report);
    }
}
