#!/bin/bash
#发布正式环境 后台 请小心操作
ips=( 172.17.42.40 )

src=/data/publish/Jet_Web
dst=/data/www/admin.jet.com

cd $src
git branch master
git checkout master
git pull origin master

rsync -auzl --delete --exclude="conf/public.conf.php" --exclude=".*" --exclude="admin/Resources"  $src/admin.jet.com/* /$dst/
chown -R nginx:nginx /data/www/admin.jet.com
chmod -R 755 /data/www/admin.jet.com
\cp -f  $src/jet.conf/admin.jet.com/public.conf.php $dst/conf/public.conf.php


export RSYNC_PASSWORD=ca2ecb725fa9b0b8cf9687258827ad19

for sip in ${ips[@]};do
    echo ${sip}
    #rsync -auzl --delete --exclude=".*" $dst/* rsync://jupiter@${sip}/jupiteropentool/
done


