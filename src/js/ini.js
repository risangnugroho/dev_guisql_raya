function loading(v){ 
    if( v ){
        $('.loading').addClass('in');
    }else{
        $('.loading').removeClass('in');
    }
    
}

function getAjax(url, data, oke){
    
    $.ajax({
        url : url,
        data: data,
        type: 'POST',
        dataType: 'json',
        beforeSend: function(){
            loading(true);
        },
        success: oke,
        complete: function(a, b){
            if( b != 'success' ){
                alert('Error Get Data');
            }

            loading(false);
        }
        
    })
    
    
}

$('form').submit(function(ev){
    ev.preventDefault();

    var $frm = this;
    getAjax($(this).attr('action'), $(this).serialize(), function(data){
       
       $($frm).trigger('berhasil.submit', [data]); 
    });

    return false;
})