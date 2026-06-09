<!-- ============================================================
     active_member_map.php  —  CodeIgniter View
============================================================ -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    html, body { height: 100%; margin: 0; padding: 0; }

    #amm-page {
        display:flex; flex-direction:column;
        height:calc(100dvh - 130px); min-height:600px;
        border-radius:12px; overflow:hidden;
        box-shadow:0 6px 32px rgba(13,71,161,.15);
        font-family:'Segoe UI',Tahoma,Arial,sans-serif;
    }

    /* TOOLBAR */
    #amm-toolbar {
        background:linear-gradient(135deg,#0d47a1 0%,#1565c0 100%);
        padding:10px 16px; display:flex; align-items:center;
        gap:8px; flex-wrap:wrap; flex-shrink:0; z-index:20;
    }
    #amm-toolbar .tb-title { color:#fff; font-weight:700; font-size:15px; margin-right:6px; white-space:nowrap; }
    .tb-btn {
        background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35);
        color:#fff; border-radius:7px; padding:6px 14px;
        font-size:12px; font-weight:700; cursor:pointer; transition:background .2s; white-space:nowrap;
    }
    .tb-btn:hover { background:rgba(255,255,255,.3); }

    /* STATS BAR */
    #amm-stats {
        background:#fff; padding:7px 16px;
        display:flex; align-items:center; gap:12px;
        border-bottom:1px solid #dde8f5; flex-shrink:0; overflow-x:auto;
    }
    .stat-chip {
        display:flex; align-items:center; gap:8px;
        white-space:nowrap; cursor:pointer;
        padding:3px 7px; border-radius:8px; transition:background .15s;
    }
    .stat-chip:hover    { background:#f0f7ff; }
    .stat-chip.selected { background:#e3f2fd; box-shadow:0 0 0 2px #1e88e5; }
    .sc-icon {
        width:30px; height:30px; border-radius:7px;
        display:flex; align-items:center; justify-content:center;
        font-size:14px; flex-shrink:0;
    }
    .sc-g  { background:#e8f5e9; color:#2e7d32; }
    .sc-pk { background:#fce4ec; color:#c62828; }
    .sc-t  { background:#e0f2f1; color:#00695c; }
    .sc-a  { background:#fff8e1; color:#e65100; }
    .sc-b  { background:#e3f2fd; color:#1565c0; }
    .sc-val        { font-size:20px; font-weight:800; line-height:1; color:#1a237e; }
    .sc-val.green  { color:#2e7d32; }
    .sc-val.pink   { color:#c62828; }
    .sc-lbl        { font-size:9px; color:#78909c; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
    .stat-sep      { width:1px; height:28px; background:#dde8f5; flex-shrink:0; }
    #desig-bar     { display:flex; gap:5px; flex-wrap:nowrap; overflow-x:auto; align-items:center; }
    .db-pill {
        border-radius:20px; padding:2px 9px;
        font-size:10px; font-weight:700; white-space:nowrap;
        border:1px solid; cursor:pointer; transition:opacity .15s;
    }
    .db-pill:hover { opacity:.75; }

    /* BODY */
    #amm-body { display:flex; flex:1 1 0%; overflow:hidden; position:relative; min-height:0; }

    /* MAP */
    #amm-map-wrap { flex:1 1 0%; position:relative; min-height:0; }
    #amm-leaflet  { width:100%; height:100%; min-height:400px; }

    #amm-loading {
        position:absolute; inset:0; background:rgba(255,255,255,.75);
        display:flex; align-items:center; justify-content:center; z-index:900;
    }
    .ld-box { text-align:center; color:#0d47a1; }
    .ld-spin {
        width:38px; height:38px; border:4px solid #e3f2fd; border-top:4px solid #1e88e5;
        border-radius:50%; margin:0 auto 10px; animation:spin .8s linear infinite;
    }
    @keyframes spin { to { transform:rotate(360deg); } }

    #amm-legend {
        position:absolute; bottom:26px; left:12px;
        background:rgba(255,255,255,.96); border-radius:9px;
        padding:9px 13px; border:1px solid #dde8f5;
        font-size:11px; z-index:500; box-shadow:0 2px 10px rgba(0,0,0,.12);
    }
    #amm-legend b { display:block; color:#0d47a1; font-size:9px; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
    .lg-row { display:flex; align-items:center; gap:6px; margin-bottom:3px; }
    .lg-dot { width:12px; height:12px; border-radius:50%; flex-shrink:0; }

    /* RIGHT PANEL */
    #amm-dp {
        position:absolute; top:0; right:0; width:270px;
        background:#fff; border-left:1px solid #dde8f5;
        z-index:500; height:100%; display:flex; flex-direction:column;
        box-shadow:-3px 0 16px rgba(13,71,161,.08);
    }
    #amm-dp.hidden { display:none; }
    #dp-hdr {
        background:linear-gradient(135deg,#0d47a1,#1565c0);
        color:#fff; font-weight:700; font-size:12px;
        padding:11px 14px; text-transform:uppercase; letter-spacing:.4px;
        flex-shrink:0; display:flex; align-items:center; justify-content:space-between;
    }
    #dp-close-btn {
        background:rgba(255,255,255,.22); border:none; border-radius:50%;
        color:#fff; width:22px; height:22px; font-size:12px; line-height:1;
        cursor:pointer; display:flex; align-items:center; justify-content:center;
    }
    #dp-list { overflow-y:auto; flex:1; }
    #dp-list::-webkit-scrollbar { width:3px; }
    #dp-list::-webkit-scrollbar-thumb { background:#90caf9; }

    .dp-state-hdr {
        background:#e8f0fe; color:#0d47a1;
        font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.4px;
        padding:6px 12px; display:flex; justify-content:space-between; align-items:center;
        position:sticky; top:0; z-index:2;
    }
    .dp-state-cnt { background:#0d47a1; color:#fff; border-radius:10px; padding:1px 7px; font-size:10px; }

    .dp-row {
        display:flex; align-items:center; gap:9px;
        padding:7px 12px; border-bottom:1px solid #f0f4fb; cursor:default;
    }
    .dp-row:last-child { border-bottom:none; }
    .dp-av {
        width:34px; height:34px; border-radius:50%;
        object-fit:cover; border:2px solid #e91e8c; flex-shrink:0;
    }
    .dp-av-txt {
        width:34px; height:34px; border-radius:50%;
        color:#fff; display:flex; align-items:center; justify-content:center;
        font-weight:800; font-size:11px; flex-shrink:0;
    }
    .dp-info   { flex:1; min-width:0; }
    .dp-name   { font-weight:700; font-size:11px; color:#1a237e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .dp-sub    { font-size:10px; color:#78909c; margin-top:1px; }

    /* MODAL */
    #amm-modal-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,.45);
        z-index:2000; display:none;
        align-items:center; justify-content:center; padding:16px;
    }
    #amm-modal-overlay.open { display:flex; }
    #amm-modal {
        background:#fff; border-radius:14px; width:100%; max-width:420px;
        max-height:85vh; display:flex; flex-direction:column;
        box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden;
        animation:modalIn .2s ease;
    }
    @keyframes modalIn { from{transform:scale(.93);opacity:0} to{transform:scale(1);opacity:1} }
    #modal-hdr {
        background:linear-gradient(135deg,#0d47a1,#1565c0);
        padding:14px 16px; flex-shrink:0;
        display:flex; align-items:flex-start; justify-content:space-between; gap:8px;
    }
    #modal-title { color:#fff; font-weight:800; font-size:15px; }
    #modal-sub   { color:#90caf9; font-size:11px; margin-top:3px; }
    #modal-count {
        background:rgba(255,255,255,.22); color:#fff; border-radius:20px;
        padding:3px 10px; font-size:11px; font-weight:700;
        white-space:nowrap; flex-shrink:0; margin-top:2px;
    }
    #modal-close {
        background:rgba(255,255,255,.22); border:none; border-radius:50%;
        color:#fff; width:28px; height:28px; font-size:15px; cursor:pointer;
        flex-shrink:0; display:flex; align-items:center; justify-content:center;
    }
    #modal-close:hover { background:rgba(255,255,255,.38); }
    #modal-body { overflow-y:auto; flex:1; padding:10px 12px; }
    #modal-body::-webkit-scrollbar { width:4px; }
    #modal-body::-webkit-scrollbar-thumb { background:#90caf9; border-radius:4px; }

    /* Modal row: photo + name + designation + mobile */
    .mm-row {
        display:flex; align-items:center; gap:12px;
        padding:10px 8px; border-bottom:1px solid #f0f4fb;
    }
    .mm-row:last-child { border-bottom:none; }
    .mm-num  { font-size:11px; font-weight:800; color:#b0bec5; min-width:20px; text-align:right; flex-shrink:0; }
    .mm-av   { width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid #e91e8c; flex-shrink:0; }
    .mm-av-txt {
        width:46px; height:46px; border-radius:50%; background:#e91e8c; color:#fff;
        display:flex; align-items:center; justify-content:center;
        font-weight:800; font-size:15px; flex-shrink:0;
    }
    .mm-info  { flex:1; min-width:0; }
    .mm-name  { font-weight:700; font-size:13px; color:#1a237e; }
    .mm-desig { font-size:10px; font-weight:700; display:inline-block; border-radius:4px; padding:1px 6px; margin-top:2px; }
    .mm-mob   { font-size:12px; color:#1565c0; font-weight:600; margin-top:3px; }

    .modal-loading { text-align:center; padding:30px 0; color:#78909c; }
    .m-spin {
        width:30px; height:30px; border:3px solid #e3f2fd; border-top:3px solid #1e88e5;
        border-radius:50%; margin:0 auto 10px; animation:spin .8s linear infinite;
    }

    @media (max-width:768px) {
        #amm-dp    { display:none !important; }
        #amm-modal { max-width:100%; max-height:92vh; }
    }
</style>

<div id="amm-page">

    <!-- TOOLBAR -->
    <div id="amm-toolbar">
        <span class="tb-title"><i class="ti-map mr-1"></i> Active Member Map</span>
        <button class="tb-btn" onclick="ammClearFilter()"><i class="ti-close"></i> Clear</button>
    </div>

    <!-- STATS BAR: Total Registered → Active Members → States → Districts → Mandals -->
    <div id="amm-stats">
        <div class="stat-chip" id="chip-reg"      onclick="ammOpenPanel('registered')">
            <div class="sc-icon sc-g"><i class="ti-user"></i></div>
            <div>
                <div class="sc-val green" id="st-total-reg">—</div>
                <div class="sc-lbl">Total Registered</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip" id="chip-active"   onclick="ammOpenPanel('active')">
            <div class="sc-icon sc-pk"><i class="ti-user"></i></div>
            <div>
                <div class="sc-val pink" id="st-members">—</div>
                <div class="sc-lbl">Active Members</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip" id="chip-states"   onclick="ammOpenPanel('states')">
            <div class="sc-icon sc-t"><i class="ti-world"></i></div>
            <div>
                <div class="sc-val" id="st-states">—</div>
                <div class="sc-lbl">States</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip" id="chip-districts" onclick="ammOpenPanel('districts')">
            <div class="sc-icon sc-a"><i class="ti-map-alt"></i></div>
            <div>
                <div class="sc-val" id="st-districts">—</div>
                <div class="sc-lbl">Districts</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip" id="chip-mandals"  onclick="ammOpenPanel('mandals')">
            <div class="sc-icon sc-b"><i class="ti-location-pin"></i></div>
            <div>
                <div class="sc-val" id="st-mandals">—</div>
                <div class="sc-lbl">Mandals</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <!-- Designation pills -->
        <div id="desig-bar"></div>
    </div>

    <!-- BODY -->
    <div id="amm-body">

        <!-- MAP -->
        <div id="amm-map-wrap">
            <div id="amm-loading">
                <div class="ld-box"><div class="ld-spin"></div>
                    <div style="font-weight:700;font-size:13px;">Loading Map…</div>
                </div>
            </div>
            <div id="amm-leaflet"></div>

            <div id="amm-legend">
                <b>Pin Type</b>
                <div class="lg-row"><div class="lg-dot" style="background:#4caf50;"></div> Registered Members</div>
                <div class="lg-row"><div class="lg-dot" style="background:#e91e8c;"></div> Active Members</div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div id="amm-dp" class="hidden">
            <div id="dp-hdr">
                <span id="dp-title">Members</span>
                <button id="dp-close-btn" onclick="ammClosePanel()">&#x2715;</button>
            </div>
            <div id="dp-list">
                <div style="padding:16px;font-size:12px;color:#90a4ae;text-align:center;">Loading…</div>
            </div>
        </div>

    </div><!-- /amm-body -->
</div><!-- /amm-page -->

<!-- MODAL -->
<div id="amm-modal-overlay" onclick="ammModalBgClick(event)">
    <div id="amm-modal">
        <div id="modal-hdr">
            <div>
                <div id="modal-title">—</div>
                <div id="modal-sub"></div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span id="modal-count"></span>
                <button id="modal-close" onclick="ammCloseModal()">&#x2715;</button>
            </div>
        </div>
        <div id="modal-body">
            <div class="modal-loading"><div class="m-spin"></div>Loading…</div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script type="text/javascript">
    var BASE_URL   = "<?php echo base_url(); ?>";
    var CSRF_NAME  = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var CSRF_TOKEN = "<?php echo $this->security->get_csrf_hash(); ?>";

    var DESIG_COLORS = {
        'PRESIDENT'                 : { bg:'#fce4ec', color:'#c62828' },
        'VICE PRESIDENT'            : { bg:'#e8eaf6', color:'#283593' },
        'GENERAL SECRETARY'         : { bg:'#e8f5e9', color:'#1b5e20' },
        'JOINT SECRETARY'           : { bg:'#e0f2f1', color:'#004d40' },
        'TREASURER'                 : { bg:'#fff8e1', color:'#e65100' },
        'EXECUTIVE MEMBER'          : { bg:'#f3e5f5', color:'#4a148c' },
        'REGIONAL DISTRICT OFFICER' : { bg:'#e0f7fa', color:'#006064' },
        'DISTRICT OFFICER'          : { bg:'#fbe9e7', color:'#bf360c' },
        'MANDAL OFFICER'            : { bg:'#f9fbe7', color:'#558b2f' },
    };

    var ammMap;
    var ammRegMarkers    = [];   /* green — registered */
    var ammActiveMarkers = [];   /* pink  — active */
    var ammPinsData      = [];   /* active pin data */
    var ammRegPinsData   = [];   /* registered pin data */
    var geocodeQueue     = [];
    var geocoding        = false;
    var ammCurrentPanel  = null;

    /* ── INIT ── */
    window.addEventListener('load', function () {
        ammMap = L.map('amm-leaflet', { center:[17.38,78.49], zoom:7, zoomControl:true });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:'&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>', maxZoom:19
        }).addTo(ammMap);

        document.getElementById('amm-loading').style.display = 'none';

        /* Force map to fill container — fixes Firefox, Safari, mobile */
        function fixMapSize() { if (ammMap) ammMap.invalidateSize(true); }
        setTimeout(fixMapSize, 100);
        setTimeout(fixMapSize, 400);
        setTimeout(fixMapSize, 1000);
        window.addEventListener('resize', fixMapSize);

        ammLoadStats();
        ammLoadActivePins({});
        ammLoadRegisteredPins({});
    });

    /* ── STATS ── */
    function ammLoadStats() {
        $.post(BASE_URL + 'masters/ActiveMemberMap/get_stats',
            { [CSRF_NAME]: CSRF_TOKEN },
            function (res) {
                if (!res) return;
                ammSetText('st-total-reg', res.total_registered || 0);
                ammSetText('st-members',   res.total_members    || 0);
                ammSetText('st-mandals',   res.total_mandals    || 0);
                ammSetText('st-districts', res.total_districts  || 0);
                ammSetText('st-states',    res.total_states     || 0);

                /* Designation pills — full name, clickable */
                var html = '';
                if (res.designations) {
                    $.each(res.designations, function (desig, cnt) {
                        if (!cnt) return;
                        var c = DESIG_COLORS[desig] || { bg:'#e3f2fd', color:'#1565c0' };
                        html += '<span class="db-pill" ' +
                            'onclick="ammOpenPanel(\'desig\',\'' + desig.replace(/'/g,"\\'") + '\')" ' +
                            'style="background:' + c.bg + ';color:' + c.color + ';border-color:' + c.color + '44;">' +
                            ammEsc(desig) + ' <strong>' + cnt + '</strong></span>';
                    });
                }
                document.getElementById('desig-bar').innerHTML = html ||
                    '<span style="font-size:11px;color:#90a4ae;">No data</span>';
            }, 'json');
    }

    /* ══════════════════════════════════════════════════
       MAP PINS — TWO LAYERS
       1. GREEN  = ALL registered members (tbl_registrations, status=active)
       2. PINK   = Active mapped members  (tbl_active_member_maping)
    ══════════════════════════════════════════════════ */

    /* Load ACTIVE member pins (PINK) */
    function ammLoadActivePins(filters) {
        document.getElementById('amm-loading').style.display = 'flex';
        ammActiveMarkers.forEach(function(m){ m.remove(); });
        ammActiveMarkers = []; ammPinsData = [];
        geocodeQueue = []; geocoding = false;

        var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_map_pins', postData,
            function (pins) {
                document.getElementById('amm-loading').style.display = 'none';
                ammPinsData = pins || [];
                ammPinsData.forEach(function(pin){
                    if (pin.lat && pin.lng && parseFloat(pin.lat) !== 0 && parseFloat(pin.lng) !== 0) {
                        ammPlaceActiveMarker(pin, pin.lat, pin.lng);
                    } else {
                        geocodeQueue.push({ pin: pin, type: 'active' });
                    }
                });
                if (geocodeQueue.length && !geocoding) ammGeocodeNext();
            }, 'json')
            .fail(function(){ document.getElementById('amm-loading').style.display='none'; });
    }

    /* Load REGISTERED member pins (GREEN) */
    function ammLoadRegisteredPins(filters) {
        ammRegMarkers.forEach(function(m){ m.remove(); });
        ammRegMarkers = []; ammRegPinsData = [];

        var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_registered_pins', postData,
            function (pins) {
                ammRegPinsData = pins || [];
                ammRegPinsData.forEach(function(pin){
                    if (pin.lat && pin.lng && parseFloat(pin.lat) !== 0 && parseFloat(pin.lng) !== 0) {
                        ammPlaceRegMarker(pin, pin.lat, pin.lng);
                    } else {
                        geocodeQueue.push({ pin: pin, type: 'registered' });
                    }
                });
                if (geocodeQueue.length && !geocoding) ammGeocodeNext();

                /* Fit bounds to all markers after both loaded */
                var allMarkers = ammRegMarkers.concat(ammActiveMarkers);
                if (allMarkers.length > 0) {
                    var bounds = L.latLngBounds(allMarkers.map(function(m){ return m.getLatLng(); }));
                    ammMap.fitBounds(bounds, { padding:[40,40] });
                }
            }, 'json');
    }

    /* ── GEOCODING via server proxy ── */
    function ammGeocodeNext() {
        if (!geocodeQueue.length) { geocoding=false; return; }
        geocoding = true;
        var item = geocodeQueue.shift();
        $.post(BASE_URL + 'masters/ActiveMemberMap/geocode_address',
            { address: item.pin.geocode_address, [CSRF_NAME]: CSRF_TOKEN },
            function(result){
                if (result && result.lat && result.lng) {
                    if (item.type === 'active') {
                        ammPlaceActiveMarker(item.pin, result.lat, result.lng);
                    } else {
                        ammPlaceRegMarker(item.pin, result.lat, result.lng);
                    }
                }
                setTimeout(ammGeocodeNext, 1200);
            }, 'json'
        ).fail(function(){ setTimeout(ammGeocodeNext, 2000); });
    }

    /* PINK marker — active members */
    function ammPlaceActiveMarker(pin, lat, lng) {
        var cnt   = parseInt(pin.member_count) || 0;
        var color = '#e91e8c';

        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="38" height="50" viewBox="0 0 40 52">' +
            '<path fill="' + color + '" d="M20 2C12.27 2 6 8.27 6 16c0 11.5 14 34 14 34S34 27.5 34 16C34 8.27 27.73 2 20 2z"/>' +
            '<circle fill="rgba(0,0,0,.18)" cx="20" cy="16" r="8"/>' +
            '<text x="20" y="21" text-anchor="middle" fill="#fff" font-family="Arial,sans-serif" font-weight="800" ' +
            'font-size="' + (cnt > 9 ? '8' : '10') + '">' + cnt + '</text>' +
            '</svg>';

        var icon = L.divIcon({ html:svg, className:'', iconSize:[38,50], iconAnchor:[19,50] });
        var marker = L.marker([lat, lng], { icon:icon, title:pin.mandal_name, zIndexOffset:1000 }).addTo(ammMap);
        marker.pinData = pin;
        marker.on('click', function(){ ammOpenModal(pin); });
        ammActiveMarkers.push(marker);
    }

    /* GREEN marker — registered members */
    function ammPlaceRegMarker(pin, lat, lng) {
        var cnt   = parseInt(pin.reg_count) || 0;
        var color = '#4caf50';

        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="46" viewBox="0 0 40 52">' +
            '<path fill="' + color + '" d="M20 2C12.27 2 6 8.27 6 16c0 11.5 14 34 14 34S34 27.5 34 16C34 8.27 27.73 2 20 2z"/>' +
            '<circle fill="rgba(0,0,0,.15)" cx="20" cy="16" r="8"/>' +
            '<text x="20" y="21" text-anchor="middle" fill="#fff" font-family="Arial,sans-serif" font-weight="800" ' +
            'font-size="' + (cnt > 9 ? '8' : '10') + '">' + cnt + '</text>' +
            '</svg>';

        var icon = L.divIcon({ html:svg, className:'', iconSize:[34,46], iconAnchor:[17,46] });
        var marker = L.marker([lat, lng], { icon:icon, title:pin.mandal_name || pin.location_name, zIndexOffset:500 }).addTo(ammMap);
        marker.pinData = pin;
        /* clicking green pin opens registration list for that location */
        marker.on('click', function(){ ammOpenRegModal(pin); });
        ammRegMarkers.push(marker);
    }

    /* ══════════════════════════════════════════════════
       RIGHT PANEL — opens when stat chip clicked
    ══════════════════════════════════════════════════ */
    function ammOpenPanel(type, extra) {
        ammCurrentPanel = type;

        /* Highlight selected chip */
        document.querySelectorAll('.stat-chip').forEach(function(c){ c.classList.remove('selected'); });
        var chipIds = { registered:'chip-reg', active:'chip-active', states:'chip-states', districts:'chip-districts', mandals:'chip-mandals' };
        if (chipIds[type]) document.getElementById(chipIds[type]).classList.add('selected');

        var titles = {
            registered: 'Total Registered',
            active    : 'Active Members',
            states    : 'States',
            districts : 'Districts',
            mandals   : 'Mandals',
            desig     : extra || 'Designation'
        };
        ammSetText('dp-title', titles[type] || type);
        document.getElementById('amm-dp').classList.remove('hidden');
        document.getElementById('dp-list').innerHTML =
            '<div style="padding:16px;font-size:12px;color:#90a4ae;text-align:center;">Loading…</div>';

        var postData = { [CSRF_NAME]: CSRF_TOKEN };

        if (type === 'registered') {
            /* All registered members list — grouped by state */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_registered_panel', postData,
                function(data){ ammRenderRegPanel(data || []); }, 'json');

        } else if (type === 'active') {
            /* Active members — state-wise */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_state_members_panel', postData,
                function(states){ ammRenderActivePanel(states || []); }, 'json');

        } else if (type === 'states') {
            /* States — Registered + Active members grouped by state */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_states_full_panel', postData,
                function(data){ ammRenderLocationFullPanel(data || [], 'States'); }, 'json');

        } else if (type === 'districts') {
            /* Districts — Registered + Active members grouped by district */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_districts_full_panel', postData,
                function(data){ ammRenderLocationFullPanel(data || [], 'Districts'); }, 'json');

        } else if (type === 'mandals') {
            /* Mandals — Registered + Active members grouped by mandal */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandals_full_panel', postData,
                function(data){ ammRenderLocationFullPanel(data || [], 'Mandals'); }, 'json');

        } else if (type === 'desig') {
            /* Designation members */
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_designation_members',
                { designation: extra, [CSRF_NAME]: CSRF_TOKEN },
                function(data){ ammRenderMemberList(data || [], '#e91e8c'); }, 'json');
        }
    }

    function ammClosePanel() {
        document.getElementById('amm-dp').classList.add('hidden');
        document.querySelectorAll('.stat-chip').forEach(function(c){ c.classList.remove('selected'); });
        ammCurrentPanel = null;
    }

    /* ── Panel renderers ── */

    /* Registered members — green avatar, grouped by state */
    function ammRenderRegPanel(states) {
        if (!states || !states.length) {
            document.getElementById('dp-list').innerHTML =
                '<div style="padding:20px;text-align:center;color:#90a4ae;font-size:11px;">No registered members</div>';
            return;
        }
        var html = '';
        states.forEach(function(st) {
            html += '<div class="dp-state-hdr">' + ammUc(st.state_name) +
                '<span class="dp-state-cnt">' + st.members.length + '</span></div>';
            st.members.forEach(function(m) {
                var initials = ammInitials(m.tr_full_name || '');
                var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key||'') + '/' + (m.tr_selfie||'');
                var hasPic   = !!(m.tr_selfie);
                html += '<div class="dp-row">' +
                    (hasPic
                        ? '<img class="dp-av" src="' + photoUrl + '" style="border-color:#4caf50;" ' +
                        'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                        '<div class="dp-av-txt" style="background:#4caf50;display:none;">' + initials + '</div>'
                        : '<div class="dp-av-txt" style="background:#4caf50;">' + initials + '</div>') +
                    '<div class="dp-info">' +
                    '<div class="dp-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                    (m.tr_mobile
                        ? '<div class="dp-sub">' + ammEsc(m.tr_mobile) + '</div>'
                        : '') +
                    (m.tr_email ? '<div class="dp-sub" style="color:#546e7a;">' + ammEsc(m.tr_email) + '</div>' : '') +
                    '</div>' +
                    '</div>';
            });
        });
        document.getElementById('dp-list').innerHTML = html;
    }

    /* Active members — pink avatar, grouped by state */
    function ammRenderActivePanel(states) {
        if (!states || !states.length) {
            document.getElementById('dp-list').innerHTML =
                '<div style="padding:20px;text-align:center;color:#90a4ae;font-size:11px;">No active members</div>';
            return;
        }
        var html = '';
        states.forEach(function(st) {
            html += '<div class="dp-state-hdr">' + ammUc(st.state_name) +
                '<span class="dp-state-cnt">' + st.members.length + '</span></div>';
            st.members.forEach(function(m) {
                var initials = ammInitials(m.tr_full_name || '');
                var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key||'') + '/' + (m.tr_selfie||'');
                var hasPic   = !!(m.tr_selfie);
                var desig    = m.tamm_designation || '';
                var dc       = DESIG_COLORS[desig] || { color:'#c62828' };
                html += '<div class="dp-row">' +
                    (hasPic
                        ? '<img class="dp-av" src="' + photoUrl + '" ' +
                        'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                        '<div class="dp-av-txt" style="background:#e91e8c;display:none;">' + initials + '</div>'
                        : '<div class="dp-av-txt" style="background:#e91e8c;">' + initials + '</div>') +
                    '<div class="dp-info">' +
                    '<div class="dp-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                    (desig ? '<div class="dp-sub" style="color:' + dc.color + ';font-weight:700;">' + ammEsc(desig) + '</div>' : '') +
                    (m.district_name ? '<div class="dp-sub"><i class="ti-location-pin" style="font-size:9px;color:#90a4ae;"></i> ' + ammUc(m.district_name) + (m.mandal_name ? ' &bull; ' + ammUc(m.mandal_name) : '') + '</div>' : '') +
                    (m.tr_mobile && (!desig || SHOW_MOBILE_DESIGS.indexOf(desig) !== -1) ? '<div class="dp-sub" style="color:#1565c0;"><i class="ti-mobile" style="font-size:10px;"></i> ' + ammEsc(m.tr_mobile) + '</div>' : '') +
                    (m.tr_email ? '<div class="dp-sub" style="color:#546e7a;">' + ammEsc(m.tr_email) + '</div>' : '') +
                    '</div>' +
                    '</div>';
            });
        });
        document.getElementById('dp-list').innerHTML = html;
    }

    /* ══════════════════════════════════════════════════
       States / Districts / Mandals — FULL DETAIL PANEL
       Shows: group header (location name + counts)
              then each member row: photo + name + mobile
              Registered = green avatar + mobile shown
              Active      = pink avatar  + NO mobile
              Designation members (PRES/VP/etc) = NO mobile
    ══════════════════════════════════════════════════ */
    var SHOW_MOBILE_DESIGS = [
        'REGIONAL DISTRICT OFFICER','DISTRICT OFFICER','MANDAL OFFICER'
    ];

    function ammRenderLocationFullPanel(groups, label) {
        if (!groups || !groups.length) {
            document.getElementById('dp-list').innerHTML =
                '<div style="padding:20px;text-align:center;color:#90a4ae;font-size:11px;">No data found</div>';
            return;
        }
        var html = '';
        groups.forEach(function(group) {
            var regCnt    = (group.registered || []).length;
            var activeCnt = (group.active     || []).length;
            var totalCnt  = regCnt + activeCnt;

            /* Group header */
            html += '<div class="dp-state-hdr">' +
                ammUc(group.name || '—') +
                '<span class="dp-state-cnt">' + totalCnt + '</span>' +
                '</div>';

            /* Sub-counts row */
            if (regCnt || activeCnt) {
                html += '<div style="padding:4px 12px 4px;display:flex;gap:8px;">' +
                    (regCnt    ? '<span style="font-size:9px;font-weight:700;color:#2e7d32;background:#e8f5e9;border-radius:10px;padding:1px 7px;">&#9679; Reg ' + regCnt    + '</span>' : '') +
                    (activeCnt ? '<span style="font-size:9px;font-weight:700;color:#c62828;background:#fce4ec;border-radius:10px;padding:1px 7px;">&#9679; Active ' + activeCnt + '</span>' : '') +
                    '</div>';
            }

            /* Registered members — green, show mobile */
            (group.registered || []).forEach(function(m) {
                html += ammDpMemberRow(m, '#4caf50', true);
            });

            /* Active members — pink, NO mobile */
            (group.active || []).forEach(function(m) {
                html += ammDpMemberRow(m, '#e91e8c', null);
            });
        });
        document.getElementById('dp-list').innerHTML = html;
    }

    /* Build a single panel member row */
    function ammDpMemberRow(m, avatarColor, showMobile) {
        var initials = ammInitials(m.tr_full_name || '');
        var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key||'') + '/' + (m.tr_selfie||'');
        var hasPic   = !!(m.tr_selfie);
        var desig    = m.tamm_designation || '';
        var dc       = DESIG_COLORS[desig] || null;

        /* showMobile=true  → always show (registered members)
           showMobile=null  → decide by designation (active members)
           showMobile=false → never show */
        if (showMobile === null) {
            showMobile = (!desig || SHOW_MOBILE_DESIGS.indexOf(desig) !== -1);
        }

        return '<div class="dp-row">' +
            (hasPic
                ? '<img class="dp-av" src="' + photoUrl + '" style="border-color:' + avatarColor + ';" ' +
                'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                '<div class="dp-av-txt" style="background:' + avatarColor + ';display:none;">' + initials + '</div>'
                : '<div class="dp-av-txt" style="background:' + avatarColor + ';">' + initials + '</div>') +
            '<div class="dp-info">' +
            '<div class="dp-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
            (desig && dc ? '<div class="dp-sub" style="color:' + dc.color + ';font-size:9px;font-weight:700;">' + ammEsc(desig) + '</div>' : '') +
            (showMobile && m.tr_mobile ? '<div class="dp-sub" style="color:#1565c0;">' + ammEsc(m.tr_mobile) + '</div>' : '') +
            (m.tr_email ? '<div class="dp-sub" style="color:#546e7a;">' + ammEsc(m.tr_email) + '</div>' : '') +
            '</div>' +
            '</div>';
    }

    /* Mandals list from loaded pin data */
    function ammRenderMandalsPanel() {
        if (!ammPinsData.length) {
            document.getElementById('dp-list').innerHTML =
                '<div style="padding:20px;text-align:center;color:#90a4ae;font-size:11px;">No mandals found</div>';
            return;
        }
        var html = '';
        ammPinsData.forEach(function(pin) {
            html += '<div class="dp-row" style="cursor:pointer;" onclick="ammOpenModal(' +
                JSON.stringify(pin).replace(/</g,'\\u003c').replace(/>/g,'\\u003e').replace(/"/g,'&quot;') + ')">' +
                '<div class="dp-av-txt" style="background:#e91e8c;font-size:10px;">' +
                ammUc(pin.mandal_name||'').substring(0,2) + '</div>' +
                '<div class="dp-info">' +
                '<div class="dp-name">' + ammUc(pin.mandal_name || '—') + '</div>' +
                '<div class="dp-sub">' + (pin.member_count||0) + ' member' + (pin.member_count!==1?'s':'') +
                ' &bull; ' + ammUc(pin.district_name||'') + '</div>' +
                '</div>' +
                '</div>';
        });
        document.getElementById('dp-list').innerHTML = html;
    }

    /* Generic member list (for designation panel) */
    function ammRenderMemberList(members, avatarColor) {
        if (!members.length) {
            document.getElementById('dp-list').innerHTML =
                '<div style="padding:20px;text-align:center;color:#90a4ae;font-size:11px;">No members found</div>';
            return;
        }
        var html = '';
        members.forEach(function(m) {
            var initials = ammInitials(m.tr_full_name || '');
            var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key||'') + '/' + (m.tr_selfie||'');
            var hasPic   = !!(m.tr_selfie);
            html += '<div class="dp-row">' +
                (hasPic
                    ? '<img class="dp-av" src="' + photoUrl + '" ' +
                    'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                    '<div class="dp-av-txt" style="background:' + avatarColor + ';display:none;">' + initials + '</div>'
                    : '<div class="dp-av-txt" style="background:' + avatarColor + ';">' + initials + '</div>') +
                '<div class="dp-info">' +
                '<div class="dp-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                '<div class="dp-sub">' + ammUc(m.district_name||'') + (m.mandal_name ? ' · ' + ammUc(m.mandal_name) : '') + '</div>' +
                (m.tr_email ? '<div class="dp-sub" style="color:#546e7a;">' + ammEsc(m.tr_email) + '</div>' : '') +
                '</div>' +
                '</div>';
        });
        document.getElementById('dp-list').innerHTML = html;
    }

    /* ══════════════════════════════════════════════════
       MODAL — ONE CLICK on PINK (active) pin
       Shows: photo + full name + designation + mobile
    ══════════════════════════════════════════════════ */
    function ammOpenModal(pin) {
        ammSetText('modal-title', ammUc(pin.mandal_name || '—'));
        ammSetText('modal-sub',   ammUc(pin.district_name||'') + ' · ' + ammUc(pin.state_name||''));
        ammSetText('modal-count', (pin.member_count||0) + ' Member' + (pin.member_count!==1?'s':''));

        document.getElementById('modal-body').innerHTML =
            '<div class="modal-loading"><div class="m-spin"></div>Loading members…</div>';
        document.getElementById('amm-modal-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandal_members',
            { mandal_id: pin.mandal_id, [CSRF_NAME]: CSRF_TOKEN },
            function(members){ ammRenderModal(members||[], false); }, 'json');
    }

    /* Modal for GREEN (registered) pin */
    function ammOpenRegModal(pin) {
        ammSetText('modal-title', ammUc(pin.location_name || pin.mandal_name || '—'));
        ammSetText('modal-sub',   ammUc(pin.district_name||'') + ' · ' + ammUc(pin.state_name||''));
        ammSetText('modal-count', (pin.reg_count||0) + ' Registered');

        document.getElementById('modal-body').innerHTML =
            '<div class="modal-loading"><div class="m-spin"></div>Loading members…</div>';
        document.getElementById('amm-modal-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_registered_by_location',
            { state_id: pin.state_id, district_id: pin.district_id, mandal_id: pin.mandal_id, [CSRF_NAME]: CSRF_TOKEN },
            function(members){ ammRenderModal(members||[], true); }, 'json');
    }

    /* Render modal list:
       isReg=true  → green (registered): mobile ALWAYS shown, email shown
       isReg=false → pink (active):      mobile shown ONLY for RDO/DO/MO, email shown
    */
    function ammRenderModal(members, isReg) {
        var body = document.getElementById('modal-body');
        if (!members.length) {
            body.innerHTML = '<div class="modal-loading" style="color:#90a4ae;">' +
                '<i class="ti-user" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px;"></i>' +
                'No members found.</div>';
            return;
        }

        ammSetText('modal-count', members.length + ' Member' + (members.length!==1?'s':''));
        var avatarColor  = isReg ? '#4caf50' : '#e91e8c';
        var avatarBorder = isReg ? '#4caf50' : '#e91e8c';

        var html = '';
        members.forEach(function(m, i) {
            var initials = ammInitials(m.tr_full_name || '');
            var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key||'') + '/' + (m.tr_selfie||'');
            var hasPic   = !!(m.tr_selfie);
            var desig    = m.tamm_designation || '';
            var dc       = DESIG_COLORS[desig] || { bg:'#f5f5f5', color:'#546e7a' };

            /* Mobile logic */
            var showMobile = isReg ? true : (SHOW_MOBILE_DESIGS.indexOf(desig) !== -1);

            html += '<div class="mm-row">' +
                '<div class="mm-num">' + (i+1) + '</div>' +
                (hasPic
                    ? '<img class="mm-av" src="' + photoUrl + '" style="border-color:' + avatarBorder + ';" ' +
                    'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                    '<div class="mm-av-txt" style="background:' + avatarColor + ';display:none;">' + initials + '</div>'
                    : '<div class="mm-av-txt" style="background:' + avatarColor + ';">' + initials + '</div>') +
                '<div class="mm-info">' +
                '<div class="mm-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                (desig
                    ? '<span class="mm-desig" style="background:' + dc.bg + ';color:' + dc.color + ';">' + ammEsc(desig) + '</span>'
                    : '') +
                (showMobile && m.tr_mobile
                    ? '<div class="mm-mob"><i class="ti-mobile mr-1"></i>' + ammEsc(m.tr_mobile) + '</div>'
                    : '') +
                (m.tr_email
                    ? '<div class="mm-mob" style="color:#546e7a;"><i class="ti-email mr-1"></i>' + ammEsc(m.tr_email) + '</div>'
                    : '') +
                '</div>' +
                '</div>';
        });
        body.innerHTML = html;
    }

    function ammCloseModal() {
        document.getElementById('amm-modal-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    function ammModalBgClick(e) {
        if (e.target===document.getElementById('amm-modal-overlay')) ammCloseModal();
    }
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') ammCloseModal(); });

    /* ── CLEAR ── */
    function ammClearFilter() {
        ammCloseModal();
        ammClosePanel();
        ammLoadActivePins({});
        ammLoadRegisteredPins({});
        ammLoadStats();
        ammMap.setView([17.38,78.49], 7);
        setTimeout(function(){ ammMap.invalidateSize(true); }, 200);
    }

    /* ── HELPERS ── */
    function ammSetText(id, val) { var el=document.getElementById(id); if(el) el.textContent=val; }
    function ammUc(str) {
        if (!str) return '';
        return String(str).toLowerCase().replace(/(?:^|\s)\S/g, function(a){ return a.toUpperCase(); });
    }
    function ammEsc(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function ammInitials(name) {
        if (!name) return '?';
        var parts = name.trim().split(' ').filter(Boolean);
        if (parts.length >= 2) return (parts[0][0]+parts[1][0]).toUpperCase();
        return (parts[0]||'?')[0].toUpperCase();
    }
</script>
