<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 11:50 PM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\PurchaseOrder
 *
 * @property int $id
 * @property int $company_id
 * @property int $supplier_id
 * @property int $warehouse_id
 * @property int $vendor_trucking_id
 * @property string|null $code
 * @property \Carbon\Carbon|null $po_created
 * @property string|null $po_type
 * @property \Carbon\Carbon|null $shipping_date
 * @property string|null $supplier_type
 * @property string|null $walk_in_supplier
 * @property string|null $walk_in_supplier_detail
 * @property string|null $article_code
 * @property string|null $remarks
 * @property string|null $internal_remarks
 * @property string|null $private_remarks
 * @property string|null $status
 * @property float|null $disc_percent
 * @property float|null $disc_value
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read mixed $company_h_id
 * @property-read mixed $h_id
 * @property-read mixed $supplier_h_id
 * @property-read mixed $warehouse_h_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Item[] $items
 * @property-read \App\Models\Supplier $supplier
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PurchaseOrder onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereArticleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereDiscPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereDiscValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereInternalRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder wherePoCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder wherePoType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder wherePrivateRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereShippingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereSupplierType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereVendorTruckingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereWalkInSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereWalkInSupplierDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseOrder whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PurchaseOrder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PurchaseOrder withoutTrashed()
 * @mixin \Eloquent
 */
class PurchaseOrder extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $table = 'purchase_orders';

    protected $dates = ['deleted_at', 'po_created', 'shipping_date'];

    protected $fillable = [
        'code',
        'po_type',
        'po_created',
        'shipping_date',
        'supplier_type',
        'walk_in_supplier',
        'walk_in_supplier_detail',
        'remarks',
        'internal_remarks',
        'private_remarks',
        'status',
        'disc_percent',
        'disc_value',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'supplier_id',
        'vendor_trucking_id',
        'warehouse_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'supplierHId',
        'vendorTruckingHId',
        'warehouseHId',
        'statusI18n',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getSupplierHIdAttribute()
    {
        return HashIds::encode($this->attributes['supplier_id']);
    }

    public function getWarehouseHIdAttribute()
    {
        return HashIds::encode($this->attributes['supplier_id']);
    }

    public function items()
    {
        return $this->morphMany('App\Models\Item', 'itemable');
    }

    public function receipts()
    {
        return null;//$this->hasManyThrough('App\Model\Receipt', 'App\Model\Item', 'itemable_id', 'item_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    public function vendorTrucking()
    {
        return null;//$this->belongsTo('App\Model\VendorTrucking', 'vendor_trucking_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function warehouse()
    {
        return null;//$this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function payments()
    {
        return null;//$this->morphMany('App\Models\Payment', 'payable');
    }

    public function totalAmount()
    {
        $itemAmounts = $this->items->map(function($item){
            return $item->price * $item->to_base_quantity;
        });

        $itemTotalAmount = count($itemAmounts) > 0 ? $itemAmounts->sum() : 0;

        $itemDiscounts = $this->items->map(function ($item) {
            return $item->discounts->map(function ($discount) {
                return $discount->item_disc_value;
            })->all();
        })->flatten();

        $itemDiscountAmount = count($itemDiscounts) > 0 ? $itemDiscounts->sum() : 0;

        $expenseAmounts = $this->expenses->map(function ($expense){
            return $expense->type === 'EXPENSETYPE.ADD' ? $expense->amount : ($expense->amount * -1);
        });

        $expenseTotalAmount = count($expenseAmounts) > 0 ? $expenseAmounts->sum() : 0;

        return $itemTotalAmount + $expenseTotalAmount - $itemDiscountAmount - $this->disc_value;
    }

    public function totalAmountPaid()
    {
        return $this->payments->filter(function ($payment, $key){
            return $payment->status !== 'TRFPAYMENTSTATUS.UNCONFIRMED'
            && $payment->status !== 'GIROPAYMENTSTATUS.WE'
            && $payment->status !== 'PAYMENTTYPE.FR';
        })->sum('total_amount');
    }

    public function expenses()
    {
        return null;//$this->morphMany('App\Models\Expense', 'expensable');
    }

    public function copies()
    {
        return null;//$this->hasMany('App\Models\PurchaseOrderCopy', 'main_po_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->deleted_by = $user->id;
                $model->save();
            }
        });
    }
}