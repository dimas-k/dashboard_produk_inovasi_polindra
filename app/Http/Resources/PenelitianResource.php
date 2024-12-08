<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenelitianResource extends JsonResource
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
            'judul' => $this->judul,
            'abstrak' => $this->abstrak,
            'gambar' => $this->gambar,
            'penulis' => $this->penulis,
            'penulis_lainnya' => $this->penulis_lainnya,
            'email_penulis' => $this->email_penulis,
            'penulis_korespondensi' => $this->penulis_korespondensi,
            'penulis_bersama' => $this->penulis_bersama,
            'lampiran' => $this->lampiran,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'status' => $this->tanggal_publikasi,
            'kelompokKeahlian' => $this->whenLoaded('kelompokKeahlian'),
        ];
    }
}
