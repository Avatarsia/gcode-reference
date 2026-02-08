# Build Plugin ZIP for WordPress Update
# Ensures the ZIP contains a root folder "gcode-reference"
# Usage: ./build-plugin-zip.ps1

$ErrorActionPreference = "Stop"

$pluginSlug = "gcode-reference"
$zipName = "gcode-reference-update.zip"
$sourceDir = Get-Location
$stagingDir = "$sourceDir\dist_build"
$pluginDest = "$stagingDir\$pluginSlug"

Write-Host "Starting build process..." -ForegroundColor Cyan
Write-Host "Source: $sourceDir"
Write-Host "Dest: $pluginDest"
Write-Host "Zip: $zipName"

# 1. Clean previous build artifacts
try {
    if (Test-Path $stagingDir) { 
        Write-Host "Cleaning staging dir (using cmd)..."
        cmd /c "rmdir /s /q `"$stagingDir`""
        if (Test-Path $stagingDir) {
            Write-Warning "Failed to fully remove staging dir via cmd. Retrying with PowerShell..."
            Remove-Item $stagingDir -Recurse -Force -ErrorAction SilentlyContinue
        }
    }
    if (Test-Path $zipName) { 
        Write-Host "Removing old zip..."
        Remove-Item $zipName -Force 
    }
}
catch {
    Write-Warning "Cleanup had issues: $_"
    # Continue anyway, robocopy might handle it
}

# 2. Create staging directory structure
New-Item -ItemType Directory -Force -Path $pluginDest | Out-Null

# 3. Define exclusion list (files/folders NOT to include in the ZIP)
# 3. Define exclusion lists
$excludeDirs = @(
    ".git",
    ".github",
    ".vscode",
    "node_modules",
    "dist_build",
    "dist_staging",
    "tests"
)

$excludeFiles = @(
    "*.zip",
    "*.log",
    ".gitignore",
    ".eslintrc.json",
    "package.json",
    "package-lock.json",
    "composer.json",
    "phpcs.xml",
    "README.txt",
    "README.md",
    "CONTRIBUTING.md",
    "CHANGELOG.md",
    "build-plugin-zip.ps1",
    "gcode-backup.tmp",
    "build_log.txt",
    ".DS_Store"
)

Write-Host "Copying files with Robocopy..." -ForegroundColor Cyan

# Construct Robocopy command - flatten arrays manually
$roboArgs = @($sourceDir, $pluginDest, "/MIR", "/NFL", "/NDL", "/NJH", "/NJS")
$roboArgs += @("/XD") + $excludeDirs
$roboArgs += @("/XF") + $excludeFiles

Write-Host "Robocopy Args: $($roboArgs -join ' ')" -ForegroundColor Yellow
# exit 0 # Debugging

# Execute Robocopy
& robocopy $roboArgs

# Robocopy exit codes 0-7 are success/partial success
$exitCode = $LASTEXITCODE
Write-Host "Robocopy exit code: $exitCode"

if ($exitCode -gt 7) {
    Write-Error "Robocopy failed with exit code $exitCode"
    exit 1
}

# 5. Create ZIP archive
Write-Host "Creating ZIP archive: $zipName" -ForegroundColor Cyan
try {
    Compress-Archive -Path "$pluginDest" -DestinationPath "$sourceDir\$zipName" -Force
}
catch {
    Write-Error "Failed to create ZIP: $_"
    exit 1
}

# 6. Verify ZIP
if (Test-Path "$sourceDir\$zipName") {
    $size = (Get-Item "$sourceDir\$zipName").Length
    Write-Host "Success! ZIP created: $zipName ($size bytes)" -ForegroundColor Green
}
else {
    Write-Error "ZIP file not found after creation attempt."
    exit 1
}

# 7. Cleanup
Remove-Item $stagingDir -Recurse -Force
Write-Host "Cleanup done."
