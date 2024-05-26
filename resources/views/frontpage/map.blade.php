@extends('layouts.app-map')
@push('styles')
@endpush
@section('content')
<div id="sidebar-left">
    <h5>Aset <span style="position: absolute; right:10px; top:5px;"> <button class="btn btn-xs btn-secondary" id="close-sidebar-left"> <i class="fa fa-window-close"></i> </button> </span></h5>
    <hr>
    <livewire:frontpage.sidebar-map>
</div>
<div id="sidebar-right">
    <h5>Map Service <span style="position: absolute; right:10px; top:5px;"> <button class="btn btn-xs btn-secondary" id="close-sidebar-right"> <i class="fa fa-window-close"></i> </button> </span></h5>
    <hr>
    <div id="controlService">
    </div>
</div>
<div id="map"></div>
<div class="modal fade" id="modalHelp" tabindex="-1" aria-labelledby="modalHelp" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalHelp">Panduan Pengunaan Peta</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
         {!! setting('site.guide_map') !!}
        </div>
      </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="popupFeature">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Info Feature</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="popupFeatureInfo">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Properties</button>
                  <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Foto</button>
                  </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <table id="tableFeature" class="table">
                        <tbody></tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div id="featureFoto">

                    </div>
                </div>
              </div>

        </div>
      </div>
    </div>
  </div>

@endsection
@push('javascript')
<script>

    /* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}

    var map = L.map('map');
    map.setView([-0.4957158,117.1443478], 11);

    wmsPane = map.createPane('wmsPane');
    wmsPane.style.zIndex = 250;

    var osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; OpenStreetMap contributors'
    });
    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });
    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(map);
    var saluranIrigasi = L.tileLayer.betterWms("https://geoserver.geoportal.samarindakota.go.id/geoserver/wms", {
            layers: "bappeda:Saluran_Irigasi_LN",
            transparent: true,
            format: 'image/png',
            pane : wmsPane,

    });
    var adminSamarinda = L.tileLayer.betterWms("https://geoserver.geoportal.samarindakota.go.id/geoserver/wms", {
            layers: "bappeda:Batas_Administrasi_SAMARINDA_AR_2020",
            transparent: true,
            format: 'image/png',
            pane : wmsPane,
    });
    var polaSamarinda = L.tileLayer.betterWms("https://geoserver-tarudpupr.kaltimprov.go.id/geoserver/wms", {
            layers: "penataanruang:pola_ruang_kota_samarinda2",
            transparent: true,
            format: 'image/png',
            pane : wmsPane,
    });

    adminSamarinda.setOpacity(0.5);
    polaSamarinda.setOpacity(0.5);

    var baseMaps = {
        "OpenStreetMap": osm,
        'Google Street' : googleStreets,
        'Google Satelite' : googleSat,
    };

    var overlayService = {
        "Administrasi Kota Samarinda <br /> <img src='https://geoserver.geoportal.samarindakota.go.id/geoserver/wms/wms?REQUEST=GetLegendGraphic&VERSION=1.1.0&FORMAT=image/png&LAYER=bappeda:Batas_Administrasi_SAMARINDA_AR_2020' /> <hr /> "  : adminSamarinda,
        "Pola Kota Samarinda <br /> <img src='https://geoserver-tarudpupr.kaltimprov.go.id/geoserver/wms/wms?REQUEST=GetLegendGraphic&VERSION=1.1.0&FORMAT=image/png&LAYER=penataanruang:pola_ruang_kota_samarinda2' /> <hr /> "  : polaSamarinda,
    };
    var overlayAset = {
        "Saluran irigasi <br /> <img src='https://geoserver.geoportal.samarindakota.go.id/geoserver/wms/wms?REQUEST=GetLegendGraphic&VERSION=1.1.0&FORMAT=image/png&LAYER=bappeda:Saluran_Irigasi_LN' /> " : saluranIrigasi,
    };

    featureSorotGroup = L.featureGroup().addTo(map);
    asetKendaraan = L.featureGroup().addTo(map);
    asetTanah = L.featureGroup().addTo(map);
    asetBangunan = L.featureGroup().addTo(map);
    asetKibD = L.featureGroup().addTo(map);
    asetKibE = L.featureGroup().addTo(map);
    asetKibF = L.featureGroup().addTo(map);

    var layerControlService = L.control.layers({}, overlayService,{
        collapsed : false,
    }).addTo(map);
    var controlBasemap = L.control.layers(baseMaps, {},{
        collapsed : true,
    }).addTo(map);
    $("#controlService").html(layerControlService.getContainer());

    var sidebarLeft = L.control.sidebar('sidebar-left', {
        closeButton: false,
        position: 'left'
    });
    var sidebarRight = L.control.sidebar('sidebar-right', {
        closeButton: false,
        position: 'right'
    });
    map.addControl(sidebarLeft);
    map.addControl(sidebarRight);

    // var sidebarSearch = L.control.sidebar('sidebar-search', {
    //     closeButton: false,
    //     position: 'left'
    // });
    // map.addControl(sidebarSearch);

    $("#close-sidebar-search").click(function (e) {
        e.preventDefault();
        sidebarSearch.hide();
    });
    $("#close-sidebar-left").click(function (e) {
        e.preventDefault();
        sidebarLeft.hide();
    });
    $("#close-sidebar-right").click(function (e) {
        e.preventDefault();
        sidebarRight.hide();
    });

    L.control.betterscale({
        metric: true,
        imperial: false
    }).addTo(map);

    var buttonKategoriAset = L.easyButton('fa-building', function(btn, map){
        sidebarLeft.toggle();
    }).addTo(map);

    // var buttonSearch = L.easyButton('fa-solid fa-magnifying-glass-location', function(btn, map){
    //     sidebarSearch.toggle();
    // }).addTo(map);

    var buttonHelp = L.easyButton('fa-info', function(btn, map){
       $("#modalHelp").modal('show');
    }).addTo(map);
    var buttonService = L.easyButton('fa-layer-group', function(btn, map){
       sidebarRight.toggle();
    }).addTo(map);

     // show coordinate
     L.control.mouseCoordinate({
        utm: true,
    }).addTo(map);

    buttonHelp.addTo(map);
    // buttonSearch.addTo(map);
    buttonKategoriAset.addTo(map);
    buttonService.addTo(map);

    function loadFeatureAsetKendaraan(){
                var optionIcon = {
                    icon: 'motorcycle'
                    , iconShape: 'marker'
                    , borderColor: 'red'
                    , textColor: 'Red'
                    , backgroundColor: '#FFF607'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
                var carIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkAsetKendaraan");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-aset-kendaraan/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                // isi style atau lainnya disini
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                if (layer instanceof L.Marker) {
                                    layer.setIcon(carIcon)
                                }
                                layer.addTo(asetKendaraan);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetKendaraan.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    function loadFeatureAsetTanah(){
                var optionIcon = {
                    icon: 'motorcycle'
                    , iconShape: 'marker'
                    , borderColor: 'red'
                    , textColor: 'Red'
                    , backgroundColor: '#FFF607'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
            var carIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkAsetTanah");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-aset-tanah/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                style: function(feature) {
                                    // switch (v['sertifikat_nomor']) {
                                    //     case 'S': return {
                                    //         fillColor: "#23a625",
                                    //         color: "#333",
                                    //         weight: 1,
                                    //         opacity: 1,
                                    //         fillOpacity: 0.8
                                    //     };
                                    //     case 'T':   return {
                                    //         fillColor: "#4287f5",
                                    //         color: "#333",
                                    //         weight: 1,
                                    //         opacity: 1,
                                    //         fillOpacity: 0.8
                                    //     };
                                    // }
                                    if (v['hak_tanah'] !== 'SPPT' && v['hak_tanah'] !== null) {
                                        return {
                                            fillColor: "#23a625",
                                            color: "#333",
                                            weight: 1,
                                            opacity: 1,
                                            fillOpacity: 0.8
                                        }
                                    }

                                    else if (v['hak_tanah'] == '') {
                                        return {
                                            fillColor: "#4287f5",
                                            color: "#333",
                                            weight: 1,
                                            opacity: 1,
                                            fillOpacity: 0.8
                                        }
                                    }
                                    else {
                                       return {
                                        fillColor: "#4287f5",
                                            color: "#333",
                                            weight: 1,
                                            opacity: 1,
                                            fillOpacity: 0.8
                                       }
                                    }
                                }
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                layer.addTo(asetTanah);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetTanah.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    function loadFeatureAsetBangunan(){
                var optionIcon = {
                    icon: 'building'
                    , iconShape: 'marker'
                    , borderColor: '#CCC'
                    , textColor: 'White'
                    , backgroundColor: 'Green'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
            var buildingIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkAsetBangunan");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-aset-bangunan/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                // isi style atau lainnya disini
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                if (layer instanceof L.Marker) {
                                    layer.setIcon(buildingIcon)
                                }
                                layer.addTo(asetBangunan);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetBangunan.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    function loadFeatureKibD(){
                var optionIcon = {
                    icon: 'road'
                    , iconShape: 'marker'
                    , borderColor: '#CCC'
                    , textColor: 'White'
                    , backgroundColor: 'Green'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
            var buildingIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkKibD");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-kib-d/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                // isi style atau lainnya disini
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                if (layer instanceof L.Marker) {
                                    layer.setIcon(buildingIcon)
                                }
                                layer.addTo(asetKibD);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetKibD.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    function loadFeatureKibE(){
                var optionIcon = {
                    icon: 'suitcase'
                    , iconShape: 'marker'
                    , borderColor: '#CCC'
                    , textColor: 'White'
                    , backgroundColor: 'Red'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
            var buildingIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkKibE");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-kib-e/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                // isi style atau lainnya disini
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                if (layer instanceof L.Marker) {
                                    layer.setIcon(buildingIcon)
                                }
                                layer.addTo(asetKibE);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetKibE.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    function loadFeatureKibF(){
                var optionIcon = {
                    icon: 'person-digging'
                    , iconShape: 'marker'
                    , borderColor: '#CCC'
                    , textColor: '#333'
                    , backgroundColor: 'Orange'
                    , innerIconStyle: 'font-size:9px;padding-top:1px;'
                };
            var buildingIcon = L.BeautifyIcon.icon(optionIcon);

            var checkBox = document.getElementById("checkKibF");
            if (checkBox.checked == true){
                $.ajax({
                url : "/api/get-all-spasial-kib-f/",
                dataType :'json',
                type : 'get',
                beforeSend: function() {
                    map.spin(true);
                },
                success: function(result){
                    $.each(result, function(i , v){
                            var wkt = ""+v['geom']+"";
                            var geojson_parser = Terraformer.WKT.parse(wkt);
                            var geojson = L.geoJson(geojson_parser, {
                                // isi style atau lainnya disini
                            }).addTo(map);
                            geojson.eachLayer(function(layer) {
                                // set Feature Properties
                                feature = layer.feature = layer.feature || {};
                                feature.type = feature.type || "Feature";
                                var props = feature.properties = feature.properties || {};
                                // set ID Layer
                                props.id = v['id'];
                                if (layer instanceof L.Marker) {
                                    layer.setIcon(buildingIcon)
                                }
                                layer.addTo(asetKibF);
                            });
                    })
                }
            }).done(function() { //use this
                map.spin(false);
            });
            } else {
                asetKibF.eachLayer(function (layer) {
                    map.removeLayer(layer);
                });
            }

    }

    asetKendaraan.on('click', function(e) {
        sidebarLeft.hide();
        $("#tableFeature tbody tr").remove();
        $("#featureFoto img").remove();
        $("#popupFeature").modal('show');
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-aset-kendaraan/" + e.layer.feature.properties.id,
            success: function (r) {
                $("#tableFeature tbody").append(`
                <tr><th width='150'>Nomor Register</th><td>`+r['no_register']+`</td></tr>
                <tr><th>Merk</th><td>`+r['merk']+`</td></tr>
                <tr><th>Type</th><td>`+r['type']+`</td></tr>
                <tr><th>Ukuran/CC</th><td>`+r['cc']+`</td></tr>
                <tr><th>Bahan</th><td>`+r['bahan']+`</td></tr>
                <tr><th>Tanggal Pembelian</th><td>`+r['tgl_perolehan']+`</td></tr>
                <tr><th>Nomor Rangka</th><td>`+r['nomor_rangka']+`</td></tr>
                <tr><th>Nomor Mesin</th><td>`+r['nomor_mesin']+`</td></tr>
                <tr><th>Nomor Polisi</th><td>`+r['nomor_polisi']+`</td></tr>
                <tr><th>Nomor BPKB</th><td>`+r['nomor_bpkb']+`</td></tr>
                <tr><th>Harga</th><td>Rp. `+r['harga']+`</td></tr>
                <tr><th>Asal Usul</th><td>`+r['asal_usul']+`</td></tr>
                <tr><th>Kondisi</th><td>`+r['kondisi']+`</td></tr>
                `);
            }
        });
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-foto-kendaraan/"+e.layer.feature.properties.id,
            success: function (r) {
               $.each(r, function (i, v) {
                $("#featureFoto").append(`
                <a><img src="/storage/`+v['image']+`" class="img-rounded mr-3 mb-3 mt-3" width="200" /></a>
                `);
               });
            }
        });
    });

    asetTanah.on('click', function(e) {
        sidebarLeft.hide();
        $("#tableFeature tbody tr").remove();
        $("#featureFoto img").remove();
        $("#popupFeature").modal('show');
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-aset-tanah/" + e.layer.feature.properties.id,
            success: function (r) {
                var berkas = r['berkas'];
                if (berkas == undefined) {
                    file_berkas = "Tidak tersedia";
                } else {
                    file_berkas = `<a href="/storage/`+ berkas +`" target="_blank">Lihat</a>`;
                }
                if (r['status_sertifikat'] == 'S') {
                    var status = 'Sudah Sertifikat'
                } else {
                    var status = 'Belum Sertifikat'
                }
                $("#tableFeature tbody").append(`
                <tr><th width='150'>Nomor Register</th><td>`+r['no_register']+`</td></tr>
                <tr><th>Luas</th><td>`+r['luas_m2']+` M2</td></tr>
                <tr><th>Harga</th><td>Rp. `+r['harga']+`</td></tr>
                <tr><th>Hak Tanah</th><td>`+r['hak_tanah']+`</td></tr>
                <tr><th>Nomor Surat</th><td>`+r['sertifikat_nomor']+`</td></tr>
                <tr><th>Alamat</th><td>`+r['alamat']+`</td></tr>
                <tr><th>Penggunaan</th><td>`+r['penggunaan']+`</td></tr>
                <tr><th>Asal Usul</th><td>`+r['asal_usul']+`</td></tr>
                <tr><th>Keterangan</th><td>`+r['keterangan']+`</td></tr>
                <tr><th>Berkas</th><td>`+ file_berkas +`</td></tr>
                `);
            }
        });
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-foto-tanah/"+e.layer.feature.properties.id,
            success: function (r) {
               $.each(r, function (i, v) {
                $("#featureFoto").append(`
                <a href="/storage/`+v['image']+`" data-lightbox="image"><img src="/storage/`+v['image']+`" class="img-rounded mr-3 mb-3 mt-3" width="200" /></a>
                `);
               });
            }
        });
    });

    asetBangunan.on('click', function(e) {
        sidebarLeft.hide();
        $("#tableFeature tbody tr").remove();
        $("#featureFoto img").remove();
        $("#popupFeature").modal('show');
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-aset-bangunan/" + e.layer.feature.properties.id,
            success: function (r) {
                $("#tableFeature tbody").append(`
                <tr><th width='150'>Nomor Register</th><td>`+r['no_register']+`</td></tr>
                <tr><th>Tanggal Perolehan</th><td>`+r['tgl_perolehan']+`</td></tr>
                <tr><th>Luas Lantai</th><td>`+r['luas_lantai']+` M2</td></tr>
                <tr><th>Bertingkat/Tidak</th><td>`+r['bertingkat_tidak']+`</td></tr>
                <tr><th>Beton/Tidak</th><td>`+r['beton_tidak']+`</td></tr>
                <tr><th>Lokasi</th><td>`+r['lokasi']+`</td></tr>
                <tr><th>Kondisi</th><td>`+r['kondisi']+`</td></tr>
                <tr><th>Dokumen Tanggal</th><td>`+r['dokumen_tanggal']+`</td></tr>
                <tr><th>Dokumen Nomor</th><td>`+r['dokumen_nomor']+`</td></tr>
                <tr><th>Harga</th><td>`+r['harga']+`</td></tr>
                <tr><th>Asal Usul</th><td>`+r['asal_usul']+`</td></tr>
                `);
            }
        });
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-foto-bangunan/"+e.layer.feature.properties.id,
            success: function (r) {
               $.each(r, function (i, v) {
                $("#featureFoto").append(`
                <a href="/storage/`+v['image']+`" data-lightbox="image"><img src="/storage/`+v['image']+`" class="img-rounded mr-3 mb-3 mt-3" width="200" /></a>
                `);
               });
            }
        });
    });

    asetKibD.on('click', function(e) {
        sidebarLeft.hide();
        $("#tableFeature tbody tr").remove();
        $("#popupFeature").modal('show');
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-kib-d/" + e.layer.feature.properties.id,
            success: function (r) {
                $("#tableFeature tbody").append(`
                <tr><th width='150'>Nomor Register</th><td>`+r['no_register']+`</td></tr>
                <tr><th>Nama Aset</th><td>`+r['judul']+`</td></tr>
                <tr><th>Tanggal Perolehan</th><td>`+r['tgl_perolehan']+`</td></tr>
                <tr><th>Konstruksi</th><td>`+r['konstruksi']+`</td></tr>
                <tr><th>Panjang</th><td>`+r['panjang']+`</td></tr>
                <tr><th>Lebar</th><td>`+r['lebar']+`</td></tr>
                <tr><th>Luas</th><td>`+r['luas']+`</td></tr>
                <tr><th>Lokasi</th><td>`+r['lokasi']+`</td></tr>
                <tr><th>Tanggal Dokumen</th><td>`+r['dokumen_tanggal']+`</td></tr>
                <tr><th>Nomor Dokumen</th><td>`+r['dokumen_nomor']+`</td></tr>
                <tr><th>Status Tanah</th><td>`+r['status_tanah']+`</td></tr>
                <tr><th>Kode Tanah</th><td>`+r['kd_tanah']+`</td></tr>
                <tr><th>Asal Usul</th><td>`+r['asal_usul']+`</td></tr>
                <tr><th>Kondisi</th><td>`+r['kondisi']+`</td></tr>
                <tr><th>Harga</th><td>`+r['harga']+`</td></tr>
                <tr><th>Masa Manfaat</th><td>`+r['masa_manfaat']+`</td></tr>
                `);
            }
        });
        $.ajax({
            type: "GET",
            url: "/api/get-spasial-foto-kib-d/"+e.layer.feature.properties.id,
            success: function (r) {
               $.each(r, function (i, v) {
                $("#featureFoto").append(`
                <a><img src="/storage/`+v['image']+`" class="img-rounded mr-3 mb-3 mt-3" width="200" /></a>
                `);
               });
            }
        });
    });

    const legend = L.control.Legend({
            position: "bottomleft",
            collapsed: false,
            symbolWidth: 24,
            opacity: 1,
            column: 2,
            legends: [
            {
                label: "Jalan/Jaringan",
                type: "image",
                url: "{{ asset('img/road.png') }}"
            },
            {
                label: "Kendaraan",
                type: "image",
                url: "{{ asset('img/motorcycle.png') }}"
            },
            {
                label: "Aset Lain",
                type: "image",
                url: "{{ asset('img/case.png') }}"
            },
            {
                label: "Bangunan",
                type: "image",
                url: "{{ asset('img/building.png') }}"
            },
            // {
            //     label: " Tanah Belum sertifikat",
            //     type: "polygon",
            //     sides: 5,
            //     color: "#333",
            //     fillColor: "#4287f5",
            //     weight: 2
            // }, {
            //     label: " Tanah Bersertifikat",
            //     type: "polygon",
            //     sides: 5,
            //     color: "#333",
            //     fillColor: "#23a625",
            //     weight: 2
            // }
        ]
        })
        .addTo(map);


</script>
@endpush
