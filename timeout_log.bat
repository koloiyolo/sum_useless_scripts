@echo off
setlocal enabledelayedexpansion

set ip_to_ping=your.chosen.ip.address
set timeout=your_delay_in_seconds
set log_file=\\path\to\file.txt

:pingloop
ping -n 1 %ip_to_ping% | find "TTL=" > nul
if errorlevel 1 (
    echo dupa
    set datetime=!date! !time!
    echo !datetime! - Delay: %timeout% seconds >> %log_file%
    timeout /t %timeout% > nul
)
goto pingloop
