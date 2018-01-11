#!/bin/sh
pids=`ps -ef|grep -P /data/www/admin.jet.com/admin/Resources|awk '{print $2}'`

for  pid  in   $pids;
do
  kill -9 $pid
done

export RSYNC_PASSWORD=ca2ecb725fa9b0b8cf9687258827ad19
#手游渠道配置
#负载均衡机器
source /data/www/jet.com/app/sh/server.sh

for sip in ${sip_list};do
    echo ${sip}
    rsync -avzlu /data/www/admin.jet.com/admin/Resources/ rsync://jupiter@${sip}/jetimg
done
