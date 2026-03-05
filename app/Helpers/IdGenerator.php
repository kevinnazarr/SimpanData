<?php

namespace App\Helpers;

use App\Models\User;
use Carbon\Carbon;

class IdGenerator
{
    /**
     * Generate Custom ID: gi-[type]-[YYMM]-[XX]
     * 
     * @param string $role Roles: 'admin' or 'peserta'
     * @param string|null $jenisKegiatan 'PKL' or 'Magang' (required if role is peserta)
     * @return string
     */
    public static function generate(string $role, ?string $jenisKegiatan = null): string
    {
        $prefix = '';
        
        if ($role === 'admin') {
            $prefix = 'gi-adm';
        } else {
            $prefix = strtolower($jenisKegiatan) === 'pkl' ? 'gi-pkl' : 'gi-mag';
        }

        $now = Carbon::now();
        $datePrefix = $now->format('ym');
        
        $searchPrefix = $prefix . '-' . $datePrefix . '-';
        
        $lastId = User::where('id', 'like', $searchPrefix . '%')
            ->orderByRaw('LENGTH(id) DESC')
            ->orderByRaw('id DESC')
            ->value('id');

        if ($lastId) {
            $parts = explode('-', $lastId);
            $lastNumber = (int) end($parts);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $searchPrefix . $newNumber;
    }
}
