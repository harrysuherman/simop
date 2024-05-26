<div>
    <ol class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkBendungan" wire:click="showFeatureBendungan()"> Bendungan</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsBendungan::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableBendungan()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkEmbung" wire:click="showFeatureIrigasi()"> Embung</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsEmbung::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableEmbung()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkIrigasi" wire:click="showFeatureIrigasi()"> Irigasi</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsIrigasi::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableIrigasi()'><i class="fa fa-table"></i></a></span>
          </li>

          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkPantai" wire:click="showFeaturePantai()"> Pantai</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsPantai::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTablePantai()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkSungai" wire:click="showFeatureSungai()"> Sungai</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsSungai::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableSungai()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkDanau" wire:click="showFeatureDanau()"> Danau</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsDanau::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableDanau()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkAirBaku" wire:click="showFeatureAirBaku()"> AirBaku</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsAirBaku::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableAirBaku()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkAirTanah" wire:click="showFeatureAirTanah()"> AirTanah</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\AsetsAirTanah::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableAirTanah()'><i class="fa fa-table"></i></a></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="fw-bold"> <input type="checkbox" id="checkSubAset" wire:click="showFeatureSubAset()"> SubAset</div>
            </div>
            <span class="badge bg-primary rounded-pill">
            {{ number_format(App\Models\SubAset::count()) }}
            </span> &nbsp;&nbsp; <span class="badge bg-secondary rounded-pill "><a style="cursor:pointer;" class="text-white" wire:click='openTableSubAset()'><i class="fa fa-table"></i></a></span>
          </li>
          </ol>
</div>
