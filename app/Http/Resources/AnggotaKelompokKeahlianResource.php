<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnggotaKelompokKeahlianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kbk_id' => $this->kbk_id,
            'nama_lengkap' => $this->nama_lengkap,
            'jabatan' => $this->jabatan,
            'email' => $this->email,
            'kelompok_keahlian' => new KelompokKeahlianResource($this->whenLoaded('kelompokKeahlian')),
        ];
    }
}
