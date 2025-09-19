<?php

namespace App\Livewire;
use App\Models\PackagesView;

use Livewire\Component;

class ShowPackages extends Component
{
    public function render()
    {
        $packages = PackagesView::where('package_status', 1)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('livewire.show-packages', [
            'packages' => $packages,
        ]);
    }
}
