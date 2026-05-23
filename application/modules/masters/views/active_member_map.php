<!-- ============================================================
     active_member_map.php  —  CodeIgniter View  (FINAL FIX)
     Place: application/modules/masters/views/active_member_map.php
============================================================ -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    /* ══════════════════════════════════════════════════
       FULL SCREEN MAP — NO position:fixed (causes conflicts)
       Instead we use a tall flex container that fills the
       remaining page height using calc()
    ══════════════════════════════════════════════════ */
    * { box-sizing: border-box; }

    #amm-page {
        display        : flex;
        flex-direction : column;
        width          : 100%;
        height         : calc(100vh - 120px); /* adjust 120px = your navbar height */
        min-height     : 550px;
        font-family    : 'Segoe UI', Arial, sans-serif;
        background     : #f0f4fb;
        border-radius  : 10px;
        overflow       : hidden;
        box-shadow     : 0 4px 20px rgba(13,71,161,.12);
    }

    /* ── TOOLBAR ───────────────────────────────────── */
    #amm-toolbar {
        background  : linear-gradient(135deg,#0d47a1,#1565c0);
        padding     : 8px 14px;
        display     : flex;
        align-items : center;
        gap         : 7px;
        flex-wrap   : wrap;
        flex-shrink : 0;
        z-index     : 10;
    }
    #amm-toolbar .tbt { color:#fff; font-weight:700; font-size:14px; margin-right:4px; white-space:nowrap; }
    #amm-toolbar select {
        background : rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3);
        color:#fff; border-radius:6px; padding:4px 8px; font-size:12px;
        cursor:pointer; min-width:120px; outline:none;
    }
    #amm-toolbar select option { background:#1565c0; color:#fff; }
    .tb-btn {
        background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.3);
        color:#fff; border-radius:6px; padding:5px 12px; font-size:12px;
        font-weight:700; cursor:pointer; white-space:nowrap; transition:background .2s;
    }
    .tb-btn:hover  { background:rgba(255,255,255,.3); }
    .tb-btn.ac     { background:#fff; color:#0d47a1; }
    .tb-btn.ac:hover { background:#e3f2fd; }

    /* ── STATS BAR ─────────────────────────────────── */
    #amm-stats {
        background:white; padding:5px 14px;
        display:flex; align-items:center; gap:12px;
        border-bottom:1px solid #dde8f5; flex-shrink:0; overflow-x:auto;
    }
    .sc  { display:flex; align-items:center; gap:7px; white-space:nowrap; }
    .sci { width:26px; height:26px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:12px; }
    .ib{background:#e3f2fd;color:#1565c0;} .ig{background:#e8f5e9;color:#2e7d32;}
    .ia{background:#fff8e1;color:#e65100;} .it{background:#e0f2f1;color:#00695c;}
    .scv { font-size:18px; font-weight:800; color:#1a237e; line-height:1; }
    .scl { font-size:9px; color:#78909c; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
    .sp  { width:1px; height:22px; background:#dde8f5; flex-shrink:0; }
    #desig-bar { display:flex; gap:4px; flex-wrap:nowrap; overflow-x:auto; align-items:center; }
    .dbp { border-radius:20px; padding:2px 8px; font-size:10px; font-weight:700; border:1px solid transparent; white-space:nowrap; }

    /* ── GEOCODE STATUS BAR ────────────────────────── */
    #amm-geobar {
        background:#fff3cd; border-bottom:1px solid #ffc107;
        padding:4px 14px; font-size:11px; color:#856404;
        display:none; flex-shrink:0; align-items:center; gap:8px;
    }
    .gspin { width:13px; height:13px; border:2px solid #ffc107; border-top:2px solid #856404; border-radius:50%; animation:ammSpin .6s linear infinite; flex-shrink:0; }
    @keyframes ammSpin { to { transform:rotate(360deg); } }

    /* ── BODY ──────────────────────────────────────── */
    #amm-body {
        display:flex; flex:1; overflow:hidden; min-height:0; position:relative;
    }

    /* ── MAP ───────────────────────────────────────── */
    #amm-map-wrap { flex:1; position:relative; min-width:0; }
    #amm-leaflet  { width:100%; height:100%; }
    .leaflet-container { background:#e8eef7; }

    #amm-loading {
        position:absolute; inset:0; background:rgba(255,255,255,.85);
        display:flex; align-items:center; justify-content:center; z-index:900;
    }
    .lds    { text-align:center; color:#0d47a1; }
    .ldsp   { width:36px; height:36px; border:4px solid #e3f2fd; border-top:4px solid #1e88e5; border-radius:50%; margin:0 auto 8px; animation:ammSpin .8s linear infinite; }

    #amm-legend {
        position:absolute; bottom:24px; left:10px;
        background:rgba(255,255,255,.96); border-radius:8px;
        padding:8px 12px; border:1px solid #dde8f5;
        font-size:10px; z-index:500; box-shadow:0 2px 8px rgba(0,0,0,.1);
    }
    #amm-legend b { display:block; color:#0d47a1; font-size:9px; text-transform:uppercase; letter-spacing:.4px; margin-bottom:4px; }
    .lgr { display:flex; align-items:center; gap:5px; margin-bottom:3px; }
    .lgd { width:11px; height:11px; border-radius:50%; flex-shrink:0; }

    /* ── DISTRICT PANEL ────────────────────────────── */
    #amm-dp {
        width:210px; flex-shrink:0;
        background:rgba(255,255,255,.97);
        border-left:1px solid #dde8f5;
        display:flex; flex-direction:column; overflow:hidden;
    }
    #dp-hdr { background:#0d47a1; color:#fff; font-weight:700; font-size:11px; padding:9px 12px; text-transform:uppercase; letter-spacing:.4px; flex-shrink:0; }
    #dp-list { overflow-y:auto; flex:1; }
    #dp-list::-webkit-scrollbar { width:3px; }
    #dp-list::-webkit-scrollbar-thumb { background:#90caf9; }
    .dpi { padding:7px 12px; border-bottom:1px solid #f0f4fb; cursor:pointer; transition:background .15s; overflow:hidden; }
    .dpi:hover { background:#e3f2fd; }
    .dpi-nm   { font-weight:700; color:#1a237e; font-size:12px; }
    .dpi-meta { font-size:10px; color:#90a4ae; margin-top:1px; }
    .dpi-cnt  { float:right; background:#1e88e5; color:#fff; border-radius:10px; padding:2px 7px; font-size:10px; font-weight:700; }
    .dpi-dc   { display:flex; flex-wrap:wrap; gap:3px; margin-top:4px; clear:both; }
    .dchip    { border-radius:4px; padding:1px 5px; font-size:9px; font-weight:700; }

    /* ── SIDEBAR ───────────────────────────────────── */
    #amm-sb {
        width:370px; flex-shrink:0; background:#fff;
        border-left:1px solid #dde8f5;
        display:flex; flex-direction:column;
        transform:translateX(100%);
        transition:transform .3s cubic-bezier(.4,0,.2,1);
        position:absolute; right:0; top:0; height:100%; z-index:600;
    }
    #amm-sb.open { transform:translateX(0); }

    #sbhdr { background:linear-gradient(135deg,#0d47a1,#1565c0); padding:12px 15px; flex-shrink:0; position:relative; }
    .sbm   { color:#fff; font-weight:800; font-size:15px; margin-bottom:2px; }
    .sbl   { color:#90caf9; font-size:11px; }
    #sbcls { position:absolute; top:11px; right:11px; width:26px; height:26px; background:rgba(255,255,255,.22); border:none; border-radius:50%; color:#fff; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; }
    #sbcls:hover { background:rgba(255,255,255,.38); }

    /* Sidebar location info bar */
    #sb-locbar {
        background:#e8f0fe; border-bottom:1px solid #c5d5f8;
        padding:7px 13px; flex-shrink:0;
    }
    .sbloc-row { display:flex; align-items:center; gap:6px; font-size:11px; color:#283593; margin-bottom:3px; }
    .sbloc-row:last-child { margin-bottom:0; }
    .sbloc-icon { font-size:12px; color:#1565c0; flex-shrink:0; }
    .sbloc-val  { font-weight:600; }

    #sbchips { display:flex; border-bottom:1px solid #dde8f5; background:#f8fbff; flex-shrink:0; }
    .sbc2   { flex:1; text-align:center; padding:7px 4px; border-right:1px solid #dde8f5; }
    .sbc2:last-child { border-right:none; }
    .sbcv   { font-size:18px; font-weight:800; color:#0d47a1; line-height:1; }
    .sbcl   { font-size:9px; color:#78909c; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }

    #sbdesig { padding:8px 12px; border-bottom:1px solid #dde8f5; background:#f0f7ff; flex-shrink:0; display:flex; flex-wrap:wrap; gap:4px; }
    .sdch { border-radius:20px; padding:2px 8px; font-size:10px; font-weight:700; }

    #sblist { flex:1; overflow-y:auto; padding:10px; }
    #sblist::-webkit-scrollbar { width:3px; }
    #sblist::-webkit-scrollbar-thumb { background:#90caf9; }

    .sbsec { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; padding:5px 9px; border-radius:5px; margin:0 -2px 7px; }

    /* Member card */
    .mc { background:#fff; border:1px solid #dde8f5; border-radius:9px; margin-bottom:8px; overflow:hidden; cursor:pointer; transition:box-shadow .2s; }
    .mc:hover { box-shadow:0 3px 14px rgba(13,71,161,.11); }
    .mc.open  { border-color:#1e88e5; }
    .mct  { display:flex; align-items:center; gap:9px; padding:9px 12px; }
    .mcav { width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid #1e88e5; flex-shrink:0; background:#e3f2fd; }
    .mcavt{ width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0; }
    .mci  { flex:1; min-width:0; }
    .mcnm { font-weight:700; font-size:13px; color:#1a237e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .mcdg { display:inline-block; border-radius:20px; padding:2px 7px; font-size:9px; font-weight:700; margin-top:2px; }
    .mcarr{ color:#90a4ae; font-size:11px; transition:transform .25s; flex-shrink:0; }
    .mc.open .mcarr { transform:rotate(180deg); }
    .mcb  { border-top:1px solid #dde8f5; background:#f8fbff; max-height:0; overflow:hidden; transition:max-height .35s ease; }
    .mc.open .mcb { max-height:600px; }
    .mcbi { padding:11px; }

    /* Member detail photo */
    .mcph { text-align:center; margin-bottom:10px; }
    .mcph img { width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #1e88e5; box-shadow:0 3px 10px rgba(13,71,161,.18); }
    .mcpht { width:72px; height:72px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:22px; }

    /* Member detail rows */
    .dr  { display:flex; gap:5px; margin-bottom:5px; font-size:11px; border-bottom:1px dashed #f0f0f0; padding-bottom:4px; }
    .dr:last-child { border-bottom:none; margin-bottom:0; }
    .dl  { font-weight:700; color:#78909c; min-width:85px; flex-shrink:0; }
    .dv  { color:#263238; word-break:break-word; }

    /* Location section inside member card */
    .mc-loc-section {
        background:#e8f0fe; border-radius:7px; padding:8px 10px; margin-bottom:8px;
        border:1px solid #c5d5f8;
    }
    .mc-loc-title { font-size:9px; font-weight:800; color:#1565c0; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
    .mc-loc-row   { display:flex; align-items:flex-start; gap:6px; font-size:11px; margin-bottom:3px; color:#283593; }
    .mc-loc-row:last-child { margin-bottom:0; }
    .mc-loc-icon  { flex-shrink:0; color:#1565c0; font-size:11px; margin-top:1px; }

    /* Contact section inside member card */
    .mc-contact-section {
        background:#e8f5e9; border-radius:7px; padding:8px 10px; margin-bottom:8px;
        border:1px solid #a5d6a7;
    }
    .mc-contact-title { font-size:9px; font-weight:800; color:#2e7d32; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; }
    .mc-contact-row   { display:flex; align-items:center; gap:6px; font-size:11px; margin-bottom:3px; color:#1b5e20; }
    .mc-contact-row:last-child { margin-bottom:0; }
    .mc-contact-icon  { flex-shrink:0; color:#2e7d32; font-size:11px; }
    .mc-contact-val   { font-weight:600; word-break:break-all; }

    #sbnd { display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:#90a4ae; padding:24px; text-align:center; }
    #sbnd i { font-size:40px; opacity:.25; margin-bottom:10px; }
    .sbspin { width:26px; height:26px; border:3px solid #e3f2fd; border-top:3px solid #1e88e5; border-radius:50%; margin:0 auto 8px; animation:ammSpin .8s linear infinite; }

    /* Popup */
    .lp-wrap  { min-width:220px; max-width:270px; font-family:'Segoe UI',Arial,sans-serif; }
    .lpttl    { font-weight:800; font-size:14px; color:#0d47a1; margin-bottom:2px; }
    .lp-locrow{ font-size:11px; color:#546e7a; margin-bottom:2px; display:flex; align-items:center; gap:4px; }
    .lptot    { display:inline-block; background:#e3f2fd; color:#1565c0; border-radius:5px; padding:2px 8px; font-size:11px; font-weight:700; margin:6px 0; }
    .lpdc2    { display:flex; flex-wrap:wrap; gap:3px; margin-bottom:7px; }
    .lpd      { border-radius:4px; padding:2px 6px; font-size:10px; font-weight:700; }
    .lpnm     { font-size:11px; color:#546e7a; margin-bottom:8px; line-height:1.6; }
    .lpbtn    { display:block; width:100%; background:#0d47a1; color:#fff; border:none; border-radius:7px; padding:7px; font-size:12px; font-weight:700; cursor:pointer; text-align:center; }
    .lpbtn:hover { background:#1565c0; }

    @media(max-width:768px) {
        #amm-sb { width:100%; }
        #amm-dp { display:none; }
        #amm-page { height:calc(100vh - 60px); }
    }
</style>

<!-- ══════════════════════════════════════════════ -->
<div id="amm-page">

    <!-- TOOLBAR -->
    <div id="amm-toolbar">
        <span class="tbt"><i class="ti-map mr-1"></i> Active Member Map</span>

        <select id="tb-country" onchange="ammOnCountry()">
            <option value="">&#127758; All Countries</option>
            <?php if (!empty($country_list)): foreach ($country_list as $c): ?>
                <option value="<?php echo (int)$c['tc_country_ID']; ?>">
                    <?php echo htmlspecialchars(ucwords(strtolower($c['tc_country_name']))); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <select id="tb-state"    onchange="ammOnState()">   <option value="">All States</option></select>
        <select id="tb-district" onchange="ammOnDistrict()"><option value="">All Districts</option></select>
        <select id="tb-mandal">                             <option value="">All Mandals</option></select>

        <button class="tb-btn ac" onclick="ammApplyFilter()"><i class="ti-search"></i> Search</button>
        <button class="tb-btn"    onclick="ammClearFilter()"><i class="ti-close"></i> Clear</button>
    </div>

    <!-- STATS BAR -->
    <div id="amm-stats">
        <div class="sc"><div class="sci ib"><i class="ti-user"></i></div><div><div class="scv" id="st-m">—</div><div class="scl">Members</div></div></div>
        <div class="sp"></div>
        <div class="sc"><div class="sci ig"><i class="ti-location-pin"></i></div><div><div class="scv" id="st-mn">—</div><div class="scl">Mandals</div></div></div>
        <div class="sp"></div>
        <div class="sc"><div class="sci ia"><i class="ti-map-alt"></i></div><div><div class="scv" id="st-d">—</div><div class="scl">Districts</div></div></div>
        <div class="sp"></div>
        <div class="sc"><div class="sci it"><i class="ti-world"></i></div><div><div class="scv" id="st-s">—</div><div class="scl">States</div></div></div>
        <div class="sp"></div>
        <div id="desig-bar"></div>
    </div>

    <!-- GEOCODE STATUS BAR -->
    <div id="amm-geobar">
        <div class="gspin"></div>
        <span id="geo-txt">Locating mandals on map…</span>
        <span id="geo-prog" style="color:#0d47a1;font-weight:700;margin-left:6px;"></span>
    </div>

    <!-- BODY -->
    <div id="amm-body">

        <!-- MAP -->
        <div id="amm-map-wrap">
            <div id="amm-loading">
                <div class="lds"><div class="ldsp"></div><div style="font-weight:700;font-size:13px;">Loading Map…</div></div>
            </div>
            <div id="amm-leaflet"></div>
            <div id="amm-legend">
                <b>Members / Mandal</b>
                <div class="lgr"><div class="lgd" style="background:#4caf50;"></div> 1 member</div>
                <div class="lgr"><div class="lgd" style="background:#ff9800;"></div> 2–4 members</div>
                <div class="lgr"><div class="lgd" style="background:#f44336;"></div> 5+ members</div>
            </div>
        </div>

        <!-- DISTRICT PANEL -->
        <div id="amm-dp">
            <div id="dp-hdr"><i class="ti-layout-grid2 mr-1"></i> Districts</div>
            <div id="dp-list">
                <div style="padding:16px;text-align:center;">
                    <div class="ldsp" style="width:22px;height:22px;margin:0 auto 6px;"></div>
                    <span style="font-size:11px;color:#90a4ae;">Loading…</span>
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div id="amm-sb">
            <div id="sbhdr">
                <div class="sbm" id="sb-mname">—</div>
                <div class="sbl" id="sb-tagline"></div>
                <button id="sbcls" onclick="ammCloseSb()">&#x2715;</button>
            </div>

            <!-- Location info bar -->
            <div id="sb-locbar">
                <div class="sbloc-row"><i class="ti-world sbloc-icon"></i><span class="sbloc-val" id="sb-country">—</span></div>
                <div class="sbloc-row"><i class="ti-map sbloc-icon"></i><span class="sbloc-val" id="sb-state">—</span></div>
                <div class="sbloc-row"><i class="ti-map-alt sbloc-icon"></i><span class="sbloc-val" id="sb-district">—</span></div>
                <div class="sbloc-row"><i class="ti-location-pin sbloc-icon"></i><span class="sbloc-val" id="sb-mandal-loc">—</span></div>
            </div>

            <div id="sbchips">
                <div class="sbc2"><div class="sbcv" id="sb-cm">0</div><div class="sbcl">Members</div></div>
                <div class="sbc2"><div class="sbcv" id="sb-cr">0</div><div class="sbcl">Roles</div></div>
            </div>
            <div id="sbdesig"></div>
            <div id="sblist">
                <div id="sbnd"><i class="ti-pin-alt"></i><p>Click a map pin to view members</p></div>
            </div>
        </div>

    </div><!-- /amm-body -->
</div><!-- /amm-page -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    /* ═══════════════════════════════════════
       GLOBALS
    ═══════════════════════════════════════ */
    var BASE_URL   = "<?php echo base_url(); ?>";
    var CSRF_NAME  = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var CSRF_TOKEN = "<?php echo $this->security->get_csrf_hash(); ?>";

    var DC = {
        'PRESIDENT'                :{ bg:'#fce4ec', c:'#c62828', s:'PRES'  },
        'VICE PRESIDENT'           :{ bg:'#e8eaf6', c:'#283593', s:'VP'    },
        'GENERAL SECRETARY'        :{ bg:'#e8f5e9', c:'#1b5e20', s:'GS'    },
        'JOINT SECRETARY'          :{ bg:'#e0f2f1', c:'#004d40', s:'JS'    },
        'TREASURER'                :{ bg:'#fff8e1', c:'#e65100', s:'TRES'  },
        'EXECUTIVE MEMBER'         :{ bg:'#f3e5f5', c:'#4a148c', s:'EM'    },
        'REGIONAL DISTRICT OFFICER':{ bg:'#e0f7fa', c:'#006064', s:'RDO'  },
        'DISTRICT OFFICER'         :{ bg:'#fbe9e7', c:'#bf360c', s:'DO'    },
        'MANDAL OFFICER'           :{ bg:'#f9fbe7', c:'#558b2f', s:'MO'    },
    };

    var ammMap, ammPopup;
    var ammMarkers = [];
    var ammPins    = [];
    var geoQ       = [];
    var geoTotal   = 0;
    var geoDone    = 0;
    var geoActive  = false;

    /* ═══════════════════════════════════════
       INIT — called on window load
       Uses setTimeout so the flex container
       has fully rendered before Leaflet init
    ═══════════════════════════════════════ */
    window.addEventListener('load', function () {
        setTimeout(function () {
            var el = document.getElementById('amm-leaflet');
            if (!el) return;

            ammMap = L.map('amm-leaflet', {
                center    : [20.5937, 78.9629],   /* India centre */
                zoom      : 5,
                zoomControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
                maxZoom    : 19,
            }).addTo(ammMap);

            ammPopup = L.popup({ maxWidth: 290, className: 'amm-popup' });

            ammMap.invalidateSize();
            window.addEventListener('resize', function () { ammMap.invalidateSize(); });

            document.getElementById('amm-loading').style.display = 'none';

            /* Load everything on start */
            ammLoadStats();
            ammLoadPins({});        /* DEFAULT — all pins */
            ammLoadDistricts({});   /* DEFAULT — all districts */

        }, 200);
    });

    /* ═══════════════════════════════════════
       STATS
    ═══════════════════════════════════════ */
    function ammLoadStats() {
        $.post(BASE_URL + 'masters/ActiveMemberMap/get_stats', { [CSRF_NAME]: CSRF_TOKEN }, function (r) {
            if (!r) return;
            T('st-m',  r.total_members   || 0);
            T('st-mn', r.total_mandals   || 0);
            T('st-d',  r.total_districts || 0);
            T('st-s',  r.total_states    || 0);
            var h = '';
            $.each(r.designations || {}, function (d, n) {
                if (!n) return;
                var c = DC[d] || { bg:'#e3f2fd', c:'#1565c0', s:d.substring(0,2) };
                h += '<span class="dbp" style="background:' + c.bg + ';color:' + c.c + ';border-color:' + c.c + '44;">' + c.s + ' <b>' + n + '</b></span>';
            });
            document.getElementById('desig-bar').innerHTML = h || '<span style="font-size:10px;color:#90a4ae;">No data</span>';
        }, 'json');
    }

    /* ═══════════════════════════════════════
       LOAD PINS — works for default (empty
       filters) AND after search
    ═══════════════════════════════════════ */
    function ammLoadPins(filters) {
        document.getElementById('amm-loading').style.display = 'flex';

        /* Clear old markers */
        ammMarkers.forEach(function (m) { m.remove(); });
        ammMarkers = [];
        ammPins    = [];
        geoQ       = [];
        geoActive  = false;
        geoTotal   = 0;
        geoDone    = 0;

        var postData = $.extend({}, filters || {}, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_map_pins', postData, function (pins) {
            document.getElementById('amm-loading').style.display = 'none';
            ammPins = pins || [];

            if (!ammPins.length) {
                hideGeoBar();
                return;
            }

            var instant = [];
            var queued  = [];

            ammPins.forEach(function (p) {
                var la = parseFloat(p.lat);
                var ln = parseFloat(p.lng);
                if (la && ln && la !== 0 && ln !== 0) {
                    instant.push({ p: p, la: la, ln: ln });
                } else {
                    queued.push(p);
                }
            });

            /* Place stored-coordinate pins immediately */
            instant.forEach(function (x) { ammPlace(x.p, x.la, x.ln); });

            /* Queue name-based geocoding for the rest */
            if (queued.length) {
                geoTotal = queued.length;
                geoDone  = 0;
                queued.forEach(function (p) { geoQ.push(p); });
                showGeoBar('Locating ' + geoTotal + ' mandal' + (geoTotal > 1 ? 's' : '') + ' on map…');
                if (!geoActive) ammGeoNext();
            } else {
                hideGeoBar();
            }

            /* Fit bounds after a short delay so geocoded pins are included */
            setTimeout(function () { ammFitBounds(); ammMap.invalidateSize(); }, 600);

        }, 'json').fail(function () {
            document.getElementById('amm-loading').style.display = 'none';
        });
    }

    /* ═══════════════════════════════════════
       NOMINATIM GEOCODING QUEUE
       1 req/sec — OSM Terms of Service
       Strategy: Mandal+District+State → fallback District+State
    ═══════════════════════════════════════ */
    function ammGeoNext() {
        if (!geoQ.length) {
            geoActive = false;
            hideGeoBar();
            ammFitBounds();
            return;
        }
        geoActive = true;
        var pin   = geoQ.shift();

        T('geo-txt',  'Locating: ' + uc(pin.mandal_name));
        T('geo-prog', '(' + geoDone + '/' + geoTotal + ')');

        var q = [pin.mandal_name, pin.district_name, pin.state_name, 'India']
            .filter(Boolean).join(', ');

        nominatim(q, function (la, ln) {
            if (la && ln) {
                ammPlace(pin, la, ln);
                geoDone++;
                setTimeout(ammGeoNext, 1100);
            } else {
                /* Fallback: district + state */
                var q2 = [pin.district_name, pin.state_name, 'India'].filter(Boolean).join(', ');
                nominatim(q2, function (la2, ln2) {
                    if (la2 && ln2) {
                        /* Small random offset so pins don't stack */
                        ammPlace(pin,
                            la2 + (Math.random() * 0.1 - 0.05),
                            ln2 + (Math.random() * 0.1 - 0.05)
                        );
                    }
                    geoDone++;
                    setTimeout(ammGeoNext, 1100);
                });
            }
        });
    }

    function nominatim(query, cb) {
        $.ajax({
            url     : 'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=in&q=' + encodeURIComponent(query),
            dataType: 'json',
            headers : { 'Accept-Language': 'en' },
            success : function (d) { cb(d && d.length ? parseFloat(d[0].lat) : 0, d && d.length ? parseFloat(d[0].lon) : 0); },
            error   : function ()  { cb(0, 0); }
        });
    }

    function ammFitBounds() {
        if (ammMarkers.length) {
            try {
                ammMap.fitBounds(
                    L.latLngBounds(ammMarkers.map(function (m) { return m.getLatLng(); })),
                    { padding: [50, 50], maxZoom: 13 }
                );
            } catch(e) {}
        }
    }

    function showGeoBar(msg) { var b = document.getElementById('amm-geobar'); b.style.display = 'flex'; T('geo-txt', msg || ''); }
    function hideGeoBar()    { document.getElementById('amm-geobar').style.display = 'none'; }

    /* ═══════════════════════════════════════
       PLACE MARKER
    ═══════════════════════════════════════ */
    function pinColor(n) { return n >= 5 ? '#f44336' : n >= 2 ? '#ff9800' : '#4caf50'; }

    function ammPlace(pin, la, ln) {
        var n   = parseInt(pin.member_count) || 0;
        var col = pinColor(n);
        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="52" viewBox="0 0 40 52">' +
            '<path fill="' + col + '" d="M20 2C12.27 2 6 8.27 6 16c0 11.5 14 34 14 34s14-22.5 14-34c0-7.73-6.27-14-14-14z"/>' +
            '<circle fill="rgba(0,0,0,.18)" cx="20" cy="16" r="8.5"/>' +
            '<text x="20" y="21" text-anchor="middle" fill="#fff" font-family="Arial,sans-serif" font-weight="800" font-size="' + (n > 9 ? '8' : '10') + '">' + n + '</text>' +
            '</svg>';

        var mk = L.marker([la, ln], {
            icon : L.divIcon({ html:svg, className:'', iconSize:[40,52], iconAnchor:[20,52], popupAnchor:[0,-56] }),
            title: uc(pin.mandal_name) + ' — ' + n + ' member' + (n !== 1 ? 's' : '')
        }).addTo(ammMap);

        mk.pinData = pin;
        mk.on('click', function () { ammShowPopup(mk, pin); });
        ammMarkers.push(mk);
    }

    /* ═══════════════════════════════════════
       POPUP — shows on pin click
       Includes: Mandal, District, State,
       member count, designation chips,
       member name preview, View button
    ═══════════════════════════════════════ */
    function ammShowPopup(mk, pin) {
        var dc    = pin.designation_counts || {};
        var names = (pin.member_names_arr  || []).slice(0, 4);

        var dh = '';
        $.each(DC, function (desig, c) {
            var n = dc[desig] || 0;
            if (!n) return;
            dh += '<span class="lpd" style="background:' + c.bg + ';color:' + c.c + ';">' + c.s + ' ' + n + '</span>';
        });

        var nh = '';
        if (names.length) {
            nh = '<div class="lpnm">' +
                names.map(function (n) { return '&#x2022; ' + uc(n); }).join('<br>') +
                (pin.member_count > 4 ? '<br><em>+' + (pin.member_count - 4) + ' more…</em>' : '') +
                '</div>';
        }

        var html =
            '<div class="lp-wrap">' +
            '<div class="lpttl"><i class="ti-location-pin" style="color:#1565c0;margin-right:4px;"></i>' + uc(pin.mandal_name) + '</div>' +
            '<div class="lp-locrow"><i class="ti-map-alt" style="color:#1565c0;"></i> ' + uc(pin.district_name || '—') + '</div>' +
            '<div class="lp-locrow"><i class="ti-map"     style="color:#1565c0;"></i> ' + uc(pin.state_name   || '—') + '</div>' +
            '<div class="lp-locrow"><i class="ti-world"   style="color:#1565c0;"></i> ' + uc(pin.country_name || '—') + '</div>' +
            '<span class="lptot">&#128100; ' + pin.member_count + ' Active Member' + (pin.member_count !== 1 ? 's' : '') + '</span>' +
            (dh ? '<div class="lpdc2">' + dh + '</div>' : '') +
            nh +
            '<button class="lpbtn" onclick="ammOpenSb(' + pin.mandal_id + ')">View All Members &#8594;</button>' +
            '</div>';

        ammPopup.setLatLng(mk.getLatLng()).setContent(html).openOn(ammMap);
    }

    /* ═══════════════════════════════════════
       SIDEBAR OPEN
       Shows full location: Country, State,
       District, Mandal + member details
    ═══════════════════════════════════════ */
    function ammOpenSb(mid) {
        ammMap.closePopup();

        var pin = null;
        for (var i = 0; i < ammPins.length; i++) {
            if (parseInt(ammPins[i].mandal_id) === parseInt(mid)) { pin = ammPins[i]; break; }
        }

        if (pin) {
            T('sb-mname',     uc(pin.mandal_name));
            T('sb-tagline',   uc(pin.district_name || '') + ' · ' + uc(pin.state_name || ''));

            /* Location bar */
            T('sb-country',   uc(pin.country_name  || '—'));
            T('sb-state',     uc(pin.state_name    || '—'));
            T('sb-district',  uc(pin.district_name || '—'));
            T('sb-mandal-loc',uc(pin.mandal_name   || '—') + ' Mandal');

            /* Stat chips */
            var dc    = pin.designation_counts || {};
            var roles = 0;
            $.each(dc, function (k, v) { if (v > 0) roles++; });
            T('sb-cm', pin.member_count);
            T('sb-cr', roles);

            /* Designation summary */
            var dh = '';
            $.each(DC, function (d, c) {
                var n = dc[d] || 0;
                if (!n) return;
                dh += '<span class="sdch" style="background:' + c.bg + ';color:' + c.c + ';">' + c.s + ' <b>' + n + '</b></span>';
            });
            document.getElementById('sbdesig').innerHTML = dh;
        }

        /* Loading state */
        document.getElementById('sblist').innerHTML =
            '<div style="text-align:center;padding:28px 0;color:#78909c;">' +
            '<div class="sbspin"></div>' +
            '<div style="font-size:12px;font-weight:600;">Loading members…</div></div>';

        document.getElementById('amm-sb').classList.add('open');

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandal_members',
            { mandal_id: mid, [CSRF_NAME]: CSRF_TOKEN },
            function (ms) { ammRenderMembers(ms || [], pin); },
            'json'
        );
    }

    function ammCloseSb() { document.getElementById('amm-sb').classList.remove('open'); }

    /* ═══════════════════════════════════════
       RENDER MEMBERS — grouped by designation
       Each card shows:
       • Photo/avatar
       • Name + designation badge
       • Expand → full details:
           Country, State, District, Mandal
           Mobile, Email, DOB, Address, etc.
    ═══════════════════════════════════════ */
    function ammRenderMembers(ms, pin) {
        var el = document.getElementById('sblist');

        if (!ms || !ms.length) {
            el.innerHTML = '<div id="sbnd"><i class="ti-user"></i><p>No active members found.</p></div>';
            return;
        }

        /* Group by designation */
        var grps = {};
        ms.forEach(function (m) {
            var d = m.tamm_designation || 'OTHER';
            if (!grps[d]) grps[d] = [];
            grps[d].push(m);
        });

        var html = '';
        var done = {};

        /* Render in canonical order */
        Object.keys(DC).forEach(function (desig) {
            if (!grps[desig] || !grps[desig].length) return;
            var c   = DC[desig];
            var grp = grps[desig];

            html += '<div class="sbsec" style="background:' + c.bg + ';color:' + c.c + ';">' +
                desig + ' <span style="opacity:.7;">(' + grp.length + ')</span></div>';

            grp.forEach(function (m, idx) {
                var cid   = 'mc_' + desig.replace(/\s+/g, '_') + '_' + idx;
                var ph    = BASE_URL + 'uploads/registration/' + (m.tr_reg_key || '') + '/' + (m.tr_selfie || '');
                var ini   = initials(m.tr_full_name || '');
                var hasPh = !!m.tr_selfie;

                /* Country, State, District, Mandal for this member */
                var cname = uc(m.country_name  || (pin ? pin.country_name  : '') || '—');
                var sname = uc(m.state_name    || (pin ? pin.state_name    : '') || '—');
                var dname = uc(m.district_name || (pin ? pin.district_name : '') || '—');
                var mname = uc(m.mandal_name   || (pin ? pin.mandal_name   : '') || '—');

                html +=
                    '<div class="mc" id="' + cid + '" onclick="ammTog(\'' + cid + '\')">' +
                    '<div class="mct">' +
                    (hasPh
                        ? '<img class="mcav" src="' + ph + '" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" alt="">' +
                        '<div class="mcavt" style="background:' + c.c + ';color:#fff;display:none;">' + ini + '</div>'
                        : '<div class="mcavt" style="background:' + c.c + ';color:#fff;">' + ini + '</div>') +
                    '<div class="mci">' +
                    '<div class="mcnm">' + uc(m.tr_full_name || '—') + '</div>' +
                    '<div class="mcdg" style="background:' + c.bg + ';color:' + c.c + ';">' + esc(m.tamm_designation) + '</div>' +
                    '</div>' +
                    '<span class="mcarr">&#x25BE;</span>' +
                    '</div>' +

                    '<div class="mcb"><div class="mcbi">' +
                    /* Photo large */
                    '<div class="mcph">' +
                    (hasPh
                        ? '<img src="' + ph + '" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'inline-flex\';" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid ' + c.c + ';">' +
                        '<div class="mcpht" style="background:' + c.c + ';color:#fff;display:none;">' + ini + '</div>'
                        : '<div class="mcpht" style="background:' + c.c + ';color:#fff;">' + ini + '</div>') +
                    '</div>' +

                    /* Location section */
                    '<div class="mc-loc-section">' +
                    '<div class="mc-loc-title"><i class="ti-location-pin mr-1"></i> Location</div>' +
                    '<div class="mc-loc-row"><i class="ti-world mc-loc-icon"></i><span>' + cname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-map mc-loc-icon"></i><span>' + sname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-map-alt mc-loc-icon"></i><span>' + dname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-location-pin mc-loc-icon"></i><span>' + mname + ' Mandal</span></div>' +
                    (m.tr_village ? '<div class="mc-loc-row"><i class="ti-home mc-loc-icon"></i><span>' + uc(m.tr_village) + '</span></div>' : '') +
                    (m.tr_pincode ? '<div class="mc-loc-row"><i class="ti-bookmark mc-loc-icon"></i><span>PIN: ' + esc(m.tr_pincode) + '</span></div>' : '') +
                    '</div>' +

                    /* Contact section */
                    '<div class="mc-contact-section">' +
                    '<div class="mc-contact-title"><i class="ti-mobile mr-1"></i> Contact</div>' +
                    '<div class="mc-contact-row"><i class="ti-mobile mc-contact-icon"></i><span class="mc-contact-val">' + esc(m.tr_mobile || '—') + '</span></div>' +
                    (m.tr_email ? '<div class="mc-contact-row"><i class="ti-email mc-contact-icon"></i><span class="mc-contact-val">' + esc(m.tr_email) + '</span></div>' : '') +
                    '</div>' +

                    /* Other details */
                    dr('DOB',      m.tr_dob                 || '—') +
                    dr('Language', m.tr_language            || '—') +
                    dr('Reg Type', m.tr_registration_type   || '—') +
                    dr('Reg Key',  m.tr_reg_key             || '—') +
                    dr('Unique',   m.tr_reg_ukey            || '—') +
                    dr('Aadhaar',  m.tr_aadhar_no ? '****' + (m.tr_aadhar_no + '').slice(-4) : '—') +
                    dr('PAN',      m.tr_pan_no              || '—') +
                    (m.tr_full_address ? dr('Address', m.tr_full_address) : '') +

                    '</div></div>' +
                    '</div>';
            });

            done[desig] = true;
        });

        /* Non-standard designations */
        $.each(grps, function (d, grp) {
            if (done[d] || !grp || !grp.length) return;
            html += '<div class="sbsec" style="background:#f5f5f5;color:#546e7a;">' + esc(d) + ' (' + grp.length + ')</div>';
            grp.forEach(function (m, idx) {
                var cid = 'mc_oth_' + idx;
                var ini = initials(m.tr_full_name || '');
                var cname = uc(m.country_name  || (pin ? pin.country_name  : '') || '—');
                var sname = uc(m.state_name    || (pin ? pin.state_name    : '') || '—');
                var dname = uc(m.district_name || (pin ? pin.district_name : '') || '—');
                var mname = uc(m.mandal_name   || (pin ? pin.mandal_name   : '') || '—');
                html +=
                    '<div class="mc" id="' + cid + '" onclick="ammTog(\'' + cid + '\')">' +
                    '<div class="mct"><div class="mcavt" style="background:#78909c;color:#fff;">' + ini + '</div>' +
                    '<div class="mci"><div class="mcnm">' + uc(m.tr_full_name || '—') + '</div>' +
                    '<div class="mcdg" style="background:#f5f5f5;color:#546e7a;">' + esc(m.tamm_designation) + '</div></div>' +
                    '<span class="mcarr">&#x25BE;</span></div>' +
                    '<div class="mcb"><div class="mcbi">' +
                    '<div class="mc-loc-section">' +
                    '<div class="mc-loc-title"><i class="ti-location-pin mr-1"></i> Location</div>' +
                    '<div class="mc-loc-row"><i class="ti-world mc-loc-icon"></i><span>' + cname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-map mc-loc-icon"></i><span>' + sname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-map-alt mc-loc-icon"></i><span>' + dname + '</span></div>' +
                    '<div class="mc-loc-row"><i class="ti-location-pin mc-loc-icon"></i><span>' + mname + ' Mandal</span></div>' +
                    '</div>' +
                    '<div class="mc-contact-section">' +
                    '<div class="mc-contact-title"><i class="ti-mobile mr-1"></i> Contact</div>' +
                    '<div class="mc-contact-row"><i class="ti-mobile mc-contact-icon"></i><span class="mc-contact-val">' + esc(m.tr_mobile || '—') + '</span></div>' +
                    (m.tr_email ? '<div class="mc-contact-row"><i class="ti-email mc-contact-icon"></i><span class="mc-contact-val">' + esc(m.tr_email) + '</span></div>' : '') +
                    '</div>' +
                    dr('Address', m.tr_full_address || '—') +
                    '</div></div></div>';
            });
        });

        el.innerHTML = html;
    }

    function ammTog(id) {
        var el = document.getElementById(id);
        if (!el) return;
        var wasOpen = el.classList.contains('open');
        document.querySelectorAll('.mc.open').forEach(function (c) { c.classList.remove('open'); });
        if (!wasOpen) el.classList.add('open');
    }

    /* ═══════════════════════════════════════
       DISTRICT PANEL
    ═══════════════════════════════════════ */
    function ammLoadDistricts(filters) {
        var pd = $.extend({}, filters || {}, { [CSRF_NAME]: CSRF_TOKEN });

        $.post(BASE_URL + 'masters/ActiveMemberMap/get_district_summary', pd, function (ds) {
            var h = '';
            if (!ds || !ds.length) {
                h = '<div style="padding:14px;font-size:11px;color:#90a4ae;text-align:center;">No districts found</div>';
            } else {
                ds.forEach(function (d) {
                    var dc = d.designation_counts || {};
                    var ch = '';
                    $.each(DC, function (dg, c) {
                        var n = dc[dg] || 0;
                        if (!n) return;
                        ch += '<span class="dchip" style="background:' + c.bg + ';color:' + c.c + ';">' + c.s + ' ' + n + '</span>';
                    });
                    h += '<div class="dpi" onclick="ammZoomDist(' + d.district_id + ')">' +
                        '<span class="dpi-cnt">' + d.member_count + '</span>' +
                        '<div class="dpi-nm">' + uc(d.district_name) + '</div>' +
                        '<div class="dpi-meta">' + d.mandal_count + ' mandal' + (d.mandal_count !== 1 ? 's' : '') +
                        (d.state_name ? ' &bull; ' + uc(d.state_name) : '') + '</div>' +
                        (ch ? '<div class="dpi-dc">' + ch + '</div>' : '') +
                        '</div>';
                });
            }
            document.getElementById('dp-list').innerHTML = h;
        }, 'json');
    }

    function ammZoomDist(did) {
        ammLoadPins({ district_id: did });
        ammLoadDistricts({ district_id: did });
    }

    /* ═══════════════════════════════════════
       TOOLBAR CASCADE
    ═══════════════════════════════════════ */
    function ammOnCountry() {
        var cid = document.getElementById('tb-country').value;
        rst('tb-state','All States'); rst('tb-district','All Districts'); rst('tb-mandal','All Mandals');
        if (!cid) return;
        $.post(BASE_URL + 'masters/ActiveMemberMap/get_states', { country_id:cid, [CSRF_NAME]:CSRF_TOKEN }, function (a) {
            var h = '<option value="">All States</option>';
            (a||[]).forEach(function (s) { h += '<option value="' + s.ts_state_ID + '">' + uc(s.ts_state_name) + '</option>'; });
            document.getElementById('tb-state').innerHTML = h;
        }, 'json');
    }

    function ammOnState() {
        var sid = document.getElementById('tb-state').value;
        rst('tb-district','All Districts'); rst('tb-mandal','All Mandals');
        if (!sid) return;
        $.post(BASE_URL + 'masters/ActiveMemberMap/get_districts', { state_id:sid, [CSRF_NAME]:CSRF_TOKEN }, function (a) {
            var h = '<option value="">All Districts</option>';
            (a||[]).forEach(function (d) { h += '<option value="' + d.tdt_district_ID + '">' + uc(d.tdt_district_name) + '</option>'; });
            document.getElementById('tb-district').innerHTML = h;
        }, 'json');
    }

    function ammOnDistrict() {
        var did = document.getElementById('tb-district').value;
        rst('tb-mandal','All Mandals');
        if (!did) return;
        $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandals', { district_id:did, [CSRF_NAME]:CSRF_TOKEN }, function (a) {
            var h = '<option value="">All Mandals</option>';
            (a||[]).forEach(function (m) { h += '<option value="' + m.tm_mandal_ID + '">' + uc(m.tm_mandal) + '</option>'; });
            document.getElementById('tb-mandal').innerHTML = h;
        }, 'json');
    }

    function ammApplyFilter() {
        var f = {
            country_id  : document.getElementById('tb-country').value  || 0,
            state_id    : document.getElementById('tb-state').value    || 0,
            district_id : document.getElementById('tb-district').value || 0,
            mandal_id   : document.getElementById('tb-mandal').value   || 0,
        };
        ammLoadPins(f);
        ammLoadDistricts(f);

        /* If specific mandal chosen, open sidebar after geocoding delay */
        var mid = document.getElementById('tb-mandal').value;
        if (mid) {
            setTimeout(function () { ammOpenSb(parseInt(mid)); }, 2400);
        }
    }

    function ammClearFilter() {
        document.getElementById('tb-country').value = '';
        rst('tb-state','All States');
        rst('tb-district','All Districts');
        rst('tb-mandal','All Mandals');
        ammCloseSb();
        ammLoadPins({});         /* reload ALL pins */
        ammLoadDistricts({});    /* reload ALL districts */
        ammMap.setView([20.5937, 78.9629], 5);
    }

    /* ═══════════════════════════════════════
       UTILITIES
    ═══════════════════════════════════════ */
    function dr(l, v) {
        return '<div class="dr"><span class="dl">' + esc(l) + '</span><span class="dv">' + esc(String(v || '—')) + '</span></div>';
    }
    function rst(id, ph) { var e = document.getElementById(id); if (e) e.innerHTML = '<option value="">' + ph + '</option>'; }
    function T(id, v)    { var e = document.getElementById(id); if (e) e.textContent = v; }
    function uc(s)  { if (!s) return ''; return String(s).toLowerCase().replace(/(?:^|\s)\S/g, function(a){return a.toUpperCase();}); }
    function esc(s) { if (s===null||s===undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function initials(n) { if (!n) return '?'; var p=n.trim().split(' ').filter(Boolean); return p.length>=2?(p[0][0]+p[1][0]).toUpperCase():p[0][0].toUpperCase(); }
</script>
