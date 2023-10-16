$('#btnAdd').on('click',function(e)
{
    e.preventDefault();
    var data = $('#frmIndustry').serialize();
    $.ajax({
        url:"<?=base_url('save-industry')?>",method:"POST",data:data,success:function(response)
        {
            if(response==="success"){
                Swal.fire(
                    'Great',
                    'Successfully added',
                    'success'
                  );
            }else{alert(response);}
        }
    });
});