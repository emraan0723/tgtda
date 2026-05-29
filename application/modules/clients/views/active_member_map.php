<!-- ============================================================
     active_member_map.php  —  CodeIgniter View
     Place: application/modules/masters/views/active_member_map.php

     Features:
      • Leaflet.js + OpenStreetMap (FREE, no API key)
      • Nominatim geocoding fallback (free, built-in)
      • Country → State → District → Mandal drill-down toolbar
      • Animated SVG pins coloured by member count
      • Click pin → popup with designation breakdown badges
      • "View Members" → slide-in sidebar with full details
      • District panel (right side) showing designation counts
      • Stats bar: total members, mandals, districts, states
      • Each member card expands to show photo + all fields
     PHP 5.6 / 7.2 compatible  (no short array syntax in PHP blocks)
============================================================ -->

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
/* ═══════════════════════════════════════════════════
   PAGE WRAPPER
═══════════════════════════════════════════════════ */
/*
#amm-page {
    display        : flex;
    flex-direction : column;
    height         : calc(100vh - 130px);
    min-height     : 600px;
    border-radius  : 12px;
    overflow       : hidden;
    box-shadow     : 0 6px 32px rgba(13,71,161,.15);
    font-family    : 'Segoe UI', Tahoma, Arial, sans-serif;
}
*/

/* NEW */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

#amm-page {
    display        : flex;
    flex-direction : column;
    height         : calc(100dvh - 130px);   /* dvh = dynamic viewport height, fixes mobile */
    min-height     : 600px;
    border-radius  : 12px;
    overflow       : hidden;
    box-shadow     : 0 6px 32px rgba(13,71,161,.15);
    font-family    : 'Segoe UI', Tahoma, Arial, sans-serif;
}

/* Ensure body/amm-body fills correctly */
#amm-body {
    display  : flex;
    flex     : 1 1 0%;          /* 1 1 0% instead of just flex:1 */
    overflow : hidden;
    position : relative;
    min-height: 0;               /* critical for Firefox flex shrink */
}

#amm-map-wrap {
    flex     : 1 1 0%;           /* same fix */
    position : relative;
    min-height: 0;               /* critical for Firefox */
}

#amm-leaflet {
    width    : 100%;
    height   : 100%;
    min-height: 400px;           /* fallback if flex fails */
}

/* ═══════════════════════════════════════════════════
   TOOLBAR
═══════════════════════════════════════════════════ */
#amm-toolbar {
    background  : linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
    padding     : 10px 16px;
    display     : flex;
    align-items : center;
    gap         : 8px;
    flex-wrap   : wrap;
    flex-shrink : 0;
    z-index     : 1;
}
#amm-toolbar .tb-title {
    color          : #fff;
    font-weight    : 700;
    font-size      : 15px;
    margin-right   : 6px;
    white-space    : nowrap;
}
#amm-toolbar select {
    background    : rgba(255,255,255,.15);
    border        : 1px solid rgba(255,255,255,.3);
    color         : #fff;
    border-radius : 7px;
    padding       : 5px 9px;
    font-size     : 12px;
    cursor        : pointer;
    min-width     : 130px;
    outline       : none;
}
#amm-toolbar select option { background: #1565c0; color: #fff; }
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
.tb-btn:hover  { background: rgba(255,255,255,.3); }
.tb-btn.accent { background: #fff; color: #0d47a1; border-color: #fff; }
.tb-btn.accent:hover { background: #e3f2fd; }

/* ═══════════════════════════════════════════════════
   STATS BAR
═══════════════════════════════════════════════════ */
#amm-stats {
    background    : #fff;
    padding       : 7px 16px;
    display       : flex;
    align-items   : center;
    gap           : 16px;
    border-bottom : 1px solid #dde8f5;
    flex-shrink   : 0;
    overflow-x    : auto;
}
.stat-chip { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
.sc-icon {
    width         : 30px;
    height        : 30px;
    border-radius : 7px;
    display       : flex;
    align-items   : center;
    justify-content: center;
    font-size     : 14px;
    flex-shrink   : 0;
}
.sc-b { background:#e3f2fd; color:#1565c0; }
.sc-g { background:#e8f5e9; color:#2e7d32; }
.sc-a { background:#fff8e1; color:#e65100; }
.sc-t { background:#e0f2f1; color:#00695c; }
.sc-val { font-size: 20px; font-weight: 800; color: #1a237e; line-height: 1; }
.sc-lbl { font-size: 9px; color: #78909c; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.stat-sep { width: 1px; height: 28px; background: #dde8f5; flex-shrink: 0; }
#desig-bar { display: flex; gap: 5px; flex-wrap: nowrap; overflow-x: auto; align-items: center; }
.db-pill {
    background    : #e3f2fd;
    color         : #1565c0;
    border-radius : 20px;
    padding       : 2px 9px;
    font-size     : 10px;
    font-weight   : 700;
    white-space   : nowrap;
    border        : 1px solid #bbdefb;
}

/* ═══════════════════════════════════════════════════
   BODY  (map + sidebar)
═══════════════════════════════════════════════════ */
#amm-body {
    display  : flex;
    flex     : 1;
    overflow : hidden;
    position : relative;
}

/* ─── MAP ──────────────────────────────────────── */
#amm-map-wrap { flex: 1; position: relative; }
#amm-leaflet  { width: 100%; height: 100%; }

/* Map loading overlay */
#amm-loading {
    position        : absolute;
    inset           : 0;
    background      : rgba(255,255,255,.75);
    display         : flex;
    align-items     : center;
    justify-content : center;
    z-index         : 900;
}
.ld-box { text-align: center; color: #0d47a1; }
.ld-spin {
    width        : 38px;
    height       : 38px;
    border       : 4px solid #e3f2fd;
    border-top   : 4px solid #1e88e5;
    border-radius: 50%;
    margin       : 0 auto 10px;
    animation    : spin .8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Map Legend */
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

/* ─── DISTRICT PANEL ───────────────────────────── */
#amm-dp {
    position      : absolute;
    top           : 12px;
    right         : 12px;
    width         : 230px;
    background    : rgba(255,255,255,.97);
    border-radius : 10px;
    border        : 1px solid #dde8f5;
    z-index       : 500;
    overflow      : hidden;
    max-height    : calc(100% - 24px);
    display       : flex;
    flex-direction: column;
    box-shadow    : 0 4px 18px rgba(13,71,161,.12);
}
#amm-dp.hidden { display: none; }
#dp-hdr {
    background  : #0d47a1;
    color       : #fff;
    font-weight : 700;
    font-size   : 11px;
    padding     : 9px 13px;
    text-transform: uppercase;
    letter-spacing: .4px;
    flex-shrink : 0;
}
#dp-list { overflow-y: auto; flex: 1; }
#dp-list::-webkit-scrollbar { width: 3px; }
#dp-list::-webkit-scrollbar-thumb { background: #90caf9; }
.dp-item {
    padding       : 8px 13px;
    border-bottom : 1px solid #f0f4fb;
    cursor        : pointer;
    transition    : background .15s;
}
.dp-item:hover { background: #e3f2fd; }
.dp-item:last-child { border-bottom: none; }
.dp-name { font-weight: 700; color: #1a237e; font-size: 12px; }
.dp-meta { font-size: 10px; color: #90a4ae; margin-top: 1px; }
.dp-cnt {
    float         : right;
    background    : #1e88e5;
    color         : #fff;
    border-radius : 10px;
    padding       : 2px 8px;
    font-size     : 10px;
    font-weight   : 700;
    margin-top    : 1px;
}
/* Designation mini-chips inside district panel */
.dp-desig-row { display:flex; flex-wrap:wrap; gap:3px; margin-top:5px; }
.dp-dc {
    background    : #e3f2fd;
    color         : #1565c0;
    border-radius : 4px;
    padding       : 1px 5px;
    font-size      : 9px;
    font-weight    : 700;
}

/* ─── SIDEBAR ──────────────────────────────────── */
#amm-sidebar {
    width         : 370px;
    background    : #fff;
    border-left   : 1px solid #dde8f5;
    display       : flex;
    flex-direction: column;
    transform     : translateX(100%);
    transition    : transform .3s cubic-bezier(.4,0,.2,1);
    position      : absolute;
    right         : 0;
    top           : 0;
    height        : 100%;
    z-index       : 600;
    flex-shrink   : 0;
}
#amm-sidebar.open { transform: translateX(0); }

#sb-hdr {
    background  : linear-gradient(135deg,#0d47a1,#1565c0);
    padding     : 14px 16px;
    flex-shrink : 0;
    position    : relative;
}
#sb-hdr .sb-mandal { color:#fff; font-weight:800; font-size:15px; }
#sb-hdr .sb-loc    { color:#90caf9; font-size:11px; margin-top:2px; }
#sb-close {
    position      : absolute;
    top           : 12px;
    right         : 12px;
    width         : 26px;
    height        : 26px;
    background    : rgba(255,255,255,.22);
    border        : none;
    border-radius : 50%;
    color         : #fff;
    cursor        : pointer;
    font-size     : 14px;
    display       : flex;
    align-items   : center;
    justify-content: center;
}
#sb-close:hover { background: rgba(255,255,255,.38); }

/* Sidebar stat chips */
#sb-chips {
    display       : flex;
    border-bottom : 1px solid #dde8f5;
    background    : #f8fbff;
    flex-shrink   : 0;
}
.sbc { flex:1; text-align:center; padding:8px 4px; border-right:1px solid #dde8f5; }
.sbc:last-child { border-right: none; }
.sbc-v { font-size:20px; font-weight:800; color:#0d47a1; line-height:1; }
.sbc-l { font-size:9px; color:#78909c; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }

/* Designation summary inside sidebar */
#sb-desig-summary {
    padding     : 10px 14px;
    border-bottom: 1px solid #dde8f5;
    background  : #f0f7ff;
    flex-shrink : 0;
    display     : flex;
    flex-wrap   : wrap;
    gap         : 5px;
}
.sd-chip {
    display       : flex;
    align-items   : center;
    gap           : 4px;
    border-radius : 20px;
    padding       : 3px 9px;
    font-size     : 11px;
    font-weight   : 700;
}

/* Member list */
#sb-members { flex:1; overflow-y:auto; padding:12px; }
#sb-members::-webkit-scrollbar { width:3px; }
#sb-members::-webkit-scrollbar-thumb { background:#90caf9; }

.sb-section-title {
    font-size     : 10px;
    font-weight   : 800;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding       : 6px 10px;
    margin        : 0 -2px 8px;
    border-radius : 6px;
}

/* Member card */
.mc {
    background    : #fff;
    border        : 1px solid #dde8f5;
    border-radius : 10px;
    margin-bottom : 8px;
    overflow      : hidden;
    cursor        : pointer;
    transition    : box-shadow .2s;
}
.mc:hover { box-shadow: 0 4px 16px rgba(13,71,161,.12); }
.mc.open  { border-color: #1e88e5; }

.mc-top { display:flex; align-items:center; gap:10px; padding:10px 12px; }
.mc-av  {
    width        : 46px;
    height       : 46px;
    border-radius: 50%;
    object-fit   : cover;
    border       : 2px solid #1e88e5;
    flex-shrink  : 0;
    background   : #e3f2fd;
}
.mc-av-txt {
    width        : 46px;
    height       : 46px;
    border-radius: 50%;
    background   : #1e88e5;
    color        : #fff;
    display      : flex;
    align-items  : center;
    justify-content: center;
    font-weight  : 800;
    font-size    : 14px;
    flex-shrink  : 0;
}
.mc-info   { flex:1; min-width:0; }
.mc-nm     { font-weight:700; font-size:13px; color:#1a237e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.mc-dg     { display:inline-block; border-radius:20px; padding:2px 8px; font-size:9px; font-weight:700; margin-top:2px; }
.mc-arr    { color:#90a4ae; font-size:12px; transition:transform .25s; flex-shrink:0; }
.mc.open .mc-arr { transform: rotate(180deg); }

.mc-body {
    border-top  : 1px solid #dde8f5;
    background  : #f8fbff;
    max-height  : 0;
    overflow    : hidden;
    transition  : max-height .3s ease;
}
.mc.open .mc-body { max-height: 500px; }
.mc-body-in { padding: 12px; }

.mc-photo { text-align:center; margin-bottom:10px; }
.mc-photo img {
    width        : 72px;
    height       : 72px;
    border-radius: 50%;
    object-fit   : cover;
    border       : 3px solid #1e88e5;
    box-shadow   : 0 3px 12px rgba(13,71,161,.18);
}
.mc-photo .mc-av-txt-lg {
    width        : 72px;
    height       : 72px;
    border-radius: 50%;
    background   : #1e88e5;
    color        : #fff;
    display      : inline-flex;
    align-items  : center;
    justify-content: center;
    font-weight  : 800;
    font-size    : 20px;
}

.dr { display:flex; gap:6px; margin-bottom:5px; font-size:11px; }
.dl { font-weight:700; color:#78909c; min-width:86px; flex-shrink:0; }
.dv { color:#263238; word-break:break-word; }

/* No data */
#sb-nodata {
    display        : flex;
    flex-direction : column;
    align-items    : center;
    justify-content: center;
    flex           : 1;
    color          : #90a4ae;
    padding        : 24px;
    text-align     : center;
}
#sb-nodata i  { font-size:44px; opacity:.25; margin-bottom:10px; }
#sb-nodata p  { font-size:12px; }

/* Sidebar loader */
.sb-spin {
    width        : 30px;
    height       : 30px;
    border       : 3px solid #e3f2fd;
    border-top   : 3px solid #1e88e5;
    border-radius: 50%;
    margin       : 0 auto 8px;
    animation    : spin .8s linear infinite;
}

/* Popup custom */
.leaflet-popup-content { margin: 10px 14px; }
.lp-title  { font-weight:800; font-size:14px; color:#0d47a1; margin-bottom:3px; }
.lp-loc    { font-size:11px; color:#78909c; margin-bottom:8px; }
.lp-total  { display:inline-block; background:#e3f2fd; color:#1565c0; border-radius:6px; padding:3px 9px; font-size:11px; font-weight:700; margin-bottom:8px; }
.lp-desig  { display:flex; flex-wrap:wrap; gap:4px; margin-bottom:10px; }
.lp-dc     { border-radius:5px; padding:2px 7px; font-size:10px; font-weight:700; }
.lp-names  { font-size:11px; color:#546e7a; margin-bottom:10px; line-height:1.5; }
.lp-btn    {
    display      : block;
    width        : 100%;
    background   : #0d47a1;
    color        : #fff;
    border       : none;
    border-radius: 7px;
    padding      : 7px;
    font-size    : 12px;
    font-weight  : 700;
    cursor       : pointer;
    text-align   : center;
}
.lp-btn:hover { background: #1565c0; }

/* Responsive */
@media (max-width: 768px) {
    #amm-sidebar { width: 100%; position:absolute; top:0; right:0; height:100%; }
    #amm-dp { display: none; }
}
</style>

<!-- ══════════════════════════════════════════════════
     PAGE
══════════════════════════════════════════════════ -->
<div id="amm-page">

    <!-- TOOLBAR -->
    <div id="amm-toolbar">
        <span class="tb-title"><i class="ti-map mr-1"></i> Active Member Map</span>

       <!-- <select id="tb-country" onchange="ammOnCountry()">
            <option value="">&#127758; All Countries</option>
            <?php /*if ( ! empty($country_list)): */?>
                <?php /*foreach ($country_list as $c): */?>
                    <option value="<?php /*echo (int)$c['tc_country_ID']; */?>">
                        <?php /*echo htmlspecialchars(ucwords(strtolower($c['tc_country_name']))); */?>
                    </option>
                <?php /*endforeach; */?>
            <?php /*endif; */?>
        </select>

        <select id="tb-state" onchange="ammOnState()">
            <option value="">All States</option>
        </select>

        <select id="tb-district" onchange="ammOnDistrict()">
            <option value="">All Districts</option>
        </select>

        <select id="tb-mandal" onchange="ammOnMandal()">
            <option value="">All Mandals</option>
        </select>

        <button class="tb-btn accent" onclick="ammApplyFilter()">
            <i class="ti-search"></i> Search
        </button>-->
        <button class="tb-btn" onclick="ammClearFilter()">
            <i class="ti-close"></i> Clear
        </button>
    </div>

    <!-- STATS BAR -->
    <div id="amm-stats">
        <div class="stat-chip">
            <div class="sc-icon sc-b"><i class="ti-user"></i></div>
            <div>
                <div class="sc-val" id="st-members">—</div>
                <div class="sc-lbl">Members</div>
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

            <!-- Legend -->
            <div id="amm-legend">
                <b>Members / Mandal</b>
                <div class="lg-row"><div class="lg-dot" style="background:#4caf50;"></div> 1 member</div>
                <div class="lg-row"><div class="lg-dot" style="background:#ff9800;"></div> 2 – 4 members</div>
                <div class="lg-row"><div class="lg-dot" style="background:#f44336;"></div> 5+ members</div>
            </div>
        </div><!-- /map-wrap -->

        <!-- DISTRICT PANEL -->
        <div id="amm-dp" style="display: none">
            <div id="dp-hdr"><i class="ti-layout-grid2 mr-1"></i> Districts Overview</div>
            <div id="dp-list"><div style="padding:12px;font-size:12px;color:#90a4ae;">Loading…</div></div>
        </div>

        <!-- SIDEBAR -->
        <div id="amm-sidebar">
            <div id="sb-hdr">
                <div class="sb-mandal" id="sb-mandal-name">—</div>
                <div class="sb-loc"    id="sb-loc"></div>
                <button id="sb-close" onclick="ammCloseSidebar()">&#x2715;</button>
            </div>

            <div id="sb-chips">
                <div class="sbc">
                    <div class="sbc-v" id="sb-cnt-m">0</div>
                    <div class="sbc-l">Members</div>
                </div>
                <div class="sbc">
                    <div class="sbc-v" id="sb-cnt-r">0</div>
                    <div class="sbc-l">Roles</div>
                </div>
            </div>

            <div id="sb-desig-summary"></div>

            <div id="sb-members">
                <div id="sb-nodata">
                    <i class="ti-pin-alt"></i>
                    <p>Click a map pin to view members at that location</p>
                </div>
            </div>
        </div>

    </div><!-- /amm-body -->
</div><!-- /amm-page -->

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script type="text/javascript">
/* ═══════════════════════════════════════════════════════════
   CONSTANTS
═══════════════════════════════════════════════════════════ */
var BASE_URL   = "<?php echo base_url(); ?>";
var CSRF_NAME  = "<?php echo $this->security->get_csrf_token_name(); ?>";
var CSRF_TOKEN = "<?php echo $this->security->get_csrf_hash(); ?>";

/* Designation colours (index matches canonical order) */
var DESIG_COLORS = {
    'PRESIDENT'               : { bg:'#fce4ec', color:'#c62828', short:'PRES' },
    'VICE PRESIDENT'          : { bg:'#e8eaf6', color:'#283593', short:'VP'   },
    'GENERAL SECRETARY'       : { bg:'#e8f5e9', color:'#1b5e20', short:'GS'   },
    'JOINT SECRETARY'         : { bg:'#e0f2f1', color:'#004d40', short:'JS'   },
    'TREASURER'               : { bg:'#fff8e1', color:'#e65100', short:'TRES' },
    'EXECUTIVE MEMBER'        : { bg:'#f3e5f5', color:'#4a148c', short:'EM'   },
    'REGIONAL DISTRICT OFFICER':{ bg:'#e0f7fa', color:'#006064', short:'RDO'  },
    'DISTRICT OFFICER'        : { bg:'#fbe9e7', color:'#bf360c', short:'DO'   },
    'MANDAL OFFICER'          : { bg:'#f9fbe7', color:'#558b2f', short:'MO'   },
};

/* ═══════════════════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════════════════ */
var ammMap, ammPopup;
var ammMarkers   = [];
var ammPinsData  = [];
var geocodeQueue = [];
var geocoding    = false;

/* ═══════════════════════════════════════════════════════════
   MAP INIT
═══════════════════════════════════════════════════════════ */
window.addEventListener('load', function () {
    ammMap = L.map('amm-leaflet', {
        center       : [17.38, 78.49],   /* Hyderabad */
        zoom         : 7,
        zoomControl  : true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution : '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
        maxZoom     : 19,
    }).addTo(ammMap);

    ammPopup = L.popup({ maxWidth: 280 });

    document.getElementById('amm-loading').style.display = 'none';

    ammLoadStats();
    ammLoadPins({});
    ammLoadDistrictPanel({});
});

/* ═══════════════════════════════════════════════════════════
   LOAD STATS
═══════════════════════════════════════════════════════════ */
function ammLoadStats() {
    $.post(BASE_URL + 'masters/ActiveMemberMap/get_stats',
        { [CSRF_NAME]: CSRF_TOKEN },
        function (res) {
            if (!res) return;
            ammSetText('st-members',   res.total_members   || 0);
            ammSetText('st-mandals',   res.total_mandals   || 0);
            ammSetText('st-districts', res.total_districts || 0);
            ammSetText('st-states',    res.total_states    || 0);

            /* Designation bar */
            var html = '';
            if (res.designations) {
                $.each(res.designations, function (desig, cnt) {
                    if (cnt === 0) return;
                    var c = DESIG_COLORS[desig] || { bg:'#e3f2fd', color:'#1565c0', short: desig.substring(0,3) };
                    html += '<span class="db-pill" style="background:' + c.bg + ';color:' + c.color + ';border-color:' + c.color + '33;">' +
                        c.short + ' <strong>' + cnt + '</strong></span>';
                });
            }
            document.getElementById('desig-bar').innerHTML = html || '<span style="font-size:11px;color:#90a4ae;">No data</span>';
        }, 'json');
}

/* ═══════════════════════════════════════════════════════════
   LOAD MAP PINS
═══════════════════════════════════════════════════════════ */
function ammLoadPins(filters) {
    document.getElementById('amm-loading').style.display = 'flex';

    /* Clear existing markers */
    ammMarkers.forEach(function (m) { m.remove(); });
    ammMarkers   = [];
    ammPinsData  = [];
    geocodeQueue = [];
    geocoding    = false;

    var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

    $.post(BASE_URL + 'masters/ActiveMemberMap/get_map_pins', postData,
        function (pins) {
            document.getElementById('amm-loading').style.display = 'none';
            ammPinsData = pins || [];
            if (!ammPinsData.length) return;

            ammPinsData.forEach(function (pin) {
                if (pin.lat && pin.lng && pin.lat !== 0 && pin.lng !== 0) {
                    /* Has coordinates — place immediately */
                    ammPlaceMarker(pin, pin.lat, pin.lng);
                } else {
                    /* Queue for Nominatim geocoding */
                    geocodeQueue.push(pin);
                }
            });

            if (geocodeQueue.length && !geocoding) {
                ammGeocodeNext();
            }

            /* Fit map bounds */
            var placed = ammMarkers.filter(function (m) { return m._latlng; });
            if (placed.length > 0) {
                var bounds = L.latLngBounds(placed.map(function (m) {
                    return m.getLatLng();
                }));
                ammMap.fitBounds(bounds, { padding: [40, 40] });
            }
        }, 'json')
    .fail(function () {
        document.getElementById('amm-loading').style.display = 'none';
    });
}

/* ═══════════════════════════════════════════════════════════
   NOMINATIM GEOCODING QUEUE  (rate-limited: 1 req/sec)
═══════════════════════════════════════════════════════════ */
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
            setTimeout(ammGeocodeNext, 1200); // 1.2 s — respects Nominatim ToS
        },
        'json'
    ).fail(function () {
        setTimeout(ammGeocodeNext, 2000); // back-off on failure
    });
}

/* ═══════════════════════════════════════════════════════════
   CREATE MARKER
═══════════════════════════════════════════════════════════ */
function ammPinColor(n) {
    return n >= 5 ? '#f44336' : n >= 2 ? '#ff9800' : '#4caf50';
}

function ammPlaceMarker(pin, lat, lng) {
    var cnt   = parseInt(pin.member_count) || 0;
    var color = ammPinColor(cnt);

    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="52" viewBox="0 0 40 52">' +
        '<path fill="' + color + '" d="M20 2C12.27 2 6 8.27 6 16c0 11.5 14 34 14 34S34 27.5 34 16C34 8.27 27.73 2 20 2z"/>' +
        '<circle fill="rgba(0,0,0,.18)" cx="20" cy="16" r="8"/>' +
        '<text x="20" y="21" text-anchor="middle" fill="#fff" ' +
            'font-family="Arial,sans-serif" font-weight="800" ' +
            'font-size="' + (cnt > 9 ? '8' : '10') + '">' + cnt + '</text>' +
        '</svg>';

    var icon = L.divIcon({
        html      : svg,
        className : '',
        iconSize  : [40, 52],
        iconAnchor: [20, 52],
        popupAnchor:[0, -55],
    });

    var marker = L.marker([lat, lng], { icon: icon, title: pin.mandal_name }).addTo(ammMap);
    marker.pinData = pin;

    marker.on('click', function () {
        ammShowPopup(marker, pin);
    });

    ammMarkers.push(marker);
}

/* ═══════════════════════════════════════════════════════════
   POPUP ON PIN CLICK
═══════════════════════════════════════════════════════════ */
function ammShowPopup(marker, pin) {
    var dc   = pin.designation_counts || {};
    var names = (pin.member_names_arr || []).slice(0, 4);

    /* Designation breakdown chips */
    var desigHtml = '';
    $.each(DESIG_COLORS, function (desig, c) {
        var n = dc[desig] || 0;
        if (n === 0) return;
        desigHtml += '<span class="lp-dc" style="background:' + c.bg + ';color:' + c.color + ';">' +
            c.short + ' ' + n + '</span>';
    });

    /* Member names preview */
    var namesHtml = '';
    if (names.length) {
        namesHtml = '<div class="lp-names">' +
            names.map(function (n) { return '&#x2022; ' + ammUc(n); }).join('<br>') +
            (pin.member_count > 4 ? '<br><em>+' + (pin.member_count - 4) + ' more…</em>' : '') +
            '</div>';
    }

    var content =
        '<div style="min-width:220px;">' +
            '<div class="lp-title"><i class="ti-location-pin" style="color:#1565c0;margin-right:4px;"></i>' +
            ammUc(pin.mandal_name) + '</div>' +
            '<div class="lp-loc">' + ammUc(pin.district_name) + ', ' + ammUc(pin.state_name) + '</div>' +
            '<span class="lp-total">&#128100; ' + pin.member_count + ' Active Member' +
                (pin.member_count !== 1 ? 's' : '') + '</span>' +
            '<div class="lp-desig">' + desigHtml + '</div>' +
            namesHtml +
            '<button class="lp-btn" onclick="ammOpenSidebar(' + pin.mandal_id + ')">View All Members &#8594;</button>' +
        '</div>';

    ammPopup.setLatLng(marker.getLatLng()).setContent(content).openOn(ammMap);
}

/* ═══════════════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════════════ */
function ammOpenSidebar(mandal_id) {
    ammMap.closePopup();

    /* Find pin */
    var pin = null;
    for (var i = 0; i < ammPinsData.length; i++) {
        if (parseInt(ammPinsData[i].mandal_id) === parseInt(mandal_id)) {
            pin = ammPinsData[i]; break;
        }
    }

    if (pin) {
        ammSetText('sb-mandal-name', ammUc(pin.mandal_name));
        ammSetText('sb-loc',
            ammUc(pin.district_name) + ' · ' + ammUc(pin.state_name) + ' · ' + ammUc(pin.country_name));

        var dc   = pin.designation_counts || {};
        var roles = 0;
        $.each(dc, function (k, v) { if (v > 0) roles++; });
        ammSetText('sb-cnt-m', pin.member_count);
        ammSetText('sb-cnt-r', roles);

        /* Designation summary chips */
        var dsHtml = '';
        $.each(DESIG_COLORS, function (desig, c) {
            var n = dc[desig] || 0;
            if (n === 0) return;
            dsHtml += '<span class="sd-chip" style="background:' + c.bg + ';color:' + c.color + ';">' +
                c.short + ' <strong>' + n + '</strong></span>';
        });
        document.getElementById('sb-desig-summary').innerHTML = dsHtml ||
            '<span style="font-size:11px;color:#90a4ae;">No designation data</span>';
    }

    /* Loading state */
    document.getElementById('sb-members').innerHTML =
        '<div style="text-align:center;padding:30px 0;color:#78909c;">' +
        '<div class="sb-spin"></div>' +
        '<div style="font-size:12px;font-weight:600;">Loading members…</div></div>';

    document.getElementById('amm-sidebar').classList.add('open');

    /* Hide district panel when sidebar is open */
    document.getElementById('amm-dp').classList.add('hidden');

    /* Fetch members */
    $.post(BASE_URL + 'masters/ActiveMemberMap/get_mandal_members',
        { mandal_id: mandal_id, [CSRF_NAME]: CSRF_TOKEN },
        function (members) {
            ammRenderMembers(members || []);
        }, 'json');
}

function ammCloseSidebar() {
    document.getElementById('amm-sidebar').classList.remove('open');
    document.getElementById('amm-dp').classList.remove('hidden');
}

/* ═══════════════════════════════════════════════════════════
   RENDER MEMBERS (grouped by designation)
═══════════════════════════════════════════════════════════ */
function ammRenderMembers(members) {
    var container = document.getElementById('sb-members');

    if (!members || !members.length) {
        container.innerHTML =
            '<div id="sb-nodata"><i class="ti-user"></i><p>No active members found.</p></div>';
        ammSetText('sb-cnt-m', 0);
        ammSetText('sb-cnt-r', 0);
        return;
    }

    /* Group by designation */
    var groups = {};
    members.forEach(function (m) {
        var d = m.tamm_designation || 'OTHER';
        if (!groups[d]) groups[d] = [];
        groups[d].push(m);
    });

    var html = '';
    var desigOrder = Object.keys(DESIG_COLORS);

    /* Render in canonical designation order */
    desigOrder.forEach(function (desig) {
        if (!groups[desig] || !groups[desig].length) return;
        var c   = DESIG_COLORS[desig] || { bg:'#e3f2fd', color:'#1565c0' };
        var grp = groups[desig];

        html += '<div class="sb-section-title" style="background:' + c.bg + ';color:' + c.color + ';">' +
            desig + ' <span style="opacity:.7;">(' + grp.length + ')</span></div>';

        grp.forEach(function (m, idx) {
            var cardId  = 'mc_' + desig.replace(/\s/g,'_') + '_' + idx;
            var photoUrl = BASE_URL + 'uploads/registration/' + (m.tr_reg_key || '') +
                '/' + (m.tr_selfie || '');
            var hasSelfie = !!(m.tr_selfie);
            var initials  = ammInitials(m.tr_full_name || '');
            var dob = m.tr_dob ? m.tr_dob : '—';

            html +=
            '<div class="mc" id="' + cardId + '" onclick="ammToggleMc(\'' + cardId + '\')">' +
                '<div class="mc-top">' +
                    (hasSelfie
                        ? '<img class="mc-av" src="' + photoUrl + '" ' +
                          'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\';" ' +
                          'alt="' + ammEsc(m.tr_full_name) + '">' +
                          '<div class="mc-av-txt" style="display:none;">' + initials + '</div>'
                        : '<div class="mc-av-txt">' + initials + '</div>') +
                    '<div class="mc-info">' +
                        '<div class="mc-nm">' + ammUc(m.tr_full_name || '—') + '</div>' +
                        '<div class="mc-dg" style="background:' + c.bg + ';color:' + c.color + ';">' +
                            ammEsc(m.tamm_designation) + '</div>' +
                    '</div>' +
                    '<span class="mc-arr">&#x25BE;</span>' +
                '</div>' +
                '<div class="mc-body"><div class="mc-body-in">' +
                    '<div class="mc-photo">' +
                        (hasSelfie
                            ? '<img src="' + photoUrl + '" ' +
                              'onerror="this.style.display=\'none\';this.nextSibling.style.display=\'inline-flex\';" ' +
                              'style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #1e88e5;">' +
                              '<div class="mc-av-txt-lg" style="display:none;">' + initials + '</div>'
                            : '<div class="mc-av-txt-lg">' + initials + '</div>') +
                    '</div>' +
                    ammDR('Mobile',    m.tr_mobile         || '—') +
                    ammDR('Email',     m.tr_email          || '—') +
                    //ammDR('DOB',       dob) +
                    //ammDR('Language',  m.tr_language       || '—') +
                    //ammDR('Reg Type',  m.tr_registration_type || '—') +
                    //ammDR('Reg Key',   m.tr_reg_key        || '—') +
                    //ammDR('Unique Key',m.tr_reg_ukey       || '—') +
                    //ammDR('Aadhaar',   m.tr_aadhar_no ? '****' + (m.tr_aadhar_no + '').slice(-4) : '—') +
                    //ammDR('PAN',       m.tr_pan_no         || '—') +
                    //ammDR('Village',   m.tr_village        || '—') +
                    //ammDR('Pincode',   m.tr_pincode        || '—') +
                    //ammDR('Address',   m.tr_full_address   || '—') +
                    //ammDR('District',  ammUc(m.district_name || '—')) +
                    //ammDR('State',     ammUc(m.state_name  || '—')) +
                '</div></div>' +
            '</div>';
        });

        delete groups[desig]; /* mark as rendered */
    });

    /* Render any remaining (non-standard designations) */
    $.each(groups, function (desig, grp) {
        if (!grp.length) return;
        html += '<div class="sb-section-title" style="background:#f5f5f5;color:#546e7a;">' +
            ammEsc(desig) + ' (' + grp.length + ')</div>';
        grp.forEach(function (m, idx) {
            var cardId = 'mc_other_' + idx;
            var initials = ammInitials(m.tr_full_name || '');
            html +=
            '<div class="mc" id="' + cardId + '" onclick="ammToggleMc(\'' + cardId + '\')">' +
                '<div class="mc-top">' +
                    '<div class="mc-av-txt" style="background:#78909c;">' + initials + '</div>' +
                    '<div class="mc-info">' +
                        '<div class="mc-nm">' + ammUc(m.tr_full_name || '—') + '</div>' +
                        '<div class="mc-dg" style="background:#f5f5f5;color:#546e7a;">' +
                            ammEsc(m.tamm_designation) + '</div>' +
                    '</div>' +
                    '<span class="mc-arr">&#x25BE;</span>' +
                '</div>' +
                '<div class="mc-body"><div class="mc-body-in">' +
                    ammDR('Mobile',  m.tr_mobile  || '—') +
                    ammDR('Email',   m.tr_email   || '—') +
                    ammDR('Address', m.tr_full_address || '—') +
                '</div></div>' +
            '</div>';
        });
    });

    container.innerHTML = html;
}

function ammToggleMc(id) {
    var el = document.getElementById(id);
    if (!el) return;
    var wasOpen = el.classList.contains('open');
    /* Close all */
    document.querySelectorAll('.mc.open').forEach(function (c) {
        c.classList.remove('open');
    });
    if (!wasOpen) el.classList.add('open');
}

/* ═══════════════════════════════════════════════════════════
   DISTRICT PANEL
═══════════════════════════════════════════════════════════ */
function ammLoadDistrictPanel(filters) {
    var postData = $.extend({}, filters, { [CSRF_NAME]: CSRF_TOKEN });

    $.post(BASE_URL + 'masters/ActiveMemberMap/get_district_summary', postData,
        function (districts) {
            var html = '';
            if (!districts || !districts.length) {
                html = '<div style="padding:12px;font-size:11px;color:#90a4ae;">No data</div>';
            } else {
                districts.forEach(function (d) {
                    var dc   = d.designation_counts || {};
                    var chips = '';
                    $.each(DESIG_COLORS, function (desig, c) {
                        var n = dc[desig] || 0;
                        if (!n) return;
                        chips += '<span class="dp-dc" style="background:' + c.bg + ';color:' + c.color + ';">' +
                            c.short + ' ' + n + '</span>';
                    });

                    html +=
                    '<div class="dp-item" onclick="ammFilterByDistrict(' + d.district_id + ')">' +
                        '<span class="dp-cnt">' + d.member_count + '</span>' +
                        '<div class="dp-name">' + ammUc(d.district_name) + '</div>' +
                        '<div class="dp-meta">' + d.mandal_count + ' mandal' +
                            (d.mandal_count !== 1 ? 's' : '') + ' &bull; ' + ammUc(d.state_name) + '</div>' +
                        (chips ? '<div class="dp-desig-row">' + chips + '</div>' : '') +
                    '</div>';
                });
            }
            document.getElementById('dp-list').innerHTML = html;
        }, 'json');
}

function ammFilterByDistrict(district_id) {
    document.getElementById('tb-district').value = district_id;
    /* Load mandals for this district */
    ammOnDistrict();
    ammApplyFilter();
}

/* ═══════════════════════════════════════════════════════════
   TOOLBAR CASCADING
═══════════════════════════════════════════════════════════ */
function ammOnCountry() {
    var cid = document.getElementById('tb-country').value;
    ammResetSelect('tb-state',    'All States');
    ammResetSelect('tb-district', 'All Districts');
    ammResetSelect('tb-mandal',   'All Mandals');
    if (!cid) return;

    $.post(BASE_URL + 'masters/activemembermap/get_states',
        { country_id: cid, [CSRF_NAME]: CSRF_TOKEN },
        function (states) {
            var html = '<option value="">All States</option>';
            (states || []).forEach(function (s) {
                html += '<option value="' + s.ts_state_ID + '">' +
                    ammUc(s.ts_state_name) + '</option>';
            });
            document.getElementById('tb-state').innerHTML = html;
        }, 'json');
}

function ammOnState() {
    var sid = document.getElementById('tb-state').value;
    ammResetSelect('tb-district', 'All Districts');
    ammResetSelect('tb-mandal',   'All Mandals');
    if (!sid) return;

    $.post(BASE_URL + 'masters/activemembermap/get_districts',
        { state_id: sid, [CSRF_NAME]: CSRF_TOKEN },
        function (districts) {
            var html = '<option value="">All Districts</option>';
            (districts || []).forEach(function (d) {
                html += '<option value="' + d.tdt_district_ID + '">' +
                    ammUc(d.tdt_district_name) + '</option>';
            });
            document.getElementById('tb-district').innerHTML = html;
        }, 'json');
}

function ammOnDistrict() {
    var did = document.getElementById('tb-district').value;
    ammResetSelect('tb-mandal', 'All Mandals');
    if (!did) return;

    $.post(BASE_URL + 'masters/activemembermap/get_mandals',
        { district_id: did, [CSRF_NAME]: CSRF_TOKEN },
        function (mandals) {
            var html = '<option value="">All Mandals</option>';
            (mandals || []).forEach(function (m) {
                html += '<option value="' + m.tm_mandal_ID + '">' +
                    ammUc(m.tm_mandal) + '</option>';
            });
            document.getElementById('tb-mandal').innerHTML = html;
        }, 'json');
}

function ammOnMandal() {
    /* Mandal selected — do nothing here; user presses Search */
}

/* ═══════════════════════════════════════════════════════════
   APPLY / CLEAR FILTERS
═══════════════════════════════════════════════════════════ */
function ammApplyFilter() {
    var filters = {
        country_id  : document.getElementById('tb-country').value  || 0,
        state_id    : document.getElementById('tb-state').value    || 0,
        district_id : document.getElementById('tb-district').value || 0,
        mandal_id   : document.getElementById('tb-mandal').value   || 0,
    };

    ammLoadPins(filters);
    ammLoadDistrictPanel(filters);

    /* If specific mandal selected, open sidebar after a short delay */
    var mandalId = document.getElementById('tb-mandal').value;
    if (mandalId) {
        setTimeout(function () {
            ammOpenSidebar(parseInt(mandalId));
        }, 1800);
    }
}

function ammClearFilter() {
    ammResetSelect('tb-country',  'All Countries', true);
    ammResetSelect('tb-state',    'All States');
    ammResetSelect('tb-district', 'All Districts');
    ammResetSelect('tb-mandal',   'All Mandals');
    ammCloseSidebar();
    ammLoadPins({});
    ammLoadDistrictPanel({});
    ammMap.setView([17.38, 78.49], 7);
}

/* ═══════════════════════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════════════════════ */
function ammDR(label, value) {
    return '<div class="dr"><span class="dl">' + ammEsc(label) + '</span>' +
        '<span class="dv">' + ammEsc(String(value)) + '</span></div>';
}

function ammResetSelect(id, placeholder, keepFirst) {
    var el = document.getElementById(id);
    if (!el) return;
    if (keepFirst && el.options.length > 0) {
        /* keep existing first option (preserves country list) */
        while (el.options.length > 1) el.remove(1);
        el.value = '';
    } else {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
    }
}

function ammSetText(id, val) {
    var el = document.getElementById(id);
    if (el) el.textContent = val;
}

function ammUc(str) {
    if (!str) return '';
    return String(str).toLowerCase().replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
    });
}

function ammEsc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

function ammInitials(name) {
    if (!name) return '?';
    var parts = name.trim().split(' ').filter(Boolean);
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return (parts[0] || '?')[0].toUpperCase();
}

// After ammMap = L.map(...) initialization, add:
setTimeout(function() {
    ammMap.invalidateSize();
}, 300);

// Also add resize listener:
window.addEventListener('resize', function() {
    ammMap.invalidateSize();
});
</script>
