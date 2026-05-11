<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ClickEvent;
use App\Traits\AuditsControllerActions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class SiteController extends Controller
{
    use AuditsControllerActions;
    
    public function index(Request $request)
    {
        try {
            /**
             * 🧹 Limpeza automática (1x por dia)
             */
            if (!cache()->has('cleanup_done_today')) {
                
                $deletedClicks = ClickEvent::deleteOld();
                $deletedCoupons = Coupon::deleteOld();

                Log::info('Limpeza de registros antigos executada.', [
                    'clicks_apagados'  => $deletedClicks,
                    'coupons_apagados'=> $deletedCoupons,
                    'data'            => now()->toDateTimeString(),
                ]);

                $this->audit( 'system.cleanup.executed', null, [],[
                        'clicks_apagados'  => $deletedClicks,
                        'coupons_apagados'=> $deletedCoupons,
                    ],['system']
                );

                cache()->put('cleanup_done_today', true, now()->endOfDay());
            }

            /**
             * 👁️ Registro de visita (1x por sessão)
             */
            if (!session()->has('site_visited')) {
                
                ClickEvent::create([
                    'action' => 'visit_site',
                    'source' => 'web',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $this->audit('site.visit');

                session(['site_visited' => true]);
            }

            /**
             * 🏷️ Companies + Categories (MEGA MENU)
             * -> ESSENCIAL para evitar null no Blade
             */
            $companys = Company::with(['categories' => function ($query) {
                $query->orderBy('name');
            }])
                ->orderBy('name')
                ->get();

            /**
             * Disponibiliza globalmente para o header
             */
            view()->share('companys', $companys);

            /**
             * 🦶 Produtos para o footer (ex: últimas ofertas)
             */
            $footerProducts = cache()->remember(
                'footer_products',
                now()->addMinutes(30),
                function () {
                    return \App\Models\Product::whereNotNull('promo_price')
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();
                }
            );
            
            /**
             * Compartilha globalmente
             */
            view()->share('footerProducts', $footerProducts);

            return $this->getProducts($request);
            
        } catch (Exception $e) {
            
            Log::error('Erro ao processar index.', [
                'mensagem' => $e->getMessage(),
                'linha'    => $e->getLine(),
                'arquivo'  => $e->getFile(),
            ]);

            $this->auditError('site.index.error', $e->getMessage());

            abort(500, 'Erro interno no servidor.');
        }
    }
    
    public function byCompanyCategory(Company $company, Category $category, Request $request)
    {
        $request->merge(['category' => $category->id,]);
    
        Log::info('Lista de todos os produtos da - ' . $company->name . ' - da categoria - ' . $category->name);
    
        $response = $this->getProducts($request, $company->id);
    
        // Se for AJAX, retorna direto
        if ($request->ajax()) {
            return $response;
        }
    
        // Se for view, injeta os dados do breadcrumb
        return $response->with([
            'selectedCompany'  => $company,
            'selectedCategory' => $category,
        ]);
    }

    // Produtos por empresa
    public function byCompany(Company $company, Request $request)
    {
        Log::info('Lista de todos os produtos da - ' . $company->name);
        return $this->getProducts($request, $company->id);
    }

    public function product_show(string $slug, Request $request)
    {
        Log::info('Acesso a página Product_show!');
        
        $product = Product::with(['company', 'category'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    
        $products = Product::with('company')
            ->active()
            ->latest()
            ->paginate(8);
    
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->active()
            ->where('id', '!=', $product->id)
            ->with('company')
            ->get();
    
        $companys = Company::all();
        
        ClickEvent::create([
            'action'     => 'view_product',
            'product_id' => $product->id,
            'company_id' => $product->company_id,
            'source'     => 'web',
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->auditView('product.view', $product);

        //Log::info('Visualizou o produto: ' . $slug);
        Log::info('Visualizou o produto: ' . $product->id);
    
        return view('site.show', [
            'product'          => $product,
            'products'         => $products,
            'companys'         => $companys,
            'relatedProducts'  => $relatedProducts,
            'selectedCompany'  => null,
            'footerProducts'   => $this->footerProduct(),
        ]);
    }

    public function ofertasDoDia(Request $request)
    {
        Log::info('Acesso a ofertas Do Dia!');
             
        $request->merge(['today' => 1]);
    
        $response = $this->index($request);
    
        // 🔥 Se for AJAX, retorna direto (string)
        if ($request->ajax()) {
            return $response;
        }
    
        // ✅ Só injeta breadcrumb quando for View
        return $response->with([
            'breadcrumbPage' => 'Ofertas do Dia',
        ]);
    }

    public function about()
    {
        Log::info('Acesso a página sobre!');
        
        return view('site.about', [
            'posts'    => Blog::where('published', 1)->latest()->limit(5)->get(),
            'product'  => Product::whereNotNull('promo_price')->latest()->first(),
            'companys' => Company::orderBy('name')->get(),
        ]);
    }

    public function privacy()
    {
        Log::info('Acesso a página privacy!');
        
        return view('site.privacy', [
            'posts'    => Blog::where('published', 1)->latest()->limit(5)->get(),
            'product'  => Product::whereNotNull('promo_price')->latest()->first(),
            'companys' => Company::orderBy('name')->get(),
        ]);
    }

    public function terms()
    {
        Log::info('Acesso a página termos!');
        
        return view('site.terms', [
            'posts'    => Blog::where('published', 1)->latest()->limit(5)->get(),
            'product'  => Product::whereNotNull('promo_price')->latest()->first(),
            'companys' => Company::orderBy('name')->get(),
        ]);
    }

    public function blog_view()
    {
        Log::info('Acesso a página Blog_view!');
        
        $posts = Blog::where('published', 1)
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($post) {
                $post->published_at = $post->published_at ? Carbon::parse($post->published_at) : null;
                return $post;
            });

        $companys = Company::all();
        $products = Product::all();

        return view('site.blog_view', [
            'posts' => $posts,
            'companys' => $companys,
            'products' => $products,
            'footerProducts' => $this->footerProduct(),
        ]);
    }

    public function blog_show(Blog $post)
    {
        Log::info('Acesso a página Blog_show!');
        
        $relatedPosts = Blog::where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        $product = Product::inRandomOrder()->first();

        $posts = Blog::all();
        $companys = Company::all();
        $products = Product::all();

        return view('site.blog_show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'product' => $product,
            'posts' => $posts,
            'companys' => $companys,
            'products' => $products,
            'footerProducts' => $this->footerProduct(),
        ]);
    }

    public function coupun_view(Request $request, $companyId = null)
    {
        Log::info('Acesso a página Coupun_view!');
         
        try {
            $selectedCompany = null;
            $query = Coupon::where('active', true);

            if ($companyId) {
                $query->where('company_id', $companyId);
                $selectedCompany = Company::find($companyId);
            }

            $coupons = $query->orderBy('updated_at', 'DESC')->paginate(9);
            $products = Product::all();

            $companys = Company::whereHas('coupons', function ($q) {
                $q->where('active', true);
            })->get();

            return view('site.coupun', compact('coupons', 'companys', 'products', 'selectedCompany'))
                ->with('footerProducts', $this->footerProduct());
                
        } catch (Exception $e) {
            Log::error('Erro ao carregar cupons: ' . $e->getMessage(), [
                'company_id' => $companyId,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('site.home')->with('error', 'Ocorreu um problema ao carregar os cupons.');
        }
    }

    public function category_view()
    {
        Log::info('Acesso a página Category_view!');
        
        // Lista todas as categorias (menu)
        $categorys = Category::where('active', 1)
            ->with(['products' => function ($query) {$query->orderBy('updated_at', 'DESC')->limit(1);}])
            ->get();
    
        $companys = Company::all();
        $products = Product::all();
    
        return view('site.category_view', [
            'categorys' => $categorys,
            'companys' => $companys,
            'products'  => $products,
            'footerProducts' => $this->footerProduct(),
        ]);
    }
    
    public function category_show(Category $category)
    {
        // Lista todas as categorias (menu)
        $categorys = Category::where('active', 1)->get();
        
        // Salvar log
        Log::info('Visualizar as categoria.', ['category_id' => $category->slug]);
        
        $companys = Company::all();
    
        // Produtos APENAS da categoria clicada
        $products = $category->products()->orderBy('updated_at', 'DESC')->get();
    
        return view('site.category_view', [
            'categorys' => $categorys,
            'category'  => $category,
            'companys' => $companys,
            'products'  => $products,
            'footerProducts' => $this->footerProduct(),
        ]);
    }

    private function getProducts(Request $request, $companyId = null)
    {
        $search      = $request->input('search');
        $perPage     = (int) $request->input('perPage', 8);
        $todayFilter = $request->boolean('today');
        $categoryId  = $request->input('category');
        $page        = (int) $request->input('page', 1);
    
        $query = Product::with('company')
            ->where('active', true)
            ->orderBy('updated_at', 'DESC');
    
        // ✅ Filtro "OFERTAS DO DIA"
        if ($todayFilter) {
            $query->whereDate('updated_at', now()->toDateString());
        }
    
        // 🔍 Filtro de busca
        if (!empty($search)) {
            $query->where('title', 'like', "%{$search}%");
        }
    
        // 📂 Filtro por categoria
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
    
        // 🏢 Filtro por empresa
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
    
        // 🔢 Paginação manual
        $totalProducts = (clone $query)->count();
    
        $products = $query
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    
        $hasMore = ($page * $perPage) < $totalProducts;
    
        // 🏢 Empresas com produtos
        $companys = Company::whereHas('products')
            ->orderBy('name', 'asc')
            ->get();
    
        $selectedCompany = $companyId ? Company::find($companyId) : null;
    
        // 🔄 Retorno AJAX (scroll infinito)
        if ($request->ajax()) {
            return view('site.partials.products', compact('products'))->render();
        }
    
        // 🏠 Retorno padrão
        return view('site.home', [
            'products'         => $products ?? collect(),
            'companys'         => $companys ?? collect(),
            'hasMore'          => $hasMore ?? false,
            'perPage'          => $perPage ?? 8,
            'search'           => $search ?? '',
            'selectedCompany'  => $selectedCompany ?? null,
            'today'            => $todayFilter ? 1 : 0,
            'categoryId'       => $categoryId ?? null,
            'page'             => $page ?? 1,
            'breadcrumbPage'   => $todayFilter ? 'Ofertas do Dia' : null,
            'footerProducts'   => $this->footerProduct(),
        ]);

    }

    public function product_click(Product $product, Request $request)
    {
        if ($this->isBot($request)) {
            
            Log::info('🤖 BOT ignorado (product_click)', [
                'product_id' => $product->id,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
    
            return response()->noContent();
        }
    
        ClickEvent::create([
            'action'     => 'click',
            'product_id' => $product->id,
            'source'     => 'web',
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->audit(
            'product.click',
            $product,
            [],
            ['ip' => $request->ip()],
            ['click']
        );

    
        Log::info('🖱️ Clique HUMANO registrad!o', [
            'product_id' => $product->id,
            'ip'         => $request->ip(),
        ]);
    
        return redirect()->away($product->link);
    }

    public function coupon_click(Coupon $coupon, Request $request)
    {
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (coupon_click)', [
                'coupon_id' => $coupon->id,
            ]);
    
            return response()->noContent();
        }
    
        ClickEvent::create([
            'action'     => 'click_coupon',
            'coupon_id'  => $coupon->id,
            'company_id' => $coupon->company_id,
            'source'     => 'web',
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->audit('coupon.click', $coupon, [], [], ['click']);

        return redirect()->away($coupon->link);
    }

    public function company_click(Company $company, Request $request)
    {
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (company_click)', [
                'company' => $company->name,
            ]);
    
            return response()->noContent();
        }
    
        ClickEvent::create([
            'action'     => 'click_company',
            'company_id' => $company->id,
            'source'     => 'web',
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

    
        return redirect()->away($company->link);
    }

    public function watsapp_click(Request $request)
    {
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (telegram_click)');
            return response()->noContent();
        }

        ClickEvent::create([
            'action' => 'click_WhatsApp',
            'source' => 'social',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->away('https://chat.whatsapp.com/IsH4Li5ktHGCq0xMt2wfaY?mode=ems_copy_t');
    }

    public function instagram_click(Request $request)
    {
        
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (Instagram_click)');
            return response()->noContent();
        }

        ClickEvent::create([
            'action' => 'click_Instagram',
            'source' => 'social',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->away('https://www.instagram.com/achadosmulhersabia');
    }

    public function telegram_click(Request $request)
    {
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (telegram_click)');
            return response()->noContent();
        }

        ClickEvent::create([
            'action' => 'click_Telegram',
            'source' => 'social',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->away('https://t.me/achadosmulhersabia');
    }

    public function pinterest_click(Request $request)
    {

        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (Pinterest_click)');
            return response()->noContent();
        }
        
        ClickEvent::create([
            'action' => 'click_Pinterest',
            'source' => 'social',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away('https://br.pinterest.com/achadosmulhersabia');
    }

    public function visibilize_click(Request $request)
    {
        if ($this->isBot($request)) {
            Log::info('🤖 BOT ignorado (telegram_click)');
            return response()->noContent();
        }

        ClickEvent::create([
            'action' => 'click_Visibilize',
            'source' => 'social',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away('https://visibilize.com.br/');
    }
    
    private function footerProduct()
    {
        try {
            return cache()->remember(
                'footer_products_today',
                now()->addMinutes(30),
                function () {
                    return Product::active()
                        ->whereDate('updated_at', now()->toDateString())
                        ->orderBy('updated_at', 'DESC')
                        ->limit(6) // footer não precisa de 100
                        ->get();
                }
            );
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar produtos do footer', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
    
            return collect(); // SEMPRE retorna Collection
        }
    }
    
    private function isBot(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');
    
        $bots = [
            'googlebot',
            'bingbot',
            'semrush',
            'ahrefs',
            'facebookexternalhit',
            'crawler',
            'bot',
        ];
    
        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }
    
        return false;
    }

    public function __construct()
    {
        view()->share('footerProducts', $this->footerProduct());
    }
}
