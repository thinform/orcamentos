<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf_cnpj',
        'email',
        'phone',
        'address',
        'number',
        'complement',
        'neighborhood',
        'zip_code',
        'city',
        'state',
        'notes'
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        
        if ($this->number) {
            $address .= ', ' . $this->number;
        }
        
        if ($this->complement) {
            $address .= ' - ' . $this->complement;
        }
        
        if ($this->neighborhood) {
            $address .= ', ' . $this->neighborhood;
        }
        
        if ($this->zip_code) {
            $address .= ' - CEP: ' . $this->zip_code;
        }
        
        if ($this->city && $this->state) {
            $address .= ' - ' . $this->city . '/' . $this->state;
        }
        
        return $address;
    }

    public function getFormattedCpfCnpjAttribute()
    {
        $doc = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);
        
        if (strlen($doc) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $doc);
        }
        
        if (strlen($doc) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $doc);
        }
        
        return $this->cpf_cnpj;
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        }
        
        if (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        
        return $this->phone;
    }

    public function getFormattedZipCodeAttribute()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->zip_code);
        
        if (strlen($cep) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
        }
        
        return $this->zip_code;
    }
} 