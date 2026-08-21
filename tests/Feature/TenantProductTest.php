<?php

test('tenant product routes are registered', function () {
    $url = route('tenant.products.index', ['tenantDomain' => 'demo']);

    expect($url)->toContain('demo.localhost')
        ->and(parse_url($url, PHP_URL_PATH))->toBe('/products');
});
