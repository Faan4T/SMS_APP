$(document).on('ready',function(){
    $('#checkDBConnection').click(function(e){
        $('#showResMsg').html('');
        $('#loading').show();
        var hostname = $('input[name="hostname"]').val();
        var dbname   = $('input[name="dbname"]').val();
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        $.post('../server.php',{'cmd':'check_db_conn',hostname:hostname,dbname:dbname,username:username,password:password},function(r){
            $('#loading').hide();
            if(r==1){
                $('#showResMsg').html('<span style="color:green">Connected successfully.</span>');
            }
            else{
                $('#showResMsg').html('<span style="color:red">Error: Invalid database information.</span>');
            }
        });
    });
});