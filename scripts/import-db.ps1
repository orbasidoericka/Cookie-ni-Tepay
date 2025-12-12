# Usage: .\scripts\import-db.ps1 -SqlFile database/legends.sql
param(
    [Parameter(Mandatory=$false)][string]$SqlFile = "database/legends.sql"
)

if (-not (Test-Path $SqlFile)) {
    Write-Error "SQL file not found: $SqlFile"
    exit 1
}

if (-not $env:DB_HOST -or -not $env:DB_PORT -or -not $env:DB_USERNAME -or -not $env:DB_DATABASE) {
    Write-Error "Please set DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD, and DB_DATABASE environment variables."
    exit 1
}

$host = $env:DB_HOST
$port = $env:DB_PORT
$user = $env:DB_USERNAME
$db = $env:DB_DATABASE
$pass = $env:DB_PASSWORD

Write-Host "Importing $SqlFile into $db@$host:$port"

if (-not $pass) {
    Get-Content $SqlFile -Raw | mysql -h $host -P $port -u $user $db
} else {
    Get-Content $SqlFile -Raw | mysql -h $host -P $port -u $user -p$pass $db
}

Write-Host "Done."
