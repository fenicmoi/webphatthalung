<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $districts = $districts ?? []; ?>

<!-- Leaflet.js CSS for Interactive GIS Mapping -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<!-- PAGE HEADER -->
<div class="text-white py-5 position-relative overflow-hidden" 
     style="min-height: 200px; display: flex; align-items: center; background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0369a1 100%); box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
    
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); mix-blend-mode: overlay;"></div>
    
    <div class="container position-relative py-2" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.92rem;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="fa-solid fa-house"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">ระบบสารสนเทศภูมิศาสตร์ (GIS Map)</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="fw-bold mb-1 text-white" style="font-size: 2.2rem; text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    <i class="fa-solid fa-map-location-dot text-warning me-2"></i>ระบบแผนที่สารสนเทศภูมิศาสตร์ (GIS)
                </h1>
                <p class="text-light opacity-90 mb-0">ค้นหาพิกัด ข้อมูลขอบเขตการปกครอง 11 อำเภอ 65 ตำบล และองค์กรปกครองส่วนท้องถิ่นทั่วจังหวัดพัทลุง</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2" onclick="openGisSummaryModal()">
                    <i class="fa-solid fa-file-invoice fs-5"></i>
                    <span>พิมพ์รายงานสรุปเขตการปกครอง (Summary)</span>
                </button>
                <?= $this->include('components/content_share_toolbar', ['shareTitle' => 'ระบบแผนที่สารสนเทศภูมิศาสตร์ GIS จังหวัดพัทลุง']) ?>
            </div>
        </div>
    </div>
</div>

<!-- GIS INTERACTIVE PORTAL -->
<div class="container-fluid my-4 px-lg-4">
    <div class="row g-4">
        
        <!-- LEFT PANEL: CONTROLS & DISTRICT/SUBDISTRICT EXPLORER -->
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08) !important;">
                
                <!-- Card Header with Search -->
                <div class="p-3.5 bg-light border-bottom">
                    <h5 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders"></i>
                        <span>เครื่องมือค้นหาเชิงพื้นที่</span>
                    </h5>

                    <!-- 1. ค้นหาด่วน (Quick Search) -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control border-start-0" id="gisSearchInput" placeholder="พิมพ์ชื่ออำเภอ, ตำบล, สถานที่..." oninput="onGisSearchInput(this.value)">
                    </div>

                    <!-- 2. เลือกอำเภอ (Amphoe Selector) -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fa-solid fa-building-flag text-primary me-1"></i> เลือกอำเภอ (11 อำเภอ)
                        </label>
                        <select class="form-select form-select-lg fw-bold text-primary" id="districtSelect" onchange="onDistrictChange(this.value)">
                            <option value="">-- แสดงภาพรวมทั้งจังหวัดพัทลุง --</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?= $d['id'] ?>">📍 <?= esc($d['name_th']) ?> (<?= count($d['subdistricts']) ?> ตำบล)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. เลือกตำบล (Tambon Selector) -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fa-solid fa-location-crosshairs text-success me-1"></i> เลือกตำบล (Subdistrict)
                        </label>
                        <select class="form-select" id="subdistrictSelect" onchange="onSubdistrictChange(this.value)" disabled>
                            <option value="">-- กรุณาเลือกอำเภอก่อน --</option>
                        </select>
                    </div>
                </div>

                <!-- Info Box & Subdistrict Pills Scroll Area -->
                <div class="card-body p-3.5" style="max-height: 520px; overflow-y: auto;">
                    
                    <!-- Dynamic District Details Banner -->
                    <div id="districtInfoCard" class="p-3 rounded-3 mb-3 border bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-primary mb-0" id="infoDistrictName">จังหวัดพัทลุง</h6>
                            <span class="badge bg-primary rounded-pill px-2.5 py-1" id="infoDistrictBadge">11 อำเภอ 65 ตำบล</span>
                        </div>
                        <p class="small text-muted mb-0" id="infoDistrictDesc">
                            จังหวัดในภาคใต้ฝั่งตะวันออก อุดมสมบูรณ์ด้วยระบบนิเวศ "เขา ป่า นา เล" และวัฒนธรรมมโนราห์ หนังตะลุง
                        </p>
                    </div>

                    <!-- List of Subdistricts in Selected District -->
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-secondary" id="subdistrictListHeader">
                                <i class="fa-solid fa-list-check text-primary me-1"></i> รายการตำบลในพื้นที่
                            </span>
                            <span class="badge bg-secondary rounded-pill" id="subdistrictCountBadge">65 ตำบล</span>
                        </div>

                        <div class="d-flex flex-column gap-1.5" id="subdistrictListContainer">
                            <!-- Javascript will dynamically inject subdistrict cards here -->
                        </div>
                    </div>

                    <!-- Landmark Points of Interest in District -->
                    <div class="mt-4 pt-3 border-top" id="landmarkSection">
                        <span class="small fw-bold text-secondary d-block mb-2">
                            <i class="fa-solid fa-star text-warning me-1"></i> จุดสำคัญ / แหล่งท่องเที่ยวในพื้นที่
                        </span>
                        <div class="d-flex flex-column gap-1.5" id="landmarkListContainer">
                            <!-- Javascript will inject landmarks here -->
                        </div>
                    </div>

                </div>

                <!-- Footer Quick Actions -->
                <div class="p-3 bg-light border-top d-flex flex-column gap-2">
                    <button type="button" class="btn btn-warning text-dark btn-sm w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1.5" onclick="openGisSummaryModal()">
                        <i class="fa-solid fa-file-invoice fs-6"></i>
                        <span>พิมพ์รายงานสรุปเขตการปกครอง (Summary)</span>
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm w-50 fw-bold" onclick="resetToProvinceView()">
                            <i class="fa-solid fa-rotate-left me-1"></i> ทั้งจังหวัด
                        </button>
                        <button type="button" class="btn btn-primary btn-sm w-50 fw-bold" onclick="locateUserPosition()">
                            <i class="fa-solid fa-crosshairs me-1"></i> พิกัดของฉัน
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- RIGHT PANEL: INTERACTIVE LEAFLET MAP CANVAS -->
        <div class="col-lg-8 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08) !important;">
                
                <!-- Top Map Toolbar (Layer Switcher & Controls) -->
                <div class="position-absolute top-0 start-0 w-100 p-3 d-flex flex-wrap align-items-center justify-content-between gap-2" style="z-index: 1000; pointer-events: none;">
                    
                    <!-- Map Layer Type Switcher -->
                    <div class="btn-group btn-group-sm bg-white p-1 rounded-pill shadow-sm border" style="pointer-events: auto;">
                        <button type="button" class="btn btn-light rounded-pill px-3 fw-bold active map-type-btn" onclick="setMapBaseLayer('osm')" id="btnLayerOsm">
                            <i class="fa-solid fa-map text-primary me-1"></i> แผนที่ถนน
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-3 fw-bold map-type-btn" onclick="setMapBaseLayer('satellite')" id="btnLayerSat">
                            <i class="fa-solid fa-satellite text-success me-1"></i> ดาวเทียม
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-3 fw-bold map-type-btn" onclick="setMapBaseLayer('terrain')" id="btnLayerTopo">
                            <i class="fa-solid fa-mountain text-warning me-1"></i> ภูมิประเทศ
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-3 fw-bold map-type-btn" onclick="setMapBaseLayer('dark')" id="btnLayerDark">
                            <i class="fa-solid fa-moon text-dark me-1"></i> ดาร์กโหมด
                        </button>
                    </div>

                    <!-- Map Quick Actions -->
                    <div class="d-flex align-items-center gap-2" style="pointer-events: auto;">
                        <button type="button" class="btn btn-sm btn-white bg-white shadow-sm rounded-circle border p-0" style="width: 36px; height: 36px;" onclick="resetToProvinceView()" title="กลับสู่มุมมองจังหวัดพัทลุง">
                            <i class="fa-solid fa-expand text-primary"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-white bg-white shadow-sm rounded-circle border p-0" style="width: 36px; height: 36px;" onclick="toggleMapFullscreen()" title="ขยายแผนที่เต็มจอ">
                            <i class="fa-solid fa-up-right-and-down-left-from-center text-dark"></i>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div id="phatthalungGisMap" style="height: 720px; width: 100%; z-index: 1;"></div>

                <!-- Bottom Floating Status / Coordinates Reader Bar -->
                <div class="position-absolute bottom-0 start-0 w-100 px-3 py-2 bg-dark bg-opacity-75 text-white d-flex flex-wrap align-items-center justify-content-between small" style="z-index: 1000; backdrop-filter: blur(8px);">
                    <div class="d-flex align-items-center gap-3">
                        <span><i class="fa-solid fa-crosshairs text-warning me-1"></i> พิกัดเคอร์เซอร์: <strong id="cursorCoordsText">7.6167° N, 100.0833° E</strong></span>
                        <span class="d-none d-md-inline opacity-50">|</span>
                        <span class="d-none d-md-inline"><i class="fa-solid fa-layer-group text-info me-1"></i> ระดับซูม: <strong id="zoomLevelText">10</strong></span>
                    </div>
                    <div>
                        <span class="badge bg-primary bg-opacity-75"><i class="fa-solid fa-circle-check me-1"></i> ข้อมูลพิกัด GIS มาตรฐาน WGS84</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Leaflet.js Interactive GIS Engine Script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
/* Custom Marker Styling & GIS Map Popups */
.leaflet-popup-content-wrapper {
    border-radius: 16px !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.18) !important;
    padding: 4px !important;
    font-family: 'Sarabun', 'Prompt', sans-serif !important;
}
.leaflet-popup-content {
    margin: 12px 14px !important;
    line-height: 1.5 !important;
}
.subdistrict-item-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 8px 12px;
    border-radius: 10px;
    text-align: left;
    transition: all 0.15s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: #334155;
}
.subdistrict-item-btn:hover {
    background: #e0f2fe;
    border-color: #38bdf8;
    color: #0369a1;
    transform: translateX(4px);
}
.subdistrict-item-btn.active {
    background: #1e3a8a !important;
    border-color: #1e3a8a !important;
    color: #ffffff !important;
    font-weight: bold;
}
.subdistrict-item-btn.active .badge {
    background: #ffffff !important;
    color: #1e3a8a !important;
}
.landmark-item-btn {
    border: 1px solid #f1f5f9;
    background: #f8fafc;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 0.83rem;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #475569;
}
.landmark-item-btn:hover {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #b45309;
}
.map-type-btn.active {
    background: #1e3a8a !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.map-type-btn.active i {
    color: #ffffff !important;
}
</style>

<script>
// =========================================================================
// PHATTHALUNG GIS MAP ENGINE (LEAFLET.JS)
// =========================================================================
const PROVINCE_CENTER = [7.6167, 100.0833];
const PROVINCE_ZOOM = 10;
const GIS_DATA = <?= json_encode($districts, JSON_UNESCAPED_UNICODE) ?>;

let map;
let baseLayers = {};
let currentBaseLayer;
let markersLayerGroup;
let districtPolygonsGroup;
let userLocationMarker;

document.addEventListener('DOMContentLoaded', function() {
    initGisMap();
    renderAllSubdistrictsList();
});

function initGisMap() {
    // 1. Initialize Map
    map = L.map('phatthalungGisMap', {
        center: PROVINCE_CENTER,
        zoom: PROVINCE_ZOOM,
        zoomControl: false,
        attributionControl: false
    });

    // Custom Zoom control placed at bottom right
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // 2. Define Tile Layer Providers
    baseLayers = {
        // OpenStreetMap Standard
        osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }),
        // ESRI World Imagery (Satellite)
        satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '© ESRI Satellite'
        }),
        // OpenTopoMap (Terrain)
        terrain: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '© OpenTopoMap'
        }),
        // CartoDB Dark Matter
        dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '© CartoDB'
        })
    };

    // Default to OpenStreetMap
    currentBaseLayer = baseLayers.osm;
    currentBaseLayer.addTo(map);

    // 3. Layer Groups for Markers & Shapes
    markersLayerGroup = L.layerGroup().addTo(map);
    districtPolygonsGroup = L.layerGroup().addTo(map);

    // 4. Render All District Markers & Boundaries
    renderAllDistrictsOnMap();

    // 5. Track Mouse Coordinates on Map
    map.on('mousemove', function(e) {
        document.getElementById('cursorCoordsText').textContent = `${e.latlng.lat.toFixed(4)}° N, ${e.latlng.lng.toFixed(4)}° E`;
    });

    map.on('zoomend', function() {
        document.getElementById('zoomLevelText').textContent = map.getZoom();
    });
}

// สลับชนิดแผนที่ (OSM, Satellite, Terrain, Dark)
function setMapBaseLayer(type) {
    if (baseLayers[type]) {
        map.removeLayer(currentBaseLayer);
        currentBaseLayer = baseLayers[type];
        currentBaseLayer.addTo(map);

        document.querySelectorAll('.map-type-btn').forEach(btn => btn.classList.remove('active'));
        if (type === 'osm') document.getElementById('btnLayerOsm').classList.add('active');
        else if (type === 'satellite') document.getElementById('btnLayerSat').classList.add('active');
        else if (type === 'terrain') document.getElementById('btnLayerTopo').classList.add('active');
        else if (type === 'dark') document.getElementById('btnLayerDark').classList.add('active');
    }
}

// วาดจุดศูนย์กลางอำเภอทั้งหมด 11 อำเภอลงบนแผนที่
function renderAllDistrictsOnMap() {
    markersLayerGroup.clearLayers();
    districtPolygonsGroup.clearLayers();

    GIS_DATA.forEach(d => {
        // District Boundary Radius circle
        const circle = L.circle(d.center, {
            color: '#1e3a8a',
            fillColor: '#38bdf8',
            fillOpacity: 0.12,
            radius: 8500,
            weight: 2
        }).addTo(districtPolygonsGroup);

        // Custom HTML Marker for District
        const customIcon = L.divIcon({
            className: 'custom-district-marker',
            html: `<div style="background: #1e3a8a; color: white; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 11px; white-space: nowrap; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border: 2px solid white; display: flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-landmark" style="color: #fbbf24;"></i> ${d.name_th.replace('อำเภอ', 'อ.')}
                   </div>`,
            iconSize: [120, 30],
            iconAnchor: [60, 15]
        });

        const marker = L.marker(d.center, { icon: customIcon }).addTo(markersLayerGroup);

        // Popup Content
        const popupHtml = `
            <div style="min-width: 220px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary fs-6 px-2.5 py-1">${d.name_th}</span>
                </div>
                <p class="small text-muted mb-2">${d.desc}</p>
                <div class="small mb-3">
                    <div><strong>จำนวนตำบล:</strong> ${d.subdistricts.length} ตำบล</div>
                    <div><strong>รหัสอำเภอ:</strong> ${d.code}</div>
                </div>
                <button type="button" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold" onclick="selectDistrictById(${d.id})">
                    <i class="fa-solid fa-magnifying-glass-location me-1"></i> สำรวจอำเภอนี้
                </button>
            </div>
        `;

        marker.bindPopup(popupHtml);
        circle.bindPopup(popupHtml);
    });
}

// เมื่อผู้ใช้เลือกอำเภอจาก Dropdown
function onDistrictChange(districtId) {
    if (!districtId) {
        resetToProvinceView();
        return;
    }
    selectDistrictById(parseInt(districtId));
}

// เลือกและซูมเข้าสู่อำเภอ
function selectDistrictById(id) {
    const district = GIS_DATA.find(d => d.id === id);
    if (!district) return;

    // Update Dropdown
    document.getElementById('districtSelect').value = id;

    // Update District Info Card
    document.getElementById('infoDistrictName').textContent = district.name_th;
    document.getElementById('infoDistrictBadge').textContent = `${district.subdistricts.length} ตำบล`;
    document.getElementById('infoDistrictDesc').textContent = district.desc;

    // Update Subdistrict Dropdown
    const subSelect = document.getElementById('subdistrictSelect');
    subSelect.disabled = false;
    subSelect.innerHTML = '<option value="">-- แสดงทุกตำบลใน ' + district.name_th + ' --</option>' +
        district.subdistricts.map(s => `<option value="${s.id}">📍 ${s.name_th} (${s.type})</option>`).join('');

    // Update Subdistrict List
    document.getElementById('subdistrictListHeader').innerHTML = `<i class="fa-solid fa-list-check text-primary me-1"></i> ตำบลใน ${district.name_th}`;
    document.getElementById('subdistrictCountBadge').textContent = `${district.subdistricts.length} ตำบล`;

    const subListContainer = document.getElementById('subdistrictListContainer');
    subListContainer.innerHTML = district.subdistricts.map(s => `
        <div class="subdistrict-item-btn" id="subCard-${s.id}" onclick="selectSubdistrictById(${s.id})">
            <div>
                <span class="fw-bold d-block">${s.name_th}</span>
                <small class="text-muted" style="font-size: 0.76rem;">รหัส ปณ. ${s.zipcode} • ${s.type}</small>
            </div>
            <span class="badge bg-light text-primary border">${s.name_en}</span>
        </div>
    `).join('');

    // Update Landmarks
    const landmarkContainer = document.getElementById('landmarkListContainer');
    if (district.landmarks && district.landmarks.length > 0) {
        landmarkContainer.innerHTML = district.landmarks.map(l => `
            <div class="landmark-item-btn" onclick="flyToCoords([${l.coords.join(',')}], '${l.name}', '${l.desc}')">
                <i class="fa-solid fa-location-dot text-danger"></i>
                <span class="text-truncate fw-semibold">${l.name}</span>
            </div>
        `).join('');
        document.getElementById('landmarkSection').classList.remove('d-none');
    } else {
        document.getElementById('landmarkSection').classList.add('d-none');
    }

    // Render Subdistricts on Map
    renderDistrictSubdistrictsOnMap(district);

    // Fly smoothly to district center
    map.flyTo(district.center, district.zoom, { duration: 1.2 });
}

// วาดตำบลทั้งหมดของอำเภอที่เลือกลงบนแผนที่
function renderDistrictSubdistrictsOnMap(district) {
    markersLayerGroup.clearLayers();
    districtPolygonsGroup.clearLayers();

    // Highlighting District Area
    L.circle(district.center, {
        color: '#1e3a8a',
        fillColor: '#0284c7',
        fillOpacity: 0.08,
        radius: 12000,
        weight: 3,
        dashArray: '6, 6'
    }).addTo(districtPolygonsGroup);

    // Add Subdistrict Markers
    district.subdistricts.forEach(s => {
        const subIcon = L.divIcon({
            className: 'custom-subdistrict-marker',
            html: `<div style="background: #ffffff; color: #1e3a8a; padding: 3px 8px; border-radius: 16px; font-weight: bold; font-size: 11px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border: 2px solid #0284c7; display: flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-location-dot text-primary"></i> ${s.name_th.replace('ตำบล', 'ต.')}
                   </div>`,
            iconSize: [110, 26],
            iconAnchor: [55, 13]
        });

        const marker = L.marker(s.coords, { icon: subIcon }).addTo(markersLayerGroup);

        const googleMapsLink = `https://www.google.com/maps/search/?api=1&query=${s.coords[0]},${s.coords[1]}`;

        const popupHtml = `
            <div style="min-width: 220px;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="badge bg-success rounded-pill px-2 py-1">${s.type}</span>
                    <small class="text-muted">ปณ. ${s.zipcode}</small>
                </div>
                <h5 class="fw-bold text-primary mb-1">${s.name_th}</h5>
                <p class="small text-secondary mb-2">${s.name_en}, ${district.name_th}, จังหวัดพัทลุง</p>
                <div class="p-2 rounded bg-light small mb-3 border">
                    <div><strong>พิกัดละติจูด:</strong> ${s.coords[0].toFixed(5)}° N</div>
                    <div><strong>พิกัดลองจิจูด:</strong> ${s.coords[1].toFixed(5)}° E</div>
                </div>
                <div class="d-flex gap-1.5">
                    <a href="${googleMapsLink}" target="_blank" class="btn btn-sm btn-outline-primary w-100 fw-bold">
                        <i class="fa-solid fa-diamond-turn-right me-1"></i> นำทางด้วย Google Maps
                    </a>
                </div>
            </div>
        `;

        marker.bindPopup(popupHtml);
    });

    // Add Landmark markers if any
    if (district.landmarks) {
        district.landmarks.forEach(l => {
            const landmarkIcon = L.divIcon({
                className: 'custom-landmark-marker',
                html: `<div style="background: #f59e0b; color: #1e293b; padding: 3px 8px; border-radius: 16px; font-weight: bold; font-size: 10px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.3); border: 2px solid #ffffff; display: flex; align-items: center; gap: 3px;">
                        <i class="fa-solid fa-star text-white"></i> ${l.name}
                       </div>`,
                iconSize: [120, 24],
                iconAnchor: [60, 12]
            });

            const lMarker = L.marker(l.coords, { icon: landmarkIcon }).addTo(markersLayerGroup);
            lMarker.bindPopup(`
                <div>
                    <span class="badge bg-warning text-dark mb-1">สถานที่สำคัญ / แลนด์มาร์ก</span>
                    <h6 class="fw-bold text-dark mb-1">${l.name}</h6>
                    <p class="small text-muted mb-2">${l.desc}</p>
                    <a href="https://www.google.com/maps/search/?api=1&query=${l.coords[0]},${l.coords[1]}" target="_blank" class="btn btn-sm btn-warning text-dark fw-bold w-100">
                        <i class="fa-solid fa-location-arrow me-1"></i> นำทาง
                    </a>
                </div>
            `);
        });
    }
}

// เมื่อผู้ใช้เลือกตำบลจาก Dropdown
function onSubdistrictChange(subId) {
    if (!subId) return;
    selectSubdistrictById(parseInt(subId));
}

// ซูมเข้าสู่ตำบลที่เลือก
function selectSubdistrictById(subId) {
    let foundSub = null;
    let foundDistrict = null;

    GIS_DATA.forEach(d => {
        const s = d.subdistricts.find(item => item.id === subId);
        if (s) {
            foundSub = s;
            foundDistrict = d;
        }
    });

    if (!foundSub || !foundDistrict) return;

    // Highlight card in list
    document.querySelectorAll('.subdistrict-item-btn').forEach(card => card.classList.remove('active'));
    const card = document.getElementById(`subCard-${subId}`);
    if (card) card.classList.add('active');

    // Update Dropdown value
    document.getElementById('subdistrictSelect').value = subId;

    // Fly to subdistrict coordinates
    map.flyTo(foundSub.coords, 14, { duration: 1.2 });
}

// ค้นหาด่วน (Search Input)
function onGisSearchInput(keyword) {
    keyword = keyword.toLowerCase().trim();
    if (!keyword) {
        renderAllSubdistrictsList();
        return;
    }

    const matchedSubdistricts = [];
    GIS_DATA.forEach(d => {
        d.subdistricts.forEach(s => {
            if (s.name_th.toLowerCase().includes(keyword) || 
                s.name_en.toLowerCase().includes(keyword) || 
                d.name_th.toLowerCase().includes(keyword) ||
                s.zipcode.includes(keyword)) {
                matchedSubdistricts.push({ ...s, district_name: d.name_th, district_id: d.id });
            }
        });
    });

    const subListContainer = document.getElementById('subdistrictListContainer');
    document.getElementById('subdistrictListHeader').innerHTML = `<i class="fa-solid fa-magnifying-glass text-primary me-1"></i> ผลการค้นหา "${keyword}"`;
    document.getElementById('subdistrictCountBadge').textContent = `${matchedSubdistricts.length} รายการ`;

    if (matchedSubdistricts.length === 0) {
        subListContainer.innerHTML = `<div class="text-center py-4 text-muted small"><i class="fa-regular fa-face-frown fs-4 mb-2 d-block"></i>ไม่พบข้อมูลตำบลที่ค้นหา</div>`;
        return;
    }

    subListContainer.innerHTML = matchedSubdistricts.map(s => `
        <div class="subdistrict-item-btn" onclick="selectDistrictAndSub(${s.district_id}, ${s.id})">
            <div>
                <span class="fw-bold d-block">${s.name_th}</span>
                <small class="text-muted" style="font-size: 0.76rem;">อ.${s.district_name.replace('อำเภอ','')} • ปณ. ${s.zipcode}</small>
            </div>
            <span class="badge bg-light text-primary border">${s.type}</span>
        </div>
    `).join('');
}

function selectDistrictAndSub(districtId, subId) {
    selectDistrictById(districtId);
    setTimeout(() => {
        selectSubdistrictById(subId);
    }, 600);
}

function flyToCoords(coords, title, desc) {
    map.flyTo(coords, 15, { duration: 1.2 });
}

// รีเซ็ตกลับสู่มุมมองจังหวัดพัทลุงทั้ง 11 อำเภอ
function resetToProvinceView() {
    document.getElementById('districtSelect').value = '';
    document.getElementById('subdistrictSelect').innerHTML = '<option value="">-- กรุณาเลือกอำเภอก่อน --</option>';
    document.getElementById('subdistrictSelect').disabled = true;
    document.getElementById('gisSearchInput').value = '';

    document.getElementById('infoDistrictName').textContent = 'จังหวัดพัทลุง';
    document.getElementById('infoDistrictBadge').textContent = '11 อำเภอ 65 ตำบล';
    document.getElementById('infoDistrictDesc').textContent = 'จังหวัดในภาคใต้ฝั่งตะวันออก อุดมสมบูรณ์ด้วยระบบนิเวศ "เขา ป่า นา เล" และวัฒนธรรมมโนราห์ หนังตะลุง';

    document.getElementById('landmarkSection').classList.add('d-none');
    renderAllSubdistrictsList();
    renderAllDistrictsOnMap();

    map.flyTo(PROVINCE_CENTER, PROVINCE_ZOOM, { duration: 1.2 });
}

function renderAllSubdistrictsList() {
    const subListContainer = document.getElementById('subdistrictListContainer');
    document.getElementById('subdistrictListHeader').innerHTML = `<i class="fa-solid fa-list-check text-primary me-1"></i> รายการตำบลในจังหวัดพัทลุง`;
    document.getElementById('subdistrictCountBadge').textContent = `65 ตำบล`;

    let html = '';
    GIS_DATA.forEach(d => {
        html += `<div class="small fw-bold text-primary mt-2 mb-1 border-bottom pb-1">${d.name_th} (${d.subdistricts.length} ตำบล)</div>`;
        d.subdistricts.forEach(s => {
            html += `
                <div class="subdistrict-item-btn" onclick="selectDistrictAndSub(${d.id}, ${s.id})">
                    <div>
                        <span class="fw-bold d-block">${s.name_th}</span>
                        <small class="text-muted" style="font-size: 0.75rem;">ปณ. ${s.zipcode} • ${s.type}</small>
                    </div>
                    <span class="badge bg-light text-primary border">${s.name_en}</span>
                </div>
            `;
        });
    });

    subListContainer.innerHTML = html;
}

// ค้นหาพิกัด GPS ปัจจุบันของผู้ใช้
function locateUserPosition() {
    if (!navigator.geolocation) {
        App.toast('อุปกรณ์ของคุณไม่รองรับระบบระบุพิกัด GPS', 'error');
        return;
    }

    App.toast('กำลังค้นหาพิกัด GPS ของคุณ...', 'info');

    navigator.geolocation.getCurrentPosition(function(pos) {
        const userCoords = [pos.coords.latitude, pos.coords.longitude];

        if (userLocationMarker) {
            map.removeLayer(userLocationMarker);
        }

        const userIcon = L.divIcon({
            className: 'user-gps-marker',
            html: `<div style="background: #ef4444; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.3); border: 2px solid white;">
                    <i class="fa-solid fa-person text-white"></i>
                   </div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        userLocationMarker = L.marker(userCoords, { icon: userIcon }).addTo(map);
        userLocationMarker.bindPopup(`
            <div class="text-center p-2">
                <span class="badge bg-danger mb-1">ตำแหน่งปัจจุบันของคุณ</span>
                <p class="small text-muted mb-0">ละติจูด: ${userCoords[0].toFixed(5)}<br>ลองจิจูด: ${userCoords[1].toFixed(5)}</p>
            </div>
        `).openPopup();

        map.flyTo(userCoords, 14, { duration: 1.5 });
        App.toast('พบพิกัดของคุณแล้ว!', 'success');
    }, function(err) {
        App.toast('ไม่สามารถระบุพิกัดได้ (โปรดอนุญาตการเข้าถึง Location ในเบราว์เซอร์)', 'warning');
    });
}

function toggleMapFullscreen() {
    const mapCard = document.getElementById('phatthalungGisMap').parentElement;
    if (!document.fullscreenElement) {
        mapCard.requestFullscreen().then(() => {
            setTimeout(() => map.invalidateSize(), 200);
        }).catch(err => {
            alert(`Error attempting to enable full-screen mode: ${err.message}`);
        });
    } else {
        document.exitFullscreen().then(() => {
            setTimeout(() => map.invalidateSize(), 200);
        });
    }
}

// =========================================================================
// ADMINISTRATIVE SUMMARY REPORT & OFFICIAL PRINTING ENGINE
// =========================================================================
let gisSummaryModal;

function openGisSummaryModal() {
    if (!gisSummaryModal) {
        gisSummaryModal = new bootstrap.Modal(document.getElementById('gisSummaryModal'));
    }
    renderSummaryTable('all');
    gisSummaryModal.show();
}

function renderSummaryTable(filterType) {
    const tableBody = document.getElementById('summaryReportTableBody');
    const currentDistrictId = parseInt(document.getElementById('districtSelect').value);
    
    let targetDistricts = GIS_DATA;
    if (filterType === 'selected' && currentDistrictId) {
        targetDistricts = GIS_DATA.filter(d => d.id === currentDistrictId);
    }

    let totalSubdistricts = 0;
    let totalTm = 0; // เทศบาลเมือง
    let totalTt = 0; // เทศบาลตำบล
    let totalAobt = 0; // อบต.

    targetDistricts.forEach(d => {
        d.subdistricts.forEach(s => {
            totalSubdistricts++;
            if (s.type === 'เทศบาลเมือง') totalTm++;
            else if (s.type === 'เทศบาลตำบล') totalTt++;
            else if (s.type === 'อบต.') totalAobt++;
        });
    });

    // กำหนดตัวเลขสถิติ อปท. จังหวัดพัทลุงอย่างเป็นทางการ (กรมส่งเสริมการปกครองท้องถิ่น)
    const isAllProvince = (targetDistricts.length === 11);
    const statPao = isAllProvince ? 1 : 0;
    const statTm = isAllProvince ? 1 : (targetDistricts[0].id === 1 ? 1 : 0);
    const statTt = isAllProvince ? 48 : totalTt;
    const statAobt = isAllProvince ? 24 : totalAobt;
    const statTotal = isAllProvince ? 74 : (statPao + statTm + statTt + statAobt);

    // Update Summary Stats Header in Modal
    document.getElementById('sumStatDistricts').textContent = targetDistricts.length;
    document.getElementById('sumStatSubdistricts').textContent = totalSubdistricts;
    document.getElementById('sumStatPao').textContent = statPao;
    document.getElementById('sumStatTm').textContent = statTm;
    document.getElementById('sumStatTt').textContent = statTt;
    document.getElementById('sumStatAobt').textContent = statAobt;
    document.getElementById('sumStatTotalLaos').textContent = statTotal;

    // Build Detailed Table Rows
    let rowsHtml = '';

    // หากแสดงภาพรวมทั้งจังหวัด ให้เพิ่มแถวของ องค์การบริหารส่วนจังหวัดพัทลุง (อบจ.) เป็นแถวแรกระดับจังหวัด
    if (targetDistricts.length === 11) {
        rowsHtml += `
            <tr class="table-primary" style="background-color: #e0f2fe !important; border-left: 4px solid #0284c7;">
                <td class="text-center fw-bold align-middle">★</td>
                <td class="align-middle">
                    <strong class="text-primary fs-6">องค์การบริหารส่วนจังหวัดพัทลุง (อบจ.พัทลุง)</strong><br>
                    <small class="text-muted">Phatthalung Provincial Administrative Organization</small>
                </td>
                <td class="text-center align-middle fw-bold fs-6">11 อำเภอ</td>
                <td class="align-middle">
                    <span class="badge bg-primary px-2.5 py-1.5 fs-7"><i class="fa-solid fa-landmark me-1"></i> องค์กรปกครองส่วนท้องถิ่นระดับจังหวัด (ครอบคลุมทั้ง 11 อำเภอ 65 ตำบล)</span>
                </td>
                <td class="align-middle d-none d-md-table-cell">
                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-building me-1 text-primary"></i> สำนักงาน อบจ.พัทลุง (อ.เมืองพัทลุง)</span>
                </td>
            </tr>
        `;
    }

    targetDistricts.forEach((d, idx) => {
        const subdistrictsFormatted = d.subdistricts.map(s => {
            let badgeColor = 'bg-secondary';
            if (s.type === 'เทศบาลเมือง') badgeColor = 'bg-primary';
            else if (s.type === 'เทศบาลตำบล') badgeColor = 'bg-info text-dark';
            else if (s.type === 'อบต.') badgeColor = 'bg-success';

            return `<span class="badge ${badgeColor} me-1 mb-1 font-monospace" style="font-weight: 500; font-size: 0.8rem;">
                ${s.name_th} (${s.type}) <small class="opacity-75">[ปณ.${s.zipcode}]</small>
            </span>`;
        }).join(' ');

        const landmarksSummary = (d.landmarks && d.landmarks.length > 0) 
            ? d.landmarks.map(l => `<span class="badge bg-light text-dark border me-1"><i class="fa-solid fa-location-dot text-danger me-1"></i>${l.name}</span>`).join(' ')
            : '<span class="text-muted small">-</span>';

        rowsHtml += `
            <tr>
                <td class="text-center fw-bold align-middle">${idx + 1}</td>
                <td class="align-middle">
                    <strong class="text-primary fs-6">${d.name_th}</strong><br>
                    <small class="text-muted">${d.name_en} (รหัส ${d.code})</small>
                </td>
                <td class="text-center align-middle fw-bold fs-6">${d.subdistricts.length}</td>
                <td class="align-middle" style="line-height: 1.8;">
                    ${subdistrictsFormatted}
                </td>
                <td class="align-middle d-none d-md-table-cell">
                    ${landmarksSummary}
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = rowsHtml;
}

function printAdministrativeReport() {
    window.print();
}
</script>

<!-- ======================================================================= -->
<!-- MODAL: ADMINISTRATIVE SUMMARY REPORT (รายงานสรุปข้อมูลเขตการปกครอง) -->
<!-- ======================================================================= -->
<div class="modal fade" id="gisSummaryModal" tabindex="-1" aria-labelledby="gisSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <!-- Modal Header (Screen Only) -->
            <div class="modal-header bg-primary text-white py-3 px-4 no-print" style="background: linear-gradient(135deg, #1e3a8a, #0369a1) !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-warning fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="gisSummaryModalLabel">
                            รายงานสรุปโครงสร้างเขตการปกครองและองค์กรปกครองส่วนท้องถิ่น จังหวัดพัทลุง
                        </h5>
                        <small class="opacity-90">ข้อมูลสรุปจำนวนอำเภอ ตำบล และประเภท อปท. ครบถ้วนทั้ง 11 อำเภอ</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body & Printable Report Area -->
            <div class="modal-body p-4 p-md-5" id="printableAdministrativeReport">
                
                <!-- Official Report Letterhead (Always Visible in Print) -->
                <div class="text-center mb-4 pb-3 border-bottom official-print-header">
                    <div class="mb-2">
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="ตราประจำจังหวัดพัทลุง" style="height: 70px; width: auto;" onerror="this.style.display='none'">
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">รายงานสรุปข้อมูลโครงสร้างเขตการปกครองและองค์กรปกครองส่วนท้องถิ่น</h3>
                    <h5 class="fw-semibold text-primary mb-2">จังหวัดพัทลุง (Phatthalung Provincial Administration Summary)</h5>
                    <p class="text-muted small mb-0">
                        ดึงข้อมูลเชิงพื้นที่จากระบบสารสนเทศภูมิศาสตร์ GIS • ณ วันที่ <?= function_exists('thai_date') ? thai_date(date('Y-m-d'), 'day_full') : date('d/m/Y') ?>
                    </p>
                </div>

                <!-- Executive Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center border bg-light h-100">
                            <span class="text-muted small d-block">จำนวนอำเภอ</span>
                            <span class="fs-3 fw-bold text-primary" id="sumStatDistricts">11</span>
                            <span class="small text-secondary d-block">อำเภอ</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center border bg-light h-100">
                            <span class="text-muted small d-block">จำนวนตำบล</span>
                            <span class="fs-3 fw-bold text-success" id="sumStatSubdistricts">65</span>
                            <span class="small text-secondary d-block">ตำบล</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center border bg-light h-100">
                            <span class="text-muted small d-block">อปท. ทั้งหมด</span>
                            <span class="fs-3 fw-bold text-dark" id="sumStatTotalLaos">74</span>
                            <span class="small text-secondary d-block">แห่ง (รวม อบจ.)</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center border bg-light h-100">
                            <span class="text-muted small d-block mb-1">แยกประเภท อปท. (74 แห่ง)</span>
                            <div class="small fw-semibold text-start">
                                <div>• อบจ.พัทลุง: <strong class="text-dark" id="sumStatPao">1</strong> แห่ง</div>
                                <div>• เทศบาลเมือง: <strong class="text-primary" id="sumStatTm">1</strong> แห่ง</div>
                                <div>• เทศบาลตำบล: <strong class="text-info" id="sumStatTt">48</strong> แห่ง</div>
                                <div>• อบต.: <strong class="text-success" id="sumStatAobt">24</strong> แห่ง</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Pills (Screen Only) -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 no-print">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" id="btnSumAll" onclick="renderSummaryTable('all'); this.classList.add('active'); document.getElementById('btnSumSelected').classList.remove('active');">
                            <i class="fa-solid fa-list me-1"></i> แสดงทั้ง 11 อำเภอ
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btnSumSelected" onclick="renderSummaryTable('selected'); this.classList.add('active'); document.getElementById('btnSumAll').classList.remove('active');">
                            <i class="fa-solid fa-filter me-1"></i> กรองเฉพาะอำเภอที่เลือกบนแผนที่
                        </button>
                    </div>
                    <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> สามารถกดปุ่มสั่งพิมพ์ด้านล่างเพื่อพิมพ์ออกกระดาษ A4 หรือบันทึก PDF</small>
                </div>

                <!-- Detailed Breakdown Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0" style="font-size: 0.92rem;">
                        <thead class="table-dark" style="background: #1e3a8a !important; color: white;">
                            <tr class="text-center">
                                <th style="width: 5%;">ที่</th>
                                <th style="width: 20%;">ชื่ออำเภอ (District)</th>
                                <th style="width: 8%;">จำนวนตำบล</th>
                                <th style="width: 45%;">รายชื่อตำบล & องค์กรปกครองส่วนท้องถิ่น (อปท.)</th>
                                <th style="width: 22%;" class="d-none d-md-table-cell">สถานที่สำคัญ / แลนด์มาร์ก</th>
                            </tr>
                        </thead>
                        <tbody id="summaryReportTableBody">
                            <!-- Injected via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Report Footer Signatures (for Print) -->
                <div class="row mt-5 pt-4 official-print-footer" style="page-break-inside: avoid;">
                    <div class="col-6 text-center">
                        <p class="mb-4">ผู้จัดทำรายงาน / เจ้าหน้าที่ระบบสารสนเทศภูมิศาสตร์</p>
                        <p class="mb-0">ลงชื่อ .................................................................</p>
                        <p class="small text-muted">(.................................................................)</p>
                    </div>
                    <div class="col-6 text-center">
                        <p class="mb-4">ผู้ตรวจสอบ / หัวหน้ากลุ่มงานยุทธศาสตร์และข้อมูล</p>
                        <p class="mb-0">ลงชื่อ .................................................................</p>
                        <p class="small text-muted">(.................................................................)</p>
                    </div>
                </div>

            </div>

            <!-- Modal Actions Footer (Screen Only) -->
            <div class="modal-footer bg-light border-top no-print d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> ปิดหน้าต่าง
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" onclick="printAdministrativeReport()" style="background: #1e40af; border: none;">
                    <i class="fa-solid fa-print fs-5"></i>
                    <span>สั่งพิมพ์รายงานเอกสารราชการ / บันทึกเป็น PDF</span>
                </button>
            </div>

        </div>
    </div>
</div>

<style>
/* Print Styling for Administrative Summary Report */
@media print {
    /* ซ่อนองค์ประกอบอื่นๆ ของหน้าเว็บทั้งหมด */
    .gov-header-wrapper,
    footer,
    .no-print,
    .leaflet-control-container,
    .btn,
    .card,
    .container-fluid,
    .content-toolbar-box,
    .ambient-glow {
        display: none !important;
    }

    /* แสดงเฉพาะเนื้อหาใน Modal Report */
    body, html {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 11pt !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #gisSummaryModal {
        display: block !important;
        position: static !important;
        opacity: 1 !important;
        padding: 0 !important;
    }

    .modal-dialog {
        max-width: 100% !important;
        margin: 0 !important;
    }

    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }

    .modal-body {
        padding: 0 !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 10pt !important;
    }

    table th, table td {
        border: 1px solid #333333 !important;
        padding: 6px 8px !important;
        color: #000000 !important;
    }

    thead th {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
    }

    .badge {
        border: 1px solid #666666 !important;
        color: #000000 !important;
        background: transparent !important;
        padding: 2px 4px !important;
    }

    .official-print-header,
    .official-print-footer {
        display: block !important;
    }
}
</style>

<?= $this->endSection() ?>

