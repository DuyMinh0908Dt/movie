# Define Local Scope for Variables
$local:PHP_VERSION = "8.1.x"
$local:PHP_URL = "https://windows.php.net/downloads/releases/php-8.1.28-nts-Win32-vs16-x64.zip"
$local:FULL_PATH = $PSScriptRoot # Use this for the script's directory
$local:PHP_DIR = Join-Path $FULL_PATH "php_dir" 
$local:PHP_EXE = Join-Path $PHP_DIR "php.exe"
$local:CERT_PATH = Join-Path $FULL_PATH "ssl\cacert.crt"
$local:COMPOSER_CMD = "& '$($local:PHP_EXE)' '$($local:PHP_DIR)\composer.phar'" # Execute as a command
$local:FULL_PATH = $PSScriptRoot # Use this for the script's directory
Write-Host "PHP directory is: $($local:PHP_DIR)"

# Check if PHP is already installed
if (-Not (Test-Path $local:PHP_DIR)) {
    Write-Host "PHP not found, downloading..."
    New-Item -ItemType Directory -Path $local:PHP_DIR | Out-Null 

    # Download and Extract PHP
    Invoke-WebRequest $local:PHP_URL -OutFile (Join-Path $local:PHP_DIR "php.zip") 
    Expand-Archive (Join-Path $local:PHP_DIR "php.zip") -DestinationPath $local:PHP_DIR 

    # Configure PHP (php.ini)
    Copy-Item (Join-Path $local:PHP_DIR "php.ini-development") (Join-Path $local:PHP_DIR "php.ini") 

    Add-Content (Join-Path $local:PHP_DIR "php.ini") @(
        "extension=./ext/php_openssl.dll"
        "extension=./ext/php_mysqli.dll"
        "extension=./ext/php_curl.dll"
        "extension=./ext/php_gd.dll"
        "extension=./ext/php_mbstring.dll"
        "post_max_size = 900M"
        "upload_max_filesize = 900M"
        "openssl.cafile=$($local:CERT_PATH)"
        "date.timezone=Asia/Ho_Chi_Minh"
        "curl.cainfo=$($local:CERT_PATH)"
    )
    # Install Composer //"-r copy('https://getcomposer.org/installer', 'composer-setup.php');"
    Invoke-WebRequest -Uri 'https://getcomposer.org/installer' -OutFile (Join-Path $local:PHP_DIR 'composer-setup.php')
    Invoke-Expression -Command "cd $local:PHP_DIR; $local:PHP_EXE 'composer-setup.php'"


    # Install project dependencies (if composer.json exists)
    if (Test-Path (Join-Path $FULL_PATH "composer.json")) {
        Write-Host "Installing dependencies"
        Invoke-Expression -Command "cd $FULL_PATH; $local:COMPOSER_CMD install"
    }
}

# Update dependencies and autoload (if composer.json exists)
if (Test-Path (Join-Path $FULL_PATH "composer.json")) {
    Invoke-Expression  -Command "$local:COMPOSER_CMD update"
    Invoke-Expression -Command "$local:COMPOSER_CMD dump-autoload"
}

# Start PHP Built-in Server
Write-Host "Starting PHP built-in server..."
$choice = Read-Host "Do you want to open the browser? (y/n)"
if ($choice -eq "y") {
    Start-Process "http://localhost:8000"
}

# Non-interactive server start
& $local:PHP_EXE -S localhost:8000

