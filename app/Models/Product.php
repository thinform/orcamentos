<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'cost_price',
        'profit_margin',
        'sale_price',
        'installment_fee',
        'installment_price',
        'brand',
        'supplier',
        'unit',
        'height',
        'width',
        'depth',
        'weight',
        'supplier_link',
        'image',
        'category_id',
        'active'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'installment_fee' => 'decimal:2',
        'installment_price' => 'decimal:2',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'weight' => 'decimal:2',
        'active' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class, 'quote_product')
            ->withPivot(['quantity', 'unit_price', 'discount'])
            ->withTimestamps();
    }

    public function quoteItems()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function calculateSalePrice(): float
    {
        if (!$this->cost_price || !$this->profit_margin) {
            return 0;
        }

        // Valor à vista = custo / (1 - margem%)
        $margin = $this->profit_margin / 100;
        return ceil($this->cost_price / (1 - $margin) * 100) / 100;
    }

    public function calculateInstallmentPrice(): float
    {
        $salePrice = $this->calculateSalePrice();
        if (!$salePrice) {
            return 0;
        }

        // Valor parcelado = valor à vista / (1 - 0.18)
        return ceil($salePrice / (1 - 0.18) * 100) / 100;
    }

    public function getInstallmentValueAttribute(): float
    {
        return $this->installment_price / 10;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->isDirty(['cost_price', 'profit_margin'])) {
                $product->sale_price = $product->calculateSalePrice();
                $product->installment_price = $product->calculateInstallmentPrice();
            }
        });
    }

    // Mutators
    public function setCostPriceAttribute($value)
    {
        $this->attributes['cost_price'] = $this->formatCurrency($value);
        $this->calculatePrices();
    }

    public function setProfitMarginAttribute($value)
    {
        $this->attributes['profit_margin'] = $value;
        $this->calculatePrices();
    }

    public function setInstallmentFeeAttribute($value)
    {
        $this->attributes['installment_fee'] = $value;
        $this->calculatePrices();
    }

    // Accessors
    public function getFormattedCostPriceAttribute()
    {
        return 'R$ ' . number_format($this->cost_price, 2, ',', '.');
    }

    public function getFormattedSalePriceAttribute()
    {
        return 'R$ ' . number_format($this->sale_price, 2, ',', '.');
    }

    public function getFormattedInstallmentPriceAttribute()
    {
        return 'R$ ' . number_format($this->installment_price, 2, ',', '.');
    }

    public function getMonthlyInstallmentAttribute()
    {
        return 'R$ ' . number_format($this->installment_price / 10, 2, ',', '.');
    }

    // Métodos auxiliares
    private function calculatePrices()
    {
        // Calcula o preço de venda (custo + margem de lucro)
        if (isset($this->attributes['cost_price']) && isset($this->attributes['profit_margin'])) {
            $cost = $this->attributes['cost_price'];
            $margin = $this->attributes['profit_margin'];
            $this->attributes['sale_price'] = $cost * (1 + ($margin / 100));
        }

        // Calcula o preço parcelado
        if (isset($this->attributes['sale_price']) && isset($this->attributes['installment_fee'])) {
            $sale = $this->attributes['sale_price'];
            $fee = $this->attributes['installment_fee'];
            $this->attributes['installment_price'] = $sale / (1 - ($fee / 100));
        }
    }

    private function formatCurrency($value)
    {
        if (is_string($value)) {
            // Remove R$ e qualquer caractere que não seja número ou ponto
            $value = preg_replace('/[^0-9.]/', '', $value);
        }
        return (float) $value;
    }
}
