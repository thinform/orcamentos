<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quote_number',
        'client_id',
        'category_id',
        'validity',
        'shipping_cost',
        'additional_cost',
        'notes',
        'status',
        'user_id'
    ];

    protected $casts = [
        'validity' => 'integer',
        'shipping_cost' => 'decimal:2',
        'additional_cost' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'quote_product')
            ->withPivot(['quantity', 'unit_price', 'discount'])
            ->withTimestamps();
    }

    public function history(): HasMany
    {
        return $this->hasMany(QuoteHistory::class)->latest();
    }

    public function getSubtotalAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->unit_price;
        });
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->shipping_cost + $this->additional_cost;
    }

    public function getInstallmentTotalAttribute()
    {
        return $this->total / (1 - 0.18); // 18% de acréscimo
    }

    public function getMonthlyInstallmentAttribute()
    {
        return $this->installment_total / 10;
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
        ][$this->status] ?? 'gray';
    }

    public function getStatusTextAttribute()
    {
        return [
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
        ][$this->status] ?? 'Desconhecido';
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        // Gerar número do orçamento no formato AAAAMMDDHHMMSS
        static::creating(function ($quote) {
            if (!$quote->quote_number) {
                $quote->quote_number = now()->format('YmdHis');
            }
        });

        // Registrar criação no histórico
        static::created(function ($quote) {
            $quote->history()->create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'new_status' => $quote->status,
                'comment' => 'Orçamento criado'
            ]);
        });

        // Registrar atualização no histórico
        static::updated(function ($quote) {
            $changes = $quote->getChanges();
            
            // Remove timestamps dos changes
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                $oldStatus = $quote->getOriginal('status');
                $newStatus = $changes['status'] ?? null;
                
                $quote->history()->create([
                    'user_id' => Auth::id(),
                    'action' => $newStatus ? 'status_changed' : 'updated',
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changes' => $changes,
                    'comment' => $newStatus 
                        ? "Status alterado de {$oldStatus} para {$newStatus}"
                        : 'Orçamento atualizado'
                ]);
            }
        });

        // Registrar exclusão no histórico
        static::deleted(function ($quote) {
            $quote->history()->create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'old_status' => $quote->status,
                'comment' => 'Orçamento excluído'
            ]);
        });
    }
}
