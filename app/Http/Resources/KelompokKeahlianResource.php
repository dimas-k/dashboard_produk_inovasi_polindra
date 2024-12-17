<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelompokKeahlianResource extends JsonResource
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
            'nama_kbk' => $this->nama_kbk,
            'jurusan' => $this->jurusan,
            'deskripsi' => $this->deskripsi,
            'produks' => ProdukResource::collection($this->whenLoaded('produk')),
            'penelitians' => PenelitianResource::collection($this->whenLoaded('penelitian')),
            'users' => UserResource::collection($this->whenLoaded('users'))
        ];
    }
}
