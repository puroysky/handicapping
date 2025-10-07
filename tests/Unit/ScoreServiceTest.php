<?php

namespace Tests\Unit;

use App\Services\ScoreService;
use PHPUnit\Framework\TestCase;

class ScoreServiceTest extends TestCase
{
    public function test_compute_hole_numeric(): void
    {
        $svc = new ScoreService();
        $this->assertSame(4, $svc->computeHole('4', 3));
        $this->assertSame(5, $svc->computeHole(5, 4));
    }

    public function test_compute_hole_x_penalty(): void
    {
        $svc = new ScoreService(2);
        $this->assertSame(7, $svc->computeHole('x', 5));
        $this->assertSame(4, $svc->computeHole('X', 2));
    }

    public function test_compute_hole_blank_null(): void
    {
        $svc = new ScoreService();
        $this->assertNull($svc->computeHole('', 4));
        $this->assertNull($svc->computeHole(null, 3));
    }

    public function test_compute_round_basic(): void
    {
        $svc = new ScoreService(2);
        $raw = [];
        $pars = [];
        for ($i = 1; $i <= 18; $i++) {
            $pars[$i] = 4;
        }
        $raw[1] = '4';
        $raw[2] = 'x'; // becomes 6
        $raw[3] = '';
        $raw[4] = 5;
        $out = $svc->computeRound($raw, $pars);
        $this->assertSame(4 + 6 + 5, $out['computedTotal']);
        $this->assertSame(4 + 5, $out['enteredTotal']);
        $this->assertSame(6, $out['perHole'][2]);
        $this->assertNull($out['perHole'][3]);
    }
}
