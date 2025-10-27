<?php

use function Pest\Stressless\stress;

describe('Orders Stress Test', function () {

    describe('orders/good endpoint', function () {

        it('handles 1 concurrent request', function () {
            $result = stress('http://localhost/orders/good?date=2025-10-24')
                ->concurrency(1)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->successful())->toBeGreaterThan(0)
                ->and($result->requests()->failed())->toBe(0);
        });

        it('handles 10 concurrent requests', function () {
            $result = stress('http://localhost/orders/good?date=2025-10-24')
                ->concurrency(10)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->successful())->toBeGreaterThan(0)
                ->and($result->requests()->failed())->toBe(0);
        });

        it('handles 40 concurrent requests', function () {
            $result = stress('http://localhost/orders/good?date=2025-10-24')
                ->concurrency(40)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->successful())->toBeGreaterThan(0)
                ->and($result->requests()->failed())->toBe(0);
        });

        it('handles 1000 concurrent requests', function () {
            $result = stress('http://localhost/orders/good?date=2025-10-24')
                ->concurrency(1000)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->successful())->toBeGreaterThan(0)
                ->and($result->requests()->failed())->toBe(0);
        })->group('heavy');

    });

    describe('orders/bad endpoint', function () {

        it('handles 1 concurrent request', function () {
            $result = stress('http://localhost/orders/bad?date=2025-10-24')
                ->concurrency(1)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->count())->toBeGreaterThan(0);
        });

        it('handles 10 concurrent requests', function () {
            $result = stress('http://localhost/orders/bad?date=2025-10-24')
                ->concurrency(10)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->count())->toBeGreaterThan(0);
        });

        it('handles 40 concurrent requests', function () {
            $result = stress('http://localhost/orders/bad?date=2025-10-24')
                ->concurrency(40)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->count())->toBeGreaterThan(0);
        });

        it('handles 1000 concurrent requests', function () {
            $result = stress('http://localhost/orders/bad?date=2025-10-24')
                ->concurrency(1000)
                ->for(5)->seconds()
                ->run();

            expect($result->requests()->count())->toBeGreaterThan(0);
        })->group('heavy');

    });

    describe('performance comparison', function () {

        it('compares good vs bad endpoint performance with 40 concurrent requests', function () {
            $goodResult = stress('http://localhost/orders/good?date=2025-10-24')
                ->concurrency(40)
                ->for(10)->seconds()
                ->run();

            $badResult = stress('http://localhost/orders/bad?date=2025-10-24')
                ->concurrency(40)
                ->for(10)->seconds()
                ->run();

            echo "\n\n";
            echo "=== orders/good ===\n";
            echo "Total Requests: {$goodResult->requests()->count()}\n";
            echo "Failed: {$goodResult->requests()->failed()->count()}\n";
            echo "Duration avg: " . number_format($goodResult->requests()->duration()->avg(), 2) . " ms\n";
            echo "Duration p99: " . number_format($goodResult->requests()->duration()->p95(), 2) . " ms\n";

            echo "\n=== orders/bad ===\n";
            echo "Total Requests: {$badResult->requests()->count()}\n";
            echo "Failed: {$badResult->requests()->failed()->count()}\n";
            echo "Duration avg: " . number_format($badResult->requests()->duration()->avg(), 2) . " ms\n";
            echo "Duration p99: " . number_format($badResult->requests()->duration()->p95(), 2) . " ms\n";
            echo "\n";

            expect($goodResult->requests()->count())->toBeGreaterThan(0)
                ->and($badResult->requests()->count())->toBeGreaterThan(0);
        })->group('comparison');

    });

});
