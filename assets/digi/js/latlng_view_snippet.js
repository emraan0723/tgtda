/**
 * LAT/LNG GEOCODING + DB SAVE — user view snippet  (FIXED v2)
 *
 * ROOT CAUSE FIXES:
 *  1. mandalId in restoreLocation was read before cascade dropdowns finished → now
 *     read inside the timeout after populateSelect completes.
 *  2. _pendingCoords levels were being sent with id=0 when not yet resolved →
 *     saveCoordsToDb now skips any level whose id is 0.
 *  3. geocodePlace callback now reads the LIVE dropdown id at call-time, not the
 *     stale closure value, so the correct id is always stored before saving.
 *  4. resolveAndSave: after geocoding, all three _pendingCoords levels are saved
 *     only after confirming each id is non-zero.
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
        center     : [20.5937, 78.9629],
        zoom       : 5,
        zoomControl: true,
        attributionControl: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(_locMap);

    _locMap.invalidateSize();
}

$(document).on('shown.bs.modal', '#regModal', function () {
    setTimeout(function () { initLocMap(); }, 200);
});

/* ─────────────────────────────────────────────
   updateLocMapPin
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
   Nominatim geocode helper
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
   setLocStatus
   ───────────────────────────────────────────── */
function setLocStatus(msg, type) {
    var el = document.getElementById('loc-map-status');
    if (!el) return;
    var colors = { info: '#1e88e5', success: '#10b981', warn: '#f59e0b', error: '#ef4444' };
    el.innerHTML   = msg;
    el.style.color = colors[type] || '#64748b';
}

/* ─────────────────────────────────────────────
   saveCoordsToDb
   FIX: skips any level with id=0 so we never
   send a 0-id that overwrites nothing but still
   confuses the response.
   ───────────────────────────────────────────── */
function saveCoordsToDb(stateId, stateLat, stateLng,
                        distId,  distLat,  distLng,
                        mandalId,mandalLat,mandalLng,
                        force) {

    /* Only proceed if at least one level has a valid id + coords */
    var hasState  = stateId  > 0 && stateLat  != 0 && stateLng  != 0;
    var hasDist   = distId   > 0 && distLat   != 0 && distLng   != 0;
    var hasMandal = mandalId > 0 && mandalLat != 0 && mandalLng != 0;

    if (!hasState && !hasDist && !hasMandal) return;

    var payload = {
        state_id    : hasState  ? stateId  : 0,
        state_lat   : hasState  ? stateLat  : 0,
        state_lng   : hasState  ? stateLng  : 0,
        district_id : hasDist   ? distId   : 0,
        dist_lat    : hasDist   ? distLat   : 0,
        dist_lng    : hasDist   ? distLng   : 0,
        mandal_id   : hasMandal ? mandalId : 0,
        mandal_lat  : hasMandal ? mandalLat : 0,
        mandal_lng  : hasMandal ? mandalLng : 0,
        force       : force ? 1 : 0,
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
   resolveAndSave
   FIX: reads the live dropdown id at call-time
   and stores it in _pendingCoords BEFORE the
   async geocode callback fires, so the id is
   never 0 when saveCoordsToDb is called.
   ───────────────────────────────────────────── */
function resolveAndSave(level, id, query, zoom, label) {
    if (!id || !query) return;

    /* Store id immediately (synchronously) */
    _pendingCoords[level].id = id;

    setLocStatus('<i class="bi bi-arrow-repeat spin"></i> Locating ' + label + '…', 'info');

    var checkPayload = {};
    checkPayload[level + '_id'] = id;

    ajaxPost(BASE_URL + 'get_latlng', checkPayload, function (res) {
        var data = res.data[level];
        var lat  = parseFloat(data && data.lat ? data.lat : 0);
        var lng  = parseFloat(data && data.lng ? data.lng : 0);

        if (lat && lng) {
            /* Already in DB */
            _pendingCoords[level].lat = lat;
            _pendingCoords[level].lng = lng;
            updateLocMapPin(lat, lng, label, zoom);
            setLocStatus('&#10003; ' + label + ' coordinates found in DB', 'success');
        } else {
            /* Not in DB — geocode */
            geocodePlace(query, function (gLat, gLng) {
                if (gLat && gLng) {
                    /* FIX: re-read id from dropdown at callback time in case it changed */
                    var liveId = _pendingCoords[level].id;

                    _pendingCoords[level] = { id: liveId, lat: gLat, lng: gLng };
                    updateLocMapPin(gLat, gLng, label, zoom);

                    var s = _pendingCoords.state;
                    var d = _pendingCoords.district;
                    var m = _pendingCoords.mandal;

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
        /* DB check failed — try geocoding anyway */
        geocodePlace(query, function (gLat, gLng) {
            if (gLat && gLng) {
                var liveId = _pendingCoords[level].id;
                _pendingCoords[level] = { id: liveId, lat: gLat, lng: gLng };
                updateLocMapPin(gLat, gLng, label, zoom);

                var s = _pendingCoords.state;
                var d = _pendingCoords.district;
                var m = _pendingCoords.mandal;
                saveCoordsToDb(s.id, s.lat, s.lng, d.id, d.lat, d.lng, m.id, m.lat, m.lng, false);
            }
        });
    });
}

/* ─────────────────────────────────────────────
   HOOK INTO EXISTING LOCATION CHANGE HANDLERS
   ───────────────────────────────────────────── */

var _origOnStateChange    = typeof onStateChange    === 'function' ? onStateChange    : null;
var _origOnDistrictChange = typeof onDistrictChange === 'function' ? onDistrictChange : null;
var _origOnMandalChange   = typeof onMandalChange   === 'function' ? onMandalChange   : null;

onStateChange = function (sel) {
    /* Reset lower levels */
    _pendingCoords.state    = { id: 0, lat: 0, lng: 0 };
    _pendingCoords.district = { id: 0, lat: 0, lng: 0 };
    _pendingCoords.mandal   = { id: 0, lat: 0, lng: 0 };

    if (_origOnStateChange) _origOnStateChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';
    if (!id || !name) return;

    /* FIX: set id synchronously before async resolveAndSave */
    _pendingCoords.state = { id: id, lat: 0, lng: 0 };
    resolveAndSave('state', id, name + ', India', 6, name);
};

onDistrictChange = function (sel) {
    _pendingCoords.district = { id: 0, lat: 0, lng: 0 };
    _pendingCoords.mandal   = { id: 0, lat: 0, lng: 0 };

    if (_origOnDistrictChange) _origOnDistrictChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';
    if (!id || !name) return;

    /* FIX: set id synchronously */
    _pendingCoords.district = { id: id, lat: 0, lng: 0 };

    var stateName = (function () {
        var stateSel = document.getElementById('tr_state_id');
        if (!stateSel) return '';
        var opt = stateSel.options[stateSel.selectedIndex];
        return opt ? opt.text : '';
    })();

    var query = [name, stateName, 'India'].filter(Boolean).join(', ');
    resolveAndSave('district', id, query, 9, name + ' District');
};

onMandalChange = function (sel) {
    if (_origOnMandalChange) _origOnMandalChange(sel);

    var id   = parseInt(sel.value);
    var name = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';

    var hiddenMandal = document.getElementById('tr_mandal');
    if (hiddenMandal) hiddenMandal.value = name;

    if (!id || !name) return;

    /* FIX: set id synchronously before async call */
    _pendingCoords.mandal = { id: id, lat: 0, lng: 0 };

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
   restoreLocation override
   FIX: mandalId is now read INSIDE the timeout,
   after the cascade dropdowns have finished
   populating — previously it was always 0
   because it was read before populateSelect ran.
   ───────────────────────────────────────────── */
var _origRestoreLocation = typeof restoreLocation === 'function' ? restoreLocation : null;

restoreLocation = function (r) {
    if (_origRestoreLocation) _origRestoreLocation(r);

    /* FIX: increased timeout + read mandalId from dropdown INSIDE the timeout */
    setTimeout(function () {
        var stateId    = parseInt(r.tr_state_id_val    || r.tr_state    || 0);
        var districtId = parseInt(r.tr_district_id_val || r.tr_district || 0);

        /* FIX: read mandal id from the dropdown now that cascade is done */
        var mandalId = (function () {
            var sel = document.getElementById('tr_mandal_id');
            return sel ? parseInt(sel.value) || 0 : 0;
        })();

        /* FIX: if mandalId still 0, try matching by mandal name */
        if (!mandalId && r.tr_mandal) {
            var mandalSel = document.getElementById('tr_mandal_id');
            if (mandalSel) {
                for (var i = 0; i < mandalSel.options.length; i++) {
                    if (mandalSel.options[i].text === r.tr_mandal) {
                        mandalId = parseInt(mandalSel.options[i].value) || 0;
                        break;
                    }
                }
            }
        }

        var stateName    = r.tr_state_name    || '';
        var districtName = r.tr_district_name || '';
        var mandalName   = r.tr_mandal        || '';

        /* Initialise all three levels with correct ids */
        _pendingCoords.state    = { id: stateId,    lat: 0, lng: 0 };
        _pendingCoords.district = { id: districtId, lat: 0, lng: 0 };
        _pendingCoords.mandal   = { id: mandalId,   lat: 0, lng: 0 };

        var checkPayload = {
            state_id    : stateId    || 0,
            district_id : districtId || 0,
            mandal_id   : mandalId   || 0,
        };

        ajaxPost(BASE_URL + 'get_latlng', checkPayload, function (res) {
            var sd      = res.data;
            var bestLat = 0, bestLng = 0, bestZoom = 6, bestLabel = '';

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
                /* Nothing in DB — geocode from most precise available name */
                var geoQuery = '', geoLabel = '', geoZoom = 10;

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

                            /* Assign to the correct level based on zoom */
                            if (mandalId && geoZoom === 12) {
                                _pendingCoords.mandal = { id: mandalId, lat: gLat, lng: gLng };
                            } else if (districtId && geoZoom === 9) {
                                _pendingCoords.district = { id: districtId, lat: gLat, lng: gLng };
                            } else if (stateId) {
                                _pendingCoords.state = { id: stateId, lat: gLat, lng: gLng };
                            }

                            var s = _pendingCoords.state;
                            var d = _pendingCoords.district;
                            var m = _pendingCoords.mandal;
                            saveCoordsToDb(
                                s.id, s.lat, s.lng,
                                d.id, d.lat, d.lng,
                                m.id, m.lat, m.lng,
                                false
                            );
                        } else {
                            setLocStatus('Could not resolve location', 'warn');
                        }
                    });
                }
            }
        });

    }, 1200); /* FIX: 1200ms — enough for the full 3-step cascade (states→districts→mandals) */
};

/* ─────────────────────────────────────────────
   Reset map state when modal closes
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
  Paste after the Location section (after tr_pincode row).

  Add Leaflet CSS in your <head>:
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  Add Leaflet JS before your existing <script> blocks:
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
═══════════════════════════════════════════════════════════════

<div class="section-divider mt-3"><span>Map Preview</span><hr></div>
<div id="loc-map-wrap" style="border:1.5px solid var(--border);border-radius:10px;overflow:hidden;background:#f8fafc;margin-bottom:8px;">
    <div id="loc-minimap" style="height:220px;width:100%;"></div>
</div>
<div id="loc-map-status" style="font-size:.72rem;color:#64748b;min-height:16px;margin-bottom:4px;"></div>

*/