@echo off
setlocal enabledelayedexpansion
 
set ip_to_ping=x
set timeout=y
set log_file=z
 
:pingloop
ping -n 1 %ip_to_ping% | find "TTL=" > nul
echo %DATE%/%TIME% pinging %ip_to_ping%
 
if errorlevel 1 (
    for /f "tokens=*" %%a in ('ping -n 1 %ip_to_ping% ^| find /i "unreachable"') do (
        set datetime=!date! !time!
        echo !datetime! - Delay: %timeout% seconds - Host Unreachable >> %log_file%
		echo Host unreachable
		timeout /t %timeout% > nul
        goto pingloop
    )
	echo Response delayed
	set datetime=!date! !time!
    echo !datetime! - Delay: over %timeout% seconds >> %log_file%
)
timeout /t %timeout% > nul
goto pingloop
