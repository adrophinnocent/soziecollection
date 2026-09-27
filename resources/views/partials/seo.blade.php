@php
    use App\Models\Setting;
    use Illuminate\Support\Str;

    $baseUrl = rtrim(Setting::get('canonical_url', 'https://soziecollection.twinasafaris.com'), '/');
    $siteTitle = Setting::get('site_title', 'Sozie Collection | Premium Perfumes & Fragrances');
    $metaDescription = Setting::get('meta_description', 'Discover Sozie Collection, Tanzania\'s premier luxury haute parfumerie destination.');
    $defaultOgImage = Setting::get('default_og_image', '/images/sozie-logo.png');
    $defaultKeywords = Setting::get('default_keywords', 'Sozie Collection, Sozie Perfume, Luxury Perfume Tanzania');
    $robotsSetting = Setting::get('robots_setting', 'index, follow');
    $gscCode = Setting::get('gsc_verification_code', '');
    $gaId = Setting::get('google_analytics_id', '');

    $bizName = Setting::get('business_name', 'Sozie Collection');
    $bizDesc = Setting::get('business_description', 'Haute Parfumerie & Luxury Sensory E-Commerce Destination in Tanzania.');
    $bizLogo = Setting::get('logo_url', '/images/sozie-logo.png');
    $bizEmail = Setting::get('email', 'admin@soziecollection.com');
    $bizPhone = Setting::get('phone', '+255 711 000 001');
    $bizCity = Setting::get('city', 'Dar es Salaam');
    $bizAddress = Setting::get('address', 'Oysterbay, Toure Drive, Dar es Salaam, Tanzania');
    $bizInsta = Setting::get('instagram_url', 'https://instagram.com/soziecollection');
    $bizFb = Setting::get('facebook_url', 'https://facebook.com/soziecollection');
    $bizTiktok = Setting::get('tiktok_url', 'https://tiktok.com/@soziecollection');

    // Page-specific overrides
    $pageTitle = $pageTitle ?? $siteTitle;
    $pageDescription = $pageDescription ?? $metaDescription;
    $pageImage = $pageImage ?? $defaultOgImage;
    $canonicalUrl = $canonicalUrl ?? url()->current();

    // 1. Organization Schema
    $orgLogoUrl = Str::startsWith($bizLogo, 'http') ? $bizLogo : $baseUrl . $bizLogo;
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $bizName,
        'url' => $baseUrl,
        'logo' => $orgLogoUrl,
        'description' => $bizDesc,
        'email' => $bizEmail,
        'telephone' => $bizPhone,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $bizAddress,
            'addressLocality' => $bizCity,
            'addressCountry' => 'Tanzania',
        ],
        'sameAs' => array_values(array_filter([$bizInsta, $bizFb, $bizTiktok])),
    ];

    // 2. WebSite Schema
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $bizName,
        'url' => $baseUrl,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => $baseUrl . '/shop?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // 3. Breadcrumb Schema
    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => $baseUrl . '/',
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Shop',
            'item' => $baseUrl . '/shop',
        ],
    ];

    if (isset($product)) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $product->name,
            'item' => $baseUrl . '/product/' . $product->slug,
        ];
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems,
    ];

    // 4. Product Schema
    $productSchema = null;
    if (isset($product)) {
        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $product->primary_image,
            'description' => strip_tags($product->description),
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand ?: $bizName,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $baseUrl . '/product/' . $product->slug,
                'priceCurrency' => 'TZS',
                'price' => (string) $product->effective_price,
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ];

        if ($product->approvedReviews->count() > 0) {
            $productSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $product->average_rating,
                'reviewCount' => (string) $product->reviews_count,
            ];
        }
    }
@endphp

<!-- SEO METADATA -->
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $defaultKeywords }}">
<meta name="robots" content="{{ $robotsSetting }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

@if(!empty($gscCode))
<!-- Google Search Console Verification -->
<meta name="google-site-verification" content="{{ $gscCode }}">
@endif

<!-- OPEN GRAPH (OG) METADATA -->
<meta property="og:type" content="{{ isset($product) ? 'product' : 'website' }}">
<meta property="og:site_name" content="{{ $bizName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'sw' ? 'sw_TZ' : 'en_US' }}">

<!-- TWITTER / X CARDS -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">

<!-- FAVICON -->
<link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

@if(!empty($gaId))
<!-- GOOGLE ANALYTICS (GA4) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $gaId }}');
</script>
@endif

<!-- STRUCTURED DATA: ORGANIZATION SCHEMA (JSON-LD) -->
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<!-- STRUCTURED DATA: WEBSITE SCHEMA (JSON-LD) -->
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

@if(request()->routeIs('shop*') || request()->routeIs('shop.show'))
<!-- STRUCTURED DATA: BREADCRUMBLIST SCHEMA -->
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if($productSchema)
<!-- STRUCTURED DATA: PRODUCT SCHEMA (JSON-LD) -->
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
