@ECHO OFF
SET BIN_TARGET=%~dp0/../phploc/phploc/composer/bin/phploc
php "%BIN_TARGET%" %*
