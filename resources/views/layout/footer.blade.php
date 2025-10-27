
                </div> <!-- content -->

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->

        <!-- jQuery  -->
        <script src= {{ asset("assets/js/jquery.min.js" ) }}></script>
        <script src= {{ asset("assets/js/popper.min.js" ) }}></script>
        <script src= {{ asset("assets/js/bootstrap.min.js" ) }}></script>
        <script src= {{ asset("assets/js/modernizr.min.js" ) }}></script>
        <script src= {{ asset("assets/js/detect.js" ) }}></script>
        <script src= {{ asset("assets/js/fastclick.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.slimscroll.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.blockUI.js" ) }}></script>
        <script src= {{ asset("assets/js/waves.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.nicescroll.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.scrollTo.min.js" ) }}></script>

        <!--Morris Chart-->
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.time.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/curvedLines.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.pie.js" ) }}></script>
        <script src= {{ asset("assets/plugins/morris/morris.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/raphael/raphael-min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/jquery-sparkline/jquery.sparkline.min.js" ) }}></script>

        <script src= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" ) }}></script>
        <script src="{{ asset("assets/plugins/notification/snackbar/snackbar.min.js") }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="crossorigin="anonymous"referrerpolicy="no-referrer"></script>


        {{-- <script src= {{ asset("assets/pages/crypto-dash.init.js" ) }}></script> --}}
{{-- data table --}}
    <script src= "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>

{{-- data table --}}
        <!-- App js -->
        <script src= {{ asset("assets/js/app.js" ) }}></script>
        @if(Session::get('msg'))
<script>
    var msg = "{{Session::get('msg')}}";
    Snackbar.show({
    text: msg,
    duration: 3000,
    actionTextColor: '#fff',
   backgroundColor: '#00ab55'
});
</script>
@endif
@if(Session::get('error'))
<script>
    var msg = "{{Session::get('error')}}";
    Snackbar.show({
    text: msg,
    duration: 3000,
    actionTextColor: '#fff',
   backgroundColor: '#e7515a'
});
</script>
@endif
        <script>

            $(document).ready(function() {

    $('#datatable').DataTable({

        "bSort": false,

    });
    $(function () {
    $(".select2").selectize();
  });

            $("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
            $("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
            });

        </script>

@yield('scripts')
<script src="https://kit.fontawesome.com/c1e69a781a.js" crossorigin="anonymous"></script>
    </body>
</html>
