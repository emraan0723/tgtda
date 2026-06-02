<!-- ============================================================
     active_member_map.php  —  CodeIgniter View
     Place: application/modules/masters/views/active_member_map.php
============================================================ -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    html, body { height: 100%; margin: 0; padding: 0; }

    #amm-page {
        display        : flex;
        flex-direction : column;
        height         : calc(100dvh - 130px);
        min-height     : 600px;
        border-radius  : 12px;
        overflow       : hidden;
        box-shadow     : 0 6px 32px rgba(13,71,161,.15);
        font-family    : 'Segoe UI', Tahoma, Arial, sans-serif;
    }

    /* TOOLBAR */
    #amm-toolbar {
        background  : linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        padding     : 10px 16px;
        display     : flex;
        align-items : center;
        gap         : 8px;
        flex-wrap   : wrap;
        flex-shrink : 0;
        z-index     : 20;
    }
    #amm-toolbar .tb-title {
        color        : #fff;
        font-weight  : 700;
        font-size    : 15px;
        margin-right : 6px;
        white-space  : nowrap;
    }
    .tb-btn {
        background    : rgba(255,255,255,.18);
        border        : 1px solid rgba(255,255,255,.35);
        color         : #fff;
        border-radius : 7px;
        padding       : 6px 14px;
        font-size     : 12px;
        font-weight   : 700;
        cursor        : pointer;
        transition    : background .2s;
        white-space   : nowrap;
    }
    .tb-btn:hover { background: rgba(255,255,255,.3); }

    /* STATS BAR */
    #amm-stats {
        background    : #fff;
        padding       : 7px 16px;
        display       : flex;
        align-items   : center;
        gap           : 14px;
        border-bottom : 1px solid #dde8f5;
        flex-shrink   : 0;
        overflow-x    : auto;
    }
    .stat-chip { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .sc-icon {
        width          : 30px;
        height         : 30px;
        border-radius  : 7px;
        display        : flex;
        align-items    : center;
        justify-content: center;
        font-size      : 14px;
        flex-shrink    : 0;
    }
    .sc-b { background:#e3f2fd; color:#1565c0; }
    .sc-g { background:#e8f5e9; color:#2e7d32; }
    .sc-a { background:#fff8e1; color:#e65100; }
    .sc-t { background:#e0f2f1; color:#00695c; }
    .sc-val       { font-size:20px; font-weight:800; line-height:1; color:#1a237e; }
    .sc-val.green { color:#2e7d32; }
    .sc-val.blue  { color:#1565c0; }
    .sc-lbl       { font-size:9px; color:#78909c; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
    .stat-sep     { width:1px; height:28px; background:#dde8f5; flex-shrink:0; }
    #desig-bar    { display:flex; gap:5px; flex-wrap:nowrap; overflow-x:auto; align-items:center; }
    .db-pill {
        border-radius : 20px;
        padding       : 2px 9px;
        font-size     : 10px;
        font-weight   : 700;
        white-space   : nowrap;
        border        : 1px solid;
    }

    /* BODY */
    #amm-body {
        display   : flex;
        flex      : 1 1 0%;
        overflow  : hidden;
        position  : relative;
        min-height: 0;
    }

    /* MAP */
    #amm-map-wrap {
        flex      : 1 1 0%;
        position  : relative;
        min-height: 0;
    }
    #amm-leaflet { width:100%; height:100%; min-height:400px; }

    #amm-loading {
        position        : absolute;
        inset           : 0;
        background      : rgba(255,255,255,.75);
        display         : flex;
        align-items     : center;
        justify-content : center;
        z-index         : 900;
    }
    .ld-box { text-align:center; color:#0d47a1; }
    .ld-spin {
        width        : 38px;
        height       : 38px;
        border       : 4px solid #e3f2fd;
        border-top   : 4px solid #1e88e5;
        border-radius: 50%;
        margin       : 0 auto 10px;
        animation    : spin .8s linear infinite;
    }
    @keyframes spin { to { transform:rotate(360deg); } }

    #amm-legend {
        position      : absolute;
        bottom        : 26px;
        left          : 12px;
        background    : rgba(255,255,255,.96);
        border-radius : 9px;
        padding       : 9px 13px;
        border        : 1px solid #dde8f5;
        font-size     : 11px;
        z-index       : 500;
        box-shadow    : 0 2px 10px rgba(0,0,0,.12);
    }
    #amm-legend b { display:block; color:#0d47a1; font-size:9px; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
    .lg-row { display:flex; align-items:center; gap:6px; margin-bottom:3px; }
    .lg-dot { width:12px; height:12px; border-radius:50%; flex-shrink:0; }

    /* RIGHT STATE PANEL */
    #amm-dp {
        position      : absolute;
        top           : 0;
        right         : 0;
        width         : 255px;
        background    : #fff;
        border-left   : 1px solid #dde8f5;
        z-index       : 500;
        height        : 100%;
        display       : flex;
        flex-direction: column;
        box-shadow    : -3px 0 16px rgba(13,71,161,.08);
    }
    #amm-dp.hidden { display:none; }
    #dp-hdr {
        background    : linear-gradient(135deg,#0d47a1,#1565c0);
        color         : #fff;
        font-weight   : 700;
        font-size     : 12px;
        padding       : 11px 14px;
        text-transform: uppercase;
        letter-spacing: .4px;
        flex-shrink   : 0;
    }
    #dp-list { overflow-y:auto; flex:1; }
    #dp-list::-webkit-scrollbar { width:3px; }
    #dp-list::-webkit-scrollbar-thumb { background:#90caf9; }

    .sp-state-hdr {
        background     : #e8f0fe;
        color          : #0d47a1;
        font-size      : 10px;
        font-weight    : 800;
        text-transform : uppercase;
        letter-spacing : .4px;
        padding        : 6px 12px;
        display        : flex;
        justify-content: space-between;
        align-items    : center;
        position       : sticky;
        top            : 0;
        z-index        : 2;
    }
    .sp-state-cnt {
        background    : #0d47a1;
        color         : #fff;
        border-radius : 10px;
        padding       : 1px 7px;
        font-size     : 10px;
    }
    .sp-member {
        display       : flex;
        align-items   : center;
        gap           : 9px;
        padding       : 7px 12px;
        border-bottom : 1px solid #f0f4fb;
        cursor        : pointer;
        transition    : background .15s;
    }
    .sp-member:hover { background:#f0f7ff; }
    .sp-av {
        width        : 36px;
        height       : 36px;
        border-radius: 50%;
        object-fit   : cover;
        border       : 2px solid #1e88e5;
        flex-shrink  : 0;
    }
    .sp-av-txt {
        width           : 36px;
        height          : 36px;
        border-radius   : 50%;
        background      : #1e88e5;
        color           : #fff;
        display         : flex;
        align-items     : center;
        justify-content : center;
        font-weight     : 800;
        font-size       : 12px;
        flex-shrink     : 0;
    }
    .sp-info { flex:1; min-width:0; }
    .sp-name { font-weight:700; font-size:12px; color:#1a237e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .sp-mob  { font-size:11px; color:#546e7a; margin-top:1px; }

    /* MODAL */
    #amm-modal-overlay {
        position        : fixed;
        inset           : 0;
        background      : rgba(0,0,0,.45);
        z-index         : 2000;
        display         : none;
        align-items     : center;
        justify-content : center;
        padding         : 16px;
    }
    #amm-modal-overlay.open { display:flex; }

    #amm-modal {
        background    : #fff;
        border-radius : 14px;
        width         : 100%;
        max-width     : 440px;
        max-height    : 85vh;
        display       : flex;
        flex-direction: column;
        box-shadow    : 0 20px 60px rgba(0,0,0,.25);
        overflow      : hidden;
        animation     : modalIn .2s ease;
    }
    @keyframes modalIn {
        from { transform:scale(.93); opacity:0; }
        to   { transform:scale(1);   opacity:1; }
    }
    #modal-hdr {
        background     : linear-gradient(135deg,#0d47a1,#1565c0);
        padding        : 14px 16px;
        flex-shrink    : 0;
        display        : flex;
        align-items    : flex-start;
        justify-content: space-between;
        gap            : 8px;
    }
    #modal-title { color:#fff; font-weight:800; font-size:15px; }
    #modal-sub   { color:#90caf9; font-size:11px; margin-top:3px; }
    #modal-count {
        background    : rgba(255,255,255,.22);
        color         : #fff;
        border-radius : 20px;
        padding       : 3px 10px;
        font-size     : 11px;
        font-weight   : 700;
        white-space   : nowrap;
        flex-shrink   : 0;
        margin-top    : 2px;
    }
    #modal-close {
        background     : rgba(255,255,255,.22);
        border         : none;
        border-radius  : 50%;
        color          : #fff;
        width          : 28px;
        height         : 28px;
        font-size      : 15px;
        cursor         : pointer;
        flex-shrink    : 0;
        display        : flex;
        align-items    : center;
        justify-content: center;
        transition     : background .2s;
    }
    #modal-close:hover { background:rgba(255,255,255,.38); }

    #modal-body { overflow-y:auto; flex:1; padding:10px 12px; }
    #modal-body::-webkit-scrollbar { width:4px; }
    #modal-body::-webkit-scrollbar-thumb { background:#90caf9; border-radius:4px; }

    /* Modal member row — simple list */
    .mm-row {
        display       : flex;
        align-items   : center;
        gap           : 12px;
        padding       : 10px 8px;
        border-bottom : 1px solid #f0f4fb;
    }
    .mm-row:last-child { border-bottom:none; }
    .mm-num {
        font-size  : 11px;
        font-weight: 800;
        color      : #b0bec5;
        min-width  : 20px;
        text-align : right;
        flex-shrink: 0;
    }
    .mm-av {
        width        : 48px;
        height       : 48px;
        border-radius: 50%;
        object-fit   : cover;
        border       : 2px solid #1e88e5;
        flex-shrink  : 0;
    }
    .mm-av-txt {
        width           : 48px;
        height          : 48px;
        border-radius   : 50%;
        background      : #1e88e5;
        color           : #fff;
        display         : flex;
        align-items     : center;
        justify-content : center;
        font-weight     : 800;
        font-size       : 16px;
        flex-shrink     : 0;
    }
    .mm-info  { flex:1; min-width:0; }
    .mm-name  { font-weight:700; font-size:13px; color:#1a237e; }
    .mm-desig { font-size:11px; color:#546e7a; margin-top:2px; }
    .mm-mob   { font-size:12px; color:#1565c0; font-weight:600; margin-top:3px; }

    .modal-loading { text-align:center; padding:30px 0; color:#78909c; }
    .m-spin {
        width        : 30px;
        height       : 30px;
        border       : 3px solid #e3f2fd;
        border-top   : 3px solid #1e88e5;
        border-radius: 50%;
        margin       : 0 auto 10px;
        animation    : spin .8s linear infinite;
    }

    @media (max-width: 768px) {
        #amm-dp    { display:none !important; }
        #amm-modal { max-width:100%; max-height:92vh; }
    }
</style>

<div id="amm-page">

    <!-- TOOLBAR -->
    <div id="amm-toolbar">
        <span class="tb-title"><i class="ti-map mr-1"></i> Active Member Map</span>
        <button class="tb-btn" onclick="ammClearFilter()">
            <i class="ti-close"></i> Clear
        </button>
    </div>

    <!-- STATS BAR -->
    <div id="amm-stats">
        <div class="stat-chip">
            <div class="sc-icon sc-g"><i class="ti-user"></i></div>
            <div>
                <div class="sc-val green" id="st-total-reg">—</div>
                <div class="sc-lbl">Total Registered</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip">
            <div class="sc-icon sc-b"><i class="ti-user"></i></div>
            <div>
                <div class="sc-val blue" id="st-members">—</div>
                <div class="sc-lbl">Active Members</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip">
            <div class="sc-icon sc-g"><i class="ti-location-pin"></i></div>
            <div>
                <div class="sc-val" id="st-mandals">—</div>
                <div class="sc-lbl">Mandals</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip">
            <div class="sc-icon sc-a"><i class="ti-map-alt"></i></div>
            <div>
                <div class="sc-val" id="st-districts">—</div>
                <div class="sc-lbl">Districts</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-chip">
            <div class="sc-icon sc-t"><i class="ti-world"></i></div>
            <div>
                <div class="sc-val" id="st-states">—</div>
                <div class="sc-lbl">States</div>
            </div>
        </div>
        <div class="stat-sep"></div>
        <div id="desig-bar"></div>
    </div>

    <!-- BODY -->
    <div id="amm-body">

        <!-- MAP -->
        <div id="amm-map-wrap">
            <div id="amm-loading">
                <div class="ld-box">
                    <div class="ld-spin"></div>
                    <div style="font-weight:700;font-size:13px;">Loading Map…</div>
                </div>
            </div>
            <div id="amm-leaflet"></div>

            <div id="amm-legend">
                <b>Pin Type</b>
                <div class="lg-row"><div class="lg-dot" style="background:#4caf50;"></div> Registered</div>
                <div class="lg-row"><div class="lg-dot" style="background:#1e88e5;"></div> Active Members</div>
            </div>
        </div>

        <!-- RIGHT PANEL — state-wise active members -->
        <div id="amm-dp">
            <div id="dp-hdr"><i class="ti-user mr-1"></i> Active Members</div>
            <div id="dp-list">
                <div style="padding:16px;font-size:12px;color:#90a4ae;text-align:center;">Loading…</div>
            </div>
        </div>

    </div><!-- /amm-body -->
</div><!-- /amm-page -->

<!-- MODAL — opens on pin click (one click, no popup) -->
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
    var ammMarkers   = [];
    var ammPinsData  = [];
    var geocodeQueue = [];
    var geocoding    = false;

    /* ── INIT ── */
    window.addEventListener('load', function () {
        ammMap = L.map('amm-leaflet', {
            center     : [17.38, 78.49],
            zoom       : 7,
            zoomControl: true,
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
            maxZoom    : 19,
        }).addTo(ammMap);

        document.getElementById('amm-loading').style.display = 'none';

        /* Firefox / mobile fix */
        setTimeout(function () { ammMap.invalidateSize(); }, 300);
        window.addEventListener('resize', function () { ammMap.invalidateSize(); });

        ammLoadStats();
        ammLoadPins({});
        ammLoadRightPanel({});
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

                /* Full designation names — no abbreviations */
                var html = '';
                if (res.designations) {
                    $.each(res.designations, function (desig, cnt) {
                        if (!cnt) return;
                        var c = DESIG_COLORS[desig] || { bg:'#e3f2fd', color:'#1565c0' };
                        html += '<span class="db-pill" style="background:' + c.bg +
                            ';color:' + c.color + ';border-color:' + c.color + '44;">' +
                            ammEsc(desig) + ' <strong>' + cnt + '</strong></span>';
                    });
                }
                document.getElementById('desig-bar').innerHTML = html ||
                    '<span style="font-size:11px;color:#90a4ae;">No data</span>';
            }, 'json');
    }

    /* ── LOAD PINS ── */
    function ammLoadPins(filters) {
        document.getElementById('amm-loading').style.display = 'flex';
        ammMarkers.forEach(function (m) { m.remove(); });
        ammMarkers = []; ammPinsData = []; geocodeQueue = []; geocoding = false;

        var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_map_pins', postData,
            function (pins) {
                document.getElementById('amm-loading').style.display = 'none';
                ammPinsData = pins || [];
                if (!ammPinsData.length) return;

                ammPinsData.forEach(function (pin) {
                    if (pin.lat && pin.lng && pin.lat !== 0 && pin.lng !== 0) {
                        ammPlaceMarker(pin, pin.lat, pin.lng);
                    } else {
                        geocodeQueue.push(pin);
                    }
                });

                if (geocodeQueue.length && !geocoding) ammGeocodeNext();

                var placed = ammMarkers.filter(function (m) { return m._latlng; });
                if (placed.length) {
                    ammMap.fitBounds(
                        L.latLngBounds(placed.map(function (m) { return m.getLatLng(); })),
                        { padding: [40, 40] }
                    );
                }
            }, 'json')
            .fail(function () {
                document.getElementById('amm-loading').style.display = 'none';
            });
    }

    /* ── GEOCODING via server proxy (avoids CORS) ── */
    function ammGeocodeNext() {
        if (!geocodeQueue.length) { geocoding = false; return; }
        geocoding = true;
        var pin = geocodeQueue.shift();

        $.post(
            BASE_URL + 'masters/ActiveMemberMap/geocode_address',
            { address: pin.geocode_address, [CSRF_NAME]: CSRF_TOKEN },
            function (result) {
                if (result && result.lat && result.lng) {
                    ammPlaceMarker(pin, result.lat, result.lng);
                }
                setTimeout(ammGeocodeNext, 1200);
            }, 'json'
        ).fail(function () { setTimeout(ammGeocodeNext, 2000); });
    }

    /* ── PLACE MARKER
       GREEN = registered location (no active mapping)
       BLUE  = has active members
    ── */
    function ammPlaceMarker(pin, lat, lng) {
        var cnt   = parseInt(pin.member_count) || 0;
        var color = (cnt > 0) ? '#1e88e5' : '#4caf50';

        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="52" viewBox="0 0 40 52">' +
            '<path fill="' + color + '" d="M20 2C12.27 2 6 8.27 6 16c0 11.5 14 34 14 34S34 27.5 34 16C34 8.27 27.73 2 20 2z"/>' +
            '<circle fill="rgba(0,0,0,.18)" cx="20" cy="16" r="8"/>' +
            '<text x="20" y="21" text-anchor="middle" fill="#fff" font-family="Arial,sans-serif" font-weight="800" ' +
            'font-size="' + (cnt > 9 ? '8' : '10') + '">' + cnt + '</text>' +
            '</svg>';

        var icon = L.divIcon({
            html: svg, className: '', iconSize: [40,52], iconAnchor: [20,52]
        });

        var marker = L.marker([lat, lng], { icon: icon, title: pin.mandal_name }).addTo(ammMap);
        marker.pinData = pin;

        /* ONE CLICK → modal opens directly, no popup */
        marker.on('click', function () { ammOpenModal(pin); });

        ammMarkers.push(marker);
    }

    /* ── MODAL ── */
    function ammOpenModal(pin) {
        ammSetText('modal-title', ammUc(pin.mandal_name || '—'));
        ammSetText('modal-sub',
            ammUc(pin.district_name || '') + ' · ' + ammUc(pin.state_name || ''));
        ammSetText('modal-count',
            (pin.member_count || 0) + ' Member' + (pin.member_count !== 1 ? 's' : ''));

        document.getElementById('modal-body').innerHTML =
            '<div class="modal-loading"><div class="m-spin"></div>Loading members…</div>';

        document.getElementById('amm-modal-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandal_members',
            { mandal_id: pin.mandal_id, [CSRF_NAME]: CSRF_TOKEN },
            function (members) { ammRenderModal(members || []); },
            'json');
    }

    function ammCloseModal() {
        document.getElementById('amm-modal-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    function ammModalBgClick(e) {
        if (e.target === document.getElementById('amm-modal-overlay')) ammCloseModal();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') ammCloseModal();
    });

    /* ── RENDER MODAL — simple scroll list: photo + full name + designation + mobile ── */
    function ammRenderModal(members) {
        var body = document.getElementById('modal-body');

        if (!members.length) {
            body.innerHTML =
                '<div class="modal-loading" style="color:#90a4ae;">' +
                '<i class="ti-user" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px;"></i>' +
                'No active members found.</div>';
            return;
        }

        ammSetText('modal-count', members.length + ' Member' + (members.length !== 1 ? 's' : ''));

        var html = '';
        members.forEach(function (m, i) {
            var initials = ammInitials(m.tr_full_name || '');
            var photoUrl = BASE_URL + 'uploads/registration/' +
                (m.tr_reg_key || '') + '/' + (m.tr_selfie || '');
            var hasPic   = !!(m.tr_selfie);
            var desig    = m.tamm_designation || '';
            var dc       = DESIG_COLORS[desig] || { bg:'#e3f2fd', color:'#1565c0' };

            html += '<div class="mm-row">' +
                '<div class="mm-num">' + (i + 1) + '</div>' +
                (hasPic
                    ? '<img class="mm-av" src="' + photoUrl + '" ' +
                    'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                    '<div class="mm-av-txt" style="display:none;">' + initials + '</div>'
                    : '<div class="mm-av-txt">' + initials + '</div>') +
                '<div class="mm-info">' +
                '<div class="mm-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                (desig
                    ? '<div class="mm-desig" style="color:' + dc.color + ';">' + ammEsc(desig) + '</div>'
                    : '') +
                (m.tr_mobile
                    ? '<div class="mm-mob"><i class="ti-mobile mr-1"></i>' + ammEsc(m.tr_mobile) + '</div>'
                    : '') +
                '</div>' +
                '</div>';
        });

        body.innerHTML = html;
    }

    /* ── RIGHT PANEL — state-wise active members (photo + name + mobile) ── */
    function ammLoadRightPanel(filters) {
        document.getElementById('dp-list').innerHTML =
            '<div style="padding:16px;font-size:12px;color:#90a4ae;text-align:center;">Loading…</div>';

        var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_state_members_panel', postData,
            function (states) {
                var html = '';
                if (!states || !states.length) {
                    html = '<div style="padding:20px;text-align:center;color:#90a4ae;">' +
                        '<i class="ti-user" style="font-size:28px;opacity:.2;display:block;margin-bottom:8px;"></i>' +
                        '<span style="font-size:11px;">No active members</span></div>';
                } else {
                    states.forEach(function (st) {
                        html += '<div class="sp-state-hdr">' + ammUc(st.state_name) +
                            '<span class="sp-state-cnt">' + st.members.length + '</span></div>';

                        st.members.forEach(function (m) {
                            var initials = ammInitials(m.tr_full_name || '');
                            var photoUrl = BASE_URL + 'uploads/registration/' +
                                (m.tr_reg_key || '') + '/' + (m.tr_selfie || '');
                            var hasPic   = !!(m.tr_selfie);

                            html += '<div class="sp-member" onclick="ammOpenModalById(' + m.mandal_id + ')">' +
                                (hasPic
                                    ? '<img class="sp-av" src="' + photoUrl + '" ' +
                                    'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                                    '<div class="sp-av-txt" style="display:none;">' + initials + '</div>'
                                    : '<div class="sp-av-txt">' + initials + '</div>') +
                                '<div class="sp-info">' +
                                '<div class="sp-name">' + ammUc(m.tr_full_name || '—') + '</div>' +
                                '<div class="sp-mob">' + (m.tr_mobile ? ammEsc(m.tr_mobile) : '—') + '</div>' +
                                '</div>' +
                                '</div>';
                        });
                    });
                }
                document.getElementById('dp-list').innerHTML = html;
            }, 'json')
            .fail(function () {
                document.getElementById('dp-list').innerHTML =
                    '<div style="padding:12px;font-size:11px;color:#e53935;text-align:center;">Failed to load.</div>';
            });
    }

    /* Open modal from right panel row click */
    function ammOpenModalById(mandal_id) {
        var pin = null;
        for (var i = 0; i < ammPinsData.length; i++) {
            if (parseInt(ammPinsData[i].mandal_id) === parseInt(mandal_id)) {
                pin = ammPinsData[i]; break;
            }
        }
        if (pin) {
            ammOpenModal(pin);
        } else {
            /* pins may not be loaded yet — open modal with minimal header */
            ammSetText('modal-title', 'Members');
            ammSetText('modal-sub', '');
            ammSetText('modal-count', '');
            document.getElementById('modal-body').innerHTML =
                '<div class="modal-loading"><div class="m-spin"></div>Loading members…</div>';
            document.getElementById('amm-modal-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
            $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandal_members',
                { mandal_id: mandal_id, [CSRF_NAME]: CSRF_TOKEN },
                function (members) { ammRenderModal(members || []); }, 'json');
        }
    }

    /* ── CLEAR ── */
    function ammClearFilter() {
        ammCloseModal();
        ammLoadPins({});
        ammLoadRightPanel({});
        ammLoadStats();
        ammMap.setView([17.38, 78.49], 7);
    }

    /* ── HELPERS ── */
    function ammSetText(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val;
    }
    function ammUc(str) {
        if (!str) return '';
        return String(str).toLowerCase().replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
    }
    function ammEsc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function ammInitials(name) {
        if (!name) return '?';
        var parts = name.trim().split(' ').filter(Boolean);
        if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
        return (parts[0] || '?')[0].toUpperCase();
    }
</script>