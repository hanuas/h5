#!/bin/bash
#发布正式环境 平台前台 请小心操作
ips=( 172.17.42.40 )

src=/data/publish/Jet_Web
dst=/data/www/jet.com

cd $src
git branch master
git checkout master
git pull origin master

rsync -auzl --delete --exclude="app/common/config.php" --exclude=".*" --exclude="webroot/static/image/Resources"  $src/jet.com/* /$dst/
chown -R nginx:nginx /data/www/jet.com
chmod -R 755 /data/www/jet.com
\cp -f  $src/jet.conf/jet.com/config.php $dst/app/common/config.php


export RSYNC_PASSWORD=ca2ecb725fa9b0b8cf9687258827ad19

for sip in ${ips[@]};do
    echo ${sip}
    rsync -auzl --delete --exclude=".*" --exclude="webroot/static/image/Resources"  $dst/* rsync://jupiter@${sip}/jetweb/
done

