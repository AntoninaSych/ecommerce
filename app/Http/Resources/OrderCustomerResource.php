<?php

namespace App\Http\Resources;

use App\Enums\CustomerStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\DateTime;

class OrderCustomerResource extends JsonResource

{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->customer->first_tname,
            'last_name' => $this->customer->last_name,
            'email' => $this->customer->email,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'phone' => $this->customer->phone
        ];
    }


}