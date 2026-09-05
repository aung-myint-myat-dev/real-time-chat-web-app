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
        $authId = $request->user()?->id;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'conversation_id' => $this->conversation_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'created_at' => $this->created_at,
            'edited_at' => $this->edited_at,
            'is_read' => $this->whenLoaded('reads', function () use ($authId) {
                if ($this->user_id === $authId) {
                    return false;
                }

                $lastReadId = $this->viewer_last_read_message_id;

                if ($lastReadId && $this->id <= (int) $lastReadId) {
                    return true;
                }

                return $this->reads->contains('user_id', $authId);
            }),
            'seen_at' => $this->whenLoaded('reads', function () {
                return optional($this->reads->firstWhere('user_id', '!=', $this->user_id))->read_at;
            }),
        ];
    }
}
