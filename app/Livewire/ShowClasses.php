<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AppointmentsView; // Use your correct model path

class ShowClasses extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Optional for Bootstrap users

    public function render()
    {
            $classes = AppointmentsView::where('appointment_status', 15)
                            ->orderBy('start_date_time', 'asc')
                            ->limit(3) // ✅ Only fetch 3 items
                            ->get();   // ✅ No pagination

        return view('livewire.show-classes', [
            'classes' => $classes
        ]);
    }
}
