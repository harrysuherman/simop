<?php

namespace App\Http\Livewire\Frontpage;

use Livewire\Component;

class SidebarMap extends Component
{
    public function render()
    {
        return view('livewire.frontpage.sidebar-map');
    }

    public function openTableAsetKendaraan(){
        $this->dispatchBrowserEvent('openTableAsetKendaraan');
    }
    public function openTableAsetBangunan(){
        $this->dispatchBrowserEvent('openTableAsetBangunan');
    }
    public function openTableAsetTanah(){
        $this->dispatchBrowserEvent('openTableAsetTanah');
    }
    public function openTableKibD(){
        $this->dispatchBrowserEvent('openTableKibD');
    }
    public function openTableKibE(){
        $this->dispatchBrowserEvent('openTableKibE');
    }
    public function openTableKibF(){
        $this->dispatchBrowserEvent('openTableKibF');
    }

    public function showFeatureAsetKendaraan(){
        $this->dispatchBrowserEvent('showFeatureAsetKendaraan');
    }
    public function showFeatureAsetTanah(){
        $this->dispatchBrowserEvent('showFeatureAsetTanah');
    }
    public function showFeatureAsetBangunan(){
        $this->dispatchBrowserEvent('showFeatureAsetBangunan');
    }
    public function showFeatureKibD(){
        $this->dispatchBrowserEvent('showFeatureKibD');
    }
    public function showFeatureKibE(){
        $this->dispatchBrowserEvent('showFeatureKibE');
    }
    public function showFeatureKibF(){
        $this->dispatchBrowserEvent('showFeatureKibF');
    }
}
