<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('title'), fn ($q) =>
                $q->where('title', 'LIKE', '%' . $request->title . '%')
            )
            ->when($request->filled('category_id'), fn ($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->when($request->filled('start_date_registration'), fn ($q) =>
                $q->where('created_at', '>=', Carbon::parse($request->start_date_registration))
            )
            ->when($request->filled('end_date_registration'), fn ($q) =>
                $q->where('created_at', '<=', Carbon::parse($request->end_date_registration))
            )
            ->orderBy('updated_at', 'DESC')
            ->paginate(18)
            ->withQueryString();

        Log::info('Listar produtos.', ['action_user_id' => Auth::id()]);

        return $this->indexView([
            'products' => $products,
            'title' => $request->title,
            'category_id' => $request->category_id,
            'start_date_registration' => $request->start_date_registration,
            'end_date_registration' => $request->end_date_registration,
        ]);
    }

    public function listByCompany(Company $company)
    {
        $products = Product::where('company_id', $company->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        Log::info('Listar produtos da empresa.', [
            'action_user_id' => Auth::id(),
            'company_id' => $company->id
        ]);

        return $this->indexView([
            'products' => $products,
            'company' => $company,
        ]);
    }

    private function indexView(array $data = [])
    {
        return view('products.index', array_merge([
            'menu' => 'products',
            'title' => null,
            'category_id' => null,
            'start_date_registration' => null,
            'end_date_registration' => null,
            'categories' => Category::all(),
            'company' => null,
        ], $data));
    }

    public function show(Product $product)
    {
        Log::info('Visualizar produto.', ['action_user_id' => Auth::id()]);

        return view('products.show', [
            'menu' => 'products',
            'product' => $product,
            'categories' => Category::all(),
            'companies' => Company::all(),
        ]);
    }

    public function create()
    {
        return view('products.create', [
            'menu' => 'products',
            'categories' => Category::all(),
            'companies' => Company::all(),
            'coupons' => Coupon::all(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/imgProducts', $imageName);
                $data['image'] = 'uploads/imgProducts/' . $imageName;
            }

            $product = Product::create($data);

            Log::info('Produto cadastrado.', ['product_id' => $product->id]);

            return redirect()
                ->route('products.show', $product)
                ->with('success', 'Produto cadastrado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar produto.', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Produto não cadastrado!');
        }
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'menu' => 'products',
            'product' => $product,
            'categories' => Category::all(),
            'companies' => Company::all(),
            'coupons' => Coupon::where('company_id', $product->company_id)
                ->where('active', true)
                ->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($product->image && $product->image !== 'uploads/imgSem.jpg') {
                    $oldPath = base_path($product->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $image = $request->file('image');
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/imgProducts', $imageName);
                $data['image'] = 'uploads/imgProducts/' . $imageName;
            } else {
                $data['image'] = $product->image;
            }

            $product->update($data);

            Log::info('Produto atualizado.', ['product_id' => $product->id]);

            return redirect()
                ->route('products.show', $product)
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function toggleActive(Product $product)
    {
        $product->active = !$product->active;
        $product->save();

        Log::info('Status do produto alterado.', [
            'action_user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return back()->with('success', 'Status atualizado!');
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image && $product->image !== 'uploads/imgSem.jpg') {
                $filePath = base_path($product->image);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            $product->delete();

            Log::info('Produto removido.', ['product_id' => $product->id]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto apagado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao apagar produto.', ['error' => $e->getMessage()]);
            return back()->with('error', 'Produto não apagado!');
        }
    }
}
