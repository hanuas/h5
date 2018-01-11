使用ACE1.3.3

//由于项目要上传IPA/APK等大文件，所以需要修改php配置文件关于上传文件大小的限制。
post_max_size = 320M  //POST数据的最大值
upload_max_filesize = 300M  //限制本次上传的最大值
memory_limit = 340M //设定了一个脚本所能够申请到的最大内存字节数。