<?php

namespace App\Http\Controllers;

use App\DTO\CreateProductDTO;
use App\Models\Products;
use App\Models\Categories;
use App\Services\ProductServices;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Exceptions\NotFoundExcept;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductImport;
use App\Services\ImportDataService;


class ProductController extends Controller
{
    public function __construct(
        protected ProductServices $productService,
        protected ImportDataService $importDataService
        )
    {
        $this->productService = $productService;
        $this->importDataService = $importDataService;
    }

    public function Product()
{
    $products = Products::with('category')->get();
    $allcate = Categories::all();



    return view('Products', compact('products', 'allcate'));
}


public function export(Request $request)
{
    $query = Products::with('category');

    $products = $query->get();

    return Excel::download(
        new ProductsExport($products),
        'products.xlsx'
    );
}

public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            app(ProductImport::class),
            $request->file('file')
        );

        return redirect()->back()->with('success', 'Products imported successfully');
    }

public function create(Request $request)
{
    // 1️⃣ Validate
    $request->validate([
        'category_id' => 'required|integer|exists:categories,id',
        'name'        => 'required|string|max:255',
        'code'        => 'required|string|unique:products,code',
        'qty'         => 'required|integer|min:0',
    ]);

    // 2️⃣ Check session
    $userId = session('id');

    if (!$userId) {
        return redirect('/')->with('error', 'Please login first');
    }

    // 3️⃣ Merge userId into request before creating DTO
    $request->merge([
        'create_uid' => $userId,
        'update_uid' => $userId,
    ]);

    // 4️⃣ Create DTO from request
    $dto = CreateProductDTO::fromRequest($request); // ✅ fromRequest not formRequest

    // 5️⃣ Pass DTO to service
    $product = $this->productService->createByDTO($dto);

    return redirect()->back()->with('success', 'Product create successfully');
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'code' => 'required',
        'qty' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
    ]);

    $userId = session('id');

    if (!$userId) {
        throw new NotFoundExcept();
    }


    $data = $request->all();
    $data['userId'] = $userId;


    $this->productService->create($data);

    return redirect()->back()->with('success', 'Product create successfully');

    // Products::create([
    //     'category_id' => $request->category_id,
    //     'name' => $request->name,
    //     'code' => $request->code,
    //     'qty' => $request->qty,
    //     'create_uid' => $userId,
    //     'update_uid' => null,
    // ]);

    // return redirect()->back()->with('success', 'Product created successfully');


}
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'code' => 'required',
        //     'qty' => 'required|integer|min:0',
        //     'category_id' => 'required|exists:categories,id',
        // ]);

        // $product = Products::findOrFail($id);
        // $product->update($request->all());

        // return redirect()->back()->with('success', 'Product updated successfully');

        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'qty' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $userId = session('id');

        if (!$userId) {
            return redirect('/')->with('error', 'Please login first');
        }

        $data = $request->all();
        $data['update_uid'] = $userId;

        $this->productService->update($id, $data);

    return redirect()->back()->with('success', 'Product update successfully');


        }

    public function destroy($id)
    {

    //    Products::findOrFail($id)->delete();

    $this->productService->delete($id);

    return redirect()->back()->with('success', 'Product deleted successfully');
    }
}
