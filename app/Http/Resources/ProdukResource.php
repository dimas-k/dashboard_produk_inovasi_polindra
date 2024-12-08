<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
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
            'kbk_id' => $this->kbk_id,
            'nama_produk' => $this->nama_produk,
            'deskripsi' => $this->deskripsi,
            'gambar' => $this->gambar,
            'inventor' => $this->inventor,
            'inventor_lainnya' => $this->inventor_lainnya,
            'anggota_inventor_lainnya' => $this->anggota_inventor_lainnya,
            'email_inventor' => $this->email_inventor,
            'lampiran' => $this->lampiran,
            'tanggal_submit' => $this->tanggal_submit,
            'tanggal_granted' => $this->tanggal_granted,
            'status' => $this->status,
            'kelompok_keahlian' => new KelompokKeahlianResource($this->kelompokKeahlian), // Relasi dengan KelompokKeahlian
            'anggota' => AnggotaKelompokKeahlianResource::collection($this->anggota), // Relasi dengan AnggotaKelompokKeahlian
            'kelompok_keahlian' => new KelompokKeahlianResource($this->whenLoaded('kelompokKeahlian')),
            'anggota' => $this->whenLoaded('anggota', function () {
                return $this->anggota->map(function ($anggota) {
                    return [
                        'id' => $anggota->id,
                        'nama_lengkap' => $anggota->nama_lengkap,
                        'jabatan' => $anggota->jabatan,
                        'detail' => $anggota->detail // Sertakan jika relasi detail ada
                    ];
                });
            }),
            'anggota' => AnggotaKelompokKeahlianResource::collection($this->whenLoaded('anggota')),
        ];
    }
}
