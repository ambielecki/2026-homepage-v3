<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Homepage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $homepage = Homepage::query()
            ->active()
            ->with([
                'heroImage',
                'activeExpertiseCards',
                'activeProjects.image',
                'activeExperiences',
            ])
            ->first() ?? Homepage::defaultContent();

        return view('homepage', [
            'homepage' => $homepage,
        ]);
    }
}
