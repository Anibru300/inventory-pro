#!/bin/bash

# StockWolf Frontend Build Script
# Usage: ./deploy.sh

echo "🐺 StockWolf Frontend Build"
echo "============================"

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Clean previous build
echo "🧹 Cleaning previous build..."
rm -rf dist

# Build for production
echo "🔨 Building for production..."
npm run build

# Check if build succeeded
if [ $? -eq 0 ]; then
    echo "✅ Build successful!"
    echo "📁 Build output: dist/"
    echo "📊 Build size:"
    du -sh dist/
else
    echo "❌ Build failed!"
    exit 1
fi

echo ""
echo "🚀 Ready to deploy!"
echo "Upload the 'dist' folder to your hosting provider."
