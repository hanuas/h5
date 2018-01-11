REM by ÇÇ³ÉÀÚ 201307
set dbName=manager
set pwd=
set backupDir=D:\PROJ\DB\BACKUP
REM =======================================================
set folderName=%dbName%
%~d0
CD %~dp0
RD /s /q %folderName%
MKDIR %folderName%

CD %~dp0
mysqldump -u root -p%pwd% %dbName% > %folderName%/%dbName%.sql 

REM rename ,ADD TIME INFO

if /i "%time:~0,1%"=="1" goto DOUBLE:
if /i "%time:~0,1%"=="2" goto DOUBLE:

set  newName=%folderName%%date:~0,4%%date:~5,2%%date:~8,2%_%time:~1,1%%time:~3,2%
ren %folderName% %newName%
goto COMPRESS


:DOUBLE
set  newName=%folderName%%date:~0,4%%date:~5,2%%date:~8,2%_%time:~0,2%%time:~3,2%
ren %folderName% %newName%
goto COMPRESS

:COMPRESS
rar a -r %newName%.rar %newName%
rem 7z a -t7z %newName%.7z %newName% -m0=LZMA 
RD /s /q %newName%
move %newName%.rar %backupDir%
exit