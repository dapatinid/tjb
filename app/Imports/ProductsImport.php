<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([

            'sku' => $row[0],
            'name' => $row[1],
            'variant' => $row[2],
            'alias' => $row[3],
            'slug' => $row[4],
            'unit_name' => $row[5],
            'weight' => $row[6],
            'contain' => $row[7],
            'description' => $row[8],
            'images' => $row[9],
            'embed_videos' => $row[10],

            'branch_id' => $row[11],
            'category_id' => $row[12],
            'brand_id' => $row[13],
            'tags' => $row[14],

            'cogs' => $row[15],
            'price' => $row[16],
            'strikethroughprice' => $row[17],
            'poin' => $row[18],

            'low_alert' => $row[19],
            'is_active' => $row[20],
            'in_stock' => $row[21],
            'is_featured' => $row[22],
            'promo' => $row[23],
            'rating' => $row[24],

            'created_by' => $row[25],
            'updated_by' => $row[26],

        ]);
    }
}
