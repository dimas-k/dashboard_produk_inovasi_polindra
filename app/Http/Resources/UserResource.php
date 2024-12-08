<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_lengkap' => $this->nama_lengkap,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'no_hp' => $this->no_hp,
            'kbk_id' => $this->kbk_id,
            'role' => $this->role,
            'username' => $this->username,
            'email' => $this->email,
        ];
    }
}

