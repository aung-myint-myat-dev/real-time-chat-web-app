<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'conversation_id' => $this->conversation_id,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'created_at' => $this->created_at,

            // only included if messageReads relationship is loaded
            'read_at' => $this->whenLoaded('messageReads', function () {
                return optional($this->messageReads->first())->read_at;
            }),
        ];
    }
}
