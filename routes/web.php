<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GrupoVipController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ClickEventController;
use App\Http\Controllers\AuditEventController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\KeywordProductController;
use App\Http\Controllers\KeywordCompanyController;
use App\Http\Controllers\PublicProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema; // Adicionado para as rotas de manutenção
use Illuminate\Support\Facades\DB;     // Adicionado para as rotas de manutenção
use Illuminate\Support\Facades\Artisan;// Adicionado para as rotas de manutenção

/*
|--------------------------------------------------------------------------
| Rotas de Teste e Debug
|--------------------------------------------------------------------------
*/
Route::get('/test-path', function () {
    $relativePath = 'uploads/imgProducts/68bf5bcf923a4.jpg';
    $absolutePath = public_path($relativePath);

    return response()->json([
        'public_path'   => public_path(),
        'relative_path' => $relativePath,
        'absolute_path' => $absolutePath,
        'exists'        => file_exists($absolutePath),
        'readable'      => is_readable($absolutePath),
    ]);
});
;

Route::get('/product/{slug}', [PublicProductController::class, 'redirect'])
    ->name('product.redirect');

Route::get('/product/click/{product}', [PublicProductController::class, 'click'])
    ->name('product.click');
/*
|--------------------------------------------------------------------------
| Autenticação
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

/*
|--------------------------------------------------------------------------
| Painel Administrativo (Protegido por Login)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth'], function () {
    
    // Página inicial do administrativo
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:dashboard');
    
    // Perfil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show')->middleware('permission:show-profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:edit-profile');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:edit-profile');
        Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit_password')->middleware('permission:edit-password-profile');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update_password')->middleware('permission:edit-password-profile');
    });

    // Usuários
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('permission:index-user');
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-user');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-user');
        Route::post('/', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-user');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-user');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-user');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:destroy-user');

        Route::get('/{user}/edit-password', [UserController::class, 'editPassword'])->name('users.edit_password')->middleware('permission:edit-password-user');
        Route::put('/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update_password')->middleware('permission:edit-password-user');

        Route::get('/generate-pdf-user/{user}', [UserController::class, 'generatePdfUser'])->name('users.generate-pdf-user')->middleware('permission:generate-pdf-user');
        Route::get('/generate-pdf/users', [UserController::class, 'generatePdfUsers'])->name('users.generate-pdf-users')->middleware('permission:generate-pdf-users');
        Route::get('/generate-csv/users', [UserController::class, 'generateCSVUsers'])->name('users.generate-csv-users')->middleware('permission:generate-csv-users');
    });

    // Papéis 
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:index-role');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-role');
        Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:show-role');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-role');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-role');
        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit-role');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:destroy-role');
    });

    // Permissão do papel
    Route::prefix('role-permissions')->group(function () {
        Route::get('/{role}', [RolePermissionController::class, 'index'])->name('role-permissions.index')->middleware('permission:index-role-permission');
        Route::get('/{role}/{permission}', [RolePermissionController::class, 'update'])->name('role-permissions.update')->middleware('permission:update-role-permission');
    });

    // Permissão
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:index-permission');
        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permission');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:show-permission');
        Route::post('/', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permission');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit-permission');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit-permission');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:destroy-permission');
    });

    // Lojas / Empresas (Companies) - ✅ CORRIGIDO
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])
            ->name('companies.index')
            ->middleware('permission:index-company');

        Route::get('/create', [CompanyController::class, 'create'])
            ->name('companies.create')
            ->middleware('permission:create-company');

        Route::post('/', [CompanyController::class, 'store'])
            ->name('companies.store')
            ->middleware('permission:create-company'); 

        Route::get('/{company}', [CompanyController::class, 'show'])
            ->name('companies.show')
            ->middleware('permission:show-company'); 

        Route::get('/{company}/edit', [CompanyController::class, 'edit'])
            ->name('companies.edit')
            ->middleware('permission:edit-company');

        Route::put('/{company}', [CompanyController::class, 'update'])
            ->name('companies.update')
            ->middleware('permission:edit-company');

        Route::delete('/{company}', [CompanyController::class, 'destroy'])
            ->name('companies.destroy')
            ->middleware('permission:destroy-company');
    });

    // Categorias (Categories)
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:index-category');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:store-category');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:create-category');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:show-category');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:edit-category');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit-category');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:destroy-category');
    });
    
    // Blogs
    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('blogs.index')->middleware('permission:index-blog');
        Route::get('/create', [BlogController::class, 'create'])->name('blogs.create')->middleware('permission:create-blog');
        Route::post('/', [BlogController::class, 'store'])->name('blogs.store')->middleware('permission:show-blog');
        Route::get('/{blog}', [BlogController::class, 'show'])->name('blogs.show')->middleware('permission:create-blog');
        Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit')->middleware('permission:edit-blog');
        Route::put('/{blog}', [BlogController::class, 'update'])->name('blogs.update')->middleware('permission:edit-blog');
        Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy')->middleware('permission:destroy-blog');
    });

    // Cupons
    Route::prefix('coupons')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('coupons.index')->middleware('permission:index-coupon');
        Route::get('/create', [CouponController::class, 'create'])->name('coupons.create')->middleware('permission:create-coupon');
        Route::post('/', [CouponController::class, 'store'])->name('coupons.store')->middleware('permission:store-coupon');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('coupons.show')->middleware('permission:show-coupon');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit')->middleware('permission:edit-coupon');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('coupons.update')->middleware('permission:update-coupon');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy')->middleware('permission:destroy-coupon');

        // Listar cupons de uma empresa
        Route::get('/{company}/coupons', [CouponController::class, 'listByCompany'])->name('coupons.byCompany')->middleware('permission:company-coupon');

        // Mudar o Status do Botão
        Route::patch('/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle')->middleware('permission:toggle-coupon');
    });

    // Produtos
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index')->middleware('permission:index-product');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:create-product');
        Route::post('/', [ProductController::class, 'store'])->name('products.store')->middleware('permission:store-product');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:show-product');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:edit-product');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:edit-product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:destroy-product');

        // Listar produtos de uma empresa
        Route::get('/{company}/products', [ProductController::class, 'listByCompany'])->name('products.company')->middleware('permission:company-product');
        
        // Mudar status do produto
        Route::put('/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active')->middleware('permission:edit-product');
    });
    
    // Keyword Products (Vinculadas a Categorias)
    Route::prefix('keyword-products')->group(function () {
        Route::get('/', [KeywordProductController::class, 'index'])
            ->name('keyword_products.index')
            ->middleware('permission:index-keyword-product');

        Route::get('/create', [KeywordProductController::class, 'create'])
            ->name('keyword_products.create')
            ->middleware('permission:create-keyword-product');

        Route::post('/', [KeywordProductController::class, 'store'])
            ->name('keyword_products.store')
            ->middleware('permission:create-keyword-product');

        Route::get('/{keywordProduct}', [KeywordProductController::class, 'show'])
            ->name('keyword_products.show')
            ->middleware('permission:show-keyword-product');

        Route::get('/{keywordProduct}/edit', [KeywordProductController::class, 'edit'])
            ->name('keyword_products.edit')
            ->middleware('permission:edit-keyword-product');

        Route::put('/{keywordProduct}', [KeywordProductController::class, 'update'])
            ->name('keyword_products.update')
            ->middleware('permission:edit-keyword-product');

        Route::delete('/{keywordProduct}', [KeywordProductController::class, 'destroy'])
            ->name('keyword_products.destroy')
            ->middleware('permission:destroy-keyword-product');
    });
    
    // Keyword Companies (Vinculadas a Empresas/Lojas)
    Route::prefix('keyword-companies')->group(function () {
        Route::get('/', [KeywordCompanyController::class, 'index'])
            ->name('keyword_companies.index')
            ->middleware('permission:index-keyword-company');

        Route::get('/create', [KeywordCompanyController::class, 'create'])
            ->name('keyword_companies.create')
            ->middleware('permission:create-keyword-company');

        Route::post('/', [KeywordCompanyController::class, 'store'])
            ->name('keyword_companies.store')
            ->middleware('permission:create-keyword-company');

        Route::get('/{keywordCompany}', [KeywordCompanyController::class, 'show'])
            ->name('keyword_companies.show')
            ->middleware('permission:show-keyword-company');

        Route::get('/{keywordCompany}/edit', [KeywordCompanyController::class, 'edit'])
            ->name('keyword_companies.edit')
            ->middleware('permission:edit-keyword-company');

        Route::put('/{keywordCompany}', [KeywordCompanyController::class, 'update'])
            ->name('keyword_companies.update')
            ->middleware('permission:edit-keyword-company');

        Route::delete('/{keywordCompany}', [KeywordCompanyController::class, 'destroy'])
            ->name('keyword_companies.destroy')
            ->middleware('permission:destroy-keyword-company');
    });

    // Click Events
    Route::prefix('click-events')->group(function () {
    
        Route::get('/', [ClickEventController::class, 'index'])
            ->name('click-events.index')
            ->middleware('permission:index-click-event');
    
        Route::get('/{clickEvent}', [ClickEventController::class, 'show'])
            ->name('click-events.show')
            ->middleware('permission:show-click-event');
    
        // Geração de PDF
        Route::get('/generate-pdf/{clickEvent}', [ClickEventController::class, 'generatePdfEvent'])
            ->name('click-events.generate-pdf-event')
            ->middleware('permission:generate-pdf-click-event');
    
        Route::get('/generate-pdf', [ClickEventController::class, 'generatePdfEvents'])
            ->name('click-events.generate-pdf-events')
            ->middleware('permission:generate-pdf-click-events');
    
        // Geração de CSV
        Route::get('/generate-csv', [ClickEventController::class, 'generateCSVEvents'])
            ->name('click-events.generate-csv-events')
            ->middleware('permission:generate-csv-click-events');
    });

    // 🛡️ Audit Events (Auditoria e Métricas)
    Route::prefix('audit-events')->group(function () {
    
        Route::get('/', [AuditEventController::class, 'index'])
            ->name('audit-events.index')
            ->middleware('permission:index-audit-event');
    
        Route::get('/{auditEvent}', [AuditEventController::class, 'show'])
            ->name('audit-events.show')
            ->middleware('permission:show-audit-event');
    
        // 📄 Geração de PDF
        Route::get('/generate-pdf/{auditEvent}', [AuditEventController::class, 'generatePdfEvent'])
            ->name('audit-events.generate-pdf-event')
            ->middleware('permission:generate-pdf-audit-event');
    
        Route::get('/generate-pdf', [AuditEventController::class, 'generatePdfEvents'])
            ->name('audit-events.generate-pdf-events')
            ->middleware('permission:generate-pdf-audit-events');
    
        // 📑 Geração de CSV
        Route::get('/generate-csv', [AuditEventController::class, 'generateCSVEvents'])
            ->name('audit-events.generate-csv-events')
            ->middleware('permission:generate-csv-audit-events');
    });

});

/*
|--------------------------------------------------------------------------
| Rotas do Site (Públicas)
|--------------------------------------------------------------------------
*/

/* ================= ROTAS FIXAS (PRIMEIRO) ================= */

// Home
Route::get('/', [SiteController::class, 'index'])->name('site.home');

// Páginas fixas
Route::get('/blog', [SiteController::class, 'blog_view'])->name('site.blog_view');
Route::get('/category', [SiteController::class, 'category_view'])->name('site.category_view');
Route::get('/coupun', [SiteController::class, 'coupun_view'])->name('site.coupun_view');

// Grupo VIP
Route::get('/grupo-vip', fn () => view('site.grupo_vip'));
Route::get('/grupo-vip/redirect', [GrupoVipController::class, 'redirect'])->name('grupo.vip.redirect');

/* ================= ROTAS DE CLICK ================= */

Route::get('/click/product/{product}', [SiteController::class, 'product_click'])->name('product.click');
Route::get('/click/coupon/{coupon}', [SiteController::class, 'coupon_click'])->name('coupon.click');
Route::get('/click/company/{company}', [SiteController::class, 'company_click'])->name('company.click');
Route::get('/click/watsapp', [SiteController::class, 'watsapp_click'])->name('watsapp.click');
Route::get('/click/instagram', [SiteController::class, 'instagram_click'])->name('instagram.click');
Route::get('/click/pinterest', [SiteController::class, 'pinterest_click'])->name('pinterest.click');
Route::get('/click/telegram', [SiteController::class, 'telegram_click'])->name('telegram.click');
Route::get('/click/visibilize', [SiteController::class, 'visibilize_click'])->name('visibilize.click');

/* ================= ROTAS COM SLUG ESPECÍFICO ================= */

Route::get('/ofertas-do-dia', [SiteController::class, 'ofertasDoDia'])->name('site.ofertasDoDia');
Route::get('/product/{product:slug}', [SiteController::class, 'product_show'])->name('product.show');
Route::get('/category/{category:slug}', [SiteController::class, 'category_show'])->name('site.category');
Route::get('/blog/{post}', [SiteController::class, 'blog_show'])->name('site.blog_show');
Route::get('/about', [SiteController::class, 'about'])->name('site.about');
Route::get('/privacy', [SiteController::class, 'privacy'])->name('site.privacy');
Route::get('/terms', [SiteController::class, 'terms'])->name('site.terms');

/* ================= ROTAS SEO E REDIRECIONAMENTOS ================= */
/* Devem ficar sempre por último */

Route::get('/ofertas-{company}/{category}', [SiteController::class, 'byCompanyCategory'])->name('site.byCompanyCategory');
Route::get('/ofertas-{company}', [SiteController::class, 'byCompany'])->name('site.byCompany');

/** * 🔥 REDIRECIONAMENTO SEO 301 
 * Isso evita que você perca ranking no Google por links antigos.
 */
Route::get('/categorys/{any?}', function ($any = null) {
    return redirect()->to('/categories/' . $any, 301);
})->where('any', '.*');

Route::get('/companys/{any?}', function ($any = null) {
    return redirect()->to('/companies/' . $any, 301);
})->where('any', '.*');

