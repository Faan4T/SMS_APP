<div style='background-color:#AFF'><h3>Encryption</h3><p><strong>PHP Version</strong></p><pre>5.6.40</pre><hr /><p><strong>Cryptor</strong></p><pre>OpenSSL</pre><hr /><p><strong>Cipher</strong></p><pre>bf-cbc</pre><hr /><p><strong>mb_internal_encoding</strong></p><pre>UTF-8</pre><hr /><div style='background-color:#AAA'><h3>IP Validation</h3><p><strong>$headers from get_ip()</strong></p><pre>Array
(
    [Connection] => TE, close
    [Host] => staging.collectbytext.ca
    [Te] => deflate,gzip;q=0.3
    [User-Agent] => SiteLock (Module: SmartDB; Source: https://www.sitelock.com/; Version: 1.0)
)
</pre><hr /><p><strong>IP Check started in</strong></p><pre>/home/koltext/public_html/staging.collectbytext.ca/tmp/473bade608166d4911de2e63fb39ca69.php</pre><hr /><p><strong>IP Check started at</strong></p><pre>2023-02-21T20:09:27-05:00</pre><hr /><p><strong>The following IPs will be tested</strong></p><pre>Array
(
    [0] => 184.154.139.53
)
</pre><hr /><p><strong>mapi_post URL</strong></p><pre>https://mapi.sitelock.com/v3/connect/</pre><hr /><p><strong>mapi_post_request</strong></p><pre>Array
(
    [pluginVersion] => 100.0.0
    [apiTargetVersion] => 3.0.0
    [token] => 374b70009a088cffe621f4c4917a552f
    [requests] => Array
        (
            [id] => cf483def98f70afd2944ab0b10ba8b19-1677028167914
            [action] => validate_ip
            [params] => Array
                (
                    [site_id] => 33570330
                    [ip] => 184.154.139.53
                )

        )

)
</pre><hr /><p><strong>mapi_request</strong></p><pre><textarea style="width:99%;height:100px;">eyJwbHVnaW5WZXJzaW9uIjoiMTAwLjAuMCIsImFwaVRhcmdldFZlcnNpb24iOiIzLjAuMCIsInRva2VuIjoiMzc0YjcwMDA5YTA4OGNmZmU2MjFmNGM0OTE3YTU1MmYiLCJyZXF1ZXN0cyI6eyJpZCI6ImNmNDgzZGVmOThmNzBhZmQyOTQ0YWIwYjEwYmE4YjE5LTE2NzcwMjgxNjc5MTQiLCJhY3Rpb24iOiJ2YWxpZGF0ZV9pcCIsInBhcmFtcyI6eyJzaXRlX2lkIjoiMzM1NzAzMzAiLCJpcCI6IjE4NC4xNTQuMTM5LjUzIn19fQ==</textarea></pre><hr /><p><strong>curl_getinfo()</strong></p><pre>Array
(
    [url] => https://mapi.sitelock.com/v3/connect/
    [content_type] => text/html; charset=UTF-8
    [http_code] => 200
    [header_size] => 781
    [request_size] => 462
    [filetime] => -1
    [ssl_verify_result] => 20
    [redirect_count] => 0
    [total_time] => 0.347246
    [namelookup_time] => 0.000317
    [connect_time] => 0.012124
    [pretransfer_time] => 0.026665
    [size_upload] => 324
    [size_download] => 511
    [speed_download] => 1471
    [speed_upload] => 933
    [download_content_length] => -1
    [upload_content_length] => 324
    [starttransfer_time] => 0.33604
    [redirect_time] => 0
    [redirect_url] => 
    [primary_ip] => 45.60.12.54
    [certinfo] => Array
        (
        )

    [primary_port] => 443
    [local_ip] => 198.177.123.234
    [local_port] => 55982
)
</pre><hr /><p><strong>mapi_response</strong></p><pre><textarea style="width:99%;height:100px;">{"apiVersion":"3.0.1","status":"ok","globalResponse":null,"banner":null,"forceLogout":false,"newToken":null,"now":1677028166,"responses":[{"id":"cf483def98f70afd2944ab0b10ba8b19-1677028167914","data":{"ip_address":"184.154.139.53","valid":true},"raw_api_url":"https:\/\/api.sitelock.com\/v1\/374b70009a088cffe621f4c4917a552f\/dbscan\/checkip","raw_response":{"@attributes":{"version":"1.1","encoding":"UTF-8"},"checkIP":{"status":"1"}},"raw_request":{"site_id":"33570330","ip":"184.154.139.53"},"status":"ok"}]}</textarea></pre><hr /><p><strong>Detected memory_limit</strong></p><pre>128M</pre><hr /><p><strong>Chunk Size</strong></p><pre>10485760</pre><hr /><div style='background-color:#AAF'><h3>CheckFeatures</h3><p><strong>Feature Code</strong></p><pre>db_scan</pre><hr /><p><strong>_POST</strong></p><pre>Array
(
)
</pre><hr /><p><strong>_GET (raw)</strong></p><pre>cmd=db_creds_ready&enc_db_creds=BKMxIVFlZZfJS7T0Dt9Sk21EWOycQMdoiTVLDXYbOXFm8uzRB7TKEo0m12T%2FMbXxsFWUUGexp3FH0n5lnd7qpMAlkQEEgn1fSDkb8g2%2FIY8%3D&smart_single_download_id=4295090</pre><hr /><p><strong>Check Features - FS</strong></p><pre>true</pre><hr /><p><strong>Check Features - CRYPTO</strong></p><pre>true</pre><hr /><p><strong>Starting MySQLi constructor</strong></p><pre></pre><hr /><p><strong>error in Mysql_New constructor</strong></p><pre>mysqli_open_fail:1045:Access denied for user ''@'localhost' (using password: NO):1</pre><hr /><p><strong>Starting MySQL constructor</strong></p><pre></pre><hr /><p><strong>MySQL connect to  FAILED for </strong></p><pre></pre><hr /><p><strong>Check Features - DB</strong></p><pre>false</pre><hr /><p><strong>Check Features - ZIP</strong></p><pre>2</pre><hr /><p><strong>Check Features - HTTP (always true at this point if check-ip did not fail)</strong></p><pre>1</pre><hr /><p><strong>Check Features - GZIP</strong></p><pre>1</pre><hr /><p><strong>Check Features - got schema?</strong></p><pre>false</pre><hr /><p><strong>$statuses - new</strong></p><pre>Array
(
    [fs] => 1
    [crypto] => 1
    [zip] => 2
    [gzip] => 1
    [http] => 1
    [db] => 
    [json] => 1
    [singlesite] => 1
    [errors] => Array
        (
            [db] => Array
                (
                    [code] => CHECK_FEATURE_ERR_DB
                    [message] => getDbObj() failed with code: 0 and message: mysql_connect_fail.
                )

        )

    [schemas] => 
)
</pre><hr /><p><strong>Bullet run time, seconds.</strong></p><pre>0.37</pre><hr />