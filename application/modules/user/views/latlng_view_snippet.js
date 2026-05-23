/**
 * ═══════════════════════════════════════════════════════════════
 *  LAT/LNG GEOCODING + DB SAVE — user view snippet
 *  Add this <script> block to your user.php view AFTER the
 *  existing <script> block (or merge into it).
 *
 *  How it works:
 *  1. When user changes State/District/Mandal in the edit form,
 *     JS first checks if coords already exist in DB (get_latlng).
 *  2. If not found, Nominatim geocodes the name.
 *  3. Resolved coords are sent back to save_latlng endpoint.
 *  4. The mini-map in the modal updates to show the selected location.
 *
 *  Dependencies already present in your view:
 *    BASE_URL, CSRF_NAME, Csrf, ajaxPost  ← all used below
 *
 *  NEW HTML to add inside #regModal modal-body (after Location section):
 *    <div id="loc-map-wrap"> ... </div>   ← see HTML block at bottom
 * ═══════════════════════════════════════════════════════════════
 */

/* ── Mini Leaflet map inside the modal ── */
var _locMap        = null;
var _locMapMarker  = null;
var _locMapInited  = false;

/* Pending coords resolved from geocoding */
var _pendingCoords = {
    state    : { id: 0, lat: 0, lng: 0 },
    district : { id: 0, lat: 0, lng: 0 },
    mandal   : { id: 0, lat: 0, lng: 0 },
};

/* ─────────────────────────────────────────────
   initLocMap — call once when modal is shown
   ───────────────────────────────────────────── */
function initLocMap() {
    if (_locMapInited) { _locMap.invalidateSize(); return; }
    _locMapInited = true;

    _locMap = L.map('loc-minimap', {
        center    : [20.5937, 78.9629],  /* India */
        zoom      : 5,
        zoomControl: true,
        attributionControl: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(_locMap);

    _locMap.invalidateSize();
}

/* Call initLocMap when the registration modal opens */
$(document).on('shown.bs.modal', '#regModal', function () {
    setTimeout(function () { initLocMap(); }, 200);
});

/* ─────────────────────────────────────────────
   updateLocMapPin — move/add marker on map
   ───────────────────────────────────────────── */
function updateLocMapPin(lat, lng, label, zoom) {
    zoom = zoom || 10;
    if (!_locMap) return;
    if (_locMapMarker) _locMapMarker.remove();
    _locMapMarker = L.marker([lat, lng]).addTo(_locMap);
    if (label) _locMapMarker.bindPopup(label).openPopup();
    _locMap.setView([lat, lng], zoom);
}

/* ─────────────────────────────────────────────
   Nominatim geocode helper (1 call, no queue
   — form interaction is slow enough to stay
   within OSM's 1-req/sec policy)
   ───────────────────────────────────────────── */
function geocodePlace(query, cb) {
    $.ajax({
        url     : 'https://nominatim.openstreetmap.org/search',
        data    : { format: 'json', limit: 1, countrycodes: 'in', q: query },
        dataType: 'json',
        headers : { 'Accept-Language': 'en' },
        success : function (d) {
            if (d && d.length) cb(parseFloat(d[0].lat), parseFloat(d[0].lon));
            else cb(0, 0);
        },
        error   : function () { cb(0, 0); }
    });
}

/* ─────────────────────────────────────────────
   setLocStatus — show a small status line
   below the mini-map
   ───────────────────────────────────────────── */
function setLocStatus(msg, type) {
    var el = document.getElementById('loc-map-status');
    if (!el) return;
    var colors = { info: '#1e88e5', success: '#10b981', warn: '#f59e0b', error: '#ef4444' };
    el.innerHTML  = msg;
    el.style.color = colors[type] || '#64748b';
}

/* ─────────────────────────────────────────────
   saveCoordsToDb — sends resolved coords to
   the save_latlng controller endpoint
   ───────────────────────────────────────────── */
function saveCoordsToDb(stateId, stateLat, stateLng,
                         distId,  distLat,  distLng,
                         mandalId,mandalLat,mandalLng,
                         force) {
    if (!stateId && !distId && !mandalId) return;

    var payload = {
        state_id    : stateId    || 0,
        state_lat   : stateLat   || 0,
        state_lng   : stateLng   || 0,
        district_id : distId     || 0,
        dist_lat    : distLat    || 0,
        dist_lng    : distLng    || 0,
        mandal_id   : mandalId   || 0,
        mandal_lat  : mandalLat  || 0,
        mandal_lng  : mandalLng  || 0,
        force       : force ? 1  : 0,
    };

    ajaxPost(BASE_URL + 'save_latlng', payload, function (res) {
        if (res.saved && res.saved.length) {
            setLocStatus('&#10003; Coordinates saved to DB (' + res.saved.join(', ') + ')', 'success');
        }
    }, function () {
        /* silent fail — coords are optional */
    });
}

/* ─────────────────────────────────────────────
   resolveAndSave — geocode + save one level
   level: 'state' | 'district' | 'mandal'
   ───────────────────────────────────────────── */
function resolveAndSave(level, id, query, zoom, label) {
    if (!id || !query) return;

    setLocStatus('<i class="bi bi-arrow-repeat spin"></i> Locating ' + label + '…', 'info');

    /* 1. Check if DB already has coords */
    var checkPayload = {};
    checkPayload[level + '_id'] = id;

    ajaxPost(BASE_URL + 'get_latlng', checkPayload, function (res) {
        var data = res.data[level];
        var lat  = parseFloat(data && data.lat ? data.lat : 0);
        var lng  = parseFloat(data && data.lng ? data.lng : 0);

        if (lat && lng) {
            /* Already in DB — just update map */
            _pendingCoords[level] = { id: id, lat: lat, lng: lng };
            updateLocMapPin(lat, lng, label, zoom);
            setLocStatus('&#10003; ' + label + ' coordinates found in DB', 'success');
        } else {
            /* Not in DB — geocode via Nominatim */
            geocodePlace(query, function (gLat, gLng) {
                if (gLat && gLng) {
                    _pendingCoords[level] = { id: id, lat: gLat, lng: gLng };
                    updateLocMapPin(gLat, gLng, label, zoom);

                    /* Save all levels together */
                    var s  = _pendingCoords.state;
                    var d  = _pendingCoords.district;
                    var m  = _pendingCoords.mandal;

                    saveCoordsToDb(
                        s.id, s.lat, s.lng,
                        d.id, d.lat, d.lng,
                        m.id, m.lat, m.lng,
                        false
                    );
                } else {
                    setLocStatus('&#9888; Could not locate ' + label + ' on map', 'warn');
                }
            });
        }
    }, function () {
        /* DB check failed — still try geocoding */
        geocodePlace(query, function (gLat, gLng) {
            if (gLat && gLng) {
                _pendingCoords[level] = { id: id, lat: gLat, lng: gLng };
                updateLocMapPin(gLat, gLng, label, zoom);
                var s = _pendingCoords.state;
                var d = _pendingCoords.district;
                var m = _pendingCoords.mandal;
                saveCoordsToDb(s.id,s.lat,s.lng, d.id,d.lat,d.lng, m.id,m.lat,m.lng, false);
            }
        });
    });
}

/* ─────────────────────────────────────────────
   HOOK INTO EXISTING LOCATION CHANGE HANDLERS
   Replace (or extend) your current onStateChange,
   onDistrictChange, onMandalChange functions.
   The code below WRAPS the existing functions.
   ───────────────────────────────────────────── */

/* Save originals */
var _origOnStateChange    = typeof onStateChange    === 'function' ? onStateChange    : null;
var _origOnDistrictChange = typeof onDistrictChange === 'function' ? onDistrictChange : null;
var _origOnMandalChange   = typeof onMandalChange   === 'function' ? onMandalChange   : null;

/* Override onStateChange */
onStateChange = function (sel) {
    /* Reset pending coords for district/mandal when state changes */
    _pendingCoords.district = { id: 0, lat: 0, lng: 0 };
    _pendingCoords.mandal   = { id: 0, lat: 0, lng: 0 };

    /* Call original */
    if (_origOnStateChange) _origOnStateChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';
    if (!id || !name) return;

    _pendingCoords.state = { id: id, lat: 0, lng: 0 };
    resolveAndSave('state', id, name + ', India', 6, name);
};

/* Override onDistrictChange */
onDistrictChange = function (sel) {
    _pendingCoords.mandal = { id: 0, lat: 0, lng: 0 };

    if (_origOnDistrictChange) _origOnDistrictChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';
    if (!id || !name) return;

    _pendingCoords.district = { id: id, lat: 0, lng: 0 };

    /* Build richer query: District, State, India */
    var stateName = (function () {
        var stateSel = document.getElementById('tr_state_id');
        if (!stateSel) return '';
        var opt = stateSel.options[stateSel.selectedIndex];
        return opt ? opt.text : '';
    })();

    var query = [name, stateName, 'India'].filter(Boolean).join(', ');
    resolveAndSave('district', id, query, 9, name + ' District');
};

/* Override onMandalChange */
onMandalChange = function (sel) {
    if (_origOnMandalChange) _origOnMandalChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';

    /* Also store the text value in hidden field (original behaviour) */
    var hiddenMandal = document.getElementById('tr_mandal');
    if (hiddenMandal) hiddenMandal.value = name;

    if (!id || !name) return;

    _pendingCoords.mandal = { id: id, lat: 0, lng: 0 };

    /* Build richer query: Mandal, District, State, India */
    var distName = (function () {
        var distSel = document.getElementById('tr_district_id');
        if (!distSel) return '';
        var opt = distSel.options[distSel.selectedIndex];
        return opt ? opt.text : '';
    })();
    var stateName = (function () {
        var stateSel = document.getElementById('tr_state_id');
        if (!stateSel) return '';
        var opt = stateSel.options[stateSel.selectedIndex];
        return opt ? opt.text : '';
    })();

    var query = [name + ' Mandal', distName, stateName, 'India'].filter(Boolean).join(', ');
    resolveAndSave('mandal', id, query, 12, name + ' Mandal');
};

/* ─────────────────────────────────────────────
   When editing an existing record (openModal),
   after restoreLocation finishes, geocode the
   already-selected mandal so the map shows it.
   ───────────────────────────────────────────── */
var _origRestoreLocation = typeof restoreLocation === 'function' ? restoreLocation : null;

restoreLocation = function (r) {
    if (_origRestoreLocation) _origRestoreLocation(r);

    /* Give the cascade dropdowns time to populate */
    setTimeout(function () {
        var stateId    = parseInt(r.tr_state_id_val  || r.tr_state    || 0);
        var districtId = parseInt(r.tr_district_id_val|| r.tr_district || 0);
        var mandalId   = (function () {
            /* We need the mandal ID — try from Location_model data */
            /* The existing restoreLocation selects the mandal by TEXT name,
               so we read the selected option value from the dropdown */
            var sel = document.getElementById('tr_mandal_id');
            return sel ? parseInt(sel.value) : 0;
        })();

        var stateName    = r.tr_state_name    || '';
        var districtName = r.tr_district_name || '';
        var mandalName   = r.tr_mandal        || '';

        /* Store IDs */
        _pendingCoords.state    = { id: stateId,    lat: 0, lng: 0 };
        _pendingCoords.district = { id: districtId, lat: 0, lng: 0 };
        _pendingCoords.mandal   = { id: mandalId,   lat: 0, lng: 0 };

        /* Check DB for all three at once */
        var checkPayload = {
            state_id    : stateId    || 0,
            district_id : districtId || 0,
            mandal_id   : mandalId   || 0,
        };

        ajaxPost(BASE_URL + 'get_latlng', checkPayload, function (res) {
            var sd = res.data;
            var bestLat = 0, bestLng = 0, bestZoom = 6, bestLabel = '';

            /* Use the most precise available level */
            if (mandalId && sd.mandal && parseFloat(sd.mandal.lat)) {
                _pendingCoords.mandal = { id: mandalId, lat: parseFloat(sd.mandal.lat), lng: parseFloat(sd.mandal.lng) };
                bestLat = parseFloat(sd.mandal.lat); bestLng = parseFloat(sd.mandal.lng);
                bestZoom = 12; bestLabel = mandalName + ' Mandal';
            } else if (districtId && sd.district && parseFloat(sd.district.lat)) {
                _pendingCoords.district = { id: districtId, lat: parseFloat(sd.district.lat), lng: parseFloat(sd.district.lng) };
                bestLat = parseFloat(sd.district.lat); bestLng = parseFloat(sd.district.lng);
                bestZoom = 9; bestLabel = districtName + ' District';
            } else if (stateId && sd.state && parseFloat(sd.state.lat)) {
                _pendingCoords.state = { id: stateId, lat: parseFloat(sd.state.lat), lng: parseFloat(sd.state.lng) };
                bestLat = parseFloat(sd.state.lat); bestLng = parseFloat(sd.state.lng);
                bestZoom = 7; bestLabel = stateName;
            }

            if (bestLat && bestLng) {
                updateLocMapPin(bestLat, bestLng, bestLabel, bestZoom);
                setLocStatus('&#10003; Location loaded from DB', 'success');
            } else {
                /* Nothing in DB — geocode the mandal (or fall back to district/state) */
                var geoQuery = '';
                var geoLabel = '';
                var geoZoom  = 10;
                if (mandalName && districtName) {
                    geoQuery = mandalName + ' Mandal, ' + districtName + ', ' + stateName + ', India';
                    geoLabel = mandalName + ' Mandal';
                    geoZoom  = 12;
                } else if (districtName) {
                    geoQuery = districtName + ', ' + stateName + ', India';
                    geoLabel = districtName + ' District';
                    geoZoom  = 9;
                } else if (stateName) {
                    geoQuery = stateName + ', India';
                    geoLabel = stateName;
                    geoZoom  = 7;
                }

                if (geoQuery) {
                    setLocStatus('<i class="bi bi-arrow-repeat spin"></i> Locating on map…', 'info');
                    geocodePlace(geoQuery, function (gLat, gLng) {
                        if (gLat && gLng) {
                            updateLocMapPin(gLat, gLng, geoLabel, geoZoom);

                            /* Determine which level to save */
                            if (mandalId && geoZoom === 12) {
                                _pendingCoords.mandal = { id: mandalId, lat: gLat, lng: gLng };
                            } else if (districtId && geoZoom === 9) {
                                _pendingCoords.district = { id: districtId, lat: gLat, lng: gLng };
                            } else {
                                _pendingCoords.state = { id: stateId, lat: gLat, lng: gLng };
                            }

                            var s = _pendingCoords.state;
                            var d = _pendingCoords.district;
                            var m = _pendingCoords.mandal;
                            saveCoordsToDb(s.id,s.lat,s.lng, d.id,d.lat,d.lng, m.id,m.lat,m.lng, false);
                        } else {
                            setLocStatus('Could not resolve location', 'warn');
                        }
                    });
                }
            }
        });
    }, 900); /* wait for cascade dropdowns to finish */
};

/* ─────────────────────────────────────────────
   Reset map state when modal closes / resets
   ───────────────────────────────────────────── */
$(document).on('hidden.bs.modal', '#regModal', function () {
    _pendingCoords = {
        state    : { id: 0, lat: 0, lng: 0 },
        district : { id: 0, lat: 0, lng: 0 },
        mandal   : { id: 0, lat: 0, lng: 0 },
    };
    if (_locMap) _locMap.setView([20.5937, 78.9629], 5);
    if (_locMapMarker) { _locMapMarker.remove(); _locMapMarker = null; }
    setLocStatus('', '');
});

/*
═══════════════════════════════════════════════════════════════
  HTML TO ADD inside #regModal modal-body
  Paste this block right after the closing </div> of the
  Location section (after the row with tr_pincode).

  Also add the Leaflet CSS in your view's <head> or link area:
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  And the JS before your existing <script> blocks:
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
═══════════════════════════════════════════════════════════════

<!-- Location Mini-Map (add after the Location section rows) -->
<div class="section-divider mt-3"><span>Map Preview</span><hr></div>
<div id="loc-map-wrap" style="border:1.5px solid var(--border);border-radius:10px;overflow:hidden;background:#f8fafc;margin-bottom:8px;">
    <div id="loc-minimap" style="height:220px;width:100%;"></div>
</div>
<div id="loc-map-status" style="font-size:.72rem;color:#64748b;min-height:16px;margin-bottom:4px;"></div>

*/
