function showCreateDatabaseWindow(){
    $('#btn_create').unbind('click');
    $('#new_name').val('');
    $('#btn_create').on('click',function(){
        var db_name =  $('#new_name').val();
        $.ajax({
            type:"post",
            url: '/?cmd=create_database',
            data: { name: db_name },
            success: function(data) {
                if(data=='1'){
                    $('#database_list').append('<li><a href="/?dbname='+db_name+'">'+db_name+'&nbsp;[empty]</a></li>');
                }
                $('#createNewElement').modal('hide');
            }
        });
    });
    $('#createNewElement').modal('show');
}

function showCreateCollectionWindows(dbname){
    if(dbname!=""){
        $('#btn_create').unbind('click');
        $('#new_name').val('');
        $('#btn_create').on('click', function(){
            var collection_name =  $('#new_name').val();
            $.ajax({
                type:"post",
                url: '/?cmd=create_collection',
                data: { name: collection_name , db: dbname},
                success: function(data) {
                    if(data=='1'){
                        $('#collection_list').append('<li><a href="/?dbname='+dbname+'&collection='+collection_name+'">'+collection_name+' &nbsp;[0]</a></li>');
                    }

                    $('#createNewElement').modal('hide');
                }
            });
        });
        $('#createNewElement').modal('show');
    }
}

function removeObject(dbname, collection, mongo_id){
    if(mongo_id!="" && dbname!="" && collection!=""){
        $('#confirmWindow').modal('show');
        $('#btn_ok').unbind('click');
        $('#btn_ok').on('click', function(){
            $.ajax({
                type:"post",
                url: '/?cmd=remove_object',
                data: { db: dbname, collection:collection, mongo_id: mongo_id},
                success: function(data) {
                    if(data=='1'){
                        console.log(parseInt($('#ccnt_'+collection).html())-1);
                        $('#ccnt_'+collection).html( parseInt($('#ccnt_'+collection).html())-1 );
                        $('#mongoid_'+mongo_id).remove();
                    }
                    $('#confirmWindow').modal('hide');
                }
            });
        });
    }
}

function editObject(dbname, collection, mongo_id){
    if(mongo_id!="" && dbname!="" && collection!=""){

    }
}

function dropDatabase(dbname){
    if(dbname!=""){
        $('#confirmWindow').modal('show');
        $('#btn_ok').unbind('click');
        $('#btn_ok').on('click', function(){
            $.ajax({
                type:"post",
                url: '/?cmd=drop_database',
                data: { name: dbname},
                success: function(data) {
                    window.location.href="/";
                }
            });
        });
    }
}


function dropCollection(dbname, collname){
    if(collname!="" && dbname!=""){
        $('#confirmWindow').modal('show');
        $('#btn_ok').unbind('click');
        $('#btn_ok').on('click', function(){
            $.ajax({
                type:"post",
                url: '/?cmd=drop_collection',
                data: { db:dbname, name: collname},
                success: function(data) {
                    window.location.href="/?dbname="+dbname;
                }
            });
        });
    }
}
