<?php

namespace App\Exports;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    protected $category;

    function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            'D1'    => ['font' => ['bold' => true]],
            'Z1'    => ['font' => ['bold' => true]],
            'AA1'    => ['font' => ['bold' => true]],

            // Style the first row as bold text.
            // 1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //  'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //  'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Name',
            'Variant',
            'Alias',
            'Slug',
            'Unit Name',
            'Weight',
            'Contain',
            'Desc',
            'Images',
            'Videos',

            'Branch',
            'Category',
            'Brand',
            'Tags',

            'COGS',
            'Price',
            'Strikethroughprice',
            'Poin',

            'Low Stock Alert',
            'Active',
            'In Stock',
            'Featured',
            'Promo',
            'Rating',

            'Created by',
            'Updated by',

            'Alert',
            'Stok Terkini',
            'Nilai Stok',
            'Stok Terbeli',
            'Stok Terjual',
            'Total Beli',
            'Total Jual',
            'Margin',
        ];
    }

    public function map($product): array
    {
        $orderitems = OrderItem::get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
        $stokterbeli = $orderitems->where('product_id', $product->id)->sum('p_quantity');
        $stokterjual = $orderitems->where('product_id', $product->id)->sum('quantity');
        $stokterkini = $stokterbeli - $stokterjual;
        $is_low = $stokterkini - $product->low_alert;
        if ($is_low <= 0) {
            $alert = 'LOW';
        } else {
            $alert = 'aman';
        }

        return [
            $product->sku,
            $product->name,
            $product->variant,
            $product->alias,
            $product->slug,
            $product->unit_name,
            $product->weight,
            $product->contain,
            $product->description,
            $product->images,
            $product->embed_videos,

            $product->branch->name,
            $product->category->name,
            $product->brand->name,
            $product->tags,

            $product->cogs,
            $product->price,
            $product->strikethroughprice,
            $product->poin,

            $product->low_alert,
            $product->is_active,
            $product->in_stock,
            $product->is_featured,
            $product->promo,
            $product->rating,

            $product->created_by,
            $product->updated_by,

            $alert,
            $stokterkini,
            $stokterkini * $product->cogs,
            $stokterbeli,
            $stokterjual,
            $orderitems->where('product_id', $product->id)->sum('p_total_amount'),
            $orderitems->where('product_id', $product->id)->sum('total_amount'),
            $orderitems->where('product_id', $product->id)->sum('p_total_amount') - $orderitems->where('product_id', $product->id)->sum('total_amount'),
        ];
    }

    public function collection()
    {
        if ($this->category != null) {
            return Product::where('branch_id', Auth::user()->branch_id)->where('category_id', $this->category)->get();
        } else {
            return Product::where('branch_id', Auth::user()->branch_id)->get();
        }
    }
}
