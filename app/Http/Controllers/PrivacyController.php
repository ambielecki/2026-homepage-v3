<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Homepage;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function __invoke(): View
    {
        $homepage = Homepage::query()
            ->active()
            ->first() ?? Homepage::defaultContent();

        return view('privacy', [
            'homepage' => $homepage,
        ]);
    }
}
