$('#btnAdd').on('click',function(e)
{
    e.preventDefault();
    var data = $('#frmIndustry').serialize();
    $.ajax({
        url:"<?=site_url('save-industry')?>",method:"POST",data:data,success:function(response)
        {
            if(response==="success"){}else{alert(response);}
        }
    });
});