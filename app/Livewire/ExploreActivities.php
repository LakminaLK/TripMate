<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;

class ExploreActivities extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        $this->search = request('search', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Activity::query();

        // Search ONLY in activity name
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.explore-activities', [
            'activities' => $activities
        ]);
    }
}
