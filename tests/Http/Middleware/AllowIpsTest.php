<?php

it('by default each ip will be allowed', function () {
    $this
        ->usingIp('1.2.3.4')
        ->get('prometheus')
        ->assertSuccessful();
});

it('it can allow only certain ips', function (string|null $ip, bool $allowed) {
    config()->set('prometheus.allowed_ips', [
        '1.2.3.4',
        '5.6.7.8',
    ]);

    $response = $this
        ->usingIp($ip)
        ->get('prometheus');

    $allowed
        ? $response->assertSuccessful()
        : $response->assertForbidden();
})->with([
    ['1.2.3.4', true],
    ['5.6.7.8', true],
    ['1.2.3.4.5', false],
    ['2.3.4.5', false],


]);
