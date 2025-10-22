<?php namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\SupplierModel;
use App\Models\TagModel;
use App\Models\ProductModel;

class Products extends BaseController
{
    protected function formLists(): array
    {
        // Cache lists to keep forms snappy
        $cache = cache();

        $categories = $cache->remember('sel_categories', 600, fn() => (new CategoryModel())->listForSelect());
        $suppliers  = $cache->remember('sel_suppliers',  600, fn() => (new SupplierModel())->listForSelect());
        $tags       = $cache->remember('sel_tags',       600, fn() => (new TagModel())->listForSelect());

        return compact('categories','suppliers','tags');
    }

    public function create()
    {
        return view('products/form', [
            'mode'       => 'create',
            'product'    => null,
            ...$this->formLists(),
        ]);
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]',
            'category_id' => 'required|is_natural_no_zero',
            'supplier_id' => 'required|is_natural_no_zero',
            'price'       => 'required|decimal',
            'tags'        => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save product + pivot tags as needed...
        // (Your save logic here)

        return redirect()->to('/products')->with('msg', 'Product created');
    }

    public function edit($id)
    {
        $product = (new ProductModel())->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Load current tag IDs for preselect (pivot: product_tags product_id/tag_id)
        $db = db_connect();
        $currentTags = $db->table('product_tags')->select('tag_id')->where('product_id', $id)->get()->getResultArray();
        $product['tag_ids'] = array_column($currentTags, 'tag_id');

        return view('products/form', [
            'mode'    => 'edit',
            'product' => $product,
            ...$this->formLists(),
        ]);
    }
}
