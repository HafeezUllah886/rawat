$(document).ready(function () {
   $(document).on('change','#routecategorie_id',function () {
       //alert('am working');
       var routecatid=$(this).val();
       $.ajax({
           url: 'route/routeregchange',
           type: 'get',
           data: {'id': routecatid, _token: '{{csrf_token()}}'},
           success: function (data) {
               //alert(data);
               $('#routeregister_id').html(data);
           }
       });
   });

   
    $(document).on('click','#deletedirection',function () {
        //alert('am working');
        var id=$(this).data('id');
        var element=$(this);
        //alert('am working'+id);
        swal({
            title: "Are you sure?",
            text: "You will not be able to Recover this Direction!",
            icon: "warning",
            buttons: [
                'No, cancel it!',
                'Yes, I am sure!'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                // alert(id);
                $.ajax({
                    url: 'direction/delete',
                    type: 'get',
                    data: {'id': id,_token: '{{csrf_token()}}'},
                    success:function (data) {
                        //alert(data)
                        if (data==0){
                            $(element).closest('tr').fadeOut();
                        }
                        else{
                            swal("Cancelled", "Direction is Safe :)", "Error");
                        }
                    }

                });
            }
        });
    });
});