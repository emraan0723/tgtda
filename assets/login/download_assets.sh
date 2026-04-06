#!/bin/bash
# ═══════════════════════════════════════════════════════════
# TGTDA – Download all CDN assets for local hosting
# Run this ONCE from your CodeIgniter project root:
#   bash download_assets.sh
# ═══════════════════════════════════════════════════════════

ASSET_DIR="assets/vendor"

mkdir -p $ASSET_DIR/css
mkdir -p $ASSET_DIR/js
mkdir -p $ASSET_DIR/fonts/bootstrap-icons
mkdir -p $ASSET_DIR/fonts/google

echo "📦 Downloading Bootstrap 5.3.0 CSS..."
curl -L "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" \
     -o "$ASSET_DIR/css/bootstrap.min.css"

echo "📦 Downloading Bootstrap 5.3.0 JS Bundle..."
curl -L "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" \
     -o "$ASSET_DIR/js/bootstrap.bundle.min.js"

echo "📦 Downloading Bootstrap Icons 1.11.0 CSS..."
curl -L "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" \
     -o "$ASSET_DIR/css/bootstrap-icons.min.css"

echo "📦 Downloading Bootstrap Icons Fonts..."
curl -L "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2" \
     -o "$ASSET_DIR/fonts/bootstrap-icons/bootstrap-icons.woff2"
curl -L "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff" \
     -o "$ASSET_DIR/fonts/bootstrap-icons/bootstrap-icons.woff"

echo "📦 Downloading jQuery 3.7.0..."
curl -L "https://code.jquery.com/jquery-3.7.0.min.js" \
     -o "$ASSET_DIR/js/jquery-3.7.0.min.js"

echo "📦 Downloading Google Fonts (Baloo 2 + Nunito)..."
# Baloo 2
curl -L "https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&display=swap" \
     -o /tmp/baloo2.css
curl -L "https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd51ncANwr.woff2" \
     -o "$ASSET_DIR/fonts/google/Baloo2-Regular.woff2"
curl -L "https://fonts.gstatic.com/s/baloo2/v21/wXKrE3kTposypRyd515cAA.woff2" \
     -o "$ASSET_DIR/fonts/google/Baloo2-Bold.woff2"
# Nunito
curl -L "https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" \
     -o /tmp/nunito.css
curl -L "https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDIkh.woff2" \
     -o "$ASSET_DIR/fonts/google/Nunito-Regular.woff2"
curl -L "https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDDsh.woff2" \
     -o "$ASSET_DIR/fonts/google/Nunito-Bold.woff2"

# Fix Bootstrap Icons CSS font path to point local
sed -i 's|../fonts/bootstrap-icons|../fonts/bootstrap-icons|g' \
    "$ASSET_DIR/css/bootstrap-icons.min.css"

echo ""
echo "✅ All assets downloaded to ./$ASSET_DIR/"
echo ""
echo "Directory structure:"
find $ASSET_DIR -type f | sort
